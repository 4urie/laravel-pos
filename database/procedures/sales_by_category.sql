DROP PROCEDURE IF EXISTS GetSalesByCategory;

CREATE PROCEDURE GetSalesByCategory(IN start_date DATE, IN end_date DATE)
BEGIN
    SELECT 
        c.name AS category_name,
        COUNT(o.id) AS order_count,
        SUM(o.total) AS total_sales,
        SUM(o.pay) AS total_paid,
        SUM(o.due) AS total_due
    FROM 
        orders o
    JOIN 
        order_details od ON o.id = od.order_id
    JOIN 
        products p ON od.product_id = p.id
    JOIN 
        categories c ON p.category_id = c.id
    WHERE 
        o.order_date BETWEEN start_date AND end_date
    GROUP BY 
        c.name
    ORDER BY 
        total_sales DESC;
END; 