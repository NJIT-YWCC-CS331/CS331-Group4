<?php
session_start();
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = "Username and password are required.";
    } else {
        $stmt = $pdo->prepare("SELECT admin_id, username, password_hash, name FROM Admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && $password === $admin['password_hash']) { 
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['name'];

            header("Location: admin_users.php");
            exit;
        } else {
            $errors[] = "Invalid username or password.";
        }
    }
}
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
    <title>Admin Login</title>
</head>
<body>
    <h1>Admin Login</h1>

    <?php if (!empty($errors)): ?>
        <div style="color:red;">
            <?php foreach($errors as $e) echo "<p>$e</p>"; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <label>Username:<br>
            <input type="text" name="username" required>
        </label><br><br>

        <label>Password:<br>
            <input type="password" name="password" required>
        </label><br><br>

        <button type="submit">Login</button>
    </form>

    <br>
    <a href="login.php">Back to User Login</a>
</body>
</html>
