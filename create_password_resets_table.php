<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Database Setup for Password Reset System
 * Run this once to create the password_resets table
 */

// Load CodeIgniter
require_once BASEPATH.'database/DB.php';
$db = &get_instance()->db;

echo "<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Table Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .info { color: #17a2b8; }
    </style>
</head>
<body>
    <h2>Password Reset System Setup</h2>";

try {
    // Create password_resets table
    $sql = "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(64) NOT NULL UNIQUE,
        expires_at DATETIME NOT NULL,
        created_at DATETIME NOT NULL,
        INDEX idx_token (token),
        INDEX idx_email (email),
        INDEX idx_expires (expires_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if ($db->query($sql)) {
        echo "<p class='success'>✅ Password resets table created successfully</p>";
    } else {
        echo "<p class='error'>❌ Error creating table: " . $db->error()['message'] . "</p>";
    }

    // Clean up expired tokens (older than 24 hours)
    $cleanup_sql = "DELETE FROM password_resets WHERE expires_at < NOW()";
    if ($db->query($cleanup_sql)) {
        echo "<p class='success'>✅ Expired tokens cleaned up</p>";
    } else {
        echo "<p class='error'>❌ Error cleaning up tokens: " . $db->error()['message'] . "</p>";
    }

    echo "<p class='info'><strong>Password reset system is ready!</strong></p>";
    echo "<p><a href='" . base_url('login') . "'>← Back to Login</a></p>";

} catch (Exception $e) {
    echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "</body></html>";
?>
