<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['full_name'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'] ?? null;

// Default counts
$borrowedCount = $reservedCount = $overdueCount = $feeAmount = 0;

// Fetch stats if logged in
if ($student_id) {
    // 1. Borrowed
    $borrowedQuery = "SELECT COUNT(*) AS total FROM borrow_records WHERE student_id = '$student_id' AND status = 'borrowed'";
    $borrowedResult = $conn->query($borrowedQuery);
    if ($borrowedResult) $borrowedCount = $borrowedResult->fetch_assoc()['total'] ?? 0;

    // 2. Reserved
    $reservedQuery = "SELECT COUNT(*) AS total FROM borrow_records WHERE student_id = '$student_id' AND status = 'reserved'";
    $reservedResult = $conn->query($reservedQuery);
    if ($reservedResult) $reservedCount = $reservedResult->fetch_assoc()['total'] ?? 0;

    // 3. Overdue
    $overdueQuery = "SELECT COUNT(*) AS total FROM borrow_records WHERE student_id = '$student_id' AND status = 'borrowed' AND due_date < NOW()";
    $overdueResult = $conn->query($overdueQuery);
    if ($overdueResult) $overdueCount = $overdueResult->fetch_assoc()['total'] ?? 0;

    // 4. Fees
    $feeQuery = "SELECT SUM(amount) AS total FROM fees WHERE student_id = '$student_id' AND status = 'unpaid'";
    $feeResult = $conn->query($feeQuery);
    if ($feeResult) $feeAmount = $feeResult->fetch_assoc()['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Library Portal</title>
    <link rel="stylesheet" href="studentstyle.css">
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="header">
        <h1>👤 Student Library Portal</h1>
        <div class="user-info">
            <div style="margin-left: 20px;">
                <?php if (isset($_SESSION['full_name'])): ?>
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <img src="profile.png" alt="Profile" style="width:30px; height:30px; border-radius:50%; object-fit:cover;">
                        <?= htmlspecialchars($_SESSION['full_name']) ?>
                    </span>
                <?php else: ?>
                    <span>Welcome, Student</span>
                <?php endif; ?>
            </div>
            <form action="logout.php" method="POST" style="margin:0;">
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Books Borrowed</div>
            <div class="stat-number borrowed"><?= $borrowedCount ?></div>
            <div style="font-size: 12px; color: #666;">3 books allowed per semester</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Books Reserved</div>
            <div class="stat-number books"><?= $reservedCount ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Overdue Books</div>
            <div class="stat-number overdue"><?= $overdueCount ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Outstanding Fees</div>
            <div class="stat-number members">₱<?= number_format($feeAmount, 2) ?></div>
        </div>
    </div>

    <!-- Borrowed Books -->
    <div class="card" style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">My Borrowed Books</h3>
            <div style="display: flex; align-items: center; gap: 10px;">
                <select style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <option>All My Books</option>
                    <option>Currently Borrowed</option>
                    <option>Reserved</option>
                    <option>Overdue</option>
                </select>
            </div>
        </div>

        <table class="book-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT b.title, b.author, b.category, b.ISBN, br.status, br.due_date
                    FROM borrow_records AS br
                    JOIN books AS b ON br.book_id = b.book_id
                    WHERE br.student_id = '$student_id'
                    ORDER BY br.borrow_date DESC
                ";

                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td><strong>" . htmlspecialchars($row['title']) . "</strong><br><small>ISBN: " . htmlspecialchars($row['ISBN']) . "</small></td>
                                <td>" . htmlspecialchars($row['author']) . "</td>
                                <td>" . htmlspecialchars($row['category']) . "</td>
                                <td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>
                                <td>
                                    <span class='status-badge status-" . htmlspecialchars($row['status']) . "'>" . ucfirst($row['status']) . "</span>
                                    " . (!empty($row['due_date']) ? "<br><small>Due: " . date('M d, Y', strtotime($row['due_date'])) . "</small>" : "") . "
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; color:#666;'>No borrowed books yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="card" style="margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0;">Books</h3>
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="text" placeholder="Search books..." style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                <button class="btn" style="margin-right: 10px;" onclick="window.location.href='Books.php'">Search</button>
                <select style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <option>All Books</option>
                    <option>Fiction</option>
                    <option>Sci-Fi</option>
                    <option>Horror</option>
                    <option>Non-Fiction</option>
                    <option>Romance</option>
                </select>
            </div>
        </div>

        <table class="book-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT * FROM books ORDER BY title ASC
                ";

                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td><strong>" . htmlspecialchars($row['title']) . "</strong><br><small>ISBN: " . htmlspecialchars($row['ISBN']) . "</small></td>
                                <td>" . htmlspecialchars($row['author']) . "</td>
                                <td>" . htmlspecialchars($row['category']) . "</td>
                                <td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>
                                <td>
                                    <span class='status-badge status-" . htmlspecialchars($row['status']) . "'>" . ucfirst($row['status']) . "</span>
                                    " . (!empty($row['due_date']) ? "<br><small>Due: " . date('M d, Y', strtotime($row['due_date'])) . "</small>" : "") . "
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; color:#666;'>No borrowed books yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Activity and Notifications -->
    <div class="content-grid">
        <!-- Recent Activity -->
        <div class="card">
            <h3>My Recent Activity</h3>
            <div>
                <?php
                if ($student_id) {
                    $activityQuery = "
                        SELECT b.title, a.action_type, a.action_date
                        FROM activity_log a
                        JOIN books b ON a.book_id = b.book_id
                        WHERE a.student_id = '$student_id'
                        ORDER BY a.action_date DESC
                        LIMIT 5
                    ";
                    $activityResult = $conn->query($activityQuery);

                    if ($activityResult && $activityResult->num_rows > 0) {
                        while ($row = $activityResult->fetch_assoc()) {
                            $date = new DateTime($row['action_date']);
                            $today = new DateTime();
                            $daysAgo = $today->diff($date)->days;

                            echo "<p>" . ucfirst($row['action_type']) . " <strong>" 
                                . htmlspecialchars($row['title']) . "</strong> — $daysAgo day"
                                . ($daysAgo == 1 ? '' : 's') . " ago</p>";
                        }
                    } else {
                        echo "<p>No recent activity found.</p>";
                    }
                } else {
                    echo "<p>Login required to view activity.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card">
            <h3>Important Notifications</h3>
            <div>
                <?php
                $notifQuery = "SELECT * FROM notifications ORDER BY date_created DESC LIMIT 5";
                $notifResult = $conn->query($notifQuery);

                if ($notifResult && $notifResult->num_rows > 0) {
                    while ($notif = $notifResult->fetch_assoc()) {
                        $bg = "#d1ecf1"; $border = "#bee5eb"; $color = "#0c5460";
                        if (strtolower($notif['type']) === 'reminder') {
                            $bg = "#fff3cd"; $border = "#ffeaa7"; $color = "#856404";
                        } elseif (strtolower($notif['type']) === 'warning') {
                            $bg = "#f8d7da"; $border = "#f5c6cb"; $color = "#721c24";
                        }

                        echo "
                        <div style='background: $bg; border: 1px solid $border; border-radius: 6px; padding: 15px; margin: 10px 0;'>
                            <div style='font-weight: bold; color: $color;'>" . htmlspecialchars($notif['type']) . "</div>
                            <div style='font-size: 12px; color: $color; margin: 5px 0;'>" . htmlspecialchars($notif['message']) . "</div>
                            <div style='font-size: 11px; color: gray;'>Posted: " . htmlspecialchars($notif['date_created']) . "</div>
                        </div>";
                    }
                } else {
                    echo "<p>No notifications at the moment.</p>";
                }
                ?>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn">View All Notifications</button>
            </div>
        </div>
    </div>

</div>
</body>
</html>
