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
            $table->string("date", 50)->nullable(false);
            $table->string("name", 100)->nullable(false);
            $table->integer("quantity")->nullable(false);
            $table->integer("purchase_price")->nullable(false);
            $table->integer("selling_price")->nullable(false);
            $table->unsignedBigInteger("user_id")->nullable(false);

            $table->foreign("user_id")->on("users")->references("id");
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
