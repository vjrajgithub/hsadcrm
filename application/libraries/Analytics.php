<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Analytics and Monitoring Library
 * Provides system monitoring and analytics capabilities
 */
class Analytics {
    
    private $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }
    
    /**
     * Track user activity
     */
    public function track_activity($action, $details = []) {
        $activity_data = [
            'user_id' => $this->CI->session->userdata('user_id'),
            'action' => $action,
            'details' => json_encode($details),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Create activity log table if it doesn't exist
        $this->ensure_activity_table();
        
        return $this->CI->db->insert('user_activities', $activity_data);
    }
    
    /**
     * Get dashboard analytics
     */
    public function get_dashboard_stats() {
        $stats = [];
        
        // Total counts
        $stats['total_companies'] = $this->CI->db->count_all('companies');
        $stats['total_clients'] = $this->CI->db->count_all('clients');
        $stats['total_quotations'] = $this->CI->db->count_all('quotations');
        $stats['total_users'] = $this->CI->db->count_all('users');
        
        // Recent activity (last 30 days)
        $thirty_days_ago = date('Y-m-d', strtotime('-30 days'));
        
        $this->CI->db->where('created_at >=', $thirty_days_ago);
        $stats['recent_quotations'] = $this->CI->db->count_all_results('quotations');
        
        $this->CI->db->where('created_at >=', $thirty_days_ago);
        $stats['recent_clients'] = $this->CI->db->count_all_results('clients');
        
        // Monthly quotation trends
        $stats['monthly_quotations'] = $this->get_monthly_quotations();
        
        // Top clients by quotation count
        $stats['top_clients'] = $this->get_top_clients();
        
        return $stats;
    }
    
    /**
     * Get system performance metrics
     */
    public function get_performance_metrics() {
        $metrics = [];
        
        // Database performance
        $start_time = microtime(true);
        $this->CI->db->get('users', 1);
        $db_response_time = (microtime(true) - $start_time) * 1000;
        
        $metrics['database_response_time'] = round($db_response_time, 2);
        $metrics['database_status'] = $db_response_time < 100 ? 'good' : 'slow';
        
        // Memory usage
        $metrics['memory_usage'] = round(memory_get_usage(true) / 1024 / 1024, 2);
        $metrics['memory_peak'] = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
        
        // Cache performance (if available)
        if (class_exists('Cache_manager')) {
            $cache_start = microtime(true);
            $this->CI->cache_manager->get('test_performance_key');
            $cache_response_time = (microtime(true) - $cache_start) * 1000;
            
            $metrics['cache_response_time'] = round($cache_response_time, 2);
            $metrics['cache_status'] = $cache_response_time < 10 ? 'excellent' : 'good';
        }
        
        // Disk usage
        $metrics['disk_free'] = round(disk_free_space('.') / 1024 / 1024 / 1024, 2);
        $metrics['disk_total'] = round(disk_total_space('.') / 1024 / 1024 / 1024, 2);
        
        return $metrics;
    }
    
    /**
     * Get security metrics
     */
    public function get_security_metrics() {
        $metrics = [];
        
        // Failed login attempts (last 24 hours)
        $yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));
        
        if ($this->table_exists('security_logs')) {
            $this->CI->db->where('event_type', 'failed_login');
            $this->CI->db->where('created_at >=', $yesterday);
            $metrics['failed_logins_24h'] = $this->CI->db->count_all_results('security_logs');
            
            // Suspicious activities
            $this->CI->db->where_in('event_type', ['unauthorized_access', 'suspicious_activity']);
            $this->CI->db->where('created_at >=', $yesterday);
            $metrics['suspicious_activities_24h'] = $this->CI->db->count_all_results('security_logs');
        } else {
            $metrics['failed_logins_24h'] = 0;
            $metrics['suspicious_activities_24h'] = 0;
        }
        
        // Active sessions
        $metrics['active_sessions'] = $this->count_active_sessions();
        
        return $metrics;
    }
    
    /**
     * Get monthly quotation data for charts
     */
    private function get_monthly_quotations() {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count,
                    SUM(total_amount) as total_value
                FROM quotations 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    /**
     * Get top clients by quotation count
     */
    private function get_top_clients() {
        $sql = "SELECT 
                    c.name,
                    COUNT(q.id) as quotation_count,
                    SUM(q.total_amount) as total_value
                FROM clients c
                LEFT JOIN quotations q ON c.id = q.client_id
                GROUP BY c.id, c.name
                ORDER BY quotation_count DESC
                LIMIT 10";
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    /**
     * Count active sessions
     */
    private function count_active_sessions() {
        $session_path = APPPATH . 'cache/sessions/';
        
        if (!is_dir($session_path)) {
            return 0;
        }
        
        $files = glob($session_path . 'ci_session*');
        $active_count = 0;
        
        foreach ($files as $file) {
            if (filemtime($file) > (time() - 7200)) { // 2 hours
                $active_count++;
            }
        }
        
        return $active_count;
    }
    
    /**
     * Ensure activity table exists
     */
    private function ensure_activity_table() {
        if (!$this->table_exists('user_activities')) {
            $sql = "CREATE TABLE IF NOT EXISTS `user_activities` (
                `id` int NOT NULL AUTO_INCREMENT,
                `user_id` int DEFAULT NULL,
                `action` varchar(100) NOT NULL,
                `details` json DEFAULT NULL,
                `ip_address` varchar(45) NOT NULL,
                `user_agent` text,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_user_id` (`user_id`),
                KEY `idx_action` (`action`),
                KEY `idx_created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            $this->CI->db->query($sql);
        }
    }
    
    /**
     * Check if table exists
     */
    private function table_exists($table_name) {
        return $this->CI->db->table_exists($table_name);
    }
    
    /**
     * Generate system health report
     */
    public function generate_health_report() {
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'dashboard_stats' => $this->get_dashboard_stats(),
            'performance_metrics' => $this->get_performance_metrics(),
            'security_metrics' => $this->get_security_metrics()
        ];
        
        // Calculate overall health score
        $performance = $report['performance_metrics'];
        $security = $report['security_metrics'];
        
        $health_score = 100;
        
        // Deduct points for performance issues
        if ($performance['database_response_time'] > 100) {
            $health_score -= 10;
        }
        if ($performance['memory_usage'] > 128) {
            $health_score -= 5;
        }
        
        // Deduct points for security issues
        if ($security['failed_logins_24h'] > 10) {
            $health_score -= 15;
        }
        if ($security['suspicious_activities_24h'] > 0) {
            $health_score -= 20;
        }
        
        $report['health_score'] = max(0, $health_score);
        $report['health_status'] = $this->get_health_status($health_score);
        
        return $report;
    }
    
    /**
     * Get health status based on score
     */
    private function get_health_status($score) {
        if ($score >= 90) return 'excellent';
        if ($score >= 75) return 'good';
        if ($score >= 60) return 'fair';
        return 'poor';
    }
}
