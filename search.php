<?php
session_start();
require 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$searchTerm = $_GET['search'] ?? '';
$books = [];

if ($searchTerm !== '') {
    $stmt = $pdo->prepare("
        SELECT DISTINCT b.ISBN, b.title, b.price
        FROM Book b
        LEFT JOIN BookAuthor ba ON b.ISBN = ba.ISBN
        LEFT JOIN Author a ON ba.author_id = a.author_id
        LEFT JOIN BookCategory bc ON b.ISBN = bc.ISBN
        LEFT JOIN Category c ON bc.category_id = c.category_id
        WHERE b.title LIKE ? OR a.name LIKE ? OR c.name LIKE ?
    ");

    $like = "%" . $searchTerm . "%";
    $stmt->execute([$like, $like, $like]);
    $books = $stmt->fetchAll();
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
    <title>Book Search</title>
</head>
<body>
    <h1>Search for Books</h1>
    <form method="GET" action="search.php">
        <input type="text" name="search" placeholder="Enter book title, author, or category" value="<?php echo htmlspecialchars($searchTerm); ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($searchTerm !== ''): ?>
        <h2>Search results for: "<?php echo htmlspecialchars($searchTerm); ?>"</h2>

        <?php if (empty($books)): ?>
            <p style="color:red;">No matching books found.</p>
        <?php else: ?>
            <table border="1" cellpadding="8">
                <tr>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['price']); ?></td>
                        <td>
                            <a href="checkout.php?isbn=<?php echo urlencode($book['ISBN']); ?>">Purchase</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="profile.php">Back to Profile</a> |
    <a href="logout.php">Logout</a>
</body>
</html>