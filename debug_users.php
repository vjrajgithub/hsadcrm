<?php
// Debug script to check user data
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();
$CI->load->model('User_model');

echo "<h2>User Debug Information</h2>";

// Get all users
$users = $CI->User_model->get_all();

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Password Hash</th></tr>";

foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user->id . "</td>";
    echo "<td>" . $user->name . "</td>";
    echo "<td>" . $user->email . "</td>";
    echo "<td>" . $user->role . "</td>";
    echo "<td>" . $user->status . "</td>";
    echo "<td>" . substr($user->password, 0, 20) . "...</td>";
    echo "</tr>";
}

echo "</table>";

// Test password verification for all users
foreach ($users as $user) {
    echo "<h3>Password Test for " . $user->email . " (" . $user->role . ")</h3>";
    
    // Test common passwords
    $test_passwords = ['admin123', 'viewer123', 'password123', 'admin', '123456', 'password', 'test123', 'user123'];
    
    foreach ($test_passwords as $test_pass) {
        $match = password_verify($test_pass, $user->password);
        if ($match) {
            echo "<strong style='color: green;'>✅ Password '{$test_pass}': MATCH</strong><br>";
        } else {
            echo "❌ Password '{$test_pass}': NO MATCH<br>";
        }
    }
    echo "<hr>";
}
?>
