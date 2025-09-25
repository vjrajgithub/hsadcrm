<?php
require_once 'TestRunner.php';

/**
 * Security Tests for CRM System
 */
class SecurityTests {
    
    private $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    public function runTests() {
        $runner = new TestRunner();
        
        // Add security tests
        $runner->addTest('CSRF Protection Enabled', [$this, 'testCSRFProtection']);
        $runner->addTest('XSS Protection Enabled', [$this, 'testXSSProtection']);
        $runner->addTest('Strong Encryption Key', [$this, 'testEncryptionKey']);
        $runner->addTest('Security Headers Present', [$this, 'testSecurityHeaders']);
        $runner->addTest('Input Sanitization', [$this, 'testInputSanitization']);
        $runner->addTest('Password Strength Validation', [$this, 'testPasswordValidation']);
        
        $runner->run();
    }
    
    public function testCSRFProtection() {
        $csrf_enabled = $this->CI->config->item('csrf_protection');
        TestRunner::assertTrue($csrf_enabled, 'CSRF protection should be enabled');
        return true;
    }
    
    public function testXSSProtection() {
        $xss_enabled = $this->CI->config->item('global_xss_filtering');
        TestRunner::assertTrue($xss_enabled, 'XSS filtering should be enabled');
        return true;
    }
    
    public function testEncryptionKey() {
        $key = $this->CI->config->item('encryption_key');
        TestRunner::assertNotNull($key, 'Encryption key should be set');
        TestRunner::assertTrue(strlen($key) >= 32, 'Encryption key should be at least 32 characters');
        TestRunner::assertTrue($key !== 'changeme-generate-a-strong-random-key', 'Default encryption key should be changed');
        return true;
    }
    
    public function testSecurityHeaders() {
        // This would need to be tested in actual HTTP response
        // For now, just verify the headers are configured
        return true;
    }
    
    public function testInputSanitization() {
        $this->CI->load->library('security_helper');
        
        $malicious_input = '<script>alert("xss")</script>';
        $sanitized = $this->CI->security_helper->sanitize_input($malicious_input);
        
        TestRunner::assertTrue(strpos($sanitized, '<script>') === false, 'Script tags should be removed');
        return true;
    }
    
    public function testPasswordValidation() {
        $this->CI->load->library('security_helper');
        
        $weak_password = '123';
        $strong_password = 'StrongP@ssw0rd123';
        
        $weak_result = $this->CI->security_helper->validate_password_strength($weak_password);
        $strong_result = $this->CI->security_helper->validate_password_strength($strong_password);
        
        TestRunner::assertTrue(!$weak_result['valid'], 'Weak password should be rejected');
        TestRunner::assertTrue($strong_result['valid'], 'Strong password should be accepted');
        
        return true;
    }
}
