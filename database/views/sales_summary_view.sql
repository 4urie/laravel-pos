CREATE OR REPLACE VIEW sales_summary_view AS
SELECT 
    DATE(orders.order_date) as sale_date,
    COUNT(DISTINCT orders.id) as total_orders,
    SUM(order_details.quantity) as total_items_sold,
    SUM(order_details.quantity * order_details.unit_price) as total_revenue,
    SUM(order_details.quantity * (order_details.unit_price - products.buying_price)) as total_profit,
    GROUP_CONCAT(DISTINCT products.product_name) as products_sold
FROM orders
JOIN order_details ON orders.id = order_details.order_id
JOIN products ON order_details.product_id = products.id
WHERE orders.status = 'completed'
GROUP BY DATE(orders.order_date)
ORDER BY sale_date DESC; 