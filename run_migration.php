<?php
/**
 * Populate Quotation Snapshots
 * Run this script ONCE after SQL migration to populate existing quotations with snapshot data
 * 
 * Access: http://localhost/crm/run_migration.php
 * 
 * IMPORTANT: This file has been repurposed for snapshot population.
 * After running successfully, you can delete this file or keep it for reference.
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'crm_db';

// HTML Output
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Populate Quotation Snapshots</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 900px; margin: 0 auto; }
        h2 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        h3 { color: #666; margin-top: 30px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0; }
        .warning { background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin: 20px 0; }
        .progress { background: #f0f0f0; padding: 15px; margin: 10px 0; border-radius: 4px; font-family: monospace; line-height: 1.8; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #4CAF50; color: white; }
        .btn { background: #4CAF50; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin: 10px 5px; }
        .btn:hover { background: #45a049; }
        .btn-secondary { background: #2196F3; }
        .btn-secondary:hover { background: #0b7dda; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h2>⚙️ Quotation Snapshot Population Tool</h2>
        <div class="info">
            <strong>ℹ️ Purpose:</strong> This script captures current data for all existing quotations and stores them as JSON snapshots.
            This ensures historical quotations remain intact even if referenced entities (companies, clients, banks, products) are deleted.
        </div>

<?php

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p class='success'>✓ Connected to database successfully</p>";
    
    // Step 1: Populate Quotation Snapshots
    echo "<h3>Step 1: Populating Quotation Snapshots</h3>";
    
    $stmt = $pdo->query("SELECT id, company_id, client_id, bank_id, mode_id FROM quotations");
    $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_quotations = count($quotations);
    $updated = 0;
    $errors = 0;
    
    echo "<p>Found <strong>{$total_quotations}</strong> quotations to process...</p>";
    echo "<div class='progress'>";
    
    foreach ($quotations as $quotation) {
        $updates = [];
        $q_id = $quotation['id'];
        
        // Capture company snapshot
        if ($quotation['company_id']) {
            $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
            $stmt->execute([$quotation['company_id']]);
            if ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $snapshot = json_encode([
                    'id' => $comp['id'],
                    'name' => $comp['name'] ?? '',
                    'address' => $comp['address'] ?? '',
                    'email' => $comp['email'] ?? '',
                    'mobile' => $comp['mobile'] ?? '',
                    'state' => $comp['state'] ?? '',
                    'gst_no' => $comp['gst_no'] ?? '',
                    'pan_card' => $comp['pan_card'] ?? '',
                    'cin_no' => $comp['cin_no'] ?? '',
                    'logo' => $comp['logo'] ?? '',
                    'captured_at' => date('Y-m-d H:i:s')
                ]);
                $updates['company_snapshot'] = $snapshot;
            }
        }
        
        // Capture client snapshot
        if ($quotation['client_id']) {
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
            $stmt->execute([$quotation['client_id']]);
            if ($cli = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $snapshot = json_encode([
                    'id' => $cli['id'],
                    'name' => $cli['name'] ?? '',
                    'address' => $cli['address'] ?? '',
                    'email' => $cli['email'] ?? '',
                    'mobile' => $cli['mobile'] ?? '',
                    'state' => $cli['state'] ?? '',
                    'gst_no' => $cli['gst_no'] ?? '',
                    'pan_card' => $cli['pan_card'] ?? '',
                    'captured_at' => date('Y-m-d H:i:s')
                ]);
                $updates['client_snapshot'] = $snapshot;
            }
        }
        
        // Capture bank snapshot
        if ($quotation['bank_id']) {
            $stmt = $pdo->prepare("SELECT * FROM banks WHERE id = ?");
            $stmt->execute([$quotation['bank_id']]);
            if ($bnk = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $snapshot = json_encode([
                    'id' => $bnk['id'],
                    'name' => $bnk['name'] ?? '',
                    'branch_address' => $bnk['branch_address'] ?? '',
                    'ac_no' => $bnk['ac_no'] ?? '',
                    'ifsc_code' => $bnk['ifsc_code'] ?? '',
                    'captured_at' => date('Y-m-d H:i:s')
                ]);
                $updates['bank_snapshot'] = $snapshot;
            }
        }
        
        // Capture mode snapshot
        if ($quotation['mode_id']) {
            $stmt = $pdo->prepare("SELECT * FROM modes WHERE id = ?");
            $stmt->execute([$quotation['mode_id']]);
            if ($mod = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $snapshot = json_encode([
                    'id' => $mod['id'],
                    'name' => $mod['name'] ?? '',
                    'captured_at' => date('Y-m-d H:i:s')
                ]);
                $updates['mode_snapshot'] = $snapshot;
            }
        }
        
        // Update quotation
        if (!empty($updates)) {
            $set_clause = [];
            $params = [];
            foreach ($updates as $column => $value) {
                $set_clause[] = "`$column` = ?";
                $params[] = $value;
            }
            $params[] = $q_id;
            
            $sql = "UPDATE quotations SET " . implode(', ', $set_clause) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $updated++;
                echo "✓ ";
            } else {
                $errors++;
                echo "❌ ";
            }
        } else {
            echo "⚠️ ";
        }
        
        if ($updated % 50 == 0) echo "<br>";
    }
    
    echo "</div>";
    echo "<p class='success'>✓ Updated <strong>{$updated}</strong> quotations with snapshots</p>";
    if ($errors > 0) {
        echo "<p class='error'>❌ {$errors} errors occurred</p>";
    }
    
    // Step 2: Populate Quotation Item Snapshots
    echo "<h3>Step 2: Populating Quotation Item Snapshots</h3>";
    
    $stmt = $pdo->query("SELECT id, category_id, product_id FROM quotation_items WHERE use_dropdown = 1 OR use_dropdown IS NULL");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_items = count($items);
    $items_updated = 0;
    $item_errors = 0;
    
    echo "<p>Found <strong>{$total_items}</strong> quotation items to process...</p>";
    echo "<div class='progress'>";
    
    foreach ($items as $item) {
        $updates = [];
        $item_id = $item['id'];
        
        // Capture category snapshot
        if ($item['category_id']) {
            $stmt = $pdo->prepare("SELECT * FROM product_service_categories WHERE id = ?");
            $stmt->execute([$item['category_id']]);
            if ($cat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $snapshot = json_encode([
                    'id' => $cat['id'],
                    'name' => $cat['name'] ?? '',
                    'captured_at' => date('Y-m-d H:i:s')
                ]);
                $updates['category_snapshot'] = $snapshot;
            }
        }
        
        // Capture product snapshot
        if ($item['product_id']) {
            $stmt = $pdo->prepare("SELECT * FROM products_services WHERE id = ?");
            $stmt->execute([$item['product_id']]);
            if ($prod = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $snapshot = json_encode([
                    'id' => $prod['id'],
                    'name' => $prod['name'] ?? '',
                    'rate_per_unit' => $prod['rate_per_unit'] ?? 0,
                    'captured_at' => date('Y-m-d H:i:s')
                ]);
                $updates['product_snapshot'] = $snapshot;
            }
        }
        
        // Update item
        if (!empty($updates)) {
            $set_clause = [];
            $params = [];
            foreach ($updates as $column => $value) {
                $set_clause[] = "`$column` = ?";
                $params[] = $value;
            }
            $params[] = $item_id;
            
            $sql = "UPDATE quotation_items SET " . implode(', ', $set_clause) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $items_updated++;
                echo "✓ ";
            } else {
                $item_errors++;
                echo "❌ ";
            }
        } else {
            echo "⚠️ ";
        }
        
        if ($items_updated % 50 == 0) echo "<br>";
    }
    
    echo "</div>";
    echo "<p class='success'>✓ Updated <strong>{$items_updated}</strong> quotation items with snapshots</p>";
    if ($item_errors > 0) {
        echo "<p class='error'>❌ {$item_errors} errors occurred</p>";
    }
    
    ?>
    
    <h3 style='color: #4CAF50;'>✓ Migration Complete!</h3>
    
    <table>
        <tr>
            <th>Summary</th>
            <th>Count</th>
        </tr>
        <tr>
            <td>Total Quotations Processed</td>
            <td><?php echo $total_quotations; ?></td>
        </tr>
        <tr>
            <td>Quotations Updated</td>
            <td><?php echo $updated; ?></td>
        </tr>
        <tr>
            <td>Total Items Processed</td>
            <td><?php echo $total_items; ?></td>
        </tr>
        <tr>
            <td>Items Updated</td>
            <td><?php echo $items_updated; ?></td>
        </tr>
    </table>
    
    <div class="warning">
        <strong>⚠️ IMPORTANT - Next Steps:</strong>
        <ol>
            <li>This script should only be run ONCE</li>
            <li>Test your quotations to ensure data is displaying correctly</li>
            <li>Try deleting a test company/client and verify quotation still shows data</li>
            <li>You can keep or delete this file after successful execution</li>
        </ol>
    </div>
    
    <h3>Testing Instructions:</h3>
    <div class="info">
        <strong>Test Scenario:</strong>
        <ol>
            <li>Go to Quotation Management</li>
            <li>Create a new test quotation with a company, client, and products</li>
            <li>View/Download the PDF - should work normally</li>
            <li>Now delete the company or client used in that quotation</li>
            <li>Go back to the quotation - it should still display with original data</li>
            <li>Download PDF again - should show the same data as before</li>
        </ol>
        <p><strong>✓ If the above works, your implementation is successful!</strong></p>
    </div>
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="index.php" class="btn">Go to CRM Dashboard</a>
        <a href="index.php/quotation" class="btn btn-secondary">Go to Quotations</a>
    </p>
    
    <?php
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<div class='warning'>";
    echo "<strong>Please ensure:</strong>";
    echo "<ol>";
    echo "<li>WAMP/XAMPP is running</li>";
    echo "<li>MySQL service is started</li>";
    echo "<li>Database 'crm_db' exists</li>";
    echo "<li>You have run the SQL migration first (add_description_to_quotation_items.sql)</li>";
    echo "<li>Database credentials are correct</li>";
    echo "</ol>";
    echo "</div>";
}

?>
    </div>
</body>
</html>
