<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inventory_item_id');
            $table->unsignedBigInteger('book_id');
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->integer('quantity');                     // positive = add, negative = subtract
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->string('reason')->nullable();           // "Order PT-xxx confirmed", "PO received", etc.
            $table->string('reference')->nullable();        // order number, PO number
            $table->timestamps();

            $table->foreign('inventory_item_id')
                  ->references('id')
                  ->on('inventory_items')
                  ->onDelete('cascade');

            $table->index('book_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
