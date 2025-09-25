<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP Test Started\n";

// Test basic PHP syntax
$test = "Hello World";
echo "Basic PHP: " . $test . "\n";

// Test file existence
$view_file = 'd:\wamp64\www\crm\application\views\quotation\view.php';
if (file_exists($view_file)) {
    echo "View file exists: YES\n";
    echo "File size: " . filesize($view_file) . " bytes\n";
    
    // Check if file is readable
    if (is_readable($view_file)) {
        echo "File is readable: YES\n";
        
        // Try to read first few lines
        $handle = fopen($view_file, 'r');
        if ($handle) {
            $line_count = 0;
            while (($line = fgets($handle)) !== false && $line_count < 5) {
                echo "Line " . ($line_count + 1) . ": " . trim($line) . "\n";
                $line_count++;
            }
            fclose($handle);
        }
    } else {
        echo "File is readable: NO\n";
    }
} else {
    echo "View file exists: NO\n";
}

echo "PHP Test Completed\n";
?>
