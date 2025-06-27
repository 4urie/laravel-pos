DROP PROCEDURE IF EXISTS GetLowStockProducts;

CREATE PROCEDURE GetLowStockProducts(IN threshold INT)
BEGIN    SELECT 
        p.id,
        p.product_name,
        p.product_code,
        p.product_store as current_stock,
        c.name as category_name,
        s.name as supplier_name,
        p.buying_price,
        p.selling_price
    FROM 
        products p
    JOIN 
        categories c ON p.category_id = c.id
    JOIN 
        suppliers s ON p.supplier_id = s.id
    WHERE 
        p.product_store <= threshold
    ORDER BY 
        p.product_store ASC;
END; 