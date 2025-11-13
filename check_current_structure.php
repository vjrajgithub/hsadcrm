<?php
/**
 * Check Current Database Structure
 * Run this to see what columns already exist
 */

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? 'root';
$db_pass = $_ENV['DB_PASS'] ?? '';
$db_name = $_ENV['DB_NAME'] ?? 'crm_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Check Database Structure</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background: #f8f9fa; }
        .missing { background: #fff3cd; color: #856404; }
        .exists { background: #d4edda; color: #155724; }
        .info { background: #d1ecf1; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
<div class='container'>
<h1>📊 Current Database Structure</h1>";

// Check quotations table
echo "<h2>Quotations Table Columns</h2>";
$result = $conn->query("SHOW COLUMNS FROM quotations");
echo "<table><tr><th>Column Name</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $result->fetch_assoc()) {
    $class = (strpos($row['Field'], 'snapshot') !== false) ? 'exists' : '';
    echo "<tr class='$class'>";
    echo "<td><strong>{$row['Field']}</strong></td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "<td>{$row['Default']}</td>";
    echo "<td>{$row['Extra']}</td>";
    echo "</tr>";
}
echo "</table>";

// Check quotation_items table
echo "<h2>Quotation Items Table Columns</h2>";
$result = $conn->query("SHOW COLUMNS FROM quotation_items");
echo "<table><tr><th>Column Name</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $result->fetch_assoc()) {
    $class = (strpos($row['Field'], 'snapshot') !== false || $row['Field'] == 'use_dropdown' || $row['Field'] == 'description') ? 'exists' : '';
    echo "<tr class='$class'>";
    echo "<td><strong>{$row['Field']}</strong></td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "<td>{$row['Default']}</td>";
    echo "<td>{$row['Extra']}</td>";
    echo "</tr>";
}
echo "</table>";

// Check what's missing
echo "<h2>Migration Status</h2>";
echo "<table><tr><th>Table</th><th>Column</th><th>Status</th></tr>";

$required_columns = [
    'quotations' => ['company_snapshot', 'client_snapshot', 'bank_snapshot', 'mode_snapshot'],
    'quotation_items' => ['use_dropdown', 'description', 'category_snapshot', 'product_snapshot']
];

foreach ($required_columns as $table => $columns) {
    foreach ($columns as $column) {
        $result = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
        $exists = $result->num_rows > 0;
        $status = $exists ? '✅ EXISTS' : '❌ MISSING';
        $class = $exists ? 'exists' : 'missing';
        echo "<tr class='$class'><td>$table</td><td>$column</td><td>$status</td></tr>";
    }
}
echo "</table>";

echo "<div class='info'><strong>Next Steps:</strong><br>";
echo "Run the migration script to add missing columns.<br>";
echo "Use: <code>http://localhost/crm/run_quotation_snapshot_migration.php</code></div>";

echo "</div></body></html>";

$conn->close();
?>
