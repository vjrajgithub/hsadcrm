<?php
// Test bank functionality
define('BASEPATH', '');
require_once 'application/config/database.php';

// Create database connection
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h2>Bank Functionality Test</h2>";

// Check if tables exist
$tables_to_check = ['banks', 'companies'];
foreach ($tables_to_check as $table) {
    $result = $mysqli->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ $table table exists</p>";
    } else {
        echo "<p style='color: red;'>✗ $table table missing</p>";
    }
}

// Create banks table if it doesn't exist
$result = $mysqli->query("SHOW TABLES LIKE 'banks'");
if ($result->num_rows == 0) {
    echo "<p>Creating banks table...</p>";
    
    $create_banks = "CREATE TABLE `banks` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `company_id` int(11) NOT NULL,
        `name` varchar(100) NOT NULL,
        `branch_address` text NOT NULL,
        `ac_no` varchar(20) NOT NULL,
        `ifsc_code` varchar(11) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `company_id` (`company_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($mysqli->query($create_banks)) {
        echo "<p style='color: green;'>✓ Banks table created</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating banks table: " . $mysqli->error . "</p>";
    }
}

// Check if there are companies to link banks to
$companies_result = $mysqli->query("SELECT COUNT(*) as count FROM companies");
if ($companies_result) {
    $companies_count = $companies_result->fetch_assoc()['count'];
    echo "<p>Companies available: $companies_count</p>";
    
    if ($companies_count == 0) {
        echo "<p style='color: orange;'>Adding sample company...</p>";
        $insert_company = "INSERT INTO companies (name, address, phone, email) VALUES ('Sample Company', '123 Main St', '1234567890', 'test@company.com')";
        if ($mysqli->query($insert_company)) {
            echo "<p style='color: green;'>✓ Sample company added</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding company: " . $mysqli->error . "</p>";
        }
    }
}

// Test bank operations
echo "<h3>Testing Bank Operations:</h3>";

// Get companies for dropdown
$companies = $mysqli->query("SELECT * FROM companies LIMIT 5");
echo "<h4>Available Companies:</h4>";
echo "<ul>";
while ($company = $companies->fetch_assoc()) {
    echo "<li>ID: {$company['id']} - {$company['name']}</li>";
}
echo "</ul>";

// Test bank list
$banks = $mysqli->query("SELECT b.*, c.name as company_name FROM banks b LEFT JOIN companies c ON b.company_id = c.id");
echo "<h4>Current Banks:</h4>";
if ($banks->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Company</th><th>Bank Name</th><th>Branch</th><th>AC No</th><th>IFSC</th></tr>";
    while ($bank = $banks->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$bank['id']}</td>";
        echo "<td>{$bank['company_name']}</td>";
        echo "<td>{$bank['name']}</td>";
        echo "<td>{$bank['branch_address']}</td>";
        echo "<td>{$bank['ac_no']}</td>";
        echo "<td>{$bank['ifsc_code']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No banks found. Adding sample bank...</p>";
    
    // Get first company ID
    $first_company = $mysqli->query("SELECT id FROM companies LIMIT 1");
    if ($first_company->num_rows > 0) {
        $company_id = $first_company->fetch_assoc()['id'];
        
        $insert_bank = "INSERT INTO banks (company_id, name, branch_address, ac_no, ifsc_code) 
                       VALUES ($company_id, 'State Bank of India', 'Main Branch, City Center', '1234567890123456', 'SBIN0001234')";
        
        if ($mysqli->query($insert_bank)) {
            echo "<p style='color: green;'>✓ Sample bank added</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding bank: " . $mysqli->error . "</p>";
        }
    }
}

echo "<hr>";
echo "<h3>Test Links:</h3>";
echo "<p><a href='bank' target='_blank'>Bank Management Page</a></p>";
echo "<p><a href='bank/list' target='_blank'>Bank List API (JSON)</a></p>";
echo "<p><a href='bank/form' target='_blank'>Bank Form</a></p>";

$mysqli->close();
?>
