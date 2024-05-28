<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restock_product_histories', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100)->nullable(false);
            $table->integer("purchase_price")->nullable(false);
            $table->integer("selling_price")->nullable(false);
            $table->integer("quantity")->nullable(false);
            $table->string("destination_address", 255)->nullable(false);
            $table->string("supplier_name", 100)->nullable(false);
            $table->string("supplier_address", 255)->nullable(false);
            $table->string("supplier_phone", 20)->nullable(false);
            $table->string("shipping_method", 100)->nullable(false);
            $table->string("payment_method", 100)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_product_histories');
    }
};
