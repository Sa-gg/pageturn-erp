<?php

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\InventoryItem;
use App\Models\StockMovement;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Suppliers
        $suppliers = [
            ['name' => 'Penguin Random House', 'email' => 'orders@penguin.example.com', 'phone' => '123-456-7890', 'address' => '1745 Broadway, New York, NY 10019'],
            ['name' => 'O\'Reilly Media', 'email' => 'distribution@oreilly.example.com', 'phone' => '098-765-4321', 'address' => '1005 Gravenstein Highway North, Sebastopol, CA 95472'],
            ['name' => 'Scholastic Corporation', 'email' => 'sales@scholastic.example.com', 'phone' => '555-555-5555', 'address' => '557 Broadway, New York, NY 10012'],
        ];

        foreach ($suppliers as $sup) {
            Supplier::updateOrCreate(['email' => $sup['email']], $sup);
        }

        // Inventory Items for 8 books
        $books = [
            1 => 'Harry Potter and the Philosopher\'s Stone',
            2 => '1984',
            3 => 'Clean Code',
            4 => 'Sapiens: A Brief History of Humankind',
            5 => 'The Cat in the Hat',
            6 => 'One Piece Vol. 1',
            7 => 'Animal Farm',
            8 => 'The Clean Coder',
        ];

        foreach ($books as $id => $title) {
            $quantity = rand(20, 100);
            
            $item = InventoryItem::firstOrCreate(
                ['book_id' => $id],
                [
                    'book_title' => $title,
                    'quantity_on_hand' => $quantity,
                    'reorder_level' => 10,
                    'reorder_quantity' => 50,
                    'location' => 'Aisle ' . rand(1, 5) . ', Shelf ' . rand(1, 10),
                ]
            );

            // Create initial stock movement if it's newly created
            if ($item->wasRecentlyCreated) {
                StockMovement::create([
                    'inventory_item_id' => $item->id,
                    'book_id'           => $item->book_id,
                    'type'              => 'in',
                    'quantity'          => $quantity,
                    'quantity_before'   => 0,
                    'quantity_after'    => $quantity,
                    'reason'            => 'Initial stock setup from seeder',
                ]);
            }
        }
    }
}
