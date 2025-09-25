<?php

class Invoice_model extends CI_Model {

  public function get_all($filters = []) {
    $this->db->select('i.*, b.name as buyer_name');
    $this->db->from('invoices i');
    $this->db->join('buyers b', 'b.id = i.buyer_id', 'left');

    if (!empty($filters['from'])) {
      $this->db->where('i.invoice_date >=', $filters['from']);
    }
    if (!empty($filters['to'])) {
      $this->db->where('i.invoice_date <=', $filters['to']);
    }
    if (!empty($filters['buyer_id'])) {
      $this->db->where('i.buyer_id', $filters['buyer_id']);
    }

    return $this->db->order_by('i.invoice_date', 'DESC')->get()->result();
  }

  public function get($id) {
    return $this->db->get_where('invoices', ['id' => $id])->row();
  }

  public function get_items($invoice_id) {
    return $this->db->get_where('invoice_items', ['invoice_id' => $invoice_id])->result();
  }

  public function insert($invoice_data, $items) {
    $this->db->insert('invoices', $invoice_data);
    $invoice_id = $this->db->insert_id();

    foreach ($items as &$item)
      $item['invoice_id'] = $invoice_id;

    $this->db->insert_batch('invoice_items', $items);
    return $invoice_id;
  }

  public function update($id, $invoice_data, $items) {
    $this->db->where('id', $id)->update('invoices', $invoice_data);
    $this->db->delete('invoice_items', ['invoice_id' => $id]);

    foreach ($items as &$item)
      $item['invoice_id'] = $id;
    $this->db->insert_batch('invoice_items', $items);
  }

  public function delete($id) {
    $this->db->delete('invoice_items', ['invoice_id' => $id]);
    $this->db->delete('invoices', ['id' => $id]);
  }

}
