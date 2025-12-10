<?php
session_start();

// 1. CONNECT TO DATABASE
$conn = new mysqli("localhost", "root", "", "library_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get Current User Name
$currentUser = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

// 2. HANDLE BORROW ACTION
if (isset($_POST['borrow_book_id'])) {
    $book_id = $_POST['borrow_book_id'];
    
    // Get details
    $checkSql = "SELECT title, author, quantity FROM books WHERE book_id = $book_id";
    $res = $conn->query($checkSql);
    $book = $res->fetch_assoc();

    if ($book['quantity'] > 0) {
        // A. Decrease Inventory
        $conn->query("UPDATE books SET quantity = quantity - 1 WHERE book_id = $book_id");
        
        // B. Add to Personal List as 'Active'
        $stmt = $conn->prepare("INSERT INTO borrowed_books (borrower_name, book_title, author, status) VALUES (?, ?, ?, 'Active')");
        $stmt->bind_param("sss", $currentUser, $book['title'], $book['author']);
        $stmt->execute();
        
        // C. Update Status if Empty
        $conn->query("UPDATE books SET status = 'Out of Stock' WHERE book_id = $book_id AND quantity = 0");
        
        $_SESSION['flash_message'] = "✅ Successfully borrowed: " . $book['title'];
        header("Location: teacher_dashboard.php");
        exit();
    }
}

// 3. HANDLE RESERVE ACTION
if (isset($_POST['reserve_book_id'])) {
    $book_id = $_POST['reserve_book_id'];
    
    // Get details
    $checkSql = "SELECT title, author FROM books WHERE book_id = $book_id";
    $res = $conn->query($checkSql);
    $book = $res->fetch_assoc();
    
    // INSERT INTO borrowed_books table with status 'Reserved'
    // This allows us to count it in the 2nd box
    $stmt = $conn->prepare("INSERT INTO borrowed_books (borrower_name, book_title, author, status) VALUES (?, ?, ?, 'Reserved')");
    $stmt->bind_param("sss", $currentUser, $book['title'], $book['author']);
    $stmt->execute();
    
    $_SESSION['flash_message'] = "🔔 Successfully reserved: " . $book['title'];
    
    header("Location: teacher_dashboard.php");
    exit();
}

// 4. FETCH INVENTORY
$sql = "SELECT * FROM books ORDER BY book_id DESC";
$result = $conn->query($sql);

// 5. CALCULATE METRICS
// Count Borrowed (Active)
$borrowSql = "SELECT COUNT(*) as count FROM borrowed_books WHERE borrower_name = '$currentUser' AND status = 'Active'";
$myBorrowedCount = $conn->query($borrowSql)->fetch_assoc()['count'];

// Count Reserved (Reserved)
$reserveSql = "SELECT COUNT(*) as count FROM borrowed_books WHERE borrower_name = '$currentUser' AND status = 'Reserved'";
$myReservedCount = $conn->query($reserveSql)->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Library Catalog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    /* ... CSS STYLES ... */
    :root {
      --bg: #f7f8fc; --panel: #ffffff; --text: #1f2937; --muted: #6b7280;
      --primary: #4f46e5; --accent: #10b981; --border: #e5e7eb;
      --warning: #f59e0b; --danger: #ef4444; --sidebar-bg: #1e293b;
    }
    * { box-sizing: border-box; }
    body { margin: 0; font-family: system-ui, -apple-system, sans-serif; background: var(--bg); color: var(--text); }
    .app { display: grid; grid-template-columns: 1fr 280px; grid-template-areas: "main sidebar"; min-height: 100vh; }
    main { grid-area: main; display: flex; flex-direction: column; overflow-y: auto; height: 100vh; }
    .sidebar { grid-area: sidebar; background: var(--sidebar-bg); color: #e5e7eb; padding: 24px; display: flex; flex-direction: column; gap: 24px; height: 100vh; overflow-y: auto; position: sticky; top: 0; }
    .brand { display: flex; align-items: center; gap: 12px; }
    .brand h1 { font-size: 18px; margin: 0; letter-spacing: 0.3px; }
    .user { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 16px; }
    .user h2 { font-size: 16px; margin: 0 0 4px; color: #fff; }
    .user p { margin: 0; color: #94a3b8; font-size: 13px; }
    .nav { display: grid; gap: 8px; }
    .nav a { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #cbd5e1; text-decoration: none; border-radius: 8px; transition: 0.2s; }
    .nav a:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .nav .signout { color: #fca5a5; margin-top: auto; }
    .header { background: var(--panel); border-bottom: 1px solid var(--border); padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10; }
    .header h2 { margin: 0; font-size: 20px; font-weight: 600; }
    .content { padding: 32px; display: grid; gap: 24px; max-width: 1200px; width: 100%; margin: 0 auto; }
    .metrics { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 20px; }
    .card { background: var(--panel); border: 1px solid var(--border); border-radius: 12px; padding: 20px; display: grid; gap: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .card .label { color: var(--muted); font-size: 14px; font-weight: 500; }
    .card .value { font-size: 28px; font-weight: 700; }
    .card .note { font-size: 13px; color: var(--accent); font-weight: 500; }
    .card.primary .value { color: var(--primary); }
    .card.danger .value { color: var(--danger); }
    .panels { display: grid; grid-template-columns: 1fr; gap: 20px; }
    .panel { background: var(--panel); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: #f8fafc; }
    .panel-header h3 { margin: 0; font-size: 16px; color: #334155; }
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    thead th { text-align: left; padding: 12px 20px; color: var(--muted); background: #f8fafc; border-bottom: 1px solid var(--border); font-weight: 600; }
    tbody td { padding: 14px 20px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    tbody tr:hover { background: #f8fafc; }
    .empty-state { padding: 20px; text-align: center; color: var(--muted); }
    
    .btn-borrow { background-color: var(--accent); color: white; padding: 6px 12px; border-radius: 6px; border:none; cursor: pointer; font-weight: 500; }
    .btn-reserve { background-color: var(--warning); color: white; padding: 6px 12px; border-radius: 6px; border:none; cursor: pointer; font-weight: 500; }
    .btn.secondary { background: #fff; color: var(--text); border: 1px solid var(--border); }
    
    .toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 100; animation: slideIn 0.5s ease-out; }
    .toast { background: white; border-left: 4px solid var(--accent); padding: 16px 24px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 12px; min-width: 300px; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    @media (max-width: 1024px) { .app { grid-template-columns: 1fr; grid-template-areas: "main"; } .sidebar { display: none; } .panels { grid-template-columns: 1fr; } }
    @media (max-width: 640px) { .metrics { grid-template-columns: 1fr; } }
  </style>
</head>
<body>
  <div class="app">
    
    <main>
      <header class="header">
        <h2>Library System</h2>
        <div class="actions">
           <button class="btn secondary">Help</button>
        </div>
      </header>

      <div class="content">
        <section class="metrics">
          <div class="card primary">
            <div class="label">My Borrowed Books</div>
            <div class="value"><?php echo $myBorrowedCount; ?></div>
            <div class="note">Currently checked out</div>
          </div>

          <div class="card">
            <div class="label">Reserved Books</div> <div class="value"><?php echo $myReservedCount; ?></div> <div class="note">Waiting for availability</div>
          </div>

          <div class="card danger">
            <div class="label">Returned books</div>
            <div class="value">0</div>
            <div class="note">Return by the end of Semester</div>
          </div>
        </section>

        <section class="panels">
          <div class="panel">
            <div class="panel-header">
              <h3>Book Inventory</h3>
            </div>
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                          $quantity = intval($row['quantity']);
                          
                          if ($quantity > 0) {
                              $displayStatus = "Available";
                              $statusColor = 'var(--accent)'; 
                          } else {
                              $displayStatus = "Out of Stock";
                              $statusColor = 'var(--danger)'; 
                          }

                          echo "<tr>";
                          echo "<td><strong>" . htmlspecialchars($row['title']) . "</strong></td>";
                          echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                          echo "<td>" . $quantity . "</td>";
                          echo "<td><span style='color:" . $statusColor . "; font-weight:700;'>" . $displayStatus . "</span></td>";
                          
                          echo "<td>";
                          if ($quantity > 0) {
                              echo '<form method="POST" style="margin:0;">';
                              echo '<input type="hidden" name="borrow_book_id" value="'.$row['book_id'].'">';
                              echo '<button type="submit" class="btn-borrow">Borrow</button>';
                              echo '</form>';
                          } else {
                              echo '<form method="POST" style="margin:0;">';
                              echo '<input type="hidden" name="reserve_book_id" value="'.$row['book_id'].'">';
                              echo '<button type="submit" class="btn-reserve">Reserve</button>';
                              echo '</form>';
                          }
                          echo "</td>";
                          echo "</tr>";
                      }
                  } else {
                      echo "<tr><td colspan='6' class='empty-state'>No books found.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </main>

    <aside class="sidebar">
      <div class="brand">
        <h1>Teachers Library</h1>
      </div>
      <section class="user">
        <h2><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest User'; ?></h2>
      </section>
      <nav class="nav">
        <a href="login.php" class="signout">🚪 Sign Out</a>
      </nav>
    </aside>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="toast-container" id="toast">
            <div class="toast">
                <div class="toast-icon">ℹ️</div>
                <div class="toast-msg"><?php echo htmlspecialchars($_SESSION['flash_message']); ?></div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const t = document.getElementById('toast');
                if(t) { t.style.opacity='0'; setTimeout(()=>t.remove(),500); }
            }, 4000);
        </script>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

  </div>
</body>
</html>

<?php $conn->close(); ?>