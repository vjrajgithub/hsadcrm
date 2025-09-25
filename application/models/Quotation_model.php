<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_model extends CI_Model {

  public function __construct() {
    parent::__construct();
  }

  /**
   * Insert Main Quotation
   */
  public function insert($data) {
    $this->db->insert('quotations', $data);
    return $this->db->insert_id();
  }

  /**
   * Insert Items (Batch)
   */
  public function insert_items($quotation_id, $items) {
    foreach ($items as $item) {
      $qty = isset($item['qty']) ? (int) $item['qty'] : 0;
      $rate = isset($item['rate']) ? (float) $item['rate'] : 0;
      $discount = isset($item['discount']) ? (float) $item['discount'] : 0;

      // Calculate net amount (before GST)
      $amount = $qty * $rate;
      if ($discount > 0) {
        $amount -= ($amount * $discount / 100);
      }

      $data = [
          'quotation_id' => $quotation_id,
          'category_id' => $item['category_id'],
          'product_id' => $item['product_id'],
          'qty' => $qty,
          'rate' => $rate,
          'discount' => $discount,
          'amount' => $amount
      ];

      $this->db->insert('quotation_items', $data);
    }
  }

  public function get_all_with_details() {
    $this->db->select('q.*,
        c.name as company_name,
        cl.name as client_name,
        cl.email as client_email,
        b.name as bank_name,
        m.name as mode_name');
    $this->db->from('quotations q');
    $this->db->join('companies c', 'q.company_id = c.id', 'left');
    $this->db->join('clients cl', 'q.client_id = cl.id', 'left');
    $this->db->join('banks b', 'q.bank_id = b.id', 'left');
    $this->db->join('modes m', 'q.mode_id = m.id', 'left');
    $this->db->order_by('q.id', 'DESC');
    return $this->db->get()->result();
  }

//  public function insert_items($quotationId, $items) {
//    if (empty($items))
//      return false;
//
//    $batch = [];
//    foreach ($items as $item) {
//      $batch[] = [
//          'quotation_id' => $quotationId,
//          'category_id' => $item['category_id'],
//          'product_id' => $item['product_id'],
//          'qty' => $item['qty'],
//          'rate' => $item['rate'],
//          'discount' => $item['discount'],
////          'gst' => $item['gst'],
////          'amount' => $item['amount']
//      ];
//    }
//
//    return $this->db->insert_batch('quotation_items', $batch);
//  }

  /**
   * Get Indian States for Dropdown
   */
  public function get_states() {
    return [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
        'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
        'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana',
        'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Andaman & Nicobar Islands',
        'Chandigarh', 'Dadra & Nagar Haveli and Daman & Diu', 'Delhi', 'Jammu & Kashmir',
        'Ladakh', 'Lakshadweep', 'Puducherry'
    ];
  }

  /**
   * Get Quotation By ID (With Items)
   */
  public function get_by_id($id) {
    $this->db->select('q.*, c.name as company_name, cl.name as client_name, b.bank_name, m.mode_name');
    $this->db->from('quotations q');
    $this->db->join('companies c', 'c.id = q.company_id', 'left');
    $this->db->join('clients cl', 'cl.id = q.client_id', 'left');
    $this->db->join('banks b', 'b.id = q.bank_id', 'left');
    $this->db->join('modes m', 'm.id = q.mode_id', 'left');
    $this->db->where('q.id', $id);
    $quotation = $this->db->get()->row();

    // Items
    $this->db->select('qi.*, pc.name as category_name, p.name as product_name');
    $this->db->from('quotation_items qi');
    $this->db->join('product_service_categories pc', 'pc.id = qi.category_id', 'left');
    $this->db->join('products p', 'p.id = qi.product_id', 'left');
    $this->db->where('qi.quotation_id', $id);
    $quotation->items = $this->db->get()->result();

    return $quotation;
  }

  /**
   * Update Quotation (For Edit Feature)
   */
  public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('quotations', $data);
  }

  /**
   * Delete Items and Re-Insert (When Updating)
   */
  public function delete_items($quotationId) {
    $this->db->where('quotation_id', $quotationId)->delete('quotation_items');
  }

  /**
   * Insert a new quotation (master + items)
   */
  public function save_quotation($data, $items) {
    // Insert master quotation record
    $this->db->insert('quotations', $data);
    $quotation_id = $this->db->insert_id();

    // Insert items
    if (!empty($items)) {
      foreach ($items as &$item) {
        $item['quotation_id'] = $quotation_id;
      }
      $this->db->insert_batch('quotation_items', $items);
    }

    return $quotation_id;
  }

  /**
   * Update an existing quotation (master + items)
   */
  public function update_quotation($quotation_id, $data, $items) {
    // Update master record
    $this->db->where('id', $quotation_id)->update('quotations', $data);

    // Delete old items
    $this->db->where('quotation_id', $quotation_id)->delete('quotation_items');

    // Insert new items
    if (!empty($items)) {
      foreach ($items as &$item) {
        $item['quotation_id'] = $quotation_id;
      }
      $this->db->insert_batch('quotation_items', $items);
    }

    return true;
  }

  /**
   * Fetch a single quotation with items
   */
  public function get_quotation($id) {
    $quotation = $this->db->get_where('quotations', ['id' => $id])->row();
    if ($quotation) {
      $quotation->items = $this->db->get_where('quotation_items', ['quotation_id' => $id])->result();
    }
    return $quotation;
  }

  /**
   * Fetch all quotations
   */
//  public function get_all() {
//    return $this->db->order_by('id', 'DESC')->get('quotations')->result();
//  }
  public function get_all() {
    $this->db->select('q.*, c.name as company_name, cl.name as client_name,cl.email as client_email');
    $this->db->from('quotations q');
    $this->db->join('companies c', 'c.id = q.company_id', 'left');
    $this->db->join('clients cl', 'cl.id = q.client_id', 'left');
    $this->db->order_by('q.id', 'DESC');
    return $this->db->get()->result();
  }

  /**
   * Delete a quotation and its items
   */
  public function delete_quotation($id) {
    $this->db->where('id', $id)->delete('quotations');
    return true;
  }

  public function get($id) {
    return $this->db->where('id', $id)->get('quotations')->row();
  }

  public function delete($id) {
    // Delete items first
    $this->db->where('quotation_id', $id)->delete('quotation_items');
    return $this->db->where('id', $id)->delete('quotations');
  }

  public function duplicate($id) {
    // Get quotation
    $quotation = $this->get($id);
    if (!$quotation)
      return false;

    // Remove id and created_at, insert copy
    unset($quotation->id);
    $quotation->created_at = date('Y-m-d H:i:s');
    $this->db->insert('quotations', $quotation);
    $new_id = $this->db->insert_id();

    // Duplicate items
    $items = $this->db->where('quotation_id', $id)->get('quotation_items')->result();
    foreach ($items as $item) {
      unset($item->id);
      $item->quotation_id = $new_id;
      $this->db->insert('quotation_items', $item);
    }
    return $new_id;
  }

  public function log_mail($quotation_id, $to, $cc, $subject, $message) {
    $data = [
        'quotation_id' => $quotation_id,
        'to' => implode(',', $to),
        'cc' => implode(',', $cc),
        'subject' => $subject,
        'message' => $message,
        'sent_at' => date('Y-m-d H:i:s')
    ];
    $this->db->insert('quotation_mail_logs', $data);
  }

  public function get_with_details($id) {
    $this->db->select('q.*, c.name as company_name, c.state as company_state, cl.name as client_name, cl.email as client_email, b.name as bank_name, m.name as mode_name');
    $this->db->from('quotations q');
    $this->db->join('companies c', 'c.id = q.company_id', 'left');
    $this->db->join('clients cl', 'cl.id = q.client_id', 'left');
    $this->db->join('banks b', 'b.id = q.bank_id', 'left');
    $this->db->join('modes m', 'm.id = q.mode_id', 'left');
    $this->db->where('q.id', $id);
    return $this->db->get()->row();
  }

  public function get_items($quotation_id) {
    $this->db->select('qi.*, pc.name as category_name, p.name as product_name');
    $this->db->from('quotation_items qi');
    $this->db->join('product_service_categories pc', 'pc.id = qi.category_id', 'left');
    $this->db->join('products p', 'p.id = qi.product_id', 'left');
    $this->db->where('qi.quotation_id', $quotation_id);
    return $this->db->get()->result();
  }

  public function getQuotationWithDetails($id) {
    $this->db->select('q.*, c.name as client_name, c.address as client_address, c.email as client_email,
                       c.state as client_state, c.gst_no as client_gstin, c.pan_card as client_pan,
                       comp.name as company_name, comp.address as company_address, comp.email as company_email,
                       comp.state as company_state,comp.logo as company_logo, comp.gst_no as company_gstin, comp.pan_card as company_pan, comp.cin_no as company_cin,
                       b.name as bank_name, b.branch_address as bank_address, b.ac_no as bank_account, b.ifsc_code as bank_ifsc,
                       m.name as mode_name');
    $this->db->from('quotations q');
    $this->db->join('clients c', 'c.id = q.client_id', 'left');
    $this->db->join('companies comp', 'comp.id = q.company_id', 'left');
    $this->db->join('banks b', 'b.id = q.bank_id', 'left');
    $this->db->join('modes m', 'm.id = q.mode_id', 'left');
    $this->db->where('q.id', $id);
    return $this->db->get()->row();
  }

  public function getQuotationItems($quotation_id) {
    $this->db->select('qi.*, p.name as product_name, cat.name as category_name');
    $this->db->from('quotation_items qi');
    $this->db->join('products p', 'p.id = qi.product_id', 'left');
    $this->db->join('categories cat', 'cat.id = qi.category_id', 'left');
    $this->db->where('qi.quotation_id', $quotation_id);
    return $this->db->get()->result();
  }

}
