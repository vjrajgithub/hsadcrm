<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Advanced Cache Manager Library
 * Provides multi-layer caching with Redis fallback to file cache
 */
class Cache_manager {
    
    private $CI;
    private $redis;
    private $file_cache_path;
    private $default_ttl = 3600; // 1 hour
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->file_cache_path = APPPATH . 'cache/data/';
        
        // Ensure cache directory exists
        if (!is_dir($this->file_cache_path)) {
            mkdir($this->file_cache_path, 0755, true);
        }
        
        // Try to connect to Redis
        $this->init_redis();
    }
    
    /**
     * Initialize Redis connection
     */
    private function init_redis() {
        if (class_exists('Redis')) {
            try {
                $this->redis = new Redis();
                $this->redis->connect('127.0.0.1', 6379);
                $this->redis->select(0); // Use database 0
            } catch (Exception $e) {
                $this->redis = null;
                log_message('info', 'Redis connection failed, falling back to file cache: ' . $e->getMessage());
            }
        }
    }
    
    /**
     * Get cached data
     */
    public function get($key) {
        $cache_key = $this->generate_key($key);
        
        // Try Redis first
        if ($this->redis) {
            try {
                $data = $this->redis->get($cache_key);
                if ($data !== false) {
                    return json_decode($data, true);
                }
            } catch (Exception $e) {
                log_message('error', 'Redis get error: ' . $e->getMessage());
            }
        }
        
        // Fallback to file cache
        return $this->get_file_cache($cache_key);
    }
    
    /**
     * Set cache data
     */
    public function set($key, $data, $ttl = null) {
        $cache_key = $this->generate_key($key);
        $ttl = $ttl ?? $this->default_ttl;
        $json_data = json_encode($data);
        
        // Try Redis first
        if ($this->redis) {
            try {
                return $this->redis->setex($cache_key, $ttl, $json_data);
            } catch (Exception $e) {
                log_message('error', 'Redis set error: ' . $e->getMessage());
            }
        }
        
        // Fallback to file cache
        return $this->set_file_cache($cache_key, $data, $ttl);
    }
    
    /**
     * Delete cached data
     */
    public function delete($key) {
        $cache_key = $this->generate_key($key);
        
        // Delete from Redis
        if ($this->redis) {
            try {
                $this->redis->del($cache_key);
            } catch (Exception $e) {
                log_message('error', 'Redis delete error: ' . $e->getMessage());
            }
        }
        
        // Delete from file cache
        $this->delete_file_cache($cache_key);
        
        return true;
    }
    
    /**
     * Clear all cache
     */
    public function flush() {
        // Clear Redis
        if ($this->redis) {
            try {
                $this->redis->flushDB();
            } catch (Exception $e) {
                log_message('error', 'Redis flush error: ' . $e->getMessage());
            }
        }
        
        // Clear file cache
        $this->clear_file_cache();
        
        return true;
    }
    
    /**
     * Cache database query results
     */
    public function cache_query($key, $query, $ttl = null) {
        $cached_result = $this->get($key);
        
        if ($cached_result !== null) {
            return $cached_result;
        }
        
        // Execute query and cache result
        $result = $this->CI->db->query($query)->result_array();
        $this->set($key, $result, $ttl);
        
        return $result;
    }
    
    /**
     * Cache model method results
     */
    public function cache_model($model, $method, $params = [], $ttl = null) {
        $cache_key = $model . '_' . $method . '_' . md5(serialize($params));
        $cached_result = $this->get($cache_key);
        
        if ($cached_result !== null) {
            return $cached_result;
        }
        
        // Load model and call method
        $this->CI->load->model($model);
        $result = call_user_func_array([$this->CI->$model, $method], $params);
        
        $this->set($cache_key, $result, $ttl);
        
        return $result;
    }
    
    /**
     * Generate cache key
     */
    private function generate_key($key) {
        return 'crm_' . md5($key);
    }
    
    /**
     * Get data from file cache
     */
    private function get_file_cache($key) {
        $file_path = $this->file_cache_path . $key . '.cache';
        
        if (!file_exists($file_path)) {
            return null;
        }
        
        $cache_data = file_get_contents($file_path);
        $cache_array = unserialize($cache_data);
        
        // Check if cache has expired
        if ($cache_array['expires'] < time()) {
            unlink($file_path);
            return null;
        }
        
        return $cache_array['data'];
    }
    
    /**
     * Set data to file cache
     */
    private function set_file_cache($key, $data, $ttl) {
        $file_path = $this->file_cache_path . $key . '.cache';
        
        $cache_array = [
            'data' => $data,
            'expires' => time() + $ttl
        ];
        
        return file_put_contents($file_path, serialize($cache_array), LOCK_EX) !== false;
    }
    
    /**
     * Delete file cache
     */
    private function delete_file_cache($key) {
        $file_path = $this->file_cache_path . $key . '.cache';
        
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    /**
     * Clear all file cache
     */
    private function clear_file_cache() {
        $files = glob($this->file_cache_path . '*.cache');
        
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
