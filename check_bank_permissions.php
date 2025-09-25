<?php
// Check and add bank permissions if missing
require_once 'application/config/database.php';

// Create database connection
$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "<h2>Bank Permissions Check</h2>";

// Check if permissions table exists
$result = $mysqli->query("SHOW TABLES LIKE 'permissions'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ Permissions table exists</p>";
    
    // Check if manage_banks permission exists
    $check_permission = $mysqli->query("SELECT * FROM permissions WHERE name = 'manage_banks'");
    if ($check_permission->num_rows > 0) {
        echo "<p style='color: green;'>✓ manage_banks permission exists</p>";
    } else {
        echo "<p style='color: orange;'>Adding manage_banks permission...</p>";
        $insert_permission = "INSERT INTO permissions (name, description, module) VALUES ('manage_banks', 'Manage bank details', 'banks')";
        if ($mysqli->query($insert_permission)) {
            echo "<p style='color: green;'>✓ manage_banks permission added</p>";
        } else {
            echo "<p style='color: red;'>✗ Error adding permission: " . $mysqli->error . "</p>";
        }
    }
    
    // Check role permissions for Super Admin
    $check_role_permission = $mysqli->query("
        SELECT rp.*, r.name as role_name, p.name as permission_name 
        FROM role_permissions rp 
        JOIN roles r ON rp.role_id = r.id 
        JOIN permissions p ON rp.permission_id = p.id 
        WHERE p.name = 'manage_banks'
    ");
    
    if ($check_role_permission->num_rows > 0) {
        echo "<p style='color: green;'>✓ manage_banks permission assigned to roles:</p>";
        echo "<ul>";
        while ($row = $check_role_permission->fetch_assoc()) {
            echo "<li>{$row['role_name']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>Assigning manage_banks permission to Super Admin...</p>";
        
        // Get permission ID
        $permission_result = $mysqli->query("SELECT id FROM permissions WHERE name = 'manage_banks'");
        $permission_id = $permission_result->fetch_assoc()['id'];
        
        // Get Super Admin role ID
        $role_result = $mysqli->query("SELECT id FROM roles WHERE name = 'Super Admin'");
        if ($role_result->num_rows > 0) {
            $role_id = $role_result->fetch_assoc()['id'];
            
            $insert_role_permission = "INSERT INTO role_permissions (role_id, permission_id) VALUES ($role_id, $permission_id)";
            if ($mysqli->query($insert_role_permission)) {
                echo "<p style='color: green;'>✓ manage_banks permission assigned to Super Admin</p>";
            } else {
                echo "<p style='color: red;'>✗ Error assigning permission: " . $mysqli->error . "</p>";
            }
        }
        
        // Also assign to Admin role if exists
        $admin_role_result = $mysqli->query("SELECT id FROM roles WHERE name = 'Admin'");
        if ($admin_role_result->num_rows > 0) {
            $admin_role_id = $admin_role_result->fetch_assoc()['id'];
            
            $insert_admin_permission = "INSERT INTO role_permissions (role_id, permission_id) VALUES ($admin_role_id, $permission_id)";
            if ($mysqli->query($insert_admin_permission)) {
                echo "<p style='color: green;'>✓ manage_banks permission assigned to Admin</p>";
            } else {
                echo "<p style='color: red;'>✗ Error assigning permission to Admin: " . $mysqli->error . "</p>";
            }
        }
    }
    
} else {
    echo "<p style='color: red;'>✗ Permissions table does not exist</p>";
}

$mysqli->close();

echo "<p><a href='" . base_url('bank') . "'>Test Bank Management</a></p>";
?>
