<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $status = $request->get('status', '');
        
        $orders = [];
        
        try {
            $params = ['page' => $page, 'limit' => 20];
            if ($status) {
                $params['status'] = $status;
            }
            
            $response = $this->orderService->getOrders($params);
            
            if ($response->successful()) {
                $orders = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch orders for admin: ' . $e->getMessage());
        }

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        try {
            $response = $this->orderService->updateOrderStatus($id, $request->status);
            
            if ($response->successful()) {
                return back()->with('success', 'Order status updated successfully.');
            }
            
            return back()->with('error', 'Failed to update order status. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to update order status: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Orders service.');
        }
    }
}
