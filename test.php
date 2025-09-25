<?php
// Simple PHP test file to check if PHP is working
echo "PHP is working correctly!";
echo "<br>PHP Version: " . phpversion();
echo "<br>Current Directory: " . __DIR__;
echo "<br>File exists check:";
echo "<br>- index.php: " . (file_exists('index.php') ? 'YES' : 'NO');
echo "<br>- application folder: " . (is_dir('application') ? 'YES' : 'NO');
echo "<br>- system folder: " . (is_dir('system') ? 'YES' : 'NO');
?>
