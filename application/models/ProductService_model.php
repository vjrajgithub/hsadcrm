<?php

class ProductService_model extends CI_Model {

  public function get_all_with_category() {
    $this->db->select('p.*, c.name as category');
    $this->db->from('products p');
    $this->db->join('product_service_categories c', 'c.id = p.category_id');
    $this->db->order_by('p.id', 'DESC');
    return $this->db->get()->result();
  }

  public function insert($data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    return $this->db->insert('products', $data);
  }

  public function update($id, $data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    $this->db->where('id', $id);
    return $this->db->update('products', $data);
  }

  public function get($id) {
    return $this->db->get_where('products', ['id' => $id])->row();
  }

  public function delete($id) {
    $this->db->delete('products', ['id' => $id]);
  }

  public function all() {
    return $this->db->get('products')->result();
  }

//  public function get_by_category($category_id) {
//    return $this->db->get_where('products', ['category_id' => $category_id])->result();
//  }

  public function get_by_category($category_id) {
    return $this->db
                    ->where('category_id', $category_id)
                    ->get('products')
                    ->result();
  }

}
