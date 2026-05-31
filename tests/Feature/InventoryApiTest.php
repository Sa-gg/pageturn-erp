<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InventoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected $supplier;
    protected $inventoryItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supplier = Supplier::create([
            'name' => 'Acme Publishing',
            'contact_name' => 'John Supplier',
            'email' => 'acme@example.com',
            'phone' => '+639170000000',
            'address' => 'Supplier St, Quezon City',
        ]);

        $this->inventoryItem = InventoryItem::create([
            'book_id' => 101,
            'book_title' => 'Laravel Masterclass',
            'quantity_on_hand' => 10,
            'reorder_level' => 3,
            'reorder_quantity' => 20,
            'location' => 'Shelf A-4',
        ]);
    }

    public function test_can_list_inventory()
    {
        $response = $this->getJson('/api/inventory');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'book_title' => 'Laravel Masterclass',
            ]);
    }

    public function test_can_create_inventory_record()
    {
        $response = $this->postJson('/api/inventory', [
            'book_id' => 202,
            'book_title' => 'Tailwind Mastery',
            'quantity_on_hand' => 15,
            'reorder_level' => 5,
            'reorder_quantity' => 25,
            'location' => 'Shelf B-2',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('inventory_items', [
            'book_id' => 202,
            'book_title' => 'Tailwind Mastery',
        ]);
    }

    public function test_can_deduct_stock_and_marks_unavailable_when_zero()
    {
        // Fake availability update call to Catalog service
        Http::fake([
            '*/api/books/101/availability' => Http::response(['message' => 'Updated'], 200),
        ]);

        $response = $this->postJson('/api/stock/deduct', [
            'items' => [
                ['book_id' => 101, 'quantity' => 10], // Deduct everything
            ],
            'reference' => 'PT-ORDER-111',
            'reason' => 'Customer purchase',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventory_items', [
            'book_id' => 101,
            'quantity_on_hand' => 0,
        ]);

        // Verify the catalog availability patch call was made
        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/books/101/availability') &&
                   $request->method() === 'PATCH' &&
                   $request['is_available'] === false;
        });
    }

    public function test_can_adjust_stock_manually()
    {
        // Fake availability update call to Catalog service
        Http::fake([
            '*/api/books/101/availability' => Http::response(['message' => 'Updated'], 200),
        ]);

        $response = $this->postJson('/api/stock/adjust', [
            'book_id' => 101,
            'quantity' => 5, // Add stock
            'reason' => 'Inventory count update',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventory_items', [
            'book_id' => 101,
            'quantity_on_hand' => 15,
        ]);
    }

    public function test_supplier_crud()
    {
        $response = $this->postJson('/api/suppliers', [
            'name' => 'New Publisher',
            'contact_name' => 'Jane Publisher',
            'email' => 'newpub@example.com',
            'phone' => '+639179999999',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('suppliers', ['email' => 'newpub@example.com']);
    }
}
