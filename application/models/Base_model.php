<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enhanced Base Model with Security and Performance Features
 * Provides secure database operations with query optimization
 */
class Base_model extends CI_Model {
    
    protected $table;
    protected $primary_key = 'id';
    protected $fillable = [];
    protected $hidden = ['password'];
    protected $timestamps = true;
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Secure get all records with pagination
     */
    public function get_all($limit = null, $offset = 0, $order_by = null) {
        if ($order_by) {
            $this->db->order_by($order_by);
        } else {
            $this->db->order_by($this->primary_key, 'DESC');
        }
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get($this->table);
        return $this->hide_sensitive_data($query->result());
    }
    
    /**
     * Secure get by ID with input validation
     */
    public function get_by_id($id) {
        if (!is_numeric($id) || $id <= 0) {
            return false;
        }
        
        $query = $this->db->get_where($this->table, [$this->primary_key => (int)$id]);
        $result = $query->row();
        
        return $result ? $this->hide_sensitive_data([$result])[0] : false;
    }
    
    /**
     * Secure insert with data validation
     */
    public function insert($data) {
        $data = $this->filter_fillable($data);
        $data = $this->prepare_timestamps($data, 'insert');
        
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        
        return false;
    }
    
    /**
     * Secure update with data validation
     */
    public function update($id, $data) {
        if (!is_numeric($id) || $id <= 0) {
            return false;
        }
        
        $data = $this->filter_fillable($data);
        $data = $this->prepare_timestamps($data, 'update');
        
        return $this->db->update($this->table, $data, [$this->primary_key => (int)$id]);
    }
    
    /**
     * Secure delete with soft delete support
     */
    public function delete($id) {
        if (!is_numeric($id) || $id <= 0) {
            return false;
        }
        
        // Check if soft delete is enabled
        if ($this->has_column('deleted_at')) {
            return $this->db->update($this->table, 
                ['deleted_at' => date('Y-m-d H:i:s')], 
                [$this->primary_key => (int)$id]
            );
        }
        
        return $this->db->delete($this->table, [$this->primary_key => (int)$id]);
    }
    
    /**
     * Secure search with SQL injection protection
     */
    public function search($field, $value, $operator = '=') {
        $allowed_operators = ['=', '!=', '>', '<', '>=', '<=', 'LIKE', 'NOT LIKE'];
        
        if (!in_array(strtoupper($operator), $allowed_operators)) {
            $operator = '=';
        }
        
        if ($operator === 'LIKE' || $operator === 'NOT LIKE') {
            $value = '%' . $this->db->escape_like_str($value) . '%';
        }
        
        $this->db->where($field . ' ' . $operator, $value);
        $query = $this->db->get($this->table);
        
        return $this->hide_sensitive_data($query->result());
    }
    
    /**
     * Get records count for pagination
     */
    public function count_all($where = null) {
        if ($where) {
            $this->db->where($where);
        }
        
        // Exclude soft deleted records
        if ($this->has_column('deleted_at')) {
            $this->db->where('deleted_at IS NULL');
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    /**
     * Batch insert with transaction support
     */
    public function batch_insert($data) {
        if (empty($data)) {
            return false;
        }
        
        $this->db->trans_start();
        
        foreach ($data as &$row) {
            $row = $this->filter_fillable($row);
            $row = $this->prepare_timestamps($row, 'insert');
        }
        
        $result = $this->db->insert_batch($this->table, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }
    
    /**
     * Filter data based on fillable fields
     */
    protected function filter_fillable($data) {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Hide sensitive data from results
     */
    protected function hide_sensitive_data($results) {
        if (empty($this->hidden) || empty($results)) {
            return $results;
        }
        
        foreach ($results as &$result) {
            if (is_object($result)) {
                foreach ($this->hidden as $field) {
                    unset($result->$field);
                }
            } elseif (is_array($result)) {
                foreach ($this->hidden as $field) {
                    unset($result[$field]);
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Prepare timestamp fields
     */
    protected function prepare_timestamps($data, $operation) {
        if (!$this->timestamps) {
            return $data;
        }
        
        $now = date('Y-m-d H:i:s');
        
        if ($operation === 'insert') {
            if ($this->has_column('created_at')) {
                $data['created_at'] = $now;
            }
            if ($this->has_column('updated_at')) {
                $data['updated_at'] = $now;
            }
        } elseif ($operation === 'update') {
            if ($this->has_column('updated_at')) {
                $data['updated_at'] = $now;
            }
        }
        
        return $data;
    }
    
    /**
     * Check if table has specific column
     */
    protected function has_column($column) {
        $fields = $this->db->list_fields($this->table);
        return in_array($column, $fields);
    }
    
    /**
     * Execute raw query with parameter binding
     */
    protected function raw_query($sql, $params = []) {
        return $this->db->query($sql, $params);
    }
}
