<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FinanceService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.finance.base_url', 'http://localhost:8005/api');
    }

    public function getReports()
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/reports/revenue");
    }

    public function getInvoices($params = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/invoices", $params);
    }

    public function payInvoice($id, $data = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->patch("{$this->baseUrl}/invoices/{$id}/pay", $data);
    }

    public function getExpenses($params = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/expenses", $params);
    }

    public function createExpense($data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->post("{$this->baseUrl}/expenses", $data);
    }

    public function getProfitReport()
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/reports/profit");
    }

    public function getTopBooksReport()
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/reports/top-books");
    }
}
