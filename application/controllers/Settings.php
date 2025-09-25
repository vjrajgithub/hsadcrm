<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Auth_Controller {

    public function __construct() {
        parent::__construct();
        // Only Super Admin can access settings
        $this->require_permission('manage_settings');
        $this->load->model('Settings_model');
        $this->load->library('form_validation');
    }

    /**
     * Settings dashboard - view all settings by category
     */
    public function index() {
        try {
            $data['settings'] = $this->Settings_model->get_all();
            $data['categories'] = $this->Settings_model->get_categories();
            $data['controller'] = $this;
            
            $this->load->view('templates/header');
            $this->load->view('templates/main_sidebar');
            $this->load->view('settings/index', $data);
            $this->load->view('templates/footer');
        } catch (Exception $e) {
            // If table doesn't exist, redirect to setup
            if (strpos($e->getMessage(), 'system_settings') !== false) {
                redirect('settings/setup');
            } else {
                show_error('Settings Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Get settings data for DataTable
     */
    public function list() {
        $settings = $this->Settings_model->get_all();
        echo json_encode(['data' => $settings]);
    }

    /**
     * Add new setting
     */
    public function add() {
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('category', 'Category', 'required|trim');
            $this->form_validation->set_rules('setting_key', 'Setting Key', 'required|trim|is_unique[system_settings.setting_key]');
            $this->form_validation->set_rules('setting_value', 'Setting Value', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('input_type', 'Input Type', 'required|in_list[text,number,email,password,textarea,select,checkbox]');

            if ($this->form_validation->run()) {
                $data = [
                    'category' => $this->input->post('category'),
                    'setting_key' => $this->input->post('setting_key'),
                    'setting_value' => $this->input->post('setting_value'),
                    'description' => $this->input->post('description'),
                    'input_type' => $this->input->post('input_type'),
                    'options' => $this->input->post('options'),
                    'sort_order' => $this->input->post('sort_order') ?: 999
                ];

                if ($this->Settings_model->create($data)) {
                    echo json_encode(['success' => true, 'message' => 'Setting added successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add setting']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => validation_errors()]);
            }
            return;
        }

        $data['categories'] = $this->Settings_model->get_categories();
        $this->load->view('settings/form', $data);
    }

    /**
     * Edit existing setting
     */
    public function edit($id) {
        $setting = $this->Settings_model->get($id);
        if (!$setting) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('category', 'Category', 'required|trim');
            $this->form_validation->set_rules('setting_key', 'Setting Key', 'required|trim|callback_check_unique_key[' . $id . ']');
            $this->form_validation->set_rules('setting_value', 'Setting Value', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('input_type', 'Input Type', 'required|in_list[text,number,email,password,textarea,select,checkbox]');

            if ($this->form_validation->run()) {
                $data = [
                    'category' => $this->input->post('category'),
                    'setting_key' => $this->input->post('setting_key'),
                    'setting_value' => $this->input->post('setting_value'),
                    'description' => $this->input->post('description'),
                    'input_type' => $this->input->post('input_type'),
                    'options' => $this->input->post('options'),
                    'sort_order' => (int)$this->input->post('sort_order')
                ];

                if ($this->Settings_model->update($id, $data)) {
                    // Clear cache after update
                    clear_settings_cache();
                    echo json_encode(['status' => true, 'message' => 'Setting updated successfully']);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Failed to update setting']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => validation_errors()]);
            }
            return;
        }

        $data['setting'] = $setting;
        $data['categories'] = $this->Settings_model->get_categories();
        $this->load->view('settings/form', $data);
    }

    /**
     * Delete setting
     */
    public function delete($id) {
        $setting = $this->Settings_model->get($id);
        if (!$setting) {
            echo json_encode(['success' => false, 'message' => 'Setting not found']);
            return;
        }

        if ($this->Settings_model->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Setting deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete setting']);
        }
    }

    /**
     * Get setting by ID for editing
     */
    public function get($id) {
        $setting = $this->Settings_model->get($id);
        header('Content-Type: application/json');
        echo json_encode($setting);
    }

    /**
     * Quick update setting value
     */
    public function quick_update() {
        $id = $this->input->post('id');
        $value = $this->input->post('value');

        if ($this->Settings_model->update($id, ['setting_value' => $value])) {
            // Clear cache after update
            clear_settings_cache();
            echo json_encode(['success' => true, 'message' => 'Setting updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update setting']);
        }
    }

    /**
     * Initialize system settings table and default data
     */
    public function setup() {
        $this->load->database();

        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>System Settings Setup</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
                .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .success { color: #28a745; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
                .error { color: #dc3545; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
                .info { color: #17a2b8; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
                .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px 0 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>System Settings Setup</h2>";

        try {
            // Drop existing table if it exists with wrong structure
            $this->db->query("DROP TABLE IF EXISTS system_settings");
            echo "<div class='info'>🔄 Dropped existing table (if any)</div>";
            
            // Create system_settings table with correct structure
            $sql = "CREATE TABLE system_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category VARCHAR(100) NOT NULL,
                setting_key VARCHAR(255) NOT NULL UNIQUE,
                setting_value TEXT,
                description TEXT,
                input_type ENUM('text', 'number', 'email', 'password', 'textarea', 'select', 'checkbox') DEFAULT 'text',
                options TEXT COMMENT 'For select/checkbox: value1:label1,value2:label2',
                sort_order INT DEFAULT 999,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                INDEX idx_category (category),
                INDEX idx_key (setting_key),
                INDEX idx_sort (sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            if ($this->db->query($sql)) {
                echo "<div class='success'>✅ System settings table created successfully</div>";
                
                // Initialize default settings
                $this->Settings_model->initialize_default_settings();
                echo "<div class='success'>✅ Default settings initialized</div>";
                
            } else {
                $error = $this->db->error();
                echo "<div class='error'>❌ Error creating table: " . $error['message'] . "</div>";
            }

            echo "<div class='info'><strong>System settings management is ready!</strong></div>";
            echo "<a href='" . base_url('settings') . "' class='btn'>Manage Settings</a>";
            echo "<a href='" . base_url('dashboard') . "' class='btn'>← Back to Dashboard</a>";

        } catch (Exception $e) {
            echo "<div class='error'>❌ Database error: " . $e->getMessage() . "</div>";
        }

        echo "</div></body></html>";
    }

    /**
     * Check email configuration status
     */
    public function check_email_config() {
        $smtp_host = get_setting('smtp_host');
        $smtp_username = get_setting('smtp_username');
        $smtp_password = get_setting('smtp_password');
        $from_email = get_setting('from_email');
        
        $configured = !empty($smtp_host) && !empty($smtp_username) && 
                     !empty($smtp_password) && !empty($from_email);
        
        echo json_encode([
            'configured' => $configured,
            'details' => [
                'smtp_host' => !empty($smtp_host),
                'smtp_username' => !empty($smtp_username),
                'smtp_password' => !empty($smtp_password),
                'from_email' => !empty($from_email)
            ]
        ]);
    }

    /**
     * Test email configuration
     */
    public function test_email() {
        if ($this->input->method() !== 'post') {
            echo json_encode(['status' => false, 'message' => 'Invalid request method']);
            return;
        }

        $test_email = $this->input->post('test_email');
        if (!filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => false, 'message' => 'Invalid email address']);
            return;
        }

        try {
            $config = get_email_config();
            $this->load->library('email', $config);
            
            $from_email = get_setting('from_email', 'billing@hsadindia.com');
            $from_name = get_setting('from_name', 'HSAD India');
            
            $this->email->from($from_email, $from_name);
            $this->email->to($test_email);
            $this->email->subject('Test Email from HSAD CRM');
            $this->email->message('This is a test email to verify your email configuration is working properly.');
            
            if ($this->email->send()) {
                echo json_encode(['status' => true, 'message' => 'Test email sent successfully!']);
            } else {
                $error = $this->email->print_debugger();
                echo json_encode(['status' => false, 'message' => 'Failed to send test email: ' . $error]);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Email configuration error: ' . $e->getMessage()]);
        }
    }

    /**
     * Custom validation for unique setting key
     */
    public function check_unique_key($key, $exclude_id) {
        if ($this->Settings_model->key_exists($key, $exclude_id)) {
            $this->form_validation->set_message('check_unique_key', 'Setting key already exists');
            return FALSE;
        }
        return TRUE;
    }
}
