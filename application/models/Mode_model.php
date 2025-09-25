<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mode_model extends CI_Model {

  public function get_all() {
    $this->db->order_by('id', 'DESC');
    return $this->db->get('modes')->result();
  }

  public function get($id) {
    return $this->db->get_where('modes', ['id' => $id])->row();
  }

  public function insert($data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    return $this->db->insert('modes', $data);
  }

  public function update($id, $data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    $this->db->where('id', $id);
    return $this->db->update('modes', $data);
  }

  public function delete($id) {
    return $this->db->delete('modes', ['id' => $id]);
  }

//  public function all() {
//    return $this->db->get('modes')->result();
//  }

  public function all() {
    return $this->db->get('modes')->result();
  }

  // Server-side DataTable methods
  public function get_datatables_data($start, $length, $search_value, $order_column, $order_dir) {
    $this->db->from('modes');
    
    // Search functionality
    if (!empty($search_value)) {
      $this->db->group_start();
      $this->db->like('name', $search_value);
      $this->db->or_like('days', $search_value);
      $this->db->group_end();
    }
    
    // Ordering
    if (!empty($order_column)) {
      $this->db->order_by($order_column, $order_dir);
    } else {
      $this->db->order_by('id', 'DESC');
    }
    
    // Pagination
    if ($length != -1) {
      $this->db->limit($length, $start);
    }
    
    return $this->db->get()->result();
  }

  public function count_all() {
    return $this->db->count_all('modes');
  }

  public function count_filtered($search_value) {
    $this->db->from('modes');
    
    if (!empty($search_value)) {
      $this->db->group_start();
      $this->db->like('name', $search_value);
      $this->db->or_like('days', $search_value);
      $this->db->group_end();
    }
    
    return $this->db->count_all_results();
  }

}
