-- E-Ticaret API Veritabanı Dump
-- PostgreSQL 13+ için hazırlanmıştır

-- Veritabanı oluşturma
CREATE DATABASE turkticaret;

-- Veritabanına bağlan
\c turkticaret;

-- Tabloları oluştur
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'user',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    category_id BIGINT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE carts (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE cart_items (
    id BIGSERIAL PRIMARY KEY,
    cart_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    quantity INTEGER NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    product_id BIGINT NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Index'ler
CREATE INDEX users_email_index ON users(email);
CREATE INDEX password_resets_email_index ON password_resets(email);
CREATE INDEX products_category_id_index ON products(category_id);
CREATE INDEX cart_items_cart_id_index ON cart_items(cart_id);
CREATE INDEX cart_items_product_id_index ON cart_items(product_id);
CREATE INDEX orders_user_id_index ON orders(user_id);
CREATE INDEX order_items_order_id_index ON order_items(order_id);
CREATE INDEX order_items_product_id_index ON order_items(product_id);

-- Sample Data

-- Kullanıcılar
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin User', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('Test User', 'user@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NOW(), NOW());

-- Kategoriler (Case study gereksinimi: en az 3 kategori)
INSERT INTO categories (name, description, created_at, updated_at) VALUES
('Elektronik', 'Elektronik ürünler ve aksesuarlar', NOW(), NOW()),
('Giyim', 'Kadın, erkek ve çocuk giyim ürünleri', NOW(), NOW()),
('Ev & Yaşam', 'Ev dekorasyonu ve yaşam ürünleri', NOW(), NOW());

-- Ürünler (Her kategoride en az 5 ürün)
INSERT INTO products (name, description, price, stock_quantity, category_id, created_at, updated_at) VALUES
('Elektronik Ürün 1', 'Elektronik kategorisinde yer alan kaliteli ürün 1', 299.99, 50, 1, NOW(), NOW()),
('Elektronik Ürün 2', 'Elektronik kategorisinde yer alan kaliteli ürün 2', 199.99, 30, 1, NOW(), NOW()),
('Elektronik Ürün 3', 'Elektronik kategorisinde yer alan kaliteli ürün 3', 399.99, 25, 1, NOW(), NOW()),
('Elektronik Ürün 4', 'Elektronik kategorisinde yer alan kaliteli ürün 4', 149.99, 40, 1, NOW(), NOW()),
('Elektronik Ürün 5', 'Elektronik kategorisinde yer alan kaliteli ürün 5', 249.99, 35, 1, NOW(), NOW()),
('Giyim Ürün 1', 'Giyim kategorisinde yer alan kaliteli ürün 1', 89.99, 60, 2, NOW(), NOW()),
('Giyim Ürün 2', 'Giyim kategorisinde yer alan kaliteli ürün 2', 79.99, 45, 2, NOW(), NOW()),
('Giyim Ürün 3', 'Giyim kategorisinde yer alan kaliteli ürün 3', 99.99, 55, 2, NOW(), NOW()),
('Giyim Ürün 4', 'Giyim kategorisinde yer alan kaliteli ürün 4', 69.99, 40, 2, NOW(), NOW()),
('Giyim Ürün 5', 'Giyim kategorisinde yer alan kaliteli ürün 5', 119.99, 50, 2, NOW(), NOW()),
('Ev & Yaşam Ürün 1', 'Ev & Yaşam kategorisinde yer alan kaliteli ürün 1', 159.99, 30, 3, NOW(), NOW()),
('Ev & Yaşam Ürün 2', 'Ev & Yaşam kategorisinde yer alan kaliteli ürün 2', 89.99, 25, 3, NOW(), NOW()),
('Ev & Yaşam Ürün 3', 'Ev & Yaşam kategorisinde yer alan kaliteli ürün 3', 199.99, 35, 3, NOW(), NOW()),
('Ev & Yaşam Ürün 4', 'Ev & Yaşam kategorisinde yer alan kaliteli ürün 4', 129.99, 20, 3, NOW(), NOW()),
('Ev & Yaşam Ürün 5', 'Ev & Yaşam kategorisinde yer alan kaliteli ürün 5', 179.99, 40, 3, NOW(), NOW());
