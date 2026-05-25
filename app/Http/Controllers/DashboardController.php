<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Sales overview stats.
     * GET /api/dashboard/sales
     */
    public function sales(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month

        // Total revenue
        $totalRevenue = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('total');

        // Today's revenue
        $todayRevenue = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->whereDate('created_at', today())
            ->sum('total');

        // This week's revenue
        $weekRevenue = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->where('created_at', '>=', now()->startOfWeek())
            ->sum('total');

        // This month's revenue
        $monthRevenue = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total');

        // Order counts
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $todayOrders   = Order::whereDate('created_at', today())->count();

        // Average order value
        $avgOrderValue = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->avg('total') ?? 0;

        // Recent orders (last 10)
        $recentOrders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Revenue trend (daily for last 30 days)
        $revenueTrend = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw("SUM(total) as revenue"),
                DB::raw("COUNT(*) as order_count")
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return response()->json([
            'total_revenue'    => round($totalRevenue, 2),
            'today_revenue'    => round($todayRevenue, 2),
            'week_revenue'     => round($weekRevenue, 2),
            'month_revenue'    => round($monthRevenue, 2),
            'total_orders'     => $totalOrders,
            'pending_orders'   => $pendingOrders,
            'today_orders'     => $todayOrders,
            'avg_order_value'  => round($avgOrderValue, 2),
            'recent_orders'    => $recentOrders,
            'revenue_trend'    => $revenueTrend,
            'orders_by_status' => $ordersByStatus,
        ]);
    }

    /**
     * Top selling books.
     * GET /api/dashboard/top-books
     */
    public function topBooks(Request $request)
    {
        $limit = $request->get('limit', 10);

        $topBooks = OrderItem::select(
                'book_id',
                'book_title',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as order_count')
            )
            ->whereHas('order', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'refunded']);
            })
            ->groupBy('book_id', 'book_title')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        return response()->json([
            'top_books' => $topBooks,
        ]);
    }
}
