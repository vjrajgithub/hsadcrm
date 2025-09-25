<?php
// Debug script to check bank management issues
require_once 'application/config/database.php';

// Create database connection
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h2>Bank Management Debug Report</h2>";

// Check if banks table exists
$result = $mysqli->query("SHOW TABLES LIKE 'banks'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Banks table exists</p>";
    
    // Check table structure
    echo "<h3>Table Structure:</h3>";
    $structure = $mysqli->query("DESCRIBE banks");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check data count
    $count_result = $mysqli->query("SELECT COUNT(*) as count FROM banks");
    $count = $count_result->fetch_assoc()['count'];
    echo "<p>Total records: {$count}</p>";
    
    // Show sample data if exists
    if ($count > 0) {
        echo "<h3>Sample Data:</h3>";
        $data = $mysqli->query("SELECT b.*, c.name as company_name FROM banks b LEFT JOIN companies c ON b.company_id = c.id LIMIT 5");
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Company</th><th>Bank Name</th><th>Branch</th><th>AC No</th><th>IFSC</th></tr>";
        while ($row = $data->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['company_name']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['branch_address']}</td>";
            echo "<td>{$row['ac_no']}</td>";
            echo "<td>{$row['ifsc_code']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} else {
    echo "<p style='color: red;'>✗ Banks table does not exist</p>";
    echo "<p>Creating banks table...</p>";
    
    $create_table = "CREATE TABLE `banks` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `company_id` int(11) NOT NULL,
        `name` varchar(100) NOT NULL,
        `branch_address` text NOT NULL,
        `ac_no` varchar(20) NOT NULL,
        `ifsc_code` varchar(11) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `company_id` (`company_id`),
        CONSTRAINT `banks_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($mysqli->query($create_table)) {
        echo "<p style='color: green;'>✓ Banks table created successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating banks table: " . $mysqli->error . "</p>";
    }
}

// Check if companies table exists
$result = $mysqli->query("SHOW TABLES LIKE 'companies'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Companies table exists</p>";
    $count_result = $mysqli->query("SELECT COUNT(*) as count FROM companies");
    $count = $count_result->fetch_assoc()['count'];
    echo "<p>Total companies: {$count}</p>";
} else {
    echo "<p style='color: red;'>✗ Companies table does not exist - this is required for bank management</p>";
}

// Test bank controller endpoints
echo "<h3>Testing Endpoints:</h3>";
echo "<p><a href='" . base_url('bank') . "' target='_blank'>Bank Index Page</a></p>";
echo "<p><a href='" . base_url('bank/list') . "' target='_blank'>Bank List API</a></p>";
echo "<p><a href='" . base_url('bank/form') . "' target='_blank'>Bank Form</a></p>";

$mysqli->close();
?>
