-- ADMIN
CREATE TABLE Admin (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL
);

-- CUSTOMER (added username/password_hash for user accounts)
CREATE TABLE Customer (
  customer_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20),
  bill_address VARCHAR(255),
  ship_address VARCHAR(255),
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  registration_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- AUTHOR
CREATE TABLE Author (
  author_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  nationality VARCHAR(50),
  biography TEXT
);

-- CATEGORY
CREATE TABLE Category (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- BOOK
CREATE TABLE Book (
  ISBN VARCHAR(20) PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  edition VARCHAR(50),
  publication_year INT,
  price DECIMAL(10,2),
  stock_qty INT DEFAULT 0 CHECK (stock_qty >= 0)
);

-- BOOKAUTHOR (M:N)
CREATE TABLE BookAuthor (
  author_id INT NOT NULL,
  ISBN VARCHAR(20) NOT NULL,
  PRIMARY KEY (author_id, ISBN),
  CONSTRAINT fk_bookauthor_author FOREIGN KEY (author_id) REFERENCES Author(author_id) ON DELETE CASCADE,
  CONSTRAINT fk_bookauthor_book FOREIGN KEY (ISBN) REFERENCES Book(ISBN) ON DELETE CASCADE
);

-- BOOKCATEGORY (M:N)
CREATE TABLE BookCategory (
  category_id INT NOT NULL,
  ISBN VARCHAR(20) NOT NULL,
  PRIMARY KEY (category_id, ISBN),
  CONSTRAINT fk_bookcategory_category FOREIGN KEY (category_id) REFERENCES Category(category_id) ON DELETE CASCADE,
  CONSTRAINT fk_bookcategory_book FOREIGN KEY (ISBN) REFERENCES Book(ISBN) ON DELETE CASCADE
);

-- ORDERS
CREATE TABLE Orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  shipping_status VARCHAR(50) DEFAULT 'Processing',
  total_amount DECIMAL(10,2) CHECK (total_amount >= 0),
  CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE
);

-- ORDERITEM
CREATE TABLE OrderItem (
  order_id INT NOT NULL,
  ISBN VARCHAR(20) NOT NULL,
  quantity INT DEFAULT 1 CHECK (quantity > 0),
  unit_price DECIMAL(10,2) CHECK (unit_price >= 0),
  PRIMARY KEY (order_id, ISBN),
  CONSTRAINT fk_orderitem_order FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
  CONSTRAINT fk_orderitem_book FOREIGN KEY (ISBN) REFERENCES Book(ISBN) ON DELETE CASCADE
);

-- PAYMENT
CREATE TABLE Payment (
  payment_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNIQUE NOT NULL,
  amount DECIMAL(10,2) CHECK (amount >= 0),
  method VARCHAR(50),
  payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payment_order FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
);

-- REVIEW
CREATE TABLE Review (
  review_id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  ISBN VARCHAR(20) NOT NULL,
  rating DECIMAL(2,1) CHECK (rating BETWEEN 1 AND 5),
  review_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  comment_text TEXT,
  CONSTRAINT unique_customer_book_review UNIQUE (customer_id, ISBN),
  CONSTRAINT fk_review_customer FOREIGN KEY (customer_id) REFERENCES Customer(customer_id) ON DELETE CASCADE,
  CONSTRAINT fk_review_book FOREIGN KEY (ISBN) REFERENCES Book(ISBN) ON DELETE CASCADE
);

-- SAMPLE DATA (simple passwords for demo; you can change later)

INSERT INTO Admin(username, password_hash, name) VALUES
('admin1', 'adminpass1', 'Alice Smith'),
('admin2', 'adminpass2', 'John Doe');

INSERT INTO Author(name, nationality, biography) VALUES
('George R.R Martin', 'American', 'Author of A Song of Ice and Fire'),
('Rick Riordan', 'American', 'Author of The Lightning Thief'),
('Robert Kirkman', 'American', 'Author of Invincible'),
('J.R.R Tolkien', 'British', 'Author of LOTR'),
('J.K. Rowling', 'British', 'Author of Harry Potter');

INSERT INTO Book(ISBN, title, edition, publication_year, price, stock_qty) VALUES
('9781368051491', 'A Song Of Ice and Fire', '2nd', 1996, 20.00, 14),
('9780606376792', 'The Lightning Thief', '1st', 2005, 9.90, 13),
('9781855496705', 'Invincible', '1st', 2003, 10.00, 5),
('9780618260300', 'The Hobbit', '1st', 2002, 8.37, 3),
('9780545425117', 'Harry Potter and the Sorcerer''s Stone', '3rd', 1998, 10.21, 4);

INSERT INTO BookAuthor VALUES
(1,'9781368051491'),
(2,'9780606376792'),
(3,'9781855496705'),
(4,'9780618260300'),
(5,'9780545425117');

INSERT INTO Category(name) VALUES
('Fantasy'),
('Thriller'),
('Horror'),
('Action'),
('Science Fiction');

INSERT INTO BookCategory VALUES
(1,'9781368051491'),
(1,'9780545425117'),
(2,'9780606376792'),
(3,'9781855496705'),
(4,'9780618260300');

-- Customers with usernames/passwords (passwords are plain here for demo)
INSERT INTO Customer(name, email, phone, bill_address, ship_address, username, password_hash) VALUES
('Johnny Doe', 'johnny@example.com', '123-456-9123', '123 Abc Lane', '123 Abc Lane', 'johnny', 'password1'),
('Joe Schmo', 'joe@example.com', '323-123-9432', '433 Zander Rd', '433 Zander Rd', 'joe', 'password2');
