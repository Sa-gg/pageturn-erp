<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Expense::create([
            'category' => 'shipping',
            'amount' => 500.00,
            'description' => 'LBC Shipping Fee',
            'expense_date' => \Carbon\Carbon::now()->subDays(2),
        ]);

        \App\Expense::create([
            'category' => 'marketing',
            'amount' => 1500.00,
            'description' => 'Facebook Ads',
            'expense_date' => \Carbon\Carbon::now()->subDays(5),
        ]);

        $invoice1 = \App\Invoice::create([
            'order_id'       => 1,
            'order_number'   => 'ORD-1001',
            'customer_name'  => 'John Doe',
            'customer_email' => 'john@example.com',
            'subtotal'       => 800.00,
            'shipping_fee'   => 50.00,
            'tax'            => 96.00,
            'discount'       => 0.00,
            'total'          => 946.00,
            'status'         => 'paid',
            'issue_date'     => \Carbon\Carbon::now()->subDays(3),
            'due_date'       => \Carbon\Carbon::now()->addDays(4),
        ]);

        $invoice1->items()->create([
            'book_id'    => 1,
            'book_title' => 'The Great Gatsby',
            'unit_price' => 400.00,
            'quantity'   => 2,
            'subtotal'   => 800.00,
        ]);

        $invoice1->payments()->create([
            'amount'         => 946.00,
            'payment_method' => 'card',
            'payment_date'   => \Carbon\Carbon::now()->subDays(3),
            'status'         => 'successful',
        ]);
    }
}
