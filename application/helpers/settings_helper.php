<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Helper
 * Global helper functions to access system settings throughout the application
 */

if (!function_exists('get_setting')) {
    /**
     * Get a system setting value by key
     * @param string $key Setting key
     * @param mixed $default Default value if setting not found
     * @return mixed Setting value or default
     */
    function get_setting($key, $default = null) {
        $CI =& get_instance();
        
        // Load Settings model if not already loaded
        if (!isset($CI->Settings_model)) {
            $CI->load->model('Settings_model');
        }
        
        // Use CodeIgniter cache for performance
        static $settings_cache = [];
        $cache_key = 'setting_' . $key;
        
        if (!isset($settings_cache[$key])) {
            // Try to get from CodeIgniter cache first
            if (isset($CI->cache)) {
                $cached_value = $CI->cache->get($cache_key);
                if ($cached_value !== FALSE) {
                    $settings_cache[$key] = $cached_value;
                    return $cached_value;
                }
            }
            
            // Get from database
            try {
                $value = $CI->Settings_model->get_setting($key);
                $final_value = $value !== null ? $value : $default;
                $settings_cache[$key] = $final_value;
                
                // Cache for 1 hour
                if (isset($CI->cache)) {
                    $CI->cache->save($cache_key, $final_value, 3600);
                }
                
                return $final_value;
            } catch (Exception $e) {
                // Return default if database error
                log_message('error', 'Settings error for key ' . $key . ': ' . $e->getMessage());
                return $default;
            }
        }
        
        return $settings_cache[$key];
    }
}

if (!function_exists('get_settings')) {
    /**
     * Get all settings as key-value pairs
     * @param string $category Optional category filter
     * @return array Settings array
     */
    function get_settings($category = null) {
        $CI =& get_instance();
        
        if (!isset($CI->Settings_model)) {
            $CI->load->model('Settings_model');
        }
        
        return $CI->Settings_model->get_settings_array($category);
    }
}

if (!function_exists('get_app_setting')) {
    /**
     * Get application specific settings
     * @param string $key Setting key
     * @param mixed $default Default value
     * @return mixed Setting value
     */
    function get_app_setting($key, $default = null) {
        return get_setting($key, $default);
    }
}

if (!function_exists('get_email_config')) {
    /**
     * Get email configuration from settings
     * @return array Email configuration array
     */
    function get_email_config() {
        // Read settings (support both smtp_encryption and smtp_crypto keys)
        $host = get_setting('smtp_host', 'smtp.gmail.com');
        $port = (int) get_setting('smtp_port', 0);
        $username = get_setting('smtp_username', '');
        $password = get_setting('smtp_password', '');
        $encryption = strtolower(trim((string) get_setting('smtp_encryption', get_setting('smtp_crypto', 'tls'))));

        // Normalize encryption value
        if ($encryption !== 'ssl' && $encryption !== 'tls') {
            $encryption = 'tls';
        }

        // If port is not set or mismatched for the chosen encryption, pick sensible defaults
        if ($port <= 0) {
            $port = ($encryption === 'ssl') ? 465 : 587;
        } else {
            // Auto-correct common misconfigs (e.g., ssl with 587 or tls with 465)
            if ($encryption === 'ssl' && $port === 587) {
                $port = 465;
            }
            if ($encryption === 'tls' && $port === 465) {
                $port = 587;
            }
        }

        // Gmail best-practice defaults
        if (stripos($host, 'gmail.com') !== false) {
            // Prefer STARTTLS on 587 for Gmail unless admin explicitly sets SSL:465
            if ($encryption !== 'ssl') {
                $encryption = 'tls';
                $port = 587;
            }
        }

        return [
            'protocol' => 'smtp',
            'smtp_host' => $host,
            'smtp_port' => $port,
            'smtp_user' => $username,
            'smtp_pass' => $password,
            'smtp_crypto' => $encryption,
            'smtp_timeout' => (int) get_setting('smtp_timeout', 30),
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n",
            'wordwrap' => TRUE,
        ];
    }
}

