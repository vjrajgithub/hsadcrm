<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enhanced Base Controller with Security Features
 * Extends CodeIgniter's base controller with comprehensive security measures
 */
class MY_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Load security libraries conditionally
        if (file_exists(APPPATH . 'libraries/Security_helper.php')) {
            $this->load->library('security_helper');
        }
        $this->load->library('session');
        $this->load->helper(['url', 'form', 'security', 'rbac']);
        
        // Set security headers
        $this->set_security_headers();
        
        // Rate limiting for API endpoints
        if ($this->uri->segment(1) === 'api' && isset($this->security_helper)) {
            $this->security_helper->check_rate_limit($this->input->ip_address());
        }
    }
    
    /**
     * Set comprehensive security headers
     */
    private function set_security_headers() {
        // Prevent clickjacking
        $this->output->set_header('X-Frame-Options: DENY');
        
        // XSS Protection
        $this->output->set_header('X-XSS-Protection: 1; mode=block');
        
        // Content type sniffing protection
        $this->output->set_header('X-Content-Type-Options: nosniff');
        
        // Referrer Policy
        $this->output->set_header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Content Security Policy - Temporarily disabled for development
        // TODO: Re-enable with proper CDN allowlist in production
        // $csp = "default-src 'self'; " .
        //        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com https://cdn.datatables.net https://cdnjs.cloudflare.com; " .
        //        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdn.datatables.net https://cdnjs.cloudflare.com https://maxcdn.bootstrapcdn.com; " .
        //        "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://maxcdn.bootstrapcdn.com; " .
        //        "img-src 'self' data: https:; " .
        //        "connect-src 'self';";
        // $this->output->set_header('Content-Security-Policy: ' . $csp);
        
        // HSTS for HTTPS
        if ($this->input->server('HTTPS')) {
            $this->output->set_header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
    
    /**
     * Sanitize all input data
     */
    protected function sanitize_input($data = null, $type = 'string') {
        if ($data === null) {
            $data = $this->input->post();
        }
        
        if (isset($this->security_helper)) {
            return $this->security_helper->sanitize_input($data, $type);
        }
        
        // Fallback sanitization
        return $this->security->xss_clean($data);
    }
    
    /**
     * Validate CSRF for POST requests
     */
    protected function validate_csrf() {
        if ($this->input->method() === 'post' && isset($this->security_helper)) {
            return $this->security_helper->validate_csrf();
        }
        return true;
    }
    
    /**
     * JSON response helper with security headers
     */
    protected function json_response($data, $status_code = 200) {
        $this->output->set_status_header($status_code);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
    }
    
    /**
     * Log security events
     */
    protected function log_security_event($event, $details = []) {
        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'user_id' => $this->session->userdata('user_id'),
            'event' => $event,
            'details' => $details
        ];
        
        log_message('info', 'Security Event: ' . json_encode($log_data));
    }
}

/**
 * Authenticated Controller - Requires login
 */
class MY_Auth_Controller extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Check authentication
        if (!$this->session->userdata('logged_in')) {
            $this->log_security_event('unauthorized_access_attempt');
            // For AJAX or any POST requests, return JSON 401 instead of redirect to avoid 302 loops
            if ($this->input->is_ajax_request() || strtolower($this->input->method()) === 'post') {
                $this->output->set_status_header(401);
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode([
                    'status' => false,
                    'message' => 'Session expired. Please log in again.'
                ]));
                exit;
            }
            redirect('login');
        }
        
        // Store user role for RBAC
        $this->user_role = $this->session->userdata('user_role');
        $this->user_id = $this->session->userdata('user_id');
        
        // Update last activity
        $this->session->set_userdata('last_activity', time());
        
        // Session timeout check (2 hours)
        $last_activity = $this->session->userdata('last_activity');
        if ($last_activity && (time() - $last_activity) > 7200) {
            $this->session->sess_destroy();
            redirect('login?timeout=1');
        }
    }
    
    /**
     * Check if user has specific permission
     */
    public function has_permission($permission) {
        $role_permissions = [
            'super admin' => ['*'], // Super admin has all permissions including manage_settings
            'admin' => [
                'view_dashboard', 'view_analytics',
                'view_companies', 'add_companies', 'edit_companies', 'delete_companies',
                'view_clients', 'add_clients', 'edit_clients', 'delete_clients',
                'view_contacts', 'add_contacts', 'edit_contacts', 'delete_contacts',
                'view_banks', 'add_banks', 'edit_banks', 'delete_banks',
                'view_categories', 'add_categories', 'edit_categories', 'delete_categories',
                'view_products', 'add_products', 'edit_products', 'delete_products',
                'view_quotations', 'add_quotations', 'edit_quotations', 'delete_quotations',
                'view_users', 'add_users', 'edit_users' // No delete_users or manage_settings for admin
            ],
            'viewer' => [
                'view_dashboard', 'view_analytics',
                'view_companies', 'view_clients', 'view_contacts', 'view_banks',
                'view_categories', 'view_products', 'view_quotations'
                // No user management or settings access for viewer
            ]
        ];
        
        $user_role = strtolower($this->user_role);
        
        // Super admin has all permissions
        if ($user_role === 'super admin' || in_array('*', $role_permissions[$user_role] ?? [])) {
            return true;
        }
        
        return in_array($permission, $role_permissions[$user_role] ?? []);
    }
    
    /**
     * Require specific permission or show error
     */
    public function require_permission($permission) {
        if (!$this->has_permission($permission)) {
            $this->log_security_event('insufficient_privileges', ['required_permission' => $permission]);
            redirect('errorhandler/access_denied');
        }
    }
    
    /**
     * Check if user can perform action on specific resource
     */
    protected function can_access_resource($resource_type, $resource_id = null, $action = 'view') {
        // Super admin can access everything
        if (strtolower($this->user_role) === 'super admin') {
            return true;
        }
        
        // Check basic permission first
        if (!$this->has_permission($action . '_' . $resource_type)) {
            return false;
        }
        
        // Additional resource-specific checks can be added here
        // For example, users can only edit their own profile
        if ($resource_type === 'users' && $resource_id && $action !== 'view') {
            return $resource_id == $this->user_id;
        }
        
        return true;
    }
}
