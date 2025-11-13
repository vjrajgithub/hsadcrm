<?php
// Simple database check for quotation 39
$conn = new mysqli('localhost', 'root', '', 'crm_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h1>Quotation 39 - Data Check</h1>";
echo "<style>body{font-family:Arial;padding:20px;} table{border-collapse:collapse;width:100%;margin:20px 0;} th,td{border:1px solid #ddd;padding:12px;text-align:left;} th{background:#007bff;color:white;} .null{color:red;font-weight:bold;} .exists{color:green;font-weight:bold;}</style>";

// Check quotation
echo "<h2>Quotation Master Data</h2>";
$q = $conn->query("SELECT * FROM quotations WHERE id = 39")->fetch_assoc();
if ($q) {
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td>ID</td><td>{$q['id']}</td></tr>";
    echo "<tr><td>Company ID</td><td>{$q['company_id']}</td></tr>";
    echo "<tr><td>Client ID</td><td>{$q['client_id']}</td></tr>";
    echo "<tr><td>Created At</td><td>{$q['created_at']}</td></tr>";
    echo "<tr><td>Company Snapshot</td><td>" . (!empty($q['company_snapshot']) ? '<span class="exists">EXISTS</span>' : '<span class="null">NULL</span>') . "</td></tr>";
    echo "<tr><td>Client Snapshot</td><td>" . (!empty($q['client_snapshot']) ? '<span class="exists">EXISTS</span>' : '<span class="null">NULL</span>') . "</td></tr>";
    echo "</table>";
} else {
    echo "<p>Quotation 39 not found!</p>";
    exit;
}

// Check items
echo "<h2>Quotation Items</h2>";
$items = $conn->query("SELECT * FROM quotation_items WHERE quotation_id = 39");
echo "<table>";
echo "<tr><th>#</th><th>Product ID</th><th>Category ID</th><th>Qty</th><th>Rate</th><th>Product Snapshot</th><th>Category Snapshot</th></tr>";
$i = 1;
while ($item = $items->fetch_assoc()) {
    echo "<tr>";
    echo "<td>$i</td>";
    echo "<td>{$item['product_id']}</td>";
    echo "<td>{$item['category_id']}</td>";
    echo "<td>{$item['qty']}</td>";
    echo "<td>{$item['rate']}</td>";
    echo "<td>" . (!empty($item['product_snapshot']) ? '<span class="exists">EXISTS</span>' : '<span class="null">NULL</span>') . "</td>";
    echo "<td>" . (!empty($item['category_snapshot']) ? '<span class="exists">EXISTS</span>' : '<span class="null">NULL</span>') . "</td>";
    echo "</tr>";
    $i++;
}
echo "</table>";

// Check if products/categories exist
echo "<h2>Master Data Check</h2>";
$items = $conn->query("SELECT * FROM quotation_items WHERE quotation_id = 39");
echo "<table>";
echo "<tr><th>#</th><th>Product Status</th><th>Product Name</th><th>Category Status</th><th>Category Name</th></tr>";
$i = 1;
while ($item = $items->fetch_assoc()) {
    $product = $conn->query("SELECT * FROM products_services WHERE id = {$item['product_id']}")->fetch_assoc();
    $category = $conn->query("SELECT * FROM product_service_categories WHERE id = {$item['category_id']}")->fetch_assoc();
    
    echo "<tr>";
    echo "<td>$i</td>";
    if ($product) {
        echo "<td><span class='exists'>✅ EXISTS</span></td>";
        echo "<td>{$product['name']}</td>";
    } else {
        echo "<td><span class='null'>❌ DELETED</span></td>";
        echo "<td>-</td>";
    }
    
    if ($category) {
        echo "<td><span class='exists'>✅ EXISTS</span></td>";
        echo "<td>{$category['name']}</td>";
    } else {
        echo "<td><span class='null'>❌ DELETED</span></td>";
        echo "<td>-</td>";
    }
    echo "</tr>";
    $i++;
}
echo "</table>";

// Test JOIN query
echo "<h2>JOIN Query Result (What Model Returns)</h2>";
$result = $conn->query("
    SELECT 
        qi.*,
        p.name as product_name,
        psc.name as category_name
    FROM quotation_items qi
    LEFT JOIN products_services p ON p.id = qi.product_id
    LEFT JOIN product_service_categories psc ON psc.id = qi.category_id
    WHERE qi.quotation_id = 39
");

echo "<table>";
echo "<tr><th>#</th><th>Product Name (from JOIN)</th><th>Category Name (from JOIN)</th></tr>";
$i = 1;
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>$i</td>";
    echo "<td>" . ($row['product_name'] ?? '<span class="null">NULL</span>') . "</td>";
    echo "<td>" . ($row['category_name'] ?? '<span class="null">NULL</span>') . "</td>";
    echo "</tr>";
    $i++;
}
echo "</table>";

echo "<h2>🔍 Diagnosis</h2>";
if (empty($q['company_snapshot'])) {
    echo "<p style='background:#fff3cd;padding:15px;border-radius:5px;'>";
    echo "<strong>⚠️ This quotation was created BEFORE the migration!</strong><br>";
    echo "It doesn't have snapshots, so if products/categories are deleted, they will show as NULL.<br><br>";
    echo "<strong>Solutions:</strong><br>";
    echo "1. Re-create this quotation (it will capture snapshots)<br>";
    echo "2. Or manually update it to capture snapshots<br>";
    echo "</p>";
} else {
    echo "<p style='background:#d4edda;padding:15px;border-radius:5px;'>";
    echo "<strong>✅ This quotation has snapshots!</strong><br>";
    echo "If products are deleted, snapshot data should be used.";
    echo "</p>";
}

$conn->close();
?>
