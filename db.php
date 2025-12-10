
<?php
    $host = "localhost";
    $user = "root";
    $pass = ""; 
    $db = "library_db"; // Ensure this database exists

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

?>