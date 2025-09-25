<?php

class Buyer_model extends CI_Model {

  public function all() {
    return $this->db->get('buyers')->result();
  }

  public function get($id) {
    return $this->db->get_where('buyers', ['id' => $id])->row();
  }

  public function get_buyer_totals() {
    return $this->db->query("
            SELECT b.name, SUM(i.grand_total) as total
            FROM invoices i
            JOIN buyers b ON b.id = i.buyer_id
            GROUP BY b.id
        ")->result();
  }

}
