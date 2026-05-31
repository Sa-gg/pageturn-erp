<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class FinanceService
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
        $this->baseUrl = config('services.finance.base_url', 'http://localhost:8005/api');
    }

    public function getReports()
    {
        return $this->client()->get("{$this->baseUrl}/reports/revenue");
    }

    public function getInvoices($params = [])
    {
        return $this->client()->get("{$this->baseUrl}/invoices", $params);
    }

    public function payInvoice($id, $data = [])
    {
        return $this->client()->patch("{$this->baseUrl}/invoices/{$id}/pay", $data);
    }

    public function getExpenses($params = [])
    {
        return $this->client()->get("{$this->baseUrl}/expenses", $params);
    }

    public function createExpense($data)
    {
        return $this->client()->post("{$this->baseUrl}/expenses", $data);
    }

    public function getProfitReport()
    {
        return $this->client()->get("{$this->baseUrl}/reports/profit");
    }

    public function getTopBooksReport()
    {
        return $this->client()->get("{$this->baseUrl}/reports/top-books");
    }
}
