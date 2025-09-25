<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enhanced Error Handler Library
 * Provides comprehensive error handling and logging capabilities
 */
class Error_handler {
    
    private $CI;
    private $log_path;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->log_path = APPPATH . 'logs/';
        
        // Ensure logs directory exists
        if (!is_dir($this->log_path)) {
            mkdir($this->log_path, 0755, true);
        }
        
        // Set custom error handlers
        set_error_handler([$this, 'handle_error']);
        set_exception_handler([$this, 'handle_exception']);
        register_shutdown_function([$this, 'handle_fatal_error']);
    }
    
    /**
     * Handle PHP errors
     */
    public function handle_error($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        
        $error_data = [
            'type' => 'PHP Error',
            'severity' => $this->get_error_severity($severity),
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'user_id' => $this->CI->session->userdata('user_id'),
            'request_uri' => $this->CI->input->server('REQUEST_URI'),
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        ];
        
        $this->log_error($error_data);
        
        // Show user-friendly error in production
        if (ENVIRONMENT === 'production') {
            show_error('An error occurred. Please try again later.', 500);
        }
        
        return true;
    }
    
    /**
     * Handle uncaught exceptions
     */
    public function handle_exception($exception) {
        $error_data = [
            'type' => 'Exception',
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'user_id' => $this->CI->session->userdata('user_id'),
            'request_uri' => $this->CI->input->server('REQUEST_URI'),
            'trace' => $exception->getTraceAsString()
        ];
        
        $this->log_error($error_data);
        
        if (ENVIRONMENT === 'production') {
            show_error('An unexpected error occurred. Please contact support.', 500);
        } else {
            echo '<h1>Uncaught Exception</h1>';
            echo '<p><strong>Message:</strong> ' . $exception->getMessage() . '</p>';
            echo '<p><strong>File:</strong> ' . $exception->getFile() . '</p>';
            echo '<p><strong>Line:</strong> ' . $exception->getLine() . '</p>';
            echo '<pre>' . $exception->getTraceAsString() . '</pre>';
        }
    }
    
    /**
     * Handle fatal errors
     */
    public function handle_fatal_error() {
        $error = error_get_last();
        
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $error_data = [
                'type' => 'Fatal Error',
                'severity' => $this->get_error_severity($error['type']),
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
                'timestamp' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
            ];
            
            $this->log_error($error_data);
        }
    }
    
    /**
     * Log application errors
     */
    public function log_application_error($message, $context = []) {
        $error_data = [
            'type' => 'Application Error',
            'message' => $message,
            'context' => $context,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'user_id' => $this->CI->session->userdata('user_id'),
            'request_uri' => $this->CI->input->server('REQUEST_URI'),
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ];
        
        $this->log_error($error_data);
    }
    
    /**
     * Log security events
     */
    public function log_security_event($event_type, $details = []) {
        $security_data = [
            'type' => 'Security Event',
            'event_type' => $event_type,
            'details' => $details,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'user_id' => $this->CI->session->userdata('user_id'),
            'request_uri' => $this->CI->input->server('REQUEST_URI')
        ];
        
        // Log to security log file
        $this->write_log_file('security_' . date('Y-m-d') . '.log', $security_data);
        
        // Also log to database if available
        if ($this->CI->db) {
            try {
                $this->CI->db->insert('security_logs', [
                    'user_id' => $security_data['user_id'],
                    'ip_address' => $security_data['ip_address'],
                    'user_agent' => $security_data['user_agent'],
                    'event_type' => $event_type,
                    'event_data' => json_encode($details),
                    'created_at' => $security_data['timestamp']
                ]);
            } catch (Exception $e) {
                // Fallback to file logging if database fails
                $this->write_log_file('error_' . date('Y-m-d') . '.log', [
                    'message' => 'Failed to log security event to database: ' . $e->getMessage(),
                    'original_event' => $security_data
                ]);
            }
        }
    }
    
    /**
     * Write error to log file
     */
    private function log_error($error_data) {
        $log_file = 'error_' . date('Y-m-d') . '.log';
        $this->write_log_file($log_file, $error_data);
        
        // Send critical errors via email in production
        if (ENVIRONMENT === 'production' && in_array($error_data['type'], ['Fatal Error', 'Exception'])) {
            $this->send_error_notification($error_data);
        }
    }
    
    /**
     * Write to log file
     */
    private function write_log_file($filename, $data) {
        $log_entry = date('Y-m-d H:i:s') . ' - ' . json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL;
        file_put_contents($this->log_path . $filename, $log_entry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Send error notification email
     */
    private function send_error_notification($error_data) {
        // Implementation depends on your email configuration
        // This is a placeholder for email notification
        $subject = 'CRM System Error Alert - ' . $error_data['type'];
        $message = "An error occurred in the CRM system:\n\n" . json_encode($error_data, JSON_PRETTY_PRINT);
        
        // Add to email queue or send immediately
        // mail('admin@yourcrm.com', $subject, $message);
    }
    
    /**
     * Get human-readable error severity
     */
    private function get_error_severity($severity) {
        $severities = [
            E_ERROR => 'Fatal Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict Notice',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated'
        ];
        
        return $severities[$severity] ?? 'Unknown Error';
    }
}
