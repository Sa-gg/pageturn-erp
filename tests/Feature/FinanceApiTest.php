<?php

namespace Tests\Feature;

use App\Invoice;
use App\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_invoice_internal()
    {
        $response = $this->postJson('/api/invoices', [
            'id' => 12,
            'order_number' => 'PT-ORDER-12345',
            'customer_name' => 'Jane Customer',
            'customer_email' => 'jane@example.com',
            'subtotal' => 1000.00,
            'shipping_fee' => 50.00,
            'tax' => 120.00,
            'total' => 1170.00,
            'items' => [
                [
                    'book_id' => 101,
                    'book_title' => 'Laravel Masterclass',
                    'unit_price' => 500.00,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('invoices', [
            'order_number' => 'PT-ORDER-12345',
            'customer_email' => 'jane@example.com',
            'total' => 1170.00,
        ]);
        $this->assertDatabaseHas('invoice_items', [
            'book_id' => 101,
            'quantity' => 2,
        ]);
    }

    public function test_can_pay_invoice_and_updates_status()
    {
        $invoice = Invoice::create([
            'order_id' => 12,
            'order_number' => 'PT-ORDER-12345',
            'customer_name' => 'Jane Customer',
            'customer_email' => 'jane@example.com',
            'subtotal' => 1000.00,
            'shipping_fee' => 50.00,
            'tax' => 120.00,
            'total' => 1170.00,
            'status' => 'unpaid',
        ]);

        $response = $this->patchJson("/api/invoices/{$invoice->id}/pay", [
            'payment_method' => 'card',
            'reference_number' => 'REF123456',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
        ]);
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'payment_method' => 'card',
            'reference_number' => 'REF123456',
        ]);
    }

    public function test_expense_crud()
    {
        $response = $this->postJson('/api/expenses', [
            'title' => 'Office Supplies',
            'category' => 'operations',
            'amount' => 150.00,
            'description' => 'Paper, pens, sticky notes',
            'incurred_at' => '2026-05-25',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('expenses', [
            'title' => 'Office Supplies',
            'amount' => 150.00,
        ]);
    }

    public function test_financial_reports()
    {
        $invoice = Invoice::create([
            'order_id' => 12,
            'order_number' => 'PT-ORDER-12345',
            'customer_name' => 'Jane Customer',
            'customer_email' => 'jane@example.com',
            'subtotal' => 1000.00,
            'shipping_fee' => 50.00,
            'tax' => 120.00,
            'total' => 1170.00,
            'status' => 'paid',
        ]);

        $response = $this->getJson('/api/reports/revenue');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'total_revenue' => 1000.00, // Revenue reports on subtotal or total
            ]);
    }
}
