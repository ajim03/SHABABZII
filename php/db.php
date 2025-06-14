<?php
// Enable error reporting (for development only – remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = 'root'; 
$dbName = 'shabab_db';
$dbPort = 8889; // Usually 3306 for MAMP's MySQL

// Create a connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName, $dbPort);

// Check the connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
