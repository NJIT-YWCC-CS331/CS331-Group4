<?php

session_start();
require 'db.php';  
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $password      = $_POST['password'] ?? '';
    $confirm_pass  = $_POST['confirm_password'] ?? '';
    $phone         = trim($_POST['phone'] ?? '');
    $bill_address  = trim($_POST['bill_address'] ?? '');
    $ship_address  = trim($_POST['ship_address'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if ($email === '') {
        $errors[] = 'Email is required.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }
    if ($password !== $confirm_pass) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT customer_id FROM Customer WHERE email = ?");
        $stmt->execute([$email]);
        $existing = $stmt->fetch();

        if ($existing) {
            $errors[] = 'An account with that email already exists.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO Customer (name, email, password_hash, phone, bill_address, ship_address)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $name,
                $email,
                $password_hash,
                $phone ?: null,
                $bill_address ?: null,
                $ship_address ?: null
            ]);

            $success = 'Registration successful. You can now log in.';
        }
    }
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
    <title>User Registration</title>
</head>
<body>
    <h1>Register</h1>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color: green;">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="register.php">
        <label>
            Name:
            <input type="text" name="name" required>
        </label>
        <br><br>

        <label>
            Email:
            <input type="email" name="email" required>
        </label>
        <br><br>

        <label>
            Password:
            <input type="password" name="password" required>
        </label>
        <br><br>

        <label>
            Confirm Password:
            <input type="password" name="confirm_password" required>
        </label>
        <br><br>

        <label>
            Phone:
            <input type="text" name="phone">
        </label>
        <br><br>

        <label>
            Billing Address:
            <input type="text" name="bill_address">
        </label>
        <br><br>

        <label>
            Shipping Address:
            <input type="text" name="ship_address">
        </label>
        <br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Log in here</a>.</p>
</body>
</html>
