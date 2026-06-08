<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminReportController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/reports
    // List laporan produk per bulan
    // Query: month, year, search, per_page, page
    // ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $month  = (int) $request->get('month', now()->month);
        $year   = (int) $request->get('year', now()->year);
        $search = $request->get('search');
        $perPage = (int) $request->get('per_page', 10);
        $page    = (int) $request->get('page', 1);

        // per_page=1000 → frontend pakai semua data untuk export Excel
        $query = Report::queryRealtime($year, $month, $search)
            ->orderByDesc('total_sold');

        $total = (clone $query)->count();
        $items = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

        $nomor     = ($page - 1) * $perPage + 1;
        $summary   = $this->getMonthlySummary($year, $month);

        $formatted = $items->map(function ($row) use (&$nomor, $month, $year) {
            return [
                'nomor'                => $nomor++,
                'product_id'           => $row->product_id,
                'product_name'         => $row->product_name,
                'product_image'        => $row->product_image ? Storage::url($row->product_image) : null,
                'category'             => $row->category_name,
                'unit'                 => $row->unit,
                'total_sold'           => $row->total_sold,
                'total_revenue'        => $row->total_revenue,
                'total_revenue_format' => 'Rp. ' . number_format($row->total_revenue, 0, ',', '.'),
                'period'               => Report::periodLabel($month, $year),
                'month'                => $month,
                'year'                 => $year,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => [
                'items'   => $formatted,
                'summary' => $summary,
                'period'  => Report::periodLabel($month, $year),
                'month'   => $month,
                'year'    => $year,
                'pagination' => [
                    'current_page' => $page,
                    'per_page'     => $perPage,
                    'total'        => $total,
                    'last_page'    => (int) ceil($total / $perPage),
                ],
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/reports/{productId}
    // Detail laporan 1 produk
    // Query: month, year
    // ──────────────────────────────────────────────────────────────
    public function show(Request $request, int $productId)
    {
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);

        $row = Report::queryRealtime($year, $month)
            ->where('products.id', $productId)
            ->first();

        if (! $row) {
            return response()->json([
                'success' => false,
                'message' => 'Data laporan tidak ditemukan untuk produk ini pada periode yang dipilih',
            ], 404);
        }

        $history = $this->getProductHistory($productId, $year, $month);

        return response()->json([
            'success' => true,
            'data'    => [
                'product' => [
                    'id'           => $row->product_id,
                    'name'         => $row->product_name,
                    'description'  => $row->description,
                    'image'        => $row->product_image ? Storage::url($row->product_image) : null,
                    'category'     => $row->category_name,
                    'unit'         => $row->unit,
                    'weight_kg'    => $row->weight_kg,
                    'price'        => $row->product_price,
                    'price_format' => 'Rp.' . number_format($row->product_price, 0, ',', '.'),
                ],
                'report' => [
                    'period'               => Report::periodLabel($month, $year),
                    'month'                => $month,
                    'year'                 => $year,
                    'total_sold'           => $row->total_sold,
                    'total_revenue'        => $row->total_revenue,
                    'total_revenue_format' => 'Rp. ' . number_format($row->total_revenue, 0, ',', '.'),
                ],
                'history' => $history,
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/reports/summary
    // Ringkasan total bulan ini + perbandingan bulan lalu
    // Query: month, year
    // ──────────────────────────────────────────────────────────────
    public function summary(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);

        $current = $this->getMonthlySummary($year, $month);

        $prevMonth = $month === 1 ? 12 : $month - 1;
        $prevYear  = $month === 1 ? $year - 1 : $year;
        $previous  = $this->getMonthlySummary($prevYear, $prevMonth);

        $revenueGrowth = $previous['total_revenue'] > 0
            ? round((($current['total_revenue'] - $previous['total_revenue']) / $previous['total_revenue']) * 100, 1)
            : 0;

        $soldGrowth = $previous['total_sold'] > 0
            ? round((($current['total_sold'] - $previous['total_sold']) / $previous['total_sold']) * 100, 1)
            : 0;

        return response()->json([
            'success' => true,
            'data'    => [
                'period'         => Report::periodLabel($month, $year),
                'current'        => $current,
                'previous'       => $previous,
                'revenue_growth' => $revenueGrowth,
                'sold_growth'    => $soldGrowth,
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/admin/reports/refresh
    // Refresh snapshot laporan bulan tertentu
    // Body: { month, year }
    // ──────────────────────────────────────────────────────────────
    public function refresh(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer|min:2020|max:2099',
        ], [
            'month.required' => 'Bulan wajib diisi',
            'year.required'  => 'Tahun wajib diisi',
        ]);

        try {
            $count = Report::generateForMonth($request->year, $request->month);

            Log::info('Report: refresh laporan', [
                'period' => Report::periodLabel($request->month, $request->year),
                'count'  => $count,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil diperbarui',
                'data'    => [
                    'period'         => Report::periodLabel($request->month, $request->year),
                    'products_count' => $count,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Report: gagal refresh', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui laporan',
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────────

    private function getMonthlySummary(int $year, int $month): array
    {
        $result = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $month)
            ->select([
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.product_id) as total_products'),
            ])
            ->first();

        return [
            'total_orders'         => (int) ($result->total_orders ?? 0),
            'total_sold'           => (int) ($result->total_sold ?? 0),
            'total_products'       => (int) ($result->total_products ?? 0),
            'total_revenue'        => (float) ($result->total_revenue ?? 0),
            'total_revenue_format' => 'Rp. ' . number_format($result->total_revenue ?? 0, 0, ',', '.'),
        ];
    }

    private function getProductHistory(int $productId, int $year, int $month): array
    {
        $history = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = $month - $i;
            $y = $year;

            while ($m <= 0) {
                $m += 12;
                $y--;
            }

            $row = DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.status', 'completed')
                ->where('order_items.product_id', $productId)
                ->whereYear('orders.created_at', $y)
                ->whereMonth('orders.created_at', $m)
                ->select([
                    DB::raw('SUM(order_items.quantity) as total_sold'),
                    DB::raw('SUM(order_items.subtotal) as total_revenue'),
                ])
                ->first();

            $history[] = [
                'period'        => Report::periodLabel($m, $y),
                'month'         => $m,
                'year'          => $y,
                'total_sold'    => (int) ($row->total_sold ?? 0),
                'total_revenue' => (float) ($row->total_revenue ?? 0),
            ];
        }

        return $history;
    }
}