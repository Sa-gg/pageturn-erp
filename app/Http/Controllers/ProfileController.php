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
            if (isset($user['id'])) {
                $response = $this->orderService->getOrders([
                    'user_id' => $user['id'],
                    'limit' => 10
                ]);
                if ($response->successful()) {
                    $orders = $response->json();
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch orders for profile: ' . $e->getMessage());
        }

        return view('profile.index', compact('user', 'orders'));
    }
}
