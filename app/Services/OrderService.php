<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OrderService
{
    protected string $baseUrl;

    protected function token(): ?string
    {
        $token = session('user.api_token') ?? session('token') ?? session('api_token');
        return is_string($token) ? $token : null;
    }

    protected function client(): PendingRequest
    {
        $token = $this->token();
        return $token ? Http::withToken($token) : Http::acceptJson();
    }

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
        return $this->client()->get("{$this->baseUrl}/orders", $params);
    }

    public function getSalesStats($params = [])
    {
        return $this->client()->get("{$this->baseUrl}/dashboard/sales", $params);
    }

    public function updateOrderStatus($id, $status)
    {
        return $this->client()->patch("{$this->baseUrl}/orders/{$id}/status", [
            'status' => $status
        ]);
    }
}
