<?php
// Test script to debug quotation view issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include CodeIgniter bootstrap
require_once 'index.php';

// Get CI instance
$CI =& get_instance();

// Load required models
$CI->load->model('Quotation_model');

// Test database connection
echo "Testing database connection...\n";
try {
    $quotations = $CI->Quotation_model->get_all_with_details();
    echo "Database connection: OK\n";
    echo "Total quotations found: " . count($quotations) . "\n";
    
    if (!empty($quotations)) {
        $first_quotation = $quotations[0];
        echo "First quotation ID: " . $first_quotation->id . "\n";
        
        // Test get_with_details method
        $quotation_details = $CI->Quotation_model->get_with_details($first_quotation->id);
        if ($quotation_details) {
            echo "Quotation details retrieved: OK\n";
            echo "Company name: " . ($quotation_details->company_name ?? 'N/A') . "\n";
            echo "Client name: " . ($quotation_details->client_name ?? 'N/A') . "\n";
        } else {
            echo "ERROR: Could not retrieve quotation details\n";
        }
        
        // Test get_items method
        $items = $CI->Quotation_model->get_items($first_quotation->id);
        echo "Items found: " . count($items) . "\n";
        
        if (!empty($items)) {
            foreach ($items as $item) {
                echo "Item: " . ($item->product_name ?? 'N/A') . " - Qty: " . ($item->qty ?? 0) . "\n";
            }
        }
    } else {
        echo "No quotations found in database\n";
    }
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
?>
