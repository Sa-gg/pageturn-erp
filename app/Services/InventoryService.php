<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class InventoryService
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
        return $token ? Http::withToken($token)->acceptJson() : Http::acceptJson();
    }

    public function __construct()
    {
        $this->baseUrl = config('services.inventory.base_url', 'http://localhost:8004/api');
    }

    public function getInventory($params = [])
    {
        return $this->client()->get("{$this->baseUrl}/inventory", $params);
    }

    public function getAlerts()
    {
        return $this->client()->get("{$this->baseUrl}/inventory/alerts");
    }

    public function adjustStock($data)
    {
        return $this->client()->post("{$this->baseUrl}/stock/adjust", $data);
    }

    public function getSuppliers($params = [])
    {
        return $this->client()->get("{$this->baseUrl}/suppliers", $params);
    }

    public function createSupplier($data)
    {
        return $this->client()->post("{$this->baseUrl}/suppliers", $data);
    }

    public function updateSupplier($id, $data)
    {
        return $this->client()->put("{$this->baseUrl}/suppliers/{$id}", $data);
    }

    public function deleteSupplier($id)
    {
        return $this->client()->delete("{$this->baseUrl}/suppliers/{$id}");
    }

    public function getPurchaseOrders($params = [])
    {
        return $this->client()->get("{$this->baseUrl}/purchase-orders", $params);
    }

    public function getPurchaseOrder($id)
    {
        return $this->client()->get("{$this->baseUrl}/purchase-orders/{$id}");
    }

    public function createPurchaseOrder($data)
    {
        return $this->client()->post("{$this->baseUrl}/purchase-orders", $data);
    }

    public function receivePurchaseOrder($id, $data = [])
    {
        return $this->client()->patch("{$this->baseUrl}/purchase-orders/{$id}/receive", $data);
    }
}
