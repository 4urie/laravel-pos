DROP TRIGGER IF EXISTS after_order_detail_insert;

CREATE TRIGGER after_order_detail_insert
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
    -- Update product stock
    UPDATE products 
    SET product_store = product_store - NEW.quantity
    WHERE id = NEW.product_id;
END; 