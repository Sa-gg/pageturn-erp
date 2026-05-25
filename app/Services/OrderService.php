<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OrderService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.orders.base_url', 'http://localhost:8003/api');
    }

    public function createOrder($data)
    {
        return Http::post("{$this->baseUrl}/orders", $data);
    }
    
    public function getOrders($params = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/orders", $params);
    }

    public function getSalesStats($params = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/dashboard/sales", $params);
    }

    public function updateOrderStatus($id, $status)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->patch("{$this->baseUrl}/orders/{$id}/status", [
            'status' => $status
        ]);
    }
}
