<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Analytics Dashboard Controller
 * Provides system monitoring and analytics interface
 */
class Analytics_dashboard extends MY_Auth_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Load analytics library manually to avoid autoload issues
        if (file_exists(APPPATH . 'libraries/Analytics.php')) {
            $this->load->library('analytics');
        }
    }
    
    /**
     * Main analytics dashboard
     */
    public function index() {
        $data = [];
        
        // Load analytics data if library is available
        if (isset($this->analytics)) {
            $data['stats'] = $this->analytics->get_dashboard_stats();
            $data['performance'] = $this->analytics->get_performance_metrics();
            $data['security'] = $this->analytics->get_security_metrics();
            $data['health_report'] = $this->analytics->generate_health_report();
        } else {
            // Provide basic fallback data
            $data['stats'] = ['total_users' => 0, 'total_clients' => 0, 'total_quotations' => 0];
            $data['performance'] = ['avg_response_time' => 0, 'db_queries' => 0];
            $data['security'] = ['failed_logins' => 0, 'security_score' => 10, 'threats_blocked' => 0];
            $data['health_report'] = null;
        }
        
        $this->load->view('templates/header');
        $this->load->view('analytics/dashboard', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * System health API endpoint
     */
    public function health() {
        $health_report = $this->analytics->generate_health_report();
        $this->json_response($health_report);
    }
    
    /**
     * Performance metrics API
     */
    public function performance() {
        $metrics = $this->analytics->get_performance_metrics();
        $this->json_response($metrics);
    }
    
    /**
     * Security metrics API
     */
    public function security() {
        $metrics = $this->analytics->get_security_metrics();
        $this->json_response($metrics);
    }
}
