<?php
// Multi-Environment Database Setup (Sample)
// Copy this file to db.php and fill in your live credentials.

$isLocal = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || $_SERVER['HTTP_HOST'] == 'localhost';

if ($isLocal) {
    // Localhost (XAMPP) Credentials
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "crm_test";
} else {
    // Live Server Credentials (FILL THESE IN ON THE LIVE SERVER)
    $host = "your-live-host.net";
    $user = "your-live-user";
    $pass = "your-live-password";
    $dbname = "your-live-db";
}

// 1. Initial Connection
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("DB Connection Failed: " . mysqli_connect_error());
}

// 2. Local Environment Automation: Create DB if missing
if ($isLocal) {
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");
}

// 3. Select Database
if (!mysqli_select_db($conn, $dbname)) {
    die("Database '$dbname' not found.");
}

// 4. Create Tables if missing
$createTable = "CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $createTable);
?>
