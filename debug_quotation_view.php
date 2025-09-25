<?php
// Debug script for quotation view issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate quotation data for testing
$quotation = (object) [
    'id' => 1,
    'company_name' => 'Test Company',
    'company_address' => 'Test Address',
    'company_state' => 'Test State',
    'company_state_code' => '09',
    'company_gstin' => 'TEST123456789',
    'company_cin' => 'TEST123',
    'company_pan' => 'TESTPAN123',
    'client_name' => 'Test Client',
    'client_address' => 'Client Address',
    'client_state' => 'Client State',
    'client_state_code' => '09',
    'client_gstin' => 'CLIENT123',
    'client_pan' => 'CLIENTPAN',
    'client_email' => 'client@test.com',
    'created_at' => date('Y-m-d H:i:s'),
    'job_no' => 'JOB001',
    'mode_name' => 'Cash',
    'department' => 'IT',
    'other_text' => 'Other info',
    'contact_person' => 'John Doe',
    'state' => 'Test State',
    'hsn_sac' => '998314',
    'gst_type' => 'CGST+SGST',
    'gst_amount' => 100.00,
    'terms' => 'Test terms',
    'notes' => 'Test notes'
];

$items = [
    (object) [
        'product_name' => 'Test Product 1',
        'qty' => 2,
        'rate' => 500.00,
        'discount' => 0
    ],
    (object) [
        'product_name' => 'Test Product 2',
        'qty' => 1,
        'rate' => 1000.00,
        'discount' => 10
    ]
];

// Mock base_url function
if (!function_exists('base_url')) {
    function base_url($uri = '') {
        return 'http://localhost/crm/' . $uri;
    }
}

// Mock site_url function
if (!function_exists('site_url')) {
    function site_url($uri = '') {
        return 'http://localhost/crm/index.php/' . $uri;
    }
}

// Test the view file
ob_start();
try {
    include 'd:\wamp64\www\crm\application\views\quotation\view.php';
    $output = ob_get_contents();
    echo "View file loaded successfully!\n";
    echo "Output length: " . strlen($output) . " characters\n";
} catch (Exception $e) {
    echo "Error loading view file: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "File: " . $e->getFile() . "\n";
} catch (ParseError $e) {
    echo "Parse error in view file: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} catch (Error $e) {
    echo "Fatal error in view file: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
ob_end_clean();
?>
