<?php
/**
 * Debug script to check what form data is being sent
 * Place this temporarily in quotation controller store method to debug
 */

// Add this at the beginning of store() method after $items = $this->_prepare_items();
echo "<pre>";
echo "POST Data:\n";
print_r($_POST);
echo "\nItems Data:\n";
print_r($items);
echo "</pre>";
exit;
?>
