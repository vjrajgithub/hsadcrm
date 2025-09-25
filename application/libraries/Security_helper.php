<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enhanced Security Helper Library
 * Provides comprehensive security functions for input validation and sanitization
 */
class Security_helper {
    
    private $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('form_validation');
    }
    
    /**
     * Sanitize input data with XSS protection
     */
    public function sanitize_input($data, $type = 'string') {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize_input($value, $type);
            }
            return $data;
        }
        
        // Remove null bytes
        $data = str_replace(chr(0), '', $data);
        
        // XSS Clean
        $data = $this->CI->security->xss_clean($data);
        
        switch ($type) {
            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var($data, FILTER_SANITIZE_URL);
            case 'int':
                return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'string':
            default:
                return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
    }
    
    /**
     * Validate CSRF token
     */
    public function validate_csrf() {
        $csrf_token = $this->CI->input->post($this->CI->config->item('csrf_token_name'));
        $csrf_cookie = $this->CI->input->cookie($this->CI->config->item('csrf_cookie_name'));
        
        if (!$csrf_token || !$csrf_cookie || $csrf_token !== $csrf_cookie) {
            show_error('CSRF token mismatch. Request denied.', 403);
        }
        
        return true;
    }
    
    /**
     * Rate limiting check
     */
    public function check_rate_limit($identifier, $max_attempts = 5, $time_window = 300) {
        $cache_key = 'rate_limit_' . md5($identifier);
        $attempts = $this->CI->cache->get($cache_key);
        
        if ($attempts === FALSE) {
            $attempts = 0;
        }
        
        if ($attempts >= $max_attempts) {
            show_error('Rate limit exceeded. Please try again later.', 429);
        }
        
        $this->CI->cache->save($cache_key, $attempts + 1, $time_window);
        return true;
    }
    
    /**
     * Validate file upload security
     */
    public function validate_file_upload($file_data, $allowed_types = ['jpg', 'jpeg', 'png', 'pdf']) {
        // Check file size (max 5MB)
        if ($file_data['size'] > 5242880) {
            return ['status' => false, 'message' => 'File size exceeds 5MB limit'];
        }
        
        // Check file extension
        $file_ext = strtolower(pathinfo($file_data['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_types)) {
            return ['status' => false, 'message' => 'Invalid file type'];
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_data['tmp_name']);
        finfo_close($finfo);
        
        $allowed_mimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf'
        ];
        
        if (!isset($allowed_mimes[$file_ext]) || $mime_type !== $allowed_mimes[$file_ext]) {
            return ['status' => false, 'message' => 'File type mismatch'];
        }
        
        return ['status' => true, 'message' => 'File validation passed'];
    }
    
    /**
     * Generate secure random token
     */
    public function generate_token($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Password strength validation
     */
    public function validate_password_strength($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }
        
        return empty($errors) ? ['valid' => true] : ['valid' => false, 'errors' => $errors];
    }
}
