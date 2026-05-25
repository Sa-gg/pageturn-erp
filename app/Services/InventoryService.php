<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InventoryService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.inventory.base_url', 'http://localhost:8004/api');
    }

    public function getInventory($params = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/inventory", $params);
    }

    public function getAlerts()
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/inventory/alerts");
    }

    public function adjustStock($data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->post("{$this->baseUrl}/stock/adjust", $data);
    }

    public function getSuppliers($params = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/suppliers", $params);
    }

    public function createSupplier($data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->post("{$this->baseUrl}/suppliers", $data);
    }

    public function updateSupplier($id, $data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->put("{$this->baseUrl}/suppliers/{$id}", $data);
    }

    public function deleteSupplier($id)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->delete("{$this->baseUrl}/suppliers/{$id}");
    }

    public function getPurchaseOrders($params = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/purchase-orders", $params);
    }

    public function getPurchaseOrder($id)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/purchase-orders/{$id}");
    }

    public function createPurchaseOrder($data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->post("{$this->baseUrl}/purchase-orders", $data);
    }

    public function receivePurchaseOrder($id, $data = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->patch("{$this->baseUrl}/purchase-orders/{$id}/receive", $data);
    }
}
