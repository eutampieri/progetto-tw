CREATE TABLE `order` (id INTEGER PRIMARY KEY AUTO_INCREMENT, cart_id INTEGER NOT NULL, user_id INTEGER NOT NULL, tracking_number TEXT, express_courier_id INTEGER, payment_id TEXT NOT NULL);
CREATE TABLE express_courier(id INTEGER PRIMARY KEY AUTO_INCREMENT, `name` TEXT NOT NULL);
CREATE TABLE order_update(`timestamp` INTEGER NOT NULL, `status` TEXT NOT NULL, order_id INTEGER NOT NULL);
