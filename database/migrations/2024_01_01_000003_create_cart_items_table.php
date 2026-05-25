<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->string('book_title');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'book_id']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
