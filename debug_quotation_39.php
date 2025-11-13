<?php
/**
 * Debug Quotation ID 39
 * Check what data is being returned
 */

// Bootstrap CodeIgniter properly
$_SERVER['REQUEST_URI'] = '/debug_quotation_39.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

require_once __DIR__ . '/index.php';

$CI =& get_instance();
$CI->load->model('Quotation_model');
$CI->load->database();

$quotation_id = 39;

echo "<!DOCTYPE html><html><head><title>Debug Quotation 39</title>";
echo "<style>
body { font-family: monospace; padding: 20px; background: #f5f5f5; }
.box { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
.error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; }
.success { color: #155724; background: #d4edda; padding: 10px; border-radius: 4px; }
.warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; }
</style></head><body>";

echo "<h1>🔍 Debug Quotation ID: $quotation_id</h1>";

// Test 1: Direct database query
echo "<div class='box'>";
echo "<h2>1. Direct Database Query (Raw Data)</h2>";
$raw_items = $CI->db->query("SELECT * FROM quotation_items WHERE quotation_id = $quotation_id")->result();
echo "<strong>Number of items:</strong> " . count($raw_items) . "<br><br>";
foreach ($raw_items as $idx => $item) {
    echo "<strong>Item #" . ($idx + 1) . ":</strong><br>";
    echo "<pre>";
    print_r($item);
    echo "</pre>";
}
echo "</div>";

// Test 2: Model method - getQuotationItems
echo "<div class='box'>";
echo "<h2>2. Model Method: getQuotationItems()</h2>";
$model_items = $CI->Quotation_model->getQuotationItems($quotation_id);
echo "<strong>Number of items:</strong> " . count($model_items) . "<br><br>";
foreach ($model_items as $idx => $item) {
    echo "<strong>Item #" . ($idx + 1) . ":</strong><br>";
    echo "<strong>Product Name:</strong> " . ($item->product_name ?? '<span class="error">NULL</span>') . "<br>";
    echo "<strong>Category Name:</strong> " . ($item->category_name ?? '<span class="error">NULL</span>') . "<br>";
    echo "<strong>Product ID:</strong> " . ($item->product_id ?? 'NULL') . "<br>";
    echo "<strong>Category ID:</strong> " . ($item->category_id ?? 'NULL') . "<br>";
    echo "<strong>Has Product Snapshot:</strong> " . (!empty($item->product_snapshot) ? 'YES' : 'NO') . "<br>";
    echo "<strong>Has Category Snapshot:</strong> " . (!empty($item->category_snapshot) ? 'YES' : 'NO') . "<br>";
    
    if (!empty($item->product_snapshot)) {
        $prod_snap = json_decode($item->product_snapshot, true);
        echo "<strong>Product Snapshot Data:</strong><br>";
        echo "<pre>" . print_r($prod_snap, true) . "</pre>";
    }
    
    if (!empty($item->category_snapshot)) {
        $cat_snap = json_decode($item->category_snapshot, true);
        echo "<strong>Category Snapshot Data:</strong><br>";
        echo "<pre>" . print_r($cat_snap, true) . "</pre>";
    }
    
    echo "<hr>";
}
echo "</div>";

// Test 3: Check if products exist
echo "<div class='box'>";
echo "<h2>3. Check Master Tables</h2>";
foreach ($raw_items as $idx => $item) {
    echo "<strong>Item #" . ($idx + 1) . ":</strong><br>";
    
    // Check product
    $product = $CI->db->get_where('products_services', ['id' => $item->product_id])->row();
    if ($product) {
        echo "<div class='success'>✅ Product exists: {$product->name}</div>";
    } else {
        echo "<div class='error'>❌ Product ID {$item->product_id} NOT FOUND (deleted)</div>";
    }
    
    // Check category
    $category = $CI->db->get_where('product_service_categories', ['id' => $item->category_id])->row();
    if ($category) {
        echo "<div class='success'>✅ Category exists: {$category->name}</div>";
    } else {
        echo "<div class='error'>❌ Category ID {$item->category_id} NOT FOUND (deleted)</div>";
    }
    
    echo "<br>";
}
echo "</div>";

// Test 4: Test JOIN query
echo "<div class='box'>";
echo "<h2>4. Test JOIN Query (What Model Does)</h2>";
$join_result = $CI->db->query("
    SELECT 
        qi.*,
        p.name as product_name,
        psc.name as category_name
    FROM quotation_items qi
    LEFT JOIN products_services p ON p.id = qi.product_id
    LEFT JOIN product_service_categories psc ON psc.id = qi.category_id
    WHERE qi.quotation_id = $quotation_id
")->result();

foreach ($join_result as $idx => $item) {
    echo "<strong>Item #" . ($idx + 1) . ":</strong><br>";
    echo "<strong>Product Name from JOIN:</strong> " . ($item->product_name ?? '<span class="error">NULL</span>') . "<br>";
    echo "<strong>Category Name from JOIN:</strong> " . ($item->category_name ?? '<span class="error">NULL</span>') . "<br>";
    echo "<br>";
}
echo "</div>";

// Test 5: Check quotation snapshots
echo "<div class='box'>";
echo "<h2>5. Quotation Master Data Snapshots</h2>";
$quotation = $CI->db->get_where('quotations', ['id' => $quotation_id])->row();
echo "<strong>Has Company Snapshot:</strong> " . (!empty($quotation->company_snapshot) ? 'YES' : 'NO') . "<br>";
echo "<strong>Has Client Snapshot:</strong> " . (!empty($quotation->client_snapshot) ? 'YES' : 'NO') . "<br>";
echo "<strong>Has Bank Snapshot:</strong> " . (!empty($quotation->bank_snapshot) ? 'YES' : 'NO') . "<br>";
echo "</div>";

echo "</body></html>";
?>
