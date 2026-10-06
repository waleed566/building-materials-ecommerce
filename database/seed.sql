USE construction_store;

INSERT INTO users (role_id, full_name, email, phone, password_hash, address, city)
VALUES
(3, 'Admin User', 'admin@constructionstore.com', '0500000000', '$2y$10$abcdefghijklmnopqrstuv', 'Main Street', 'Riyadh'),
(2, 'Seller One', 'seller1@constructionstore.com', '0511111111', '$2y$10$abcdefghijklmnopqrstuv', 'Business District', 'Jeddah'),
(1, 'Customer One', 'customer1@example.com', '0522222222', '$2y$10$abcdefghijklmnopqrstuv', 'North Area', 'Dammam');

INSERT INTO categories (name, slug, parent_id, image_url)
VALUES
('مواد بناء', 'building-materials', NULL, 'https://example.com/cat1.jpg'),
('حديد', 'steel', 1, 'https://example.com/cat2.jpg'),
('أسمنت', 'cement', 1, 'https://example.com/cat3.jpg'),
('طوب', 'bricks', 1, 'https://example.com/cat4.jpg'),
('دهانات', 'paint', 1, 'https://example.com/cat5.jpg');

INSERT INTO products (category_id, seller_id, name, slug, short_description, description, price, compare_price, stock, unit, sku, image_url, is_featured, is_active)
VALUES
(3, 2, 'أسمنت مخصب 50 كجم', 'cement-50kg', 'أسمنت عالي الجودة للاستخدام العام', 'أسمنت من النوع MPA مع مقاومة ممتازة للتكسير', 48.00, 60.00, 120, 'bag', 'CEM-50-001', 'https://example.com/product1.jpg', 1, 1),
(2, 2, 'حديد تسليح 12 مم', 'steel-rebar-12mm', 'حديد تسليح مناسب للأعمال الإنشائية', 'حديد من الدرجة الأولى ومناسب للخرسانة المسلحة', 420.00, 500.00, 80, 'bundle', 'STEEL-12-001', 'https://example.com/product2.jpg', 1, 1),
(4, 2, 'طوب أحمر 20x20x40', 'red-brick-20x20x40', 'طوب أحمر ممتاز للمباني', 'طوب سميك وقوي ومناسب للجدران الخارجية', 1.80, 2.30, 500, 'piece', 'BRICK-20-001', 'https://example.com/product3.jpg', 0, 1);

INSERT INTO settings (key_name, value_text) VALUES
('site_name', 'متجر المواد الإنشائية'),
('currency', 'SAR'),
('shipping_fee', '25'),
('free_shipping_threshold', '300');
