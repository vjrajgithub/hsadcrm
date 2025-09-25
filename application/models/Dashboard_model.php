<?php

class Dashboard_model extends CI_Model {

  public function get_summary() {
    return $this->db->select('COUNT(id) as total_invoices, SUM(total) as total_sales, SUM(total_gst) as total_gst')
                    ->get('invoices')->row();
  }

  public function get_monthly_sales() {
    return $this->db->query("
            SELECT DATE_FORMAT(invoice_date, '%b %Y') AS month, SUM(total) as total_amount
            FROM invoices
            GROUP BY DATE_FORMAT(invoice_date, '%Y-%m')
            ORDER BY invoice_date DESC LIMIT 6
        ")->result_array();
  }

  public function get_product_wise_invoice_count() {
    return $this->db->query("
            SELECT p.name as product_name, COUNT(ii.product_id) as count
            FROM invoice_items ii
            JOIN products p ON ii.product_id = p.id
            GROUP BY ii.product_id
        ")->result_array();
  }

  public function get_buyer_totals() {
    return $this->db->query("
            SELECT b.name, SUM(i.total) as total
            FROM invoices i
            JOIN buyers b ON i.buyer_id = b.id
            GROUP BY i.buyer_id
        ")->result_array();
  }

  public function get_recent_invoices() {
    return $this->db->query("
            SELECT i.*, b.name
            FROM invoices i
            JOIN buyers b ON i.buyer_id = b.id
            ORDER BY i.invoice_date DESC LIMIT 5
        ")->result();
  }

  public function count_table($table) {
    return $this->db->count_all($table);
  }

  public function get_user_roles_distribution() {
    $this->db->select('role, COUNT(*) as total');
    $this->db->group_by('role');
    return $this->db->get('users')->result();
  }

  public function get_clients_per_company() {
    $this->db->select('companies.name as company_name, COUNT(clients.id) as total_clients');
    $this->db->join('companies', 'companies.id = clients.company_id', 'left');
    $this->db->group_by('clients.company_id');
    return $this->db->get('clients')->result();
  }

  public function get_users_by_role() {
    $this->db->select('role, COUNT(*) as total');
    $this->db->group_by('role');
    $query = $this->db->get('users');
    return $query->result();
  }

  public function get_clients_by_company() {
    $this->db->select('c.name as company_name, COUNT(cl.id) as total');
    $this->db->from('companies c');
    $this->db->join('clients cl', 'cl.company_id = c.id', 'left');
    $this->db->group_by('c.id');
    $query = $this->db->get();
    return $query->result();
  }

}
