<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Environment Configuration Loader
 * Loads environment variables from .env file and applies them to CodeIgniter config
 */
class Env_loader {
    
    public static function load() {
        $env_file = FCPATH . '.env';
        
        if (!file_exists($env_file)) {
            return false;
        }
        
        $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip comments
            }
            
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Remove quotes if present
            $value = trim($value, '"\'');
            
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
        
        return true;
    }
    
    public static function get($key, $default = null) {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}

// Auto-load environment variables
Env_loader::load();
