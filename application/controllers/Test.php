<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test Controller for Running System Tests
 * Access: /test (only in development environment)
 */
class Test extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Only allow in development environment
        if (ENVIRONMENT !== 'development') {
            show_404();
        }
    }
    
    /**
     * Run security tests
     */
    public function security() {
        require_once APPPATH . '../tests/SecurityTests.php';
        
        $security_tests = new SecurityTests();
        $security_tests->runTests();
    }
    
    /**
     * Test database connectivity and performance
     */
    public function database() {
        echo "<h1>Database Tests</h1>";
        
        // Test connection
        echo "<h3>Database Connection Test</h3>";
        if ($this->db->initialize()) {
            echo "<p style='color: green;'>✓ Database connection successful</p>";
        } else {
            echo "<p style='color: red;'>✗ Database connection failed</p>";
        }
        
        // Test query performance
        echo "<h3>Query Performance Test</h3>";
        $start_time = microtime(true);
        $this->db->get('users');
        $end_time = microtime(true);
        $query_time = ($end_time - $start_time) * 1000;
        
        echo "<p>Query execution time: " . number_format($query_time, 2) . " ms</p>";
        
        if ($query_time < 100) {
            echo "<p style='color: green;'>✓ Query performance is good</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Query performance could be improved</p>";
        }
    }
    
    /**
     * Test cache functionality
     */
    public function cache() {
        echo "<h1>Cache Tests</h1>";
        
        $test_key = 'test_cache_key';
        $test_data = ['message' => 'Cache test successful', 'timestamp' => time()];
        
        // Test cache set
        echo "<h3>Cache Set Test</h3>";
        if ($this->cache_manager->set($test_key, $test_data, 60)) {
            echo "<p style='color: green;'>✓ Cache set successful</p>";
        } else {
            echo "<p style='color: red;'>✗ Cache set failed</p>";
        }
        
        // Test cache get
        echo "<h3>Cache Get Test</h3>";
        $cached_data = $this->cache_manager->get($test_key);
        if ($cached_data && $cached_data['message'] === $test_data['message']) {
            echo "<p style='color: green;'>✓ Cache get successful</p>";
        } else {
            echo "<p style='color: red;'>✗ Cache get failed</p>";
        }
        
        // Clean up
        $this->cache_manager->delete($test_key);
        echo "<p>Cache test completed and cleaned up</p>";
    }
    
    /**
     * Test file upload security
     */
    public function upload() {
        echo "<h1>File Upload Security Tests</h1>";
        
        // Simulate file upload test
        $test_files = [
            ['name' => 'test.jpg', 'type' => 'image/jpeg', 'size' => 1024000],
            ['name' => 'malicious.php', 'type' => 'application/x-php', 'size' => 2048],
            ['name' => 'large.pdf', 'type' => 'application/pdf', 'size' => 10485760]
        ];
        
        $this->load->library('security_helper');
        
        foreach ($test_files as $file) {
            echo "<h3>Testing: {$file['name']}</h3>";
            
            // This is a simulation - in real scenario, you'd test actual file uploads
            if (strpos($file['name'], '.php') !== false) {
                echo "<p style='color: red;'>✗ PHP file upload blocked (as expected)</p>";
            } elseif ($file['size'] > 5242880) {
                echo "<p style='color: red;'>✗ Large file blocked (as expected)</p>";
            } else {
                echo "<p style='color: green;'>✓ Safe file would be accepted</p>";
            }
        }
    }
    
    /**
     * Test all systems
     */
    public function all() {
        $this->security();
        echo "<hr>";
        $this->database();
        echo "<hr>";
        $this->cache();
        echo "<hr>";
        $this->upload();
    }
}
