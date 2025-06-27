<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Product composite indexes for filtering and sorting
        Schema::table('products', function (Blueprint $table) {
            // For filtering products by category and sorting by stock level
            $table->index(['category_id', 'product_store']);
            
            // For supplier performance reports
            $table->index(['supplier_id', 'product_store']);
            
            // For expiry tracking by category
            $table->index(['expire_date', 'category_id']);
        });

        // Orders composite indexes
        Schema::table('orders', function (Blueprint $table) {
            // For date range filtering combined with status
            $table->index(['order_date', 'order_status']);
            
            // For customer spending reports
            $table->index(['customer_id', 'order_date']);
            
            // For filtering outstanding dues
            $table->index(['order_status', 'due']);
        });
        
        // Order details composite indexes
        Schema::table('order_details', function (Blueprint $table) {
            // For product sales reports by date
            $table->index(['product_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks to avoid constraints issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Products table composite indexes
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id', 'product_store']);
            $table->dropIndex(['supplier_id', 'product_store']);
            $table->dropIndex(['expire_date', 'category_id']);
        });

        // Orders table composite indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_date', 'order_status']);
            $table->dropIndex(['customer_id', 'order_date']);
            $table->dropIndex(['order_status', 'due']);
        });
        
        // Order details composite indexes
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'order_id']);
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 