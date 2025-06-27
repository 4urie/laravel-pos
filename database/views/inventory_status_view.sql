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
        WHEN p.product_store <= 10 THEN 'Low Stock'
        WHEN p.product_store <= 5 THEN 'Critical Stock'
        ELSE 'In Stock'
    END as stock_status,
    CASE 
        WHEN p.expire_date IS NOT NULL AND p.expire_date <= DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY) 
        THEN 'Expiring Soon'
        ELSE 'Valid'
    END as expiry_status
FROM products p
JOIN categories c ON p.category_id = c.id
JOIN suppliers s ON p.supplier_id = s.id
ORDER BY p.product_store ASC;