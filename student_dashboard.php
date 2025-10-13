<?php
session_start();
// Example: fetch student name if logged in
$userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : "Student Name";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard - University Library</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #ffffff;
      border-bottom: 4px solid #1e40af;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 {
      margin: 0;
      font-size: 22px;
      color: #222;
    }
    .welcome {
      font-size: 14px;
      color: #555;
    }
    .logout-btn {
      background-color: #dc2626;
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
    }
    main {
      padding: 30px;
      max-width: 1000px;
      margin: 0 auto;
    }
    h2 {
      color: #111;
      border-bottom: 2px solid #1e40af;
      padding-bottom: 5px;
    }
    .card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 20px;
      margin-top: 20px;
    }
    .btn {
      background-color: #059669;
      color: #fff;
      border: none;
      padding: 10px 16px;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<header>
  <div>
    <h1>📚 University Library System</h1>
    <div class="welcome">Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong> (Student)</div>
  </div>
  <form action="logout.php" method="post">
    <button class="logout-btn">Logout</button>
  </form>
</header>

<main>
  <h2>Student Dashboard</h2>

  <div class="card">
    <h3>Borrowed Books</h3>
    <p>No books currently borrowed.</p>
  </div>

  <div class="card">
    <h3>Quick Actions</h3>
    <button class="btn">Reserve Book</button>
    <button class="btn">Search Books</button>
  </div>

  <div class="card">
    <h3>Penalty Status</h3>
    <p>₱0.00 — No outstanding penalties.</p>
  </div>
</main>

</body>
</html>
