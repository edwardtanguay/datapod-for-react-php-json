<?php
// create_persons_table.php

$dbPath = "../parseddata/main.sqlite";

try {
    // Create directory if it doesn't exist
    $dir = dirname($dbPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    // Connect to SQLite database (creates file if it doesn't exist)
    $db = new PDO("sqlite:" . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create persons table
    $sql = "CREATE TABLE IF NOT EXISTS persons (
        id TEXT PRIMARY KEY,
        firstName TEXT NOT NULL,
        lastName TEXT NOT NULL,
        age INTEGER
    )";
    
    $db->exec($sql);
    
    echo "Table 'persons' created successfully in: " . $dbPath . "\n";
    echo "Table schema:\n";
    echo "- id (TEXT PRIMARY KEY)\n";
    echo "- firstName (TEXT)\n";
    echo "- lastName (TEXT)\n";
    echo "- age (INTEGER)\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>