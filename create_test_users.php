<?php
// Script to create test users for Admin and Viewer roles
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();
$CI->load->model('User_model');

echo "<h2>Creating Test Users</h2>";

// Create Admin user
$admin_data = [
    'name' => 'Test Admin',
    'email' => 'admin@test.com',
    'password' => password_hash('admin123', PASSWORD_DEFAULT),
    'role' => 'Admin',
    'status' => 1
];

// Create Viewer user
$viewer_data = [
    'name' => 'Test Viewer',
    'email' => 'viewer@test.com',
    'password' => password_hash('viewer123', PASSWORD_DEFAULT),
    'role' => 'Viewer',
    'status' => 1
];

// Check if users already exist
$existing_admin = $CI->User_model->get_by_email('admin@test.com');
$existing_viewer = $CI->User_model->get_by_email('viewer@test.com');

if (!$existing_admin) {
    $CI->db->insert('users', $admin_data);
    echo "✅ Admin user created: admin@test.com / admin123<br>";
} else {
    echo "⚠️ Admin user already exists<br>";
}

if (!$existing_viewer) {
    $CI->db->insert('users', $viewer_data);
    echo "✅ Viewer user created: viewer@test.com / viewer123<br>";
} else {
    echo "⚠️ Viewer user already exists<br>";
}

echo "<br><h3>Current Users:</h3>";
$users = $CI->User_model->get_all();
foreach ($users as $user) {
    echo "- {$user->name} ({$user->email}) - Role: {$user->role}, Status: {$user->status}<br>";
}

echo "<br><h3>Test Login Credentials:</h3>";
echo "Admin: admin@test.com / admin123<br>";
echo "Viewer: viewer@test.com / viewer123<br>";
?>
