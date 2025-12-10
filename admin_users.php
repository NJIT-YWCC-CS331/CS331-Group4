<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$stmt = $pdo->query("SELECT customer_id, name, email, phone FROM Customer ORDER BY customer_id");
$customers = $stmt->fetchAll();
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
    <title>Registered Users</title>
</head>
<body>
    <h1>Registered Users</h1>
    <p>Welcome, Admin <?php echo $_SESSION['admin_name']; ?>!</p>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        <?php foreach ($customers as $c): ?>
            <tr>
                <td><?php echo $c['customer_id']; ?></td>
                <td><?php echo htmlspecialchars($c['name']); ?></td>
                <td><?php echo htmlspecialchars($c['email']); ?></td>
                <td><?php echo htmlspecialchars($c['phone']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="admin_orders.php">View Orders</a> |
    <a href="admin_logout.php">Logout</a>
</body>
</html>
