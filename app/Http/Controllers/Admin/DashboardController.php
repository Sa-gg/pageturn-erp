<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use App\Services\OrderService;
use App\Services\FinanceService;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $catalogService;
    protected $orderService;
    protected $financeService;
    protected $authService;

    public function __construct(
        CatalogService $catalogService,
        OrderService $orderService,
        FinanceService $financeService,
        AuthService $authService
    ) {
        $this->catalogService = $catalogService;
        $this->orderService = $orderService;
        $this->financeService = $financeService;
        $this->authService = $authService;
    }

    public function index()
    {
        $stats = [
            'total_revenue' => 0,
            'total_orders' => 0,
            'books_count' => 0,
            'active_users' => 0,
        ];

        try {
            $financeRes = $this->financeService->getReports();
            if ($financeRes->successful()) {
                $stats['total_revenue'] = $financeRes->json('total_revenue') ?? 0;
            }
        } catch (\Exception $e) {
            Log::error('Finance stats failed: ' . $e->getMessage());
        }

        try {
            $ordersRes = $this->orderService->getSalesStats();
            if ($ordersRes->successful()) {
                $stats['total_orders'] = $ordersRes->json('total_orders') ?? 0;
            }
        } catch (\Exception $e) {
            Log::error('Order stats failed: ' . $e->getMessage());
        }

        try {
            $catalogRes = $this->catalogService->getBooks(['limit' => 1]);
            if ($catalogRes->successful()) {
                $stats['books_count'] = $catalogRes->json('total') ?? count($catalogRes->json('data') ?? []);
            }
        } catch (\Exception $e) {
            Log::error('Catalog stats failed: ' . $e->getMessage());
        }

        try {
            $authRes = $this->authService->getUsers(['limit' => 1, 'is_active' => 1]);
            if ($authRes->successful()) {
                $stats['active_users'] = $authRes->json('total') ?? count($authRes->json('data') ?? []);
            }
        } catch (\Exception $e) {
            Log::error('Auth stats failed: ' . $e->getMessage());
        }

        return view('admin.dashboard', compact('stats'));
    }
}
