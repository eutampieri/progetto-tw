CREATE TABLE `order` (id INTEGER PRIMARY KEY, cart_id INTEGER NOT NULL, user_id INTEGER NOT NULL, tracking_number TEXT, express_courier_id INTEGER);
CREATE TABLE express_courier(id INTEGER PRIMARY KEY, `name` TEXT NOT NULL);
CREATE TABLE order_update(`timestamp` INTEGER NOT NULL, `status` TEXT NOT NULL, order_id INTEGER NOT NULL);
