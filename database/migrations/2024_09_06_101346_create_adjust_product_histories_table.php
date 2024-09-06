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
        Schema::create('adjust_product_histories', function (Blueprint $table) {
            $table->timestamp("date")->nullable(false);
            $table->unsignedBigInteger("product_id")->nullable(false);
            $table->string("name", 100)->nullable(false);
            $table->string("unit", 20)->nullable();
            $table->string("category", 100)->nullable(false);
            $table->integer("quantity")->nullable(false);
            $table->string("message", 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjust_product_histories');
    }
};
