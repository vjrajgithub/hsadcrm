<?php
// Script to reset passwords for test users
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();
$CI->load->model('User_model');

echo "<h2>Resetting User Passwords</h2>";

// Reset passwords for known test users
$password_updates = [
    'admin@test.com' => 'admin123',
    'viewer@test.com' => 'viewer123',
    'admin@example.com' => 'admin123',
    'raju@gmail.com' => 'admin123',
    'rdg@ggg.jj' => 'viewer123',
    'rajvraj121@gmail.com' => 'super123'
];

// Also update status to ensure users are active
foreach ($password_updates as $email => $new_password) {
    $CI->db->update('users', ['status' => 1], ['email' => $email]);
}

foreach ($password_updates as $email => $new_password) {
    $user = $CI->User_model->get_by_email($email);
    if ($user) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $CI->db->update('users', ['password' => $hashed_password], ['email' => $email]);
        echo "✅ Password updated for {$email} → {$new_password}<br>";
    } else {
        echo "❌ User not found: {$email}<br>";
    }
}

echo "<br><h3>Updated Login Credentials:</h3>";
echo "<table border='1'>";
echo "<tr><th>Email</th><th>Password</th><th>Role</th></tr>";

foreach ($password_updates as $email => $password) {
    $user = $CI->User_model->get_by_email($email);
    if ($user) {
        echo "<tr><td>{$email}</td><td>{$password}</td><td>{$user->role}</td></tr>";
    }
}
echo "</table>";

echo "<br><strong>Now try logging in with these credentials!</strong>";
?>
