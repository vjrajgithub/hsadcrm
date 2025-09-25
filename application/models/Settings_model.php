<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all settings grouped by category
     */
    public function get_all() {
        $this->db->order_by('category', 'ASC');
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('setting_key', 'ASC');
        return $this->db->get('system_settings')->result();
    }

    /**
     * Get settings by category
     */
    public function get_by_category($category) {
        $this->db->where('category', $category);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('setting_key', 'ASC');
        return $this->db->get('system_settings')->result();
    }

    /**
     * Get setting value by key
     */
    public function get_setting($key) {
        $query = $this->db->get_where('system_settings', ['setting_key' => $key]);
        $result = $query->row();
        return $result ? $result->setting_value : null;
    }
    
    /**
     * Get all settings as key-value array
     */
    public function get_settings_array($category = null) {
        if ($category) {
            $this->db->where('category', $category);
        }
        $query = $this->db->get('system_settings');
        $settings = [];
        foreach ($query->result() as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }

    /**
     * Get a specific setting record by ID
     */
    public function get($id) {
        $this->db->where('id', $id);
        return $this->db->get('system_settings')->row();
    }

    /**
     * Create a new setting
     */
    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('system_settings', $data);
    }

    /**
     * Update a setting
     */
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('system_settings', $data);
    }

    /**
     * Update setting by key
     */
    public function update_by_key($key, $value) {
        $data = [
            'setting_value' => $value,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('setting_key', $key);
        return $this->db->update('system_settings', $data);
    }

    /**
     * Delete a setting
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('system_settings');
    }

    /**
     * Get all categories
     */
    public function get_categories() {
        $this->db->select('category');
        $this->db->distinct();
        $this->db->order_by('category', 'ASC');
        $result = $this->db->get('system_settings')->result();
        return array_column($result, 'category');
    }

    /**
     * Check if setting key exists
     */
    public function key_exists($key, $exclude_id = null) {
        $this->db->where('setting_key', $key);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->get('system_settings')->num_rows() > 0;
    }

    /**
     * Initialize default settings
     */
    public function initialize_default_settings() {
        $default_settings = [
            // Application Settings
            ['category' => 'Application', 'setting_key' => 'app_name', 'setting_value' => 'HSAD CRM', 'description' => 'Application name displayed in headers', 'input_type' => 'text', 'sort_order' => 1],
            ['category' => 'Application', 'setting_key' => 'app_version', 'setting_value' => '1.0.0', 'description' => 'Current application version', 'input_type' => 'text', 'sort_order' => 2],
            ['category' => 'Application', 'setting_key' => 'maintenance_mode', 'setting_value' => '0', 'description' => 'Enable maintenance mode', 'input_type' => 'select', 'options' => '0:Disabled,1:Enabled', 'sort_order' => 3],
            ['category' => 'Application', 'setting_key' => 'timezone', 'setting_value' => 'Asia/Kolkata', 'description' => 'Default application timezone', 'input_type' => 'select', 'options' => 'Asia/Kolkata:India,America/New_York:New York,Europe/London:London', 'sort_order' => 4],
            
            // Email Settings
            ['category' => 'Email', 'setting_key' => 'smtp_host', 'setting_value' => 'smtp.gmail.com', 'description' => 'SMTP server hostname', 'input_type' => 'text', 'sort_order' => 1],
            ['category' => 'Email', 'setting_key' => 'smtp_port', 'setting_value' => '587', 'description' => 'SMTP server port', 'input_type' => 'number', 'sort_order' => 2],
            ['category' => 'Email', 'setting_key' => 'smtp_username', 'setting_value' => 'billing@hsadindia.com', 'description' => 'SMTP username/email', 'input_type' => 'email', 'sort_order' => 3],
            ['category' => 'Email', 'setting_key' => 'smtp_password', 'setting_value' => 'Sdla@8851', 'description' => 'SMTP password', 'input_type' => 'password', 'sort_order' => 4],
            ['category' => 'Email', 'setting_key' => 'smtp_encryption', 'setting_value' => 'tls', 'description' => 'SMTP encryption method', 'input_type' => 'select', 'options' => 'tls:TLS,ssl:SSL', 'sort_order' => 5],
            ['category' => 'Email', 'setting_key' => 'from_email', 'setting_value' => 'billing@hsadindia.com', 'description' => 'Default from email address', 'input_type' => 'email', 'sort_order' => 6],
            ['category' => 'Email', 'setting_key' => 'from_name', 'setting_value' => 'HSAD India', 'description' => 'Default from name', 'input_type' => 'text', 'sort_order' => 7],
            ['category' => 'Email', 'setting_key' => 'reply_to_email', 'setting_value' => 'billing@hsadindia.com', 'description' => 'Reply-to email address', 'input_type' => 'email', 'sort_order' => 8],
            
            // Security Settings
            ['category' => 'Security', 'setting_key' => 'session_timeout', 'setting_value' => '3600', 'description' => 'Session timeout in seconds', 'input_type' => 'number', 'sort_order' => 1],
            ['category' => 'Security', 'setting_key' => 'password_min_length', 'setting_value' => '6', 'description' => 'Minimum password length', 'input_type' => 'number', 'sort_order' => 2],
            ['category' => 'Security', 'setting_key' => 'max_login_attempts', 'setting_value' => '5', 'description' => 'Maximum login attempts before lockout', 'input_type' => 'number', 'sort_order' => 3],
            ['category' => 'Security', 'setting_key' => 'lockout_duration', 'setting_value' => '900', 'description' => 'Account lockout duration in seconds', 'input_type' => 'number', 'sort_order' => 4],
            
            // Business Settings
            ['category' => 'Business', 'setting_key' => 'company_name', 'setting_value' => 'HSAD Technologies', 'description' => 'Company name for documents', 'input_type' => 'text', 'sort_order' => 1],
            ['category' => 'Business', 'setting_key' => 'company_address', 'setting_value' => '', 'description' => 'Company address', 'input_type' => 'textarea', 'sort_order' => 2],
            ['category' => 'Business', 'setting_key' => 'company_phone', 'setting_value' => '', 'description' => 'Company phone number', 'input_type' => 'text', 'sort_order' => 3],
            ['category' => 'Business', 'setting_key' => 'company_email', 'setting_value' => '', 'description' => 'Company email address', 'input_type' => 'email', 'sort_order' => 4],
            ['category' => 'Business', 'setting_key' => 'gst_number', 'setting_value' => '', 'description' => 'GST registration number', 'input_type' => 'text', 'sort_order' => 5],
            ['category' => 'Business', 'setting_key' => 'default_currency', 'setting_value' => 'INR', 'description' => 'Default currency code', 'input_type' => 'select', 'options' => 'INR:Indian Rupee,USD:US Dollar,EUR:Euro', 'sort_order' => 6],
        ];

        foreach ($default_settings as $setting) {
            // Check if setting already exists
            $this->db->where('setting_key', $setting['setting_key']);
            $exists = $this->db->get('system_settings')->num_rows() > 0;
            
            if (!$exists) {
                $setting['created_at'] = date('Y-m-d H:i:s');
                $setting['updated_at'] = date('Y-m-d H:i:s');
                $this->db->insert('system_settings', $setting);
            }
        }
    }
}
?>
