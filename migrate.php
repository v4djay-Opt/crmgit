<?php
// migrate.php - The Migration Runner
include_once 'db.php';

// 1. Create migrations tracking table if missing
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// 2. Scan migrations folder
$dir = __DIR__ . '/migrations';
$files = scandir($dir);

$applied = [];
$res = mysqli_query($conn, "SELECT migration FROM migrations");
while ($row = mysqli_fetch_assoc($res)) {
    $applied[] = $row['migration'];
}

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;

    if (!in_array($file, $applied)) {
        echo "Applying migration: $file ... ";
        
        // Include the migration file
        include $dir . '/' . $file;
        
        // Record as applied
        $stmt = mysqli_prepare($conn, "INSERT INTO migrations (migration) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $file);
        mysqli_stmt_execute($stmt);
        
        echo "Done! \n";
    }
}
?>
