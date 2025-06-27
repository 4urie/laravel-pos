<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 
     * 
     * 1. inventory_status_view:
     *    - Monitors current stock levels of all products
     *    - Identifies products with low or critical stock levels
     *    - Tracks products that are expiring soon
     *    - Helps in inventory management and reordering decisions
     * 
     * 2. product_performance_view:
     *    - Analyzes product sales performance
     *    - Tracks revenue and profit per product
     *    - Shows ordering frequency and quantities
     *    - Helps identify best-selling and most profitable products
     * 
     * 3. customer_analytics_view:
     *    - Provides customer purchase history and behavior
     *    - Tracks customer spending patterns
     *    - Identifies valuable customers based on order frequency and value
     *    - Helps in customer retention and marketing strategies
     * 
     * 4. supplier_performance_view:
     *    - Evaluates supplier performance metrics
     *    - Tracks inventory value per supplier
     *    - Monitors product availability and expiry
     *    - Helps in supplier relationship management
     * 
     * 
     * 
     */
    public function up(): void
    {
        // Create Sales Summary View
       
        DB::statement('
            CREATE OR REPLACE VIEW inventory_status_view AS
            SELECT 
                p.id,
                p.product_name,
                p.product_code,
                p.product_store as current_stock,
                p.buying_price,
                p.selling_price,
                c.name as category_name,
                s.name as supplier_name,
                CASE 
                    WHEN p.product_store <= 10 THEN "Low Stock"
                    WHEN p.product_store <= 5 THEN "Critical Stock"
                    ELSE "In Stock"
                END as stock_status,
                CASE 
                    WHEN p.expire_date IS NOT NULL AND p.expire_date <= DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY) 
                    THEN "Expiring Soon"
                    ELSE "Valid"
                END as expiry_status
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN suppliers s ON p.supplier_id = s.id
            ORDER BY p.product_store ASC
        ');

        // Create Product Performance View
        DB::statement('
            CREATE OR REPLACE VIEW product_performance_view AS
            SELECT 
                p.id,
                p.product_name,
                p.product_code,
                p.product_image,
                c.name as category_name,
                COUNT(DISTINCT o.id) as times_ordered,
                SUM(od.quantity) as total_quantity_sold,
                SUM(od.quantity * od.unitcost) as total_revenue,
                SUM(od.quantity * (od.unitcost - p.buying_price)) as total_profit,
                AVG(od.quantity) as avg_quantity_per_order,
                p.product_store as current_stock
            FROM products p
            LEFT JOIN order_details od ON p.id = od.product_id
            LEFT JOIN orders o ON od.order_id = o.id AND o.order_status = "complete"
            JOIN categories c ON p.category_id = c.id
            GROUP BY p.id, p.product_name, p.product_code, p.product_image, c.name, p.product_store
            ORDER BY total_revenue DESC
        ');

        // Create Customer Analytics View
        DB::statement('
            CREATE OR REPLACE VIEW customer_analytics_view AS
            SELECT 
                c.id,
                c.name as customer_name,
                c.email,
                c.phone,
                COUNT(DISTINCT o.id) as total_orders,
                SUM(od.quantity * od.unitcost) as total_spent,
                AVG(od.quantity * od.unitcost) as avg_order_value,
                MAX(o.order_date) as last_order_date,
                DATEDIFF(CURRENT_DATE, MAX(o.order_date)) as days_since_last_order
            FROM customers c
            LEFT JOIN orders o ON c.id = o.customer_id AND o.order_status = "complete"
            LEFT JOIN order_details od ON o.id = od.order_id
            GROUP BY c.id, c.name, c.email, c.phone
            ORDER BY total_spent DESC
        ');

        // Create Supplier Performance View
        DB::statement('
            CREATE OR REPLACE VIEW supplier_performance_view AS
            SELECT 
                s.id,
                s.name as supplier_name,
                s.email,
                s.phone,
                COUNT(DISTINCT p.id) as total_products_supplied,
                SUM(p.product_store) as total_stock_supplied,
                SUM(p.product_store * p.buying_price) as total_inventory_value,
                COUNT(CASE WHEN p.product_store <= 10 THEN 1 END) as low_stock_products,
                COUNT(CASE WHEN p.expire_date <= DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY) THEN 1 END) as products_expiring_soon
            FROM suppliers s
            LEFT JOIN products p ON s.id = p.supplier_id
            GROUP BY s.id, s.name, s.email, s.phone
            ORDER BY total_inventory_value DESC
        ');
    }

    /**
     * Drops all created views when rolling back the migration
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS sales_summary_view');
        DB::statement('DROP VIEW IF EXISTS inventory_status_view');
        DB::statement('DROP VIEW IF EXISTS product_performance_view');
        DB::statement('DROP VIEW IF EXISTS customer_analytics_view');
        DB::statement('DROP VIEW IF EXISTS supplier_performance_view');
    }
};