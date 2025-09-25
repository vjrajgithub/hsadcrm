<?php
// One-time script to set SMTP email settings in system_settings table
// SECURITY: Delete this file after running once.

// Load DB credentials from .env to avoid including CI files with BASEPATH guards
function read_env($path) {
    if (!file_exists($path)) return [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if ($line === '' || $line[0] === '#') continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            $val = trim($val, "\"' ");
            $env[$key] = $val;
        }
    }
    return $env;
}

$env = read_env(__DIR__ . DIRECTORY_SEPARATOR . '.env');

$db_host = $env['DB_HOSTNAME'] ?? 'localhost';
$db_user = $env['DB_USERNAME'] ?? 'root';
$db_pass = $env['DB_PASSWORD'] ?? '';
$db_name = $env['DB_DATABASE'] ?? 'crm_db';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
    http_response_code(500);
    echo 'DB connection error: ' . $mysqli->connect_error;
    exit;
}

// Ensure system_settings table exists
$check = $mysqli->query("SHOW TABLES LIKE 'system_settings'");
if ($check->num_rows === 0) {
    echo "system_settings table not found. Visit /settings/setup first.";
    exit;
}

function upsert_setting(mysqli $mysqli, string $key, string $value, string $category, string $description = '', string $input_type = 'text') {
    $stmt = $mysqli->prepare(
        "INSERT INTO system_settings (category, setting_key, setting_value, description, input_type, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, NOW(), NOW())
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), description = VALUES(description), input_type = VALUES(input_type), updated_at = NOW()"
    );
    $stmt->bind_param('sssss', $category, $key, $value, $description, $input_type);
    if (!$stmt->execute()) {
        throw new Exception('Failed to upsert setting ' . $key . ': ' . $stmt->error);
    }
    $stmt->close();
}

try {
    // Provided credentials (can be overridden via query params for flexibility)
    $smtp_host = $_GET['host'] ?? 'smtp.gmail.com';
    $smtp_port = $_GET['port'] ?? '587';
    $smtp_username = $_GET['user'] ?? 'billing@hsadindia.com';
    $smtp_password = $_GET['pass'] ?? 'Sdla@8851';
    $smtp_crypto = $_GET['crypto'] ?? 'tls';
    $smtp_timeout = (string) ($_GET['timeout'] ?? '60');

    // Upsert email settings
    upsert_setting($mysqli, 'smtp_host', $smtp_host, 'Email', 'SMTP server host', 'text');
    upsert_setting($mysqli, 'smtp_port', $smtp_port, 'Email', 'SMTP server port', 'number');
    upsert_setting($mysqli, 'smtp_username', $smtp_username, 'Email', 'SMTP username', 'email');
    upsert_setting($mysqli, 'smtp_password', $smtp_password, 'Email', 'SMTP password', 'password');
    upsert_setting($mysqli, 'smtp_crypto', $smtp_crypto, 'Email', 'SMTP encryption (tls/ssl/none)', 'select');
    upsert_setting($mysqli, 'smtp_timeout', $smtp_timeout, 'Email', 'SMTP connection timeout (seconds)', 'number');

    // From details
    upsert_setting($mysqli, 'from_email', $smtp_username, 'Email', 'Default From Email', 'email');
    upsert_setting($mysqli, 'from_name', 'HSAD CRM', 'Email', 'Default From Name', 'text');

    echo '<h3>SMTP settings updated successfully.</h3>';
    echo '<ul>';
    echo '<li>Host: ' . htmlspecialchars($smtp_host) . '</li>';
    echo '<li>Port: ' . htmlspecialchars($smtp_port) . '</li>';
    echo '<li>User: ' . htmlspecialchars($smtp_username) . '</li>';
    echo '<li>Crypto: ' . htmlspecialchars($smtp_crypto) . '</li>';
    echo '<li>Timeout: ' . htmlspecialchars($smtp_timeout) . 's</li>';
    echo '</ul>';

    echo '<p>Next: Open <a href="settings" target="_blank">Settings</a> → Email tab to verify values. Then try sending a quotation email.</p>';
    echo '<p style="color:#b00">Security reminder: Delete this file (set_email_settings.php) after confirming emails work.</p>';

} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
} finally {
    $mysqli->close();
}
