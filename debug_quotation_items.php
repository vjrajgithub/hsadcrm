<?php
/**
 * Debug script to check quotation items data in database
 * Access via: http://localhost/crm/debug_quotation_items.php?id=24
 */

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'crm_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $quotation_id = isset($_GET['id']) ? (int)$_GET['id'] : 24;
    
    echo "<h2>Debug: Quotation Items for ID: {$quotation_id}</h2>";
    
    // Check table structure first
    echo "<h3>1. Table Structure:</h3>";
    $stmt = $pdo->query("DESCRIBE quotation_items");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check actual data
    echo "<h3>2. Quotation Items Data:</h3>";
    $stmt = $pdo->prepare("
        SELECT qi.*, ps.name as product_name, psc.name as category_name 
        FROM quotation_items qi 
        LEFT JOIN products_services ps ON ps.id = qi.product_id 
        LEFT JOIN product_service_categories psc ON psc.id = qi.category_id 
        WHERE qi.quotation_id = ?
    ");
    $stmt->execute([$quotation_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($items)) {
        echo "<p>No items found for quotation ID: {$quotation_id}</p>";
    } else {
        echo "<table border='1'>";
        echo "<tr>";
        foreach (array_keys($items[0]) as $key) {
            echo "<th>{$key}</th>";
        }
        echo "</tr>";
        
        foreach ($items as $item) {
            echo "<tr>";
            foreach ($item as $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check if description and use_dropdown fields exist
    $has_description = false;
    $has_use_dropdown = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'description') $has_description = true;
        if ($col['Field'] === 'use_dropdown') $has_use_dropdown = true;
    }
    
    echo "<h3>3. Field Status:</h3>";
    echo "<p>Description field exists: " . ($has_description ? "✅ YES" : "❌ NO") . "</p>";
    echo "<p>Use_dropdown field exists: " . ($has_use_dropdown ? "✅ YES" : "❌ NO") . "</p>";
    
    if (!$has_description || !$has_use_dropdown) {
        echo "<h3>4. Migration Required:</h3>";
        echo "<p style='color: red;'>⚠️ The database migration has not been run yet!</p>";
        echo "<p>Please run: <code>php run_migration.php</code></p>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>
