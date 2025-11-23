<?php
session_start();
if (!isset($_SESSION['userName'])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION['userName'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Teacher Dashboard</title>
<style>
body{
    font-family:Arial,sans-serif;
    background:#f4f6f8;
    margin:0
}
header{
    background:#fff;
    border-bottom:4px solid #1e40af;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center
}
header h1{
    margin:0;
    font-size:22px;
    color:#222
}
.welcome{
    font-size:14px;
    color:#555
}
.logout-btn{
    background:#dc2626;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    cursor:pointer
}
main{
    padding:30px;
    max-width:1000px;
    margin:0 auto
}
h2{
    color:#111;
    border-bottom:2px solid #1e40af;
    padding-bottom:5px
}
.card{
    background:#fff;
    border-radius:8px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
    padding:20px;
    margin-top:20px
}
.btn{
    background:#059669;
    color:#fff;
    border:none;
    padding:10px 16px;
    border-radius:6px;
    cursor:pointer;
    margin-top:10px}
</style>
</head>
<body>
<header>
  <div>
    <h1>📚 University Library System</h1>
    <div class="welcome">Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong> (Teacher)</div>
  </div>
  <form action="logout.php" method="post">
    <button class="logout-btn"onclick ="window.location.href= 'login.php'">Logout</button>
  </form>
</header>
<main>
  <h2>Teacher Dashboard</h2>
  <div class="card">
    <h3>Reserved Books</h3>
    <p>No current reservations.</p>
  </div>
  <div class="card">
    <h3>Class Resource Requests</h3>
    <p>No pending requests.</p>
  </div>
  <div class="card">
    <h3>Quick Actions</h3>
    <button class="btn">Request New Material</button>
    <button class="btn">View Library Catalog</button>
  </div>
</main>
</body>
</html>
