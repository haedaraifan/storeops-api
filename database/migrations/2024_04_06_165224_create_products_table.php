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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100)->nullable(false);
            $table->integer("quantity")->nullable(false);
            $table->integer("purchase_price")->nullable(false);
            $table->integer("selling_price")->nullable(false);
            $table->string("image")->nullable();
            $table->string("category", 100)->nullable(false);
            $table->unsignedBigInteger("unit_id")->nullable();
            $table->timestamps();
            $table->timestamp("deleted_at")->nullable()->default(null);

            $table->foreign("unit_id")->on("product_units")->references("id")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
