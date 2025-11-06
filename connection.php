<?php
$servername = "localhost";  // or 127.0.0.1
$username = "root";         // your database username
$password = "";             // your database password (if any)
$dbname = "bus_donation";        // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
