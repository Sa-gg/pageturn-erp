<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FinanceService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.finance.base_url');
    }

    /**
     * Create an invoice in the Finance service when an order is confirmed.
     *
     * @param array $orderData  Order details to create an invoice from
     * @return array ['success' => bool, 'invoice' => array|null, 'error' => string|null]
     */
    public function createInvoice(array $orderData)
    {
        try {
            $payload = [
                'order_id'       => $orderData['id'],
                'order_number'   => $orderData['order_number'],
                'customer_name'  => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'],
                'subtotal'       => $orderData['subtotal'],
                'shipping_fee'   => $orderData['shipping_fee'],
                'tax'            => $orderData['tax'],
                'discount'       => $orderData['discount'] ?? 0,
                'total'          => $orderData['total'],
                'items'          => $orderData['items'] ?? [],
            ];

            $internalToken = env('INTERNAL_SERVICE_TOKEN');

            $httpClient = Http::timeout(15);
            if (!empty($internalToken)) {
                $httpClient = $httpClient->withHeaders([
                    'X-Internal-Token' => $internalToken,
                ]);
            }

            $response = $httpClient->post("{$this->baseUrl}/invoices", $payload);

            if ($response->successful()) {
                Log::info("FinanceService: Invoice created for order {$orderData['order_number']}");
                return [
                    'success' => true,
                    'invoice' => $response->json(),
                    'error'   => null,
                ];
            }

            $error = $response->json('message') ?? $response->body();
            Log::warning("FinanceService: Failed to create invoice for order {$orderData['order_number']}", [
                'status' => $response->status(),
                'error'  => $error,
            ]);

            return ['success' => false, 'invoice' => null, 'error' => $error];
        } catch (\Exception $e) {
            Log::error("FinanceService: Exception creating invoice for order {$orderData['order_number']}", [
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'invoice' => null, 'error' => $e->getMessage()];
        }
    }
}
