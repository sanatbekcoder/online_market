
CREATE TABLE users(

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(100) NOT NULL,

email VARCHAR(150) UNIQUE NOT NULL,

phone VARCHAR(30),

password VARCHAR(255) NOT NULL,

role ENUM(
'user',
'admin'
)

DEFAULT 'user',

created_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP

);



CREATE TABLE categories(

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(100) NOT NULL

);



CREATE TABLE products(

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(255) NOT NULL,

description TEXT,

price DECIMAL(12,2)
NOT NULL,

image VARCHAR(255)
DEFAULT 'no-image.png',

category_id INT,

created_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY(category_id)

REFERENCES categories(id)

ON DELETE SET NULL

);



CREATE TABLE cart(

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT NOT NULL,

product_id INT NOT NULL,

quantity INT DEFAULT 1,

created_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY(user_id)

REFERENCES users(id)

ON DELETE CASCADE,

FOREIGN KEY(product_id)

REFERENCES products(id)

ON DELETE CASCADE

);



CREATE TABLE orders(

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT NOT NULL,

fullname VARCHAR(100),

phone VARCHAR(30),

address TEXT,

note TEXT,

status ENUM(

'korib_chiqilyapdi',
'tasdiqlandi',
'yetkazilyapdi',
'yetkazildi',
'bekor_qilindi'

)

DEFAULT
'korib_chiqilyapdi',

created_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY(user_id)

REFERENCES users(id)

ON DELETE CASCADE

);



CREATE TABLE order_items(

id INT AUTO_INCREMENT PRIMARY KEY,

order_id INT NOT NULL,

product_id INT NOT NULL,

quantity INT DEFAULT 1,

price DECIMAL(12,2),

FOREIGN KEY(order_id)

REFERENCES orders(id)

ON DELETE CASCADE,

FOREIGN KEY(product_id)

REFERENCES products(id)

ON DELETE CASCADE

);



INSERT INTO categories(name)
VALUES
('Texnikalar'),
('Smartfonlar'),
('Laptoplar'),
('Kompyuterlar'),
('Aksessuarlar'),
('Mevalar'),
('Sabzavotlar'),
('Ichimliklar'),
('Uy jihozlari'),
('Kiyimlar'); 