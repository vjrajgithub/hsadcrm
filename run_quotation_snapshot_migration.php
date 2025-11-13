<?php
/**
 * Quotation Snapshot Migration Runner
 * 
 * This script adds snapshot columns to quotations and quotation_items tables
 * to preserve historical data even when master records are deleted.
 * 
 * Usage: Run this file once from browser: http://localhost/crm/run_quotation_snapshot_migration.php
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

// Database configuration
$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_user = $_ENV['DB_USER'] ?? 'root';
$db_pass = $_ENV['DB_PASS'] ?? '';
$db_name = $_ENV['DB_NAME'] ?? 'crm_db';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Quotation Snapshot Migration</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 900px; margin: 0 auto; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        .step { margin: 20px 0; padding: 15px; background: #f8f9fa; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
<div class='container'>
<h1>📦 Quotation Snapshot Migration</h1>
<p>This migration adds snapshot functionality to preserve quotation data integrity.</p>
";

$errors = [];
$success = [];

// Step 1: Check if columns already exist
echo "<div class='step'><h2>Step 1: Checking Current Schema</h2>";

$check_quotations = $conn->query("SHOW COLUMNS FROM quotations LIKE 'company_snapshot'");
$check_items = $conn->query("SHOW COLUMNS FROM quotation_items LIKE 'use_dropdown'");

if ($check_quotations->num_rows > 0) {
    echo "<div class='warning'>⚠️ Snapshot columns already exist in quotations table. Skipping...</div>";
    $quotations_migrated = true;
} else {
    $quotations_migrated = false;
    echo "<div class='info'>✓ Quotations table ready for migration</div>";
}

if ($check_items->num_rows > 0) {
    echo "<div class='warning'>⚠️ Snapshot columns already exist in quotation_items table. Skipping...</div>";
    $items_migrated = true;
} else {
    $items_migrated = false;
    echo "<div class='info'>✓ Quotation items table ready for migration</div>";
}
echo "</div>";

// Step 2: Add columns to quotations table (one by one)
echo "<div class='step'><h2>Step 2: Migrating Quotations Table</h2>";

$quotation_columns = [
    ['name' => 'company_snapshot', 'after' => 'company_id', 'comment' => 'Snapshot of company data'],
    ['name' => 'client_snapshot', 'after' => 'client_id', 'comment' => 'Snapshot of client data'],
    ['name' => 'bank_snapshot', 'after' => 'bank_id', 'comment' => 'Snapshot of bank data'],
    ['name' => 'mode_snapshot', 'after' => 'mode_id', 'comment' => 'Snapshot of mode data']
];

foreach ($quotation_columns as $col) {
    $check = $conn->query("SHOW COLUMNS FROM quotations LIKE '{$col['name']}'");
    if ($check->num_rows > 0) {
        echo "<div class='info'>⊙ Column {$col['name']} already exists - skipped</div>";
    } else {
        $sql = "ALTER TABLE `quotations` ADD COLUMN `{$col['name']}` JSON NULL COMMENT '{$col['comment']}' AFTER `{$col['after']}`";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success'>✓ Added column: {$col['name']}</div>";
            $success[] = "Added {$col['name']}";
        } else {
            $error_msg = "Error adding {$col['name']}: " . $conn->error;
            echo "<div class='error'>✗ $error_msg</div>";
            $errors[] = $error_msg;
        }
    }
}
echo "</div>";

// Step 3: Add columns to quotation_items table (one by one)
echo "<div class='step'><h2>Step 3: Migrating Quotation Items Table</h2>";

$item_columns = [
    ['name' => 'use_dropdown', 'type' => 'TINYINT(1) DEFAULT 1', 'after' => 'quotation_id', 'comment' => '1=dropdown, 0=description'],
    ['name' => 'description', 'type' => 'TEXT NULL', 'after' => 'use_dropdown', 'comment' => 'Manual description'],
    ['name' => 'category_snapshot', 'type' => 'JSON NULL', 'after' => 'category_id', 'comment' => 'Snapshot of category data'],
    ['name' => 'product_snapshot', 'type' => 'JSON NULL', 'after' => 'product_id', 'comment' => 'Snapshot of product data']
];

foreach ($item_columns as $col) {
    $check = $conn->query("SHOW COLUMNS FROM quotation_items LIKE '{$col['name']}'");
    if ($check->num_rows > 0) {
        echo "<div class='info'>⊙ Column {$col['name']} already exists - skipped</div>";
    } else {
        $sql = "ALTER TABLE `quotation_items` ADD COLUMN `{$col['name']}` {$col['type']} COMMENT '{$col['comment']}' AFTER `{$col['after']}`";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success'>✓ Added column: {$col['name']}</div>";
            $success[] = "Added {$col['name']}";
        } else {
            $error_msg = "Error adding {$col['name']}: " . $conn->error;
            echo "<div class='error'>✗ $error_msg</div>";
            $errors[] = $error_msg;
        }
    }
}
echo "</div>";

// Step 4: Verify migration
echo "<div class='step'><h2>Step 4: Verification</h2>";

$result = $conn->query("SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN company_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_company_snapshot,
    SUM(CASE WHEN client_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_client_snapshot,
    SUM(CASE WHEN bank_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_bank_snapshot,
    SUM(CASE WHEN mode_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_mode_snapshot
FROM quotations");

if ($result) {
    $stats = $result->fetch_assoc();
    echo "<h3>Quotations Table Statistics:</h3>";
    echo "<table>";
    echo "<tr><th>Metric</th><th>Count</th></tr>";
    echo "<tr><td>Total Quotations</td><td>{$stats['total_records']}</td></tr>";
    echo "<tr><td>With Company Snapshot</td><td>{$stats['with_company_snapshot']}</td></tr>";
    echo "<tr><td>With Client Snapshot</td><td>{$stats['with_client_snapshot']}</td></tr>";
    echo "<tr><td>With Bank Snapshot</td><td>{$stats['with_bank_snapshot']}</td></tr>";
    echo "<tr><td>With Mode Snapshot</td><td>{$stats['with_mode_snapshot']}</td></tr>";
    echo "</table>";
    
    if ($stats['total_records'] > 0 && $stats['with_company_snapshot'] == 0) {
        echo "<div class='info'>ℹ️ Existing quotations don't have snapshots yet. New quotations will automatically capture snapshots.</div>";
    }
}

$result = $conn->query("SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN category_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_category_snapshot,
    SUM(CASE WHEN product_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_product_snapshot,
    SUM(CASE WHEN use_dropdown = 0 THEN 1 ELSE 0 END) as using_description
FROM quotation_items");

if ($result) {
    $stats = $result->fetch_assoc();
    echo "<h3>Quotation Items Table Statistics:</h3>";
    echo "<table>";
    echo "<tr><th>Metric</th><th>Count</th></tr>";
    echo "<tr><td>Total Items</td><td>{$stats['total_records']}</td></tr>";
    echo "<tr><td>With Category Snapshot</td><td>{$stats['with_category_snapshot']}</td></tr>";
    echo "<tr><td>With Product Snapshot</td><td>{$stats['with_product_snapshot']}</td></tr>";
    echo "<tr><td>Using Description Mode</td><td>{$stats['using_description']}</td></tr>";
    echo "</table>";
}

echo "</div>";

// Summary
echo "<div class='step'><h2>Migration Summary</h2>";
if (count($errors) > 0) {
    echo "<div class='error'><strong>Migration completed with errors:</strong><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";
} else {
    echo "<div class='success'><strong>✓ Migration completed successfully!</strong></div>";
    echo "<div class='info'><strong>What's Next?</strong><ul>";
    echo "<li>New quotations will automatically capture snapshots of all related data</li>";
    echo "<li>Existing quotations will continue to work using database JOINs</li>";
    echo "<li>If you delete a company/client/bank, new quotations will use snapshot data</li>";
    echo "<li>PDFs and views will automatically use snapshot data when master data is missing</li>";
    echo "</ul></div>";
}
echo "</div>";

echo "<div class='info' style='margin-top: 30px;'>";
echo "<strong>🔒 Security Note:</strong> For security reasons, delete this migration file after successful execution.";
echo "</div>";

echo "</div></body></html>";

$conn->close();
?>
