CREATE TABLE IF NOT EXISTS price_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    old_buying_price DECIMAL(10,2),
    new_buying_price DECIMAL(10,2),
    old_selling_price DECIMAL(10,2),
    new_selling_price DECIMAL(10,2),
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    changed_by BIGINT UNSIGNED,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

DROP TRIGGER IF EXISTS after_product_price_update;

CREATE TRIGGER after_product_price_update
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    -- Only log if prices have changed
    IF (OLD.buying_price != NEW.buying_price OR OLD.selling_price != NEW.selling_price) THEN
        INSERT INTO price_logs (
            product_id,
            old_buying_price,
            new_buying_price,
            old_selling_price,
            new_selling_price,
            changed_by
        ) VALUES (
            NEW.id,
            OLD.buying_price,
            NEW.buying_price,
            OLD.selling_price,
            NEW.selling_price,
            @current_user_id
        );
    END IF;
END; 