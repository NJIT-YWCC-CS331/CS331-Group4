<?php
session_start();
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } else {
        $stmt = $pdo->prepare("SELECT customer_id, name, email, password_hash FROM Customer WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $errors[] = 'No account found with that email.';
        } else {
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['customer_id'] = $user['customer_id'];
                $_SESSION['customer_name'] = $user['name'];
                $_SESSION['customer_email'] = $user['email'];

                header('Location: profile.php');
                exit;
            } else {
                $errors[] = 'Incorrect password.';
            }
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
    <title>User Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="login.php">
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

        <button type="submit">Login</button>
    </form>

    <p>Don’t have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>
