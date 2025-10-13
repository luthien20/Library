<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db"; // create this in phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
