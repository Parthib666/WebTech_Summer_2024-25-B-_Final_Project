<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'restaurant_db'; // Change to your actual database name


$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

?>