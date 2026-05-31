<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrdersApiTest extends TestCase
{
    use RefreshDatabase;

    protected $userId = 1;

    public function test_can_manipulate_cart()
    {
        // Add item to cart
        $response = $this->postJson('/api/cart/items', [
            'user_id' => $this->userId,
            'book_id' => 101,
            'quantity' => 2,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->userId,
            'book_id' => 101,
            'quantity' => 2,
        ]);

        // Get cart
        $response = $this->getJson('/api/cart?user_id=' . $this->userId);
        $response->assertStatus(200);

        // Update cart item quantity
        $cartItem = CartItem::first();
        $response = $this->putJson('/api/cart/items/' . $cartItem->id, [
            'quantity' => 5,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 5,
        ]);

        // Delete cart item
        $response = $this->deleteJson('/api/cart/items/' . $cartItem->id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_can_checkout_successfully_with_inter_service_calls()
    {
        // Fake all inter-service endpoints
        Http::fake([
            '*/api/books/101' => Http::response([
                'id' => 101,
                'title' => 'Laravel Masterclass',
                'price' => 500.00,
                'isbn' => '9781234567890'
            ], 200),
            '*/api/stock/deduct' => Http::response([
                'success' => true,
                'message' => 'Stock deducted'
            ], 200),
            '*/api/invoices' => Http::response([
                'success' => true,
                'invoice' => ['id' => 50]
            ], 201),
        ]);

        // Populate a cart item first
        CartItem::create([
            'user_id' => $this->userId,
            'book_id' => 101,
            'quantity' => 2,
        ]);

        // Perform checkout
        $response = $this->postJson('/api/orders', [
            'user_id' => $this->userId,
            'customer_name' => 'Jane Customer',
            'customer_email' => 'jane@example.com',
            'customer_phone' => '+639171234567',
            'shipping_address' => '123 Bookstore St, Manila',
            'shipping_city' => 'Manila',
            'shipping_zip' => '1000',
            'payment_method' => 'cod',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Order placed successfully',
            ]);

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'jane@example.com',
            'total' => 1170.00 // Subtotal = 1000 + 50 shipping + 120 VAT = 1170
        ]);

        // Verify cart is cleared
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $this->userId,
        ]);
    }

    public function test_can_track_order()
    {
        $order = Order::create([
            'user_id' => $this->userId,
            'order_number' => 'PT-ORDER-12345',
            'customer_name' => 'Jane Customer',
            'customer_email' => 'jane@example.com',
            'shipping_address' => 'Manila',
            'shipping_city' => 'Manila',
            'shipping_zip' => '1000',
            'subtotal' => 500,
            'shipping_fee' => 50,
            'tax' => 60,
            'total' => 610,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->getJson('/api/orders/track/PT-ORDER-12345');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'order_number' => 'PT-ORDER-12345',
                'label' => 'Order Placed',
            ]);
    }

    public function test_can_get_dashboard_sales()
    {
        Order::create([
            'user_id' => $this->userId,
            'order_number' => 'PT-ORDER-12345',
            'customer_name' => 'Jane Customer',
            'customer_email' => 'jane@example.com',
            'shipping_address' => 'Manila',
            'shipping_city' => 'Manila',
            'shipping_zip' => '1000',
            'subtotal' => 500,
            'shipping_fee' => 50,
            'tax' => 60,
            'total' => 610,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->getJson('/api/dashboard/sales');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_revenue',
                'order_count',
                'pending_count',
                'delivered_count',
                'sales_trend',
                'orders_by_status',
            ]);
    }
}
