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
        Schema::create('transaction_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id")->nullable(false);
            $table->string("name", 100)->nullable(false);
            $table->integer("quantity")->nullable(false);
            $table->integer("price")->nullable(false);
            $table->unsignedBigInteger("transaction_id")->nullable(false);
            $table->unsignedBigInteger('option_id')->nullable(false);
            $table->boolean('is_checked')->default(false);

            $table->foreign("transaction_id")->on("transactions")->references("id")->onDelete('CASCADE');
            $table->foreign('option_id')->references('id')->on('transaction_product_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_products');
    }
};
