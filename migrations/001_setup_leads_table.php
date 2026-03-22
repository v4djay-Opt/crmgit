<?php
// migrations/001_setup_leads_table.php

// 1. Ensure leads table exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// 2. Add columns if missing (for existing tables)
$columns = [
    'rm' => "ALTER TABLE leads ADD COLUMN rm VARCHAR(255) AFTER message",
    'project' => "ALTER TABLE leads ADD COLUMN project VARCHAR(255) AFTER rm"
];

foreach ($columns as $column => $sql) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM leads LIKE '$column'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, $sql);
    }
}
?>
