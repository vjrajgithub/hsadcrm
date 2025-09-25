<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple Test Runner for CRM System
 * Provides basic unit testing capabilities
 */
class TestRunner {
    
    private $tests = [];
    private $passed = 0;
    private $failed = 0;
    
    public function __construct() {
        echo "<h1>CRM System Test Suite</h1>\n";
    }
    
    /**
     * Add a test case
     */
    public function addTest($name, $callback) {
        $this->tests[] = ['name' => $name, 'callback' => $callback];
    }
    
    /**
     * Run all tests
     */
    public function run() {
        foreach ($this->tests as $test) {
            $this->runTest($test['name'], $test['callback']);
        }
        
        $this->showResults();
    }
    
    /**
     * Run individual test
     */
    private function runTest($name, $callback) {
        echo "<h3>Testing: {$name}</h3>\n";
        
        try {
            $result = call_user_func($callback);
            if ($result) {
                echo "<p style='color: green;'>✓ PASSED</p>\n";
                $this->passed++;
            } else {
                echo "<p style='color: red;'>✗ FAILED</p>\n";
                $this->failed++;
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ ERROR: " . $e->getMessage() . "</p>\n";
            $this->failed++;
        }
    }
    
    /**
     * Show test results
     */
    private function showResults() {
        $total = $this->passed + $this->failed;
        echo "<hr>\n";
        echo "<h2>Test Results</h2>\n";
        echo "<p>Total Tests: {$total}</p>\n";
        echo "<p style='color: green;'>Passed: {$this->passed}</p>\n";
        echo "<p style='color: red;'>Failed: {$this->failed}</p>\n";
        
        if ($this->failed === 0) {
            echo "<p style='color: green; font-weight: bold;'>All tests passed! ✓</p>\n";
        }
    }
    
    /**
     * Assert functions
     */
    public static function assertTrue($condition, $message = '') {
        if (!$condition) {
            throw new Exception("Assertion failed: {$message}");
        }
        return true;
    }
    
    public static function assertEquals($expected, $actual, $message = '') {
        if ($expected !== $actual) {
            throw new Exception("Assertion failed: Expected '{$expected}', got '{$actual}'. {$message}");
        }
        return true;
    }
    
    public static function assertNotNull($value, $message = '') {
        if ($value === null) {
            throw new Exception("Assertion failed: Value should not be null. {$message}");
        }
        return true;
    }
}
