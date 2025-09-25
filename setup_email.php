<?php
/**
 * Email Settings Setup Script for HSAD CRM
 * This script will configure the email settings with HSAD India credentials
 */

// Include CodeIgniter bootstrap
require_once 'index.php';

// Get CI instance
$CI =& get_instance();
$CI->load->model('Settings_model');
$CI->load->helper('settings');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Email Settings Setup - HSAD CRM</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: #dc3545; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        .info { color: #17a2b8; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px 0 0; }
        .settings-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .settings-table th, .settings-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .settings-table th { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class='container'>
        <h2>📧 Email Settings Setup - HSAD CRM</h2>";

try {
    // Email settings to update
    $email_settings = [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => '587',
        'smtp_username' => 'billing@hsadindia.com',
        'smtp_password' => 'Sdla@8851',
        'smtp_encryption' => 'tls',
        'from_email' => 'billing@hsadindia.com',
        'from_name' => 'HSAD India',
        'reply_to_email' => 'billing@hsadindia.com'
    ];

    echo "<div class='info'>🔄 Updating email settings with HSAD India credentials...</div>";

    $updated_count = 0;
    $created_count = 0;

    foreach ($email_settings as $key => $value) {
        // Check if setting exists
        $existing = $CI->db->get_where('system_settings', ['setting_key' => $key])->row();
        
        if ($existing) {
            // Update existing setting
            $CI->Settings_model->update_by_key($key, $value);
            $updated_count++;
            echo "<div class='success'>✅ Updated: {$key} = {$value}</div>";
        } else {
            // Create new setting
            $setting_data = [
                'category' => 'Email',
                'setting_key' => $key,
                'setting_value' => $value,
                'description' => ucfirst(str_replace('_', ' ', $key)),
                'input_type' => ($key === 'smtp_password') ? 'password' : (($key === 'smtp_port') ? 'number' : 'text'),
                'sort_order' => 999
            ];
            $CI->Settings_model->create($setting_data);
            $created_count++;
            echo "<div class='success'>➕ Created: {$key} = {$value}</div>";
        }
    }

    // Clear settings cache
    if (function_exists('clear_settings_cache')) {
        clear_settings_cache();
        echo "<div class='info'>🔄 Settings cache cleared</div>";
    }

    echo "<div class='success'>✅ Email settings setup completed successfully!</div>";
    echo "<div class='info'>📊 Summary: {$updated_count} settings updated, {$created_count} settings created</div>";

    // Display current email settings
    echo "<h3>📋 Current Email Settings</h3>";
    echo "<table class='settings-table'>";
    echo "<tr><th>Setting</th><th>Value</th><th>Status</th></tr>";

    foreach ($email_settings as $key => $expected_value) {
        $current_value = get_setting($key, 'Not Set');
        $status = ($current_value === $expected_value) ? '✅ Correct' : '❌ Mismatch';
        $display_value = ($key === 'smtp_password') ? str_repeat('*', strlen($current_value)) : $current_value;
        
        echo "<tr>";
        echo "<td><strong>{$key}</strong></td>";
        echo "<td>{$display_value}</td>";
        echo "<td>{$status}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Test email configuration
    echo "<h3>🧪 Email Configuration Test</h3>";
    
    $config = get_email_config();
    if (!empty($config['smtp_host']) && !empty($config['smtp_user'])) {
        echo "<div class='success'>✅ Email configuration is properly set up</div>";
        echo "<div class='info'>
            <strong>Configuration Details:</strong><br>
            • SMTP Host: {$config['smtp_host']}<br>
            • SMTP Port: {$config['smtp_port']}<br>
            • SMTP User: {$config['smtp_user']}<br>
            • Encryption: {$config['smtp_crypto']}<br>
            • From Email: " . get_setting('from_email') . "<br>
            • From Name: " . get_setting('from_name') . "
        </div>";
        
        echo "<div class='info'>
            <strong>📝 Next Steps:</strong><br>
            1. Go to <a href='" . base_url('quotation') . "'>Quotation Management</a><br>
            2. Click 'Send Email' on any quotation to test the functionality<br>
            3. Check <a href='" . base_url('settings') . "'>Settings</a> to modify email configuration if needed
        </div>";
    } else {
        echo "<div class='error'>❌ Email configuration incomplete</div>";
    }

} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
}

echo "
        <div style='margin-top: 30px;'>
            <a href='" . base_url('quotation') . "' class='btn'>📋 Go to Quotations</a>
            <a href='" . base_url('settings') . "' class='btn'>⚙️ Manage Settings</a>
            <a href='" . base_url('dashboard') . "' class='btn'>🏠 Dashboard</a>
        </div>
    </div>
</body>
</html>";
?>
