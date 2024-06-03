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
        Schema::create('add_product_histories', function (Blueprint $table) {
            $table->timestamp("date")->nullable(false);
            $table->string("name", 100)->nullable(false);
            $table->integer("quantity")->nullable(false);
            $table->string("category", 100)->nullable(false);
            $table->string("unit", 20)->nullable(false);
            $table->integer("purchase_price")->nullable(false);
            $table->integer("selling_price")->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_product_histories');
    }
};
