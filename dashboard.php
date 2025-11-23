<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Dashboard</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 50px; }
        a { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Role: <?php echo ucfirst($_SESSION['role']); ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
