<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    protected $fillable = [
        'product_id',
        'year',
        'month',
        'total_sold',
        'total_revenue',
    ];

    protected $casts = [
        'total_revenue' => 'float',
        'total_sold'    => 'integer',
        'year'          => 'integer',
        'month'         => 'integer',
    ];

    // ── Relasi ───────────────────────────────────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ── Static — Generate/Refresh laporan ────────────────────────

    /**
     * Generate atau refresh laporan untuk bulan & tahun tertentu
     * Dipanggil saat: order completed, atau admin minta refresh manual
     *
     * Cara kerja:
     * - Ambil semua order_items dari order yang completed pada bulan/tahun tsb
     * - Group by product_id
     * - Hitung total_sold & total_revenue per produk
     * - Upsert ke tabel reports
     */
    public static function generateForMonth(int $year, int $month): int
    {
        $data = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $month)
            ->select([
                'order_items.product_id',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
            ])
            ->groupBy('order_items.product_id')
            ->get();

        foreach ($data as $row) {
            static::updateOrCreate(
                [
                    'product_id' => $row->product_id,
                    'year'       => $year,
                    'month'      => $month,
                ],
                [
                    'total_sold'    => $row->total_sold,
                    'total_revenue' => $row->total_revenue,
                ]
            );
        }

        return $data->count();
    }

    /**
     * Query laporan langsung dari order_items (real-time, tanpa cache)
     * Dipakai untuk export dan tampil data fresh
     */
    public static function queryRealtime(int $year, int $month, ?string $search = null)
    {
        $query = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('product_images', function ($join) {
                $join->on('product_images.product_id', '=', 'products.id')
                     ->where('product_images.is_primary', 1);
            })
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->where('orders.status', 'completed')
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $month)
            ->select([
                'products.id as product_id',
                'products.name as product_name',
                'products.price as product_price',
                'products.weight_kg',
                'products.unit',
                'products.description',
                'categories.name as category_name',
                'product_images.image_url as product_image',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
            ])
            ->groupBy(
                'products.id',
                'products.name',
                'products.price',
                'products.weight_kg',
                'products.unit',
                'products.description',
                'categories.name',
                'product_images.image_url',
            );

        if ($search) {
            $query->where('products.name', 'like', "%{$search}%");
        }

        return $query;
    }

    /**
     * Nama bulan dalam Bahasa Indonesia
     */
    public static function monthName(int $month): string
    {
        return match($month) {
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            default => '-',
        };
    }

    /**
     * Label periode: "Maret 2026"
     */
    public static function periodLabel(int $month, int $year): string
    {
        return static::monthName($month) . ' ' . $year;
    }
}