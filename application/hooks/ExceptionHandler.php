<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ExceptionHandler {
    
    public function handle_errors() {
        // Set custom error handler
        set_error_handler(array($this, 'error_handler'));
        set_exception_handler(array($this, 'exception_handler'));
        register_shutdown_function(array($this, 'shutdown_handler'));
    }
    
    public function error_handler($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return;
        }
        
        // Log the error
        log_message('error', "PHP Error: {$message} in {$file} on line {$line}");
        
        // In production, show friendly error page
        if (ENVIRONMENT === 'production') {
            $this->show_error_page('500');
        }
        
        return false;
    }
    
    public function exception_handler($exception) {
        // Log the exception
        log_message('error', "Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
        
        // Show appropriate error page
        $this->show_error_page('500');
    }
    
    public function shutdown_handler() {
        $error = error_get_last();
        if ($error && ($error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR))) {
            log_message('error', "Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}");
            
            if (ENVIRONMENT === 'production') {
                $this->show_error_page('500');
            }
        }
    }
    
    private function show_error_page($error_code) {
        if (!headers_sent()) {
            switch ($error_code) {
                case '404':
                    header('HTTP/1.1 404 Not Found');
                    break;
                case '403':
                    header('HTTP/1.1 403 Forbidden');
                    break;
                case '500':
                default:
                    header('HTTP/1.1 500 Internal Server Error');
                    break;
            }
            
            // Redirect to error controller
            header('Location: ' . base_url('errorhandler/server_error'));
            exit;
        }
    }
}
