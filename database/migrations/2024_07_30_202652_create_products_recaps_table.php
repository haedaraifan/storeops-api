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
        Schema::create('products_recap', function (Blueprint $table) {
            $table->timestamp("date")->nullable(false);
            $table->unsignedBigInteger("product_id")->nullable(false);
            $table->string("name")->nullable(false);
            $table->integer("first_quantity")->nullable(false);
            $table->integer("last_quantity")->nullable(false);
            $table->integer("incoming_quantity")->nullable(false);
            $table->integer("outgoing_quantity")->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_recap');
    }
};
