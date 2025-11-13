<?php
/**
 * Debug script to check table names and data
 */

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'crm_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Tables</h2>";
    
    // Show all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>All Tables:</h3>";
    foreach ($tables as $table) {
        echo "- {$table}<br>";
    }
    
    // Check products table specifically
    $product_tables = array_filter($tables, function($table) {
        return strpos($table, 'product') !== false;
    });
    
    echo "<h3>Product-related Tables:</h3>";
    foreach ($product_tables as $table) {
        echo "- {$table}<br>";
    }
    
    // Check category tables
    $category_tables = array_filter($tables, function($table) {
        return strpos($table, 'categor') !== false;
    });
    
    echo "<h3>Category-related Tables:</h3>";
    foreach ($category_tables as $table) {
        echo "- {$table}<br>";
    }
    
    // Check actual product data for the items
    echo "<h3>Product Data Check:</h3>";
    
    // Check if products_services table exists and has data
    if (in_array('products_services', $tables)) {
        echo "<h4>products_services table:</h4>";
        $stmt = $pdo->query("SELECT id, name FROM products_services LIMIT 10");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $product) {
            echo "ID: {$product['id']}, Name: {$product['name']}<br>";
        }
    }
    
    // Check if products table exists
    if (in_array('products', $tables)) {
        echo "<h4>products table:</h4>";
        $stmt = $pdo->query("SELECT id, name FROM products LIMIT 10");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $product) {
            echo "ID: {$product['id']}, Name: {$product['name']}<br>";
        }
    }
    
    // Test the actual JOIN query
    echo "<h3>Testing CORRECTED JOIN Query:</h3>";
    $stmt = $pdo->prepare("
        SELECT qi.*, p.name as product_name, psc.name as category_name 
        FROM quotation_items qi 
        LEFT JOIN products p ON p.id = qi.product_id 
        LEFT JOIN product_service_categories psc ON psc.id = qi.category_id 
        WHERE qi.quotation_id = 24
    ");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Product ID</th><th>Category ID</th><th>Product Name</th><th>Category Name</th><th>Use Dropdown</th><th>Description</th></tr>";
    foreach ($items as $item) {
        echo "<tr>";
        echo "<td>{$item['id']}</td>";
        echo "<td>{$item['product_id']}</td>";
        echo "<td>{$item['category_id']}</td>";
        echo "<td>" . ($item['product_name'] ?? 'NULL') . "</td>";
        echo "<td>" . ($item['category_name'] ?? 'NULL') . "</td>";
        echo "<td>{$item['use_dropdown']}</td>";
        echo "<td>" . ($item['description'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>
