<?php
/**
 * Test Snapshot Functionality
 * This script tests if snapshots are working correctly
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
    <title>Test Snapshot Functionality</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
<div class='container'>
<h1>🧪 Snapshot Functionality Test</h1>";

// Test 1: Check if snapshot columns exist
echo "<h2>Test 1: Database Schema Check</h2>";
$quotations_cols = $conn->query("SHOW COLUMNS FROM quotations LIKE '%snapshot%'");
$items_cols = $conn->query("SHOW COLUMNS FROM quotation_items WHERE Field IN ('use_dropdown', 'description', 'category_snapshot', 'product_snapshot')");

if ($quotations_cols->num_rows >= 4 && $items_cols->num_rows >= 4) {
    echo "<div class='success'>✅ All snapshot columns exist in database</div>";
    echo "<p>Quotations table: {$quotations_cols->num_rows} snapshot columns</p>";
    echo "<p>Quotation items table: {$items_cols->num_rows} required columns</p>";
} else {
    echo "<div class='error'>❌ Snapshot columns missing. Please run migration first.</div>";
    echo "<p>Found " . $quotations_cols->num_rows . " snapshot columns in quotations table (expected 4)</p>";
    echo "<p>Found " . $items_cols->num_rows . " columns in quotation_items table (expected 4)</p>";
    echo "<div class='info'><strong>Run migration:</strong> <code>http://localhost/crm/run_quotation_snapshot_migration.php</code></div>";
    echo "</div></body></html>";
    $conn->close();
    exit;
}

// Test 2: Get a quotation and check if snapshot data is used
echo "<h2>Test 2: Quotation Data Retrieval</h2>";
$result = $conn->query("SELECT * FROM quotations ORDER BY id DESC LIMIT 1");
$quotation = $result ? $result->fetch_object() : null;

if ($quotation) {
    echo "<div class='info'><strong>Testing with Quotation ID: {$quotation->id}</strong></div>";
    
    // Get quotation with details
    $full_quotation = $CI->Quotation_model->getQuotationWithDetails($quotation->id);
    $items = $CI->Quotation_model->getQuotationItems($quotation->id);
    
    echo "<h3>Company Data:</h3>";
    if (!empty($full_quotation->company_name)) {
        echo "<div class='success'>✅ Company Name: {$full_quotation->company_name}</div>";
        if (isset($full_quotation->_company_from_snapshot)) {
            echo "<div class='warning'>⚠️ Loaded from snapshot (company was deleted)</div>";
        } else {
            echo "<div class='info'>ℹ️ Loaded from database (company exists)</div>";
        }
    } else {
        echo "<div class='error'>❌ No company data available</div>";
    }
    
    echo "<h3>Client Data:</h3>";
    if (!empty($full_quotation->client_name)) {
        echo "<div class='success'>✅ Client Name: {$full_quotation->client_name}</div>";
        if (isset($full_quotation->_client_from_snapshot)) {
            echo "<div class='warning'>⚠️ Loaded from snapshot (client was deleted)</div>";
        } else {
            echo "<div class='info'>ℹ️ Loaded from database (client exists)</div>";
        }
    } else {
        echo "<div class='error'>❌ No client data available</div>";
    }
    
    echo "<h3>Bank Data:</h3>";
    if (!empty($full_quotation->bank_name)) {
        echo "<div class='success'>✅ Bank Name: {$full_quotation->bank_name}</div>";
        if (isset($full_quotation->_bank_from_snapshot)) {
            echo "<div class='warning'>⚠️ Loaded from snapshot (bank was deleted)</div>";
        } else {
            echo "<div class='info'>ℹ️ Loaded from database (bank exists)</div>";
        }
    } else {
        echo "<div class='error'>❌ No bank data available</div>";
    }
    
    echo "<h3>Quotation Items:</h3>";
    if (!empty($items)) {
        echo "<table>";
        echo "<tr><th>#</th><th>Category</th><th>Product/Service</th><th>Qty</th><th>Rate</th><th>Source</th></tr>";
        foreach ($items as $idx => $item) {
            $source = 'Database';
            if (isset($item->_category_from_snapshot)) $source = 'Snapshot (Category Deleted)';
            if (isset($item->_product_from_snapshot)) $source = 'Snapshot (Product Deleted)';
            if (isset($item->_using_description)) $source = 'Manual Description';
            
            echo "<tr>";
            echo "<td>" . ($idx + 1) . "</td>";
            echo "<td>" . ($item->category_name ?? 'N/A') . "</td>";
            echo "<td>" . ($item->product_name ?? 'N/A') . "</td>";
            echo "<td>" . ($item->qty ?? 0) . "</td>";
            echo "<td>₹" . number_format($item->rate ?? 0, 2) . "</td>";
            echo "<td><strong>$source</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        $snapshot_count = 0;
        foreach ($items as $item) {
            if (isset($item->_category_from_snapshot) || isset($item->_product_from_snapshot)) {
                $snapshot_count++;
            }
        }
        
        if ($snapshot_count > 0) {
            echo "<div class='warning'>⚠️ $snapshot_count item(s) using snapshot data (master records deleted)</div>";
        } else {
            echo "<div class='success'>✅ All items loaded from database (no deletions)</div>";
        }
    } else {
        echo "<div class='info'>No items in this quotation</div>";
    }
    
    // Test 3: Check snapshot data in database
    echo "<h2>Test 3: Snapshot Data in Database</h2>";
    $has_snapshots = false;
    if (!empty($quotation->company_snapshot)) {
        echo "<div class='success'>✅ Company snapshot exists</div>";
        $has_snapshots = true;
    }
    if (!empty($quotation->client_snapshot)) {
        echo "<div class='success'>✅ Client snapshot exists</div>";
        $has_snapshots = true;
    }
    if (!empty($quotation->bank_snapshot)) {
        echo "<div class='success'>✅ Bank snapshot exists</div>";
        $has_snapshots = true;
    }
    if (!empty($quotation->mode_snapshot)) {
        echo "<div class='success'>✅ Mode snapshot exists</div>";
        $has_snapshots = true;
    }
    
    if (!$has_snapshots) {
        echo "<div class='warning'>⚠️ This is an old quotation (created before snapshot migration)</div>";
        echo "<div class='info'>💡 Create a new quotation to test snapshot functionality</div>";
    }
    
} else {
    echo "<div class='error'>❌ No quotations found in database. Create a quotation first.</div>";
}

echo "<h2>Summary</h2>";
echo "<div class='info'>";
echo "<strong>Next Steps:</strong><br>";
echo "1. If snapshot columns are missing, run the migration<br>";
echo "2. Create a new quotation to capture snapshots<br>";
echo "3. Delete a product/category used in that quotation<br>";
echo "4. View/Download PDF - data should still show!<br>";
echo "</div>";

echo "</div></body></html>";
?>
