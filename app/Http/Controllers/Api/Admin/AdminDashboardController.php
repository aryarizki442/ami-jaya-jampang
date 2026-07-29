<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{

    public function index()
    {

        $summary = [
            'total_users'        => User::where('role', 'customer')->count(),
            'total_products'     => Product::count(),
            'total_orders'       => Order::count(),
            'revenue_this_month' => Order::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total'),
        ];

        $orderStats = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $revenueChart = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date        = now()->subDays($i)->format('Y-m-d');
            $chartData[] = [
                'date'  => $date,
                'label' => now()->subDays($i)->translatedFormat('D'),
                'total' => $revenueChart[$date]->total ?? 0,
            ];
        }

        $lowStock = Product::where('stock', '<', 10)
            ->where('is_active', 1)
            ->with('primaryImage')
            ->orderBy('stock')
            ->take(5)
            ->get(['id', 'name', 'stock', 'unit']);

        $recentOrders = Order::with(['user:id,name', 'payment'])
            ->latest()
            ->take(5)
            ->get(['id', 'order_number', 'user_id', 'status', 'total', 'created_at']);


        $topProducts = Product::with('primaryImage')
            ->where('total_sold', '>', 0)
            ->orderByDesc('total_sold')
            ->take(5)
            ->get(['id', 'name', 'total_sold', 'price']);

        return response()->json([
            'success' => true,
            'data'    => [
                'summary'       => $summary,
                'order_stats'   => $orderStats,
                'revenue_chart' => $chartData,
                'low_stock'     => $lowStock,
                'recent_orders' => $recentOrders,
                'top_products'  => $topProducts,
            ],
        ]);
    }

    // CHART
    public function chart(Request $request)
{
    $type = $request->type;

    switch ($type) {

        case 'week':
            return $this->weekChart($request);

        case 'month':
            return $this->monthChart($request);

        case 'year':
            return $this->yearChart($request);

        default:
            return response()->json([
                'success' => false,
                'message' => 'Type tidak valid'
            ], 400);
    }
}
private function weekChart(Request $request)
{
    $start = Carbon::parse($request->start_date)->startOfDay();
    $end   = Carbon::parse($request->end_date)->endOfDay();

    $labels = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
    $data = array_fill(0, 7, 0);

    $rows = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->where('orders.status', 'completed')
        ->whereBetween('orders.created_at', [$start, $end])
        ->selectRaw('DAYOFWEEK(orders.created_at) as day, SUM(order_items.quantity) as total_sold')
        ->groupByRaw('DAYOFWEEK(orders.created_at)')
        ->get();

    foreach ($rows as $row) {
        $index = $row->day == 1 ? 6 : $row->day - 2;
        $data[$index] = (int) $row->total_sold;
    }

    return response()->json([
        'success' => true,
        'labels' => $labels,
        'data' => $data
    ]);
}

private function monthChart(Request $request)
{
    $month = $request->month;
    $year  = $request->year;

    $labels = ['Minggu 1','Minggu 2','Minggu 3','Minggu 4','Minggu 5'];
    $data = [0,0,0,0,0];

    $rows = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->where('orders.status', 'completed')
        ->whereYear('orders.created_at', $year)
        ->whereMonth('orders.created_at', $month)
        ->selectRaw('CEIL(DAY(orders.created_at)/7) as week, SUM(order_items.quantity) as total_sold')
        ->groupByRaw('CEIL(DAY(orders.created_at)/7)')
        ->get();

    foreach ($rows as $row) {
        $data[$row->week - 1] = (int) $row->total_sold;
    }

    return response()->json([
        'success' => true,
        'labels' => $labels,
        'data' => $data
    ]);
}
private function yearChart(Request $request)
{
    $year = $request->year;

    $labels = [
        'Jan','Feb','Mar','Apr',
        'Mei','Jun','Jul','Agu',
        'Sep','Okt','Nov','Des'
    ];

    $data = array_fill(0, 12, 0);

    $rows = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->where('orders.status', 'completed')
        ->whereYear('orders.created_at', $year)
        ->selectRaw('MONTH(orders.created_at) as month, SUM(order_items.quantity) as total_sold')
        ->groupByRaw('MONTH(orders.created_at)')
        ->get();

    foreach ($rows as $row) {
        $data[$row->month - 1] = (int) $row->total_sold;
    }

    return response()->json([
        'success' => true,
        'labels' => $labels,
        'data' => $data
    ]);
}
}
