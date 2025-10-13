<?php
session_start();
include 'db.php';

// ✅ Match the session variable name from login
if (!isset($_SESSION['full_name'])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION['full_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Staff Dashboard</title>
<style>
body{
    font-family:Arial,sans-serif;
    background:#f4f6f8;
    margin:0;
}
header{
    background:#fff;
    border-bottom:4px solid #1e40af;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
header h1{
    margin:0;
    font-size:22px;
    color:#222;
}
.welcome{
    font-size:14px;
    color:#555;
}
.logout-btn{
    background:#dc2626;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    cursor:pointer;
}
.logout-btn:hover{
    background:#b91c1c;
}
main{
    padding:30px;
    max-width:1000px;
    margin:0 auto;
}
h2{
    color:#111;
    border-bottom:2px solid #1e40af;
    padding-bottom:5px;
}
.card{
    background:#fff;
    border-radius:8px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
    padding:20px;
    margin-top:20px;
}
.btn{
    background:#059669;
    color:#fff;
    border:none;
    padding:10px 16px;
    border-radius:6px;
    cursor:pointer;
    margin-top:10px;
}
.btn:hover{
    background:#047857;
}
</style>
</head>
<body>
<header>
  <div>
    <h1>📚 University Library System</h1>
    <div class="welcome">
      Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong> (Staff)
    </div>
  </div>
  <form action="logout.php" method="post">
    <button class="logout-btn">Logout</button>
  </form>
</header>

<main>
  <h2>Staff Dashboard</h2>
  <div class="card">
    <h3>Inventory Updates</h3>
    <p>Assist librarians in managing book inventory and damaged materials.</p>
  </div>
  <div class="card">
    <h3>Maintenance Logs</h3>
    <p>Track facility reports and maintenance schedules.</p>
  </div>
  <div class="card">
    <h3>Quick Actions</h3>
    <button class="btn">Submit Report</button>
    <button class="btn">View Tasks</button>
  </div>
</main>
</body>
</html>
