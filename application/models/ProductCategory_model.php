<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductCategory_model extends CI_Model {

  public function get_all() {
    $this->db->order_by('id', 'DESC');
    return $this->db->get('product_service_categories')->result();
  }

  public function get($id) {
    return $this->db->get_where('product_service_categories', ['id' => $id])->row();
  }

  public function insert($data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    return $this->db->insert('product_service_categories', $data);
  }

  public function update($id, $data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    $this->db->where('id', $id);
    return $this->db->update('product_service_categories', $data);
  }

  public function delete($id) {
    return $this->db->delete('product_service_categories', ['id' => $id]);
  }

  public function all() {
    return $this->db->get('product_service_categories')->result(); // or 'categories' if that's your table
  }

}
