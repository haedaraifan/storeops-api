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
        Schema::create('transaction_custom_nominals', function (Blueprint $table) {
            $table->unsignedBigInteger("transaction_id")->nullable(false);
            $table->unsignedBigInteger("nominal_type_id")->nullable(false);
            $table->integer("amount")->nullable(false);

            $table->foreign("transaction_id")->on("transactions")->references("id")->onDelete('cascade');
            $table->foreign("nominal_type_id")->on("transaction_nominal_types")->references("id")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_custom_nominals');
    }
};
