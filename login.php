<<<<<<< HEAD
<?php
session_start();
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    
    $conn = new mysqli("localhost", "root", "", "library_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "SELECT * FROM users WHERE (email='$email' OR 
        student_id='$email') AND role='$role' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['role'] = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];

            if ($row['role'] == "Librarian") {
                header("Location: librarian_dashboard.php");
            } elseif ($row['role'] == "Teacher") {
                header("Location: teacher_dashboard.php");
            } elseif ($row['role'] == "Student") {
                header("Location: student_dashboard.php");
            } else {
                header("Location: staff_dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('User not found or role mismatch.'); window.history.back();</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Login</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="emoji">📚</div>
            <h1>Welcome Back</h1>
            <p>Sign in to your account</p>
        </div>

        <form action="login.php" method="POST">
            <div class="input-field">
                <label for="email">Email/Username</label>
                <input type="text" id="email" name="email" placeholder="Enter your email or username" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div>
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="librarian">Librarian</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <button type="submit" class="signin-btn">Sign In</button>
            <button type="button" class="forgot-btn" onclick="window.location.href='forgot_password.html'">Forgot password?</button>
        </form>

        <div class="footer">
            <p>Don't have an account?</p>
            <button onclick="window.location.href='register.php'">Create new account</button>
        </div>
    </div>

</body>
</html>

=======
<?php
session_start();
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    
    $conn = new mysqli("localhost", "root", "", "library_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "SELECT * FROM users WHERE (email='$email' OR 
        student_id='$email') AND role='$role' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['role'] = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];

            if ($row['role'] == "Librarian") {
                header("Location: librarian_dashboard.php");
            } elseif ($row['role'] == "Teacher") {
                header("Location: teacher_dashboard.php");
            } elseif ($row['role'] == "Student") {
                header("Location: student_dashboard.php");
            } else {
                header("Location: staff_dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('User not found or role mismatch.'); window.history.back();</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Login</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="emoji">📚</div>
            <h1>Welcome Back</h1>
            <p>Sign in to your account</p>
        </div>

        <form action="login.php" method="POST">
            <div class="input-field">
                <label for="email">Email/Username</label>
                <input type="text" id="email" name="email" placeholder="Enter your email or username" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div>
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="librarian">Librarian</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <button type="submit" class="signin-btn">Sign In</button>
            <button type="button" class="forgot-btn" onclick="window.location.href='forgot_password.html'">Forgot password?</button>
        </form>

        <div class="footer">
            <p>Don't have an account?</p>
            <button onclick="window.location.href='register.php'">Create new account</button>
        </div>
    </div>

</body>
</html>

>>>>>>> f618bed497b3adfbce81626dbf242f15d699dcf6
