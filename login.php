<?php
session_start();
// include 'db.php'; // You can uncomment this if you use a separate file

$error_message = ""; // Initialize an empty error variable
$email_input = "";   // To keep the email in the box if they fail

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $email_input = $email; // Remember email so user doesn't have to retype

    $conn = new mysqli("localhost", "root", "", "library_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE (email='$email' OR student_id='$email') AND role='$role' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // LOGIN SUCCESS
            $_SESSION['role'] = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['user_name'] = $row['full_name']; // For your dashboard name

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
            // CASE 1: Password Incorrect
            // We create a link that sends the email to the forgot password page
            $forgot_link = "forgot_password.php?email=" . urlencode($email);
            $error_message = "Incorrect password. <a href='$forgot_link'>Forgot Password?</a>";
        }
    } else {
        // CASE 2: User Not Found
        $error_message = "Account not found or role mismatch. <a href='register.php'>Create Account?</a>";
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
    <style>
        /* Add this style for the error box */
        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        .error-box a {
            color: #721c24;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="emoji">📚</div>
            <h1>Welcome Back</h1>
            <p>Sign in to your account</p>
        </div>

        <form action="login.php" method="POST">
            
            <?php if (!empty($error_message)): ?>
                <div class="error-box">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="input-field">
                <label for="email">Email/Username</label>
                <input type="text" id="email" name="email" placeholder="Enter your email or username" 
                       value="<?php echo htmlspecialchars($email_input); ?>" required>
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
            
            <button type="button" class="forgot-btn" onclick="window.location.href='forgot_password.php'">Forgot password?</button>
        </form>

        <div class="footer">
            <p>Don't have an account?</p>
            <button onclick="window.location.href='register.php'">Create new account</button>
        </div>
    </div>

</body>
</html>