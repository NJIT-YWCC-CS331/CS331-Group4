<?php
session_start();
require 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['isbn'])) {
    die("Invalid request: no book selected.");
}

$isbn = $_GET['isbn'];
$customer_id = $_SESSION['customer_id'];

$stmt = $pdo->prepare("SELECT title, price, stock_qty FROM Book WHERE ISBN = ?");
$stmt->execute([$isbn]);
$book = $stmt->fetch();

if (!$book) {
    die("Book not found.");
}

if ($book['stock_qty'] <= 0) {
    die("Sorry, this book is out of stock.");
}

$price = $book['price'];
$quantity = 1;
$total_amount = $price * $quantity;
$method = "Credit Card"; 

$stmt = $pdo->prepare("
    INSERT INTO Orders (customer_id, shipping_status, total_amount)
    VALUES (?, 'Processing', ?)
");
$stmt->execute([$customer_id, $total_amount]);

$order_id = $pdo->lastInsertId(); 
$stmt = $pdo->prepare("
    INSERT INTO OrderItem (order_id, ISBN, quantity, unit_price)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$order_id, $isbn, $quantity, $price]);

$stmt = $pdo->prepare("
    INSERT INTO Payment (order_id, amount, method)
    VALUES (?, ?, ?)
");
$stmt->execute([$order_id, $total_amount, $method]);

$stmt = $pdo->prepare("
    UPDATE Book SET stock_qty = stock_qty - ? WHERE ISBN = ?
");
$stmt->execute([$quantity, $isbn]);

?>
<link rel="stylesheet" href="style.css">

<nav>
    <a href="profile.php">Profile</a>
    <a href="search.php">Search Books</a>
    <a href="logout.php">Logout</a>
</nav>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Confirmation</title>
</head>
<body>
    <h1>Order Successful!</h1>
    <p>You purchased: <strong><?php echo htmlspecialchars($book['title']); ?></strong></p>
    <p>Total Amount Paid: <strong>$<?php echo number_format($total_amount, 2); ?></strong></p>
    <p>Your order number is <strong><?php echo $order_id; ?></strong></p>
    <br>
    <a href="search.php">Buy more books</a> |
    <a href="profile.php">Back to Profile</a> |
    <a href="logout.php">Logout</a>
</body>
</html>