if (!function_exists('get_company_info')) {
    /**
     * Get company information from settings
     * @return array Company information
     */
    function get_company_info() {
        return [
            'name' => get_setting('company_name', 'HSAD Technologies'),
            'address' => get_setting('company_address', ''),
            'phone' => get_setting('company_phone', ''),
            'email' => get_setting('company_email', ''),
            'gst_number' => get_setting('gst_number', ''),
            'currency' => get_setting('default_currency', 'INR')
        ];
    }
}

if (!function_exists('get_app_name')) {
    /**
     * Get application name
     * @return string Application name
     */
    function get_app_name() {
        return get_setting('app_name', 'HSAD CRM');
    }
}

if (!function_exists('get_app_version')) {
    /**
     * Get application version
     * @return string Application version
     */
    function get_app_version() {
        return get_setting('app_version', '1.0.0');
    }
}

if (!function_exists('is_maintenance_mode')) {
    /**
     * Check if maintenance mode is enabled
     * @return bool True if maintenance mode is on
     */
    function is_maintenance_mode() {
        return get_setting('maintenance_mode', '0') === '1';
    }
}

if (!function_exists('get_timezone')) {
    /**
     * Get application timezone
     * @return string Timezone
     */
    function get_timezone() {
        return get_setting('timezone', 'Asia/Kolkata');
    }
}

if (!function_exists('get_security_settings')) {
    /**
     * Get security related settings
     * @return array Security settings
     */
    function get_security_settings() {
        return [
            'session_timeout' => (int)get_setting('session_timeout', 3600),
            'password_min_length' => (int)get_setting('password_min_length', 6),
            'max_login_attempts' => (int)get_setting('max_login_attempts', 5),
            'lockout_duration' => (int)get_setting('lockout_duration', 900)
        ];
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format currency based on system settings
     * @param float $amount Amount to format
     * @param bool $show_symbol Show currency symbol
     * @return string Formatted currency
     */
    function format_currency($amount, $show_symbol = true) {
        $currency = get_setting('default_currency', 'INR');
        $symbols = [
            'INR' => '₹',
            'USD' => '$',
            'EUR' => '€'
        ];
        
        $symbol = $symbols[$currency] ?? $currency;
        $formatted = number_format($amount, 2);
        
        return $show_symbol ? $symbol . ' ' . $formatted : $formatted;
    }
}

if (!function_exists('send_system_email')) {
    /**
     * Send email using system settings
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email message
     * @param string $from_name Optional from name
     * @return bool Success status
     */
    function send_system_email($to, $subject, $message, $from_name = null) {
        $CI =& get_instance();
        
        // Load email library with system settings
        $config = get_email_config();
        $CI->load->library('email', $config);
        
        // Set email parameters
        $from_email = get_setting('from_email', 'noreply@hsadcrm.com');
        $from_name = $from_name ?: get_setting('from_name', get_app_name());
        
        $CI->email->from($from_email, $from_name);
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        
        // Send email
        if ($CI->email->send()) {
            log_message('info', "Email sent successfully to: $to");
            return true;
        } else {
            log_message('error', "Failed to send email to: $to. Error: " . $CI->email->print_debugger());
            return false;
        }
    }
}

if (!function_exists('clear_settings_cache')) {
    /**
     * Clear settings cache (useful after updating settings)
     */
    function clear_settings_cache() {
        $CI =& get_instance();
        
        // Clear CodeIgniter cache for all settings
        if (isset($CI->cache)) {
            // Clear individual setting caches
            $CI->load->model('Settings_model');
            try {
                $all_settings = $CI->Settings_model->get_all();
                foreach ($all_settings as $setting) {
                    $CI->cache->delete('setting_' . $setting->setting_key);
                }
            } catch (Exception $e) {
                // Clear common setting keys if model fails
                $common_keys = ['app_name', 'smtp_host', 'smtp_port', 'company_name', 'from_email'];
                foreach ($common_keys as $key) {
                    $CI->cache->delete('setting_' . $key);
                }
            }
        }
        
        // Reset static cache
        $GLOBALS['settings_static_cache'] = [];
    }
}
?>
