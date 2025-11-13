<?php
$conn = new mysqli('localhost', 'root', '', 'crm_db');
if ($conn->connect_error) die("Connection failed");

echo "<h1>✅ Verification: Snapshot System Fix</h1>";
echo "<style>
body{font-family:Arial;padding:20px;background:#f5f5f5;}
.box{background:white;padding:20px;margin:20px 0;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}
h2{color:#333;border-bottom:2px solid #007bff;padding-bottom:10px;}
table{border-collapse:collapse;width:100%;margin:15px 0;}
th,td{border:1px solid #ddd;padding:12px;text-align:left;}
th{background:#007bff;color:white;}
.success{background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}
.error{background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin:10px 0;}
.warning{background:#fff3cd;color:#856404;padding:15px;border-radius:5px;margin:10px 0;}
.info{background:#d1ecf1;color:#0c5460;padding:15px;border-radius:5px;margin:10px 0;}
code{background:#f8f9fa;padding:2px 6px;border-radius:3px;font-family:monospace;}
</style>";

echo "<div class='box'>";
echo "<h2>Test: Create New Quotation</h2>";
echo "<div class='info'>";
echo "<strong>📋 Instructions:</strong><br>";
echo "1. Go to: <a href='http://localhost/crm/quotation/create' target='_blank'>Create New Quotation</a><br>";
echo "2. Fill in all details and add items<br>";
echo "3. Save the quotation<br>";
echo "4. Note the quotation ID<br>";
echo "5. Come back here and refresh to check<br>";
echo "</div>";
echo "</div>";

// Check latest quotation
echo "<div class='box'>";
echo "<h2>Latest Quotations Status</h2>";
$result = $conn->query("SELECT id, created_at, 
    CASE WHEN company_snapshot IS NOT NULL THEN '✅' ELSE '❌' END as has_company,
    CASE WHEN client_snapshot IS NOT NULL THEN '✅' ELSE '❌' END as has_client,
    CASE WHEN bank_snapshot IS NOT NULL THEN '✅' ELSE '❌' END as has_bank,
    CASE WHEN mode_snapshot IS NOT NULL THEN '✅' ELSE '❌' END as has_mode
FROM quotations ORDER BY id DESC LIMIT 5");

echo "<table>";
echo "<tr><th>ID</th><th>Created At</th><th>Company</th><th>Client</th><th>Bank</th><th>Mode</th><th>Status</th></tr>";
while ($row = $result->fetch_assoc()) {
    $all_good = ($row['has_company'] == '✅' && $row['has_client'] == '✅' && $row['has_bank'] == '✅' && $row['has_mode'] == '✅');
    $status = $all_good ? '<strong style="color:green;">WORKING</strong>' : '<strong style="color:red;">NO SNAPSHOTS</strong>';
    echo "<tr>";
    echo "<td><strong>{$row['id']}</strong></td>";
    echo "<td>{$row['created_at']}</td>";
    echo "<td>{$row['has_company']}</td>";
    echo "<td>{$row['has_client']}</td>";
    echo "<td>{$row['has_bank']}</td>";
    echo "<td>{$row['has_mode']}</td>";
    echo "<td>$status</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// Check quotation items
echo "<div class='box'>";
echo "<h2>Latest Quotation Items Status</h2>";
$result = $conn->query("SELECT qi.id, qi.quotation_id, qi.product_id, qi.category_id,
    CASE WHEN qi.product_snapshot IS NOT NULL THEN '✅' ELSE '❌' END as has_product,
    CASE WHEN qi.category_snapshot IS NOT NULL THEN '✅' ELSE '❌' END as has_category,
    p.name as product_name,
    c.name as category_name
FROM quotation_items qi
LEFT JOIN products_services p ON p.id = qi.product_id
LEFT JOIN product_service_categories c ON c.id = qi.category_id
WHERE qi.quotation_id IN (SELECT id FROM quotations ORDER BY id DESC LIMIT 3)
ORDER BY qi.quotation_id DESC, qi.id");

echo "<table>";
echo "<tr><th>Item ID</th><th>Quotation ID</th><th>Product Name</th><th>Category Name</th><th>Product Snap</th><th>Category Snap</th><th>Status</th></tr>";
while ($row = $result->fetch_assoc()) {
    $all_good = ($row['has_product'] == '✅' && $row['has_category'] == '✅');
    $status = $all_good ? '<strong style="color:green;">WORKING</strong>' : '<strong style="color:red;">NO SNAPSHOTS</strong>';
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td><strong>{$row['quotation_id']}</strong></td>";
    echo "<td>" . ($row['product_name'] ?? '<em>NULL</em>') . "</td>";
    echo "<td>" . ($row['category_name'] ?? '<em>NULL</em>') . "</td>";
    echo "<td>{$row['has_product']}</td>";
    echo "<td>{$row['has_category']}</td>";
    echo "<td>$status</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// Summary
echo "<div class='box'>";
echo "<h2>📊 Summary</h2>";
$latest = $conn->query("SELECT * FROM quotations ORDER BY id DESC LIMIT 1")->fetch_assoc();
if ($latest && !empty($latest['company_snapshot'])) {
    echo "<div class='success'>";
    echo "<h3>✅ FIX IS WORKING!</h3>";
    echo "<p>Latest quotation (ID: {$latest['id']}) has snapshots captured.</p>";
    echo "<p><strong>Next steps:</strong></p>";
    echo "<ol>";
    echo "<li>View PDF of quotation {$latest['id']}</li>";
    echo "<li>Delete a product used in that quotation</li>";
    echo "<li>View PDF again - product name should still show!</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<h3>⚠️ Create a NEW Quotation to Test</h3>";
    echo "<p>The fix is applied, but you need to create a NEW quotation to see it working.</p>";
    echo "<p><a href='http://localhost/crm/quotation/create' target='_blank'><strong>→ Create New Quotation</strong></a></p>";
    echo "</div>";
}
echo "</div>";

$conn->close();
?>
