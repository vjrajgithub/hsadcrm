<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

  public function get_all() {
    $this->db->order_by('id', 'DESC');
    return $this->db->get('categories')->result();
  }

  public function get($id) {
    return $this->db->get_where('categories', ['id' => $id])->row();
  }

  public function insert($data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    return $this->db->insert('categories', $data);
  }

  public function update($id, $data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    $this->db->where('id', $id);
    return $this->db->update('categories', $data);
  }

  public function delete($id) {
    return $this->db->delete('categories', ['id' => $id]);
  }

  public function all() {
    return $this->db->get('categories')->result();
  }

}
