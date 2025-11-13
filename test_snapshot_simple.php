<?php
/**
 * Simple Snapshot Test - No CodeIgniter Required
 * Just checks database structure and data
 */

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
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

?>
<!DOCTYPE html>
<html>
<head>
    <title>Snapshot System Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background: #f8f9fa; }
        code { background: #f8f9fa; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
    </style>
</head>
<body>
<div class='container'>
<h1>🧪 Snapshot System Test</h1>

<?php
// Test 1: Check snapshot columns in quotations table
echo "<h2>Test 1: Quotations Table Structure</h2>";
$result = $conn->query("SHOW COLUMNS FROM quotations");
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

$required_snapshots = ['company_snapshot', 'client_snapshot', 'bank_snapshot', 'mode_snapshot'];
$found_snapshots = array_intersect($required_snapshots, $columns);

echo "<table>";
echo "<tr><th>Required Column</th><th>Status</th></tr>";
foreach ($required_snapshots as $col) {
    $exists = in_array($col, $columns);
    $badge = $exists ? "<span class='badge badge-success'>✅ EXISTS</span>" : "<span class='badge badge-danger'>❌ MISSING</span>";
    echo "<tr><td><code>$col</code></td><td>$badge</td></tr>";
}
echo "</table>";

if (count($found_snapshots) == 4) {
    echo "<div class='success'><strong>✅ All quotation snapshot columns exist!</strong></div>";
} else {
    echo "<div class='error'><strong>❌ Missing " . (4 - count($found_snapshots)) . " snapshot column(s)</strong></div>";
    echo "<div class='info'>Run migration: <code>http://localhost/crm/run_quotation_snapshot_migration.php</code></div>";
}

// Test 2: Check snapshot columns in quotation_items table
echo "<h2>Test 2: Quotation Items Table Structure</h2>";
$result = $conn->query("SHOW COLUMNS FROM quotation_items");
$item_columns = [];
while ($row = $result->fetch_assoc()) {
    $item_columns[] = $row['Field'];
}

$required_item_cols = ['use_dropdown', 'description', 'category_snapshot', 'product_snapshot'];
$found_item_cols = array_intersect($required_item_cols, $item_columns);

echo "<table>";
echo "<tr><th>Required Column</th><th>Status</th></tr>";
foreach ($required_item_cols as $col) {
    $exists = in_array($col, $item_columns);
    $badge = $exists ? "<span class='badge badge-success'>✅ EXISTS</span>" : "<span class='badge badge-danger'>❌ MISSING</span>";
    echo "<tr><td><code>$col</code></td><td>$badge</td></tr>";
}
echo "</table>";

if (count($found_item_cols) == 4) {
    echo "<div class='success'><strong>✅ All quotation item columns exist!</strong></div>";
} else {
    echo "<div class='error'><strong>❌ Missing " . (4 - count($found_item_cols)) . " column(s)</strong></div>";
    echo "<div class='info'>Run migration: <code>http://localhost/crm/run_quotation_snapshot_migration.php</code></div>";
}

// Test 3: Check if quotations have snapshot data
echo "<h2>Test 3: Snapshot Data Check</h2>";
$result = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN company_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_company,
    SUM(CASE WHEN client_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_client,
    SUM(CASE WHEN bank_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_bank,
    SUM(CASE WHEN mode_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_mode
FROM quotations");

$stats = $result->fetch_assoc();

echo "<table>";
echo "<tr><th>Metric</th><th>Count</th></tr>";
echo "<tr><td>Total Quotations</td><td><strong>{$stats['total']}</strong></td></tr>";
echo "<tr><td>With Company Snapshot</td><td><strong>{$stats['with_company']}</strong></td></tr>";
echo "<tr><td>With Client Snapshot</td><td><strong>{$stats['with_client']}</strong></td></tr>";
echo "<tr><td>With Bank Snapshot</td><td><strong>{$stats['with_bank']}</strong></td></tr>";
echo "<tr><td>With Mode Snapshot</td><td><strong>{$stats['with_mode']}</strong></td></tr>";
echo "</table>";

if ($stats['total'] > 0) {
    if ($stats['with_company'] > 0) {
        echo "<div class='success'>✅ {$stats['with_company']} quotation(s) have snapshots captured</div>";
    } else {
        echo "<div class='warning'>⚠️ No quotations have snapshots yet</div>";
        echo "<div class='info'>💡 <strong>This is normal if:</strong><br>";
        echo "• All quotations were created before migration<br>";
        echo "• You haven't created any new quotations yet<br><br>";
        echo "<strong>Solution:</strong> Create a NEW quotation to test snapshot functionality</div>";
    }
} else {
    echo "<div class='info'>ℹ️ No quotations in database yet. Create one to test!</div>";
}

// Test 4: Check quotation items snapshot data
echo "<h2>Test 4: Quotation Items Snapshot Data</h2>";
$result = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN category_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_category,
    SUM(CASE WHEN product_snapshot IS NOT NULL THEN 1 ELSE 0 END) as with_product,
    SUM(CASE WHEN use_dropdown = 0 THEN 1 ELSE 0 END) as using_description
FROM quotation_items");

$item_stats = $result->fetch_assoc();

echo "<table>";
echo "<tr><th>Metric</th><th>Count</th></tr>";
echo "<tr><td>Total Items</td><td><strong>{$item_stats['total']}</strong></td></tr>";
echo "<tr><td>With Category Snapshot</td><td><strong>{$item_stats['with_category']}</strong></td></tr>";
echo "<tr><td>With Product Snapshot</td><td><strong>{$item_stats['with_product']}</strong></td></tr>";
echo "<tr><td>Using Manual Description</td><td><strong>{$item_stats['using_description']}</strong></td></tr>";
echo "</table>";

// Test 5: Show latest quotation details
echo "<h2>Test 5: Latest Quotation Analysis</h2>";
$result = $conn->query("SELECT * FROM quotations ORDER BY id DESC LIMIT 1");
$latest = $result ? $result->fetch_assoc() : null;

if ($latest) {
    echo "<div class='info'><strong>Quotation ID:</strong> {$latest['id']} | <strong>Created:</strong> {$latest['created_at']}</div>";
    
    echo "<h3>Snapshot Status:</h3>";
    echo "<table>";
    echo "<tr><th>Snapshot Type</th><th>Status</th><th>Preview</th></tr>";
    
    $snapshots = [
        'Company' => $latest['company_snapshot'],
        'Client' => $latest['client_snapshot'],
        'Bank' => $latest['bank_snapshot'],
        'Mode' => $latest['mode_snapshot']
    ];
    
    foreach ($snapshots as $type => $data) {
        $status = !empty($data) ? "<span class='badge badge-success'>✅ CAPTURED</span>" : "<span class='badge badge-danger'>❌ EMPTY</span>";
        $preview = '';
        if (!empty($data)) {
            $json = json_decode($data, true);
            $preview = isset($json['name']) ? "<code>{$json['name']}</code>" : "<code>Data exists</code>";
        }
        echo "<tr><td><strong>$type</strong></td><td>$status</td><td>$preview</td></tr>";
    }
    echo "</table>";
    
    // Check items for this quotation
    $items_result = $conn->query("SELECT * FROM quotation_items WHERE quotation_id = {$latest['id']}");
    if ($items_result && $items_result->num_rows > 0) {
        echo "<h3>Items Snapshot Status:</h3>";
        echo "<table>";
        echo "<tr><th>#</th><th>Product ID</th><th>Category ID</th><th>Product Snapshot</th><th>Category Snapshot</th></tr>";
        $idx = 1;
        while ($item = $items_result->fetch_assoc()) {
            $prod_status = !empty($item['product_snapshot']) ? "✅" : "❌";
            $cat_status = !empty($item['category_snapshot']) ? "✅" : "❌";
            echo "<tr>";
            echo "<td>$idx</td>";
            echo "<td>{$item['product_id']}</td>";
            echo "<td>{$item['category_id']}</td>";
            echo "<td>$prod_status</td>";
            echo "<td>$cat_status</td>";
            echo "</tr>";
            $idx++;
        }
        echo "</table>";
    }
} else {
    echo "<div class='info'>No quotations found. Create one to test!</div>";
}

// Final Summary
echo "<h2>📊 Summary & Next Steps</h2>";

$all_columns_exist = (count($found_snapshots) == 4 && count($found_item_cols) == 4);
$has_snapshot_data = ($stats['total'] > 0 && $stats['with_company'] > 0);

if ($all_columns_exist && $has_snapshot_data) {
    echo "<div class='success'>";
    echo "<h3>✅ System is Working Correctly!</h3>";
    echo "<p><strong>What you can do now:</strong></p>";
    echo "<ol>";
    echo "<li>Delete a product/category used in a quotation</li>";
    echo "<li>View/Download the quotation PDF</li>";
    echo "<li>The deleted product/category name should still show (from snapshot)</li>";
    echo "</ol>";
    echo "</div>";
} elseif ($all_columns_exist && !$has_snapshot_data) {
    echo "<div class='warning'>";
    echo "<h3>⚠️ Columns Exist, But No Snapshot Data Yet</h3>";
    echo "<p><strong>This means:</strong></p>";
    echo "<ul>";
    echo "<li>Migration completed successfully ✅</li>";
    echo "<li>All quotations were created BEFORE migration</li>";
    echo "<li>New quotations will capture snapshots automatically</li>";
    echo "</ul>";
    echo "<p><strong>Next Step:</strong> Create a NEW quotation to test snapshot functionality</p>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h3>❌ Migration Not Complete</h3>";
    echo "<p><strong>Action Required:</strong></p>";
    echo "<p>Run the migration: <code>http://localhost/crm/run_quotation_snapshot_migration.php</code></p>";
    echo "</div>";
}

$conn->close();
?>

</div>
</body>
</html>
