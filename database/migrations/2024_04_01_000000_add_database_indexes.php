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
        // Products table indexes
        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('supplier_id');
            $table->index('product_store');
            $table->index('expire_date');
            $table->index('product_code');
            $table->index('product_name');
        });

        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('order_status');
            $table->index('order_date');
            $table->index('pay');
            $table->index('due');
        });

        // Order details table indexes
        Schema::table('order_details', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });

        // Customers table indexes
        Schema::table('customers', function (Blueprint $table) {
            $table->index('name');
            $table->index('email');
            $table->index('phone');
            if (Schema::hasColumn('customers', 'deleted_at')) {
                $table->index('deleted_at');
            }
        });

        // Suppliers table indexes
        Schema::table('suppliers', function (Blueprint $table) {
            $table->index('name');
            $table->index('email');
            $table->index('phone');
        });

        // Employees table indexes
        Schema::table('employees', function (Blueprint $table) {
            $table->index('name');
            $table->index('email');
        });

      

        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks to avoid constraints issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['product_store']);
            $table->dropIndex(['expire_date']);
            $table->dropIndex(['product_code']);
            $table->dropIndex(['product_name']);
        });

        // Orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['order_status']);
            $table->dropIndex(['order_date']);
            $table->dropIndex(['pay']);
            $table->dropIndex(['due']);
        });

        // Order details table
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });

        // Customers table
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
            if (Schema::hasColumn('customers', 'deleted_at')) {
                $table->dropIndex(['deleted_at']);
            }
        });

        // Suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
        });

        // Employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
        });

        // Attendences table
        Schema::table('attendences', function (Blueprint $table) {
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['date']);
        });

        // Categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}; 