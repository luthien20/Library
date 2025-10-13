<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Connect to database (adjust credentials)
    $conn = new mysqli("localhost", "root", "", "libary_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check credentials
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='$role'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Login Successful!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid credentials. Try again.');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Library Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('library-bg.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            height: 100vh;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.4);
            position: fixed;
            inset: 0;
        }
        .form-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px 40px;
            max-width: 380px;
            width: 100%;
            margin: auto;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .logo {
            font-size: 48px;
            margin-bottom: 8px;
        }
        .green-btn {
            background-color: #16a34a;
            color: white;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
        }
        .green-btn:hover {
            background-color: #15803d;
        }
    </style>
</head>
<body>
    <div class="overlay flex items-center justify-center ">
        <div class="form-box text-center">
            <div class="logo">📚</div>
            <h2 class="text-2xl font-bold mb-1">Welcome Back</h2>
            <p class="text-gray-600 mb-6">Sign in to your account</p>

            <form method="POST" action="">
                <div class="text-left mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email/Username</label>
                    <input type="text" name="email" required placeholder="Enter your email or username"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="text-left mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Enter your password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="text-left mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="" disabled selected>Select your role</option>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="librarian">Librarian</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <button type="submit" class="green-btn">Sign In</button>
            </form>

            <div class="mt-4">
                <a href="#" class="text-blue-600 text-sm hover:underline">Forgot password?</a>
            </div>

            <div class="mt-3 text-sm text-gray-700">
                Don't have an account? 
                <a href="register.php" class="text-blue-600 font-medium hover:underline">Create new account</a>
            </div>
        </div>
    </div>
</body>
</html>
