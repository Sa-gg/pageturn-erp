<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $user = session('user');
        
        if (!$user) {
            return redirect('/login');
        }

        $orders = [];
        try {
            // In a real app we would pass the customer email or ID, but here we just fetch recent orders to simulate
            $response = $this->orderService->getOrders(['limit' => 5]);
            if ($response->successful()) {
                $orders = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch orders for profile: ' . $e->getMessage());
        }

        return view('profile.index', compact('user', 'orders'));
    }
}
