<<<<<<< HEAD
<?php
    $host = "localhost";
    $user = "root";
    $pass = ""; 
    $db = "library"; // Ensure this database exists

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
=======
<?php
    $host = "localhost";
    $user = "root";
    $pass = ""; 
    $db = "library"; // Ensure this database exists

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
>>>>>>> f618bed497b3adfbce81626dbf242f15d699dcf6
?>