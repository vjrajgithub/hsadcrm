<?php

class Product_model extends CI_Model {

  public function all() {
    return $this->db->get('products')->result();
  }

  public function get_product_stats() {
    return $this->db->query("
            SELECT p.name as product_name, COUNT(i.id) as count
            FROM invoice_items i
            JOIN products p ON p.id = i.product_id
            GROUP BY i.product_id
        ")->result();
  }

  public function get_all_categories() {
    return $this->db->get('product_service_categories')->result();
  }

}
