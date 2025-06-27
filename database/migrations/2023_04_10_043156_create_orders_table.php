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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('set null');

            $table->date('order_date'); // Changed from string to date
            $table->string('order_status');
            $table->integer('total_products');

            // Use decimal for money-related columns to avoid rounding errors
            $table->decimal('sub_total', 10, 2)->nullable();
            $table->decimal('vat', 10, 2)->nullable();
            $table->string('invoice_no')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->decimal('pay', 10, 2)->nullable();
            $table->decimal('due', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
