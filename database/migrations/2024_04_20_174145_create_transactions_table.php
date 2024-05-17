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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamp("date")->nullable(false);
            $table->string("note", 255)->nullable();
            $table->integer("purchase_price")->nullable();
            $table->integer("selling_price")->nullable();
            $table->integer("discount")->nullable();
            $table->integer("additional_cost")->nullable();
            $table->string("payment_method", 20)->nullable();
            $table->string("customer_name", 100)->nullable();
            $table->string("customer_phone", 20)->nullable();
            $table->string("customer_address", 255)->nullable();
            $table->unsignedBigInteger("type_id")->nullable(false);
            $table->unsignedBigInteger("status_id")->nullable(false);

            $table->foreign("type_id")->on("transaction_types")->references("id");
            $table->foreign("status_id")->on("transaction_statuses")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
