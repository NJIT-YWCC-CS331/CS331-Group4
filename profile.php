<?php
session_start();
require 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM Customer WHERE customer_id = ?");
$stmt->execute([$_SESSION['customer_id']]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

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
    <title>User Profile</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>

    <h2>Your Profile Information</h2>
    <table border="1" cellpadding="8">
        <tr>
            <td><strong>Name</strong></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
        </tr>
        <tr>
            <td><strong>Email</strong></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <td><strong>Phone</strong></td>
            <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Billing Address</strong></td>
            <td><?php echo htmlspecialchars($user['bill_address'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Shipping Address</strong></td>
            <td><?php echo htmlspecialchars($user['ship_address'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><strong>Registration Date</strong></td>
            <td><?php echo htmlspecialchars($user['registration_date']); ?></td>
        </tr>
    </table>

    <br><br>
    <a href="search.php">Search for Books</a> |
    <a href="logout.php">Logout</a>

</body>
</html>
