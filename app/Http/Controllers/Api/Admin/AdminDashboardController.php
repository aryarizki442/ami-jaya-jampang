<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
}