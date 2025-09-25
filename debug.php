<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>CRM Debug Information</h2>";

// Test 1: Basic PHP
echo "<h3>1. PHP Status</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";

// Test 2: File existence
echo "<h3>2. File Structure</h3>";
echo "index.php exists: " . (file_exists('index.php') ? 'YES' : 'NO') . "<br>";
echo "application/ exists: " . (is_dir('application') ? 'YES' : 'NO') . "<br>";
echo "system/ exists: " . (is_dir('system') ? 'YES' : 'NO') . "<br>";
echo "vendor/ exists: " . (is_dir('vendor') ? 'YES' : 'NO') . "<br>";

// Test 3: Permissions
echo "<h3>3. Directory Permissions</h3>";
echo "application/cache writable: " . (is_writable('application/cache') ? 'YES' : 'NO') . "<br>";
echo "application/logs writable: " . (is_writable('application/logs') ? 'YES' : 'NO') . "<br>";

// Test 4: Try to load CodeIgniter constants
echo "<h3>4. CodeIgniter Test</h3>";
try {
    define('BASEPATH', 'system/');
    define('APPPATH', 'application/');
    define('FCPATH', __DIR__ . '/');
    
    if (file_exists('application/config/constants.php')) {
        include_once('application/config/constants.php');
        echo "Constants loaded: YES<br>";
    } else {
        echo "Constants file missing: NO<br>";
    }
    
    if (file_exists('application/config/config.php')) {
        include_once('application/config/config.php');
        echo "Config loaded: YES<br>";
    } else {
        echo "Config file missing: NO<br>";
    }
    
} catch (Exception $e) {
    echo "Error loading CI files: " . $e->getMessage() . "<br>";
}

echo "<h3>5. Session Directory</h3>";
$session_path = 'application/cache/sessions';
echo "Session path exists: " . (is_dir($session_path) ? 'YES' : 'NO') . "<br>";
echo "Session path writable: " . (is_writable($session_path) ? 'YES' : 'NO') . "<br>";

echo "<h3>6. Database Test</h3>";
if (extension_loaded('mysqli')) {
    echo "MySQLi extension: LOADED<br>";
    $connection = @mysqli_connect('localhost', 'root', '', 'crm_db');
    if ($connection) {
        echo "Database connection: SUCCESS<br>";
        mysqli_close($connection);
    } else {
        echo "Database connection: FAILED - " . mysqli_connect_error() . "<br>";
    }
} else {
    echo "MySQLi extension: NOT LOADED<br>";
}
?>
