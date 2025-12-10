<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$stmt = $pdo->query("
    SELECT 
        o.order_id,
        c.name AS customer_name,
        b.title AS book_title,
        oi.quantity,
        o.total_amount,
        o.shipping_status
    FROM Orders o
    JOIN Customer c ON o.customer_id = c.customer_id
    JOIN OrderItem oi ON o.order_id = oi.order_id
    JOIN Book b ON oi.ISBN = b.ISBN
    ORDER BY o.order_id DESC
");
$orders = $stmt->fetchAll();
?>
<link rel="stylesheet" href="style.css">

<nav>
    <a href="admin_users.php">Users</a>
    <a href="admin_orders.php">Orders</a>
    <a href="admin_logout.php">Logout</a>
</nav>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Orders</title>
</head>
<body>
    <h1>Order List</h1>

    <table border="1" cellpadding="8">
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Book</th>
            <th>Quantity</th>
            <th>Total Amount</th>
            <th>Status</th>
        </tr>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td><?php echo $o['order_id']; ?></td>
                <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($o['book_title']); ?></td>
                <td><?php echo $o['quantity']; ?></td>
                <td><?php echo $o['total_amount']; ?></td>
                <td><?php echo htmlspecialchars($o['shipping_status']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="admin_users.php">Back to Users</a> |
    <a href="admin_logout.php">Logout</a>
</body>
</html>
