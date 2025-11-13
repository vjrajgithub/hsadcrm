<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_model extends CI_Model {

  public function __construct() {
    parent::__construct();
  }

  /**
   * Capture snapshot of company data
   */
  private function capture_company_snapshot($company_id) {
    if (empty($company_id))
      return null;

    $company = $this->db->get_where('companies', ['id' => $company_id])->row_array();
    if (!$company)
      return null;

    return json_encode([
        'id' => $company['id'],
        'name' => $company['name'],
        'address' => $company['address'] ?? '',
        'email' => $company['email'] ?? '',
        'mobile' => $company['mobile'] ?? '',
        'state' => $company['state'] ?? '',
        'gst_no' => $company['gst_no'] ?? '',
        'pan_card' => $company['pan_card'] ?? '',
        'cin_no' => $company['cin_no'] ?? '',
        'logo' => $company['logo'] ?? '',
        'captured_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Capture snapshot of client data
   */
  private function capture_client_snapshot($client_id) {
    if (empty($client_id))
      return null;

    $client = $this->db->get_where('clients', ['id' => $client_id])->row_array();
    if (!$client)
      return null;

    return json_encode([
        'id' => $client['id'],
        'name' => $client['name'],
        'address' => $client['address'] ?? '',
        'email' => $client['email'] ?? '',
        'mobile' => $client['mobile'] ?? '',
        'state' => $client['state'] ?? '',
        'gst_no' => $client['gst_no'] ?? '',
        'pan_card' => $client['pan_card'] ?? '',
        'captured_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Capture snapshot of bank data
   */
  private function capture_bank_snapshot($bank_id) {
    if (empty($bank_id))
      return null;

    $bank = $this->db->get_where('banks', ['id' => $bank_id])->row_array();
    if (!$bank)
      return null;

    return json_encode([
        'id' => $bank['id'],
        'name' => $bank['name'],
        'branch_address' => $bank['branch_address'] ?? '',
        'ac_no' => $bank['ac_no'] ?? '',
        'ifsc_code' => $bank['ifsc_code'] ?? '',
        'captured_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Capture snapshot of mode data
   */
  private function capture_mode_snapshot($mode_id) {
    if (empty($mode_id))
      return null;

    $mode = $this->db->get_where('modes', ['id' => $mode_id])->row_array();
    if (!$mode)
      return null;

    return json_encode([
        'id' => $mode['id'],
        'name' => $mode['name'],
        'captured_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Capture snapshot of category data
   */
  private function capture_category_snapshot($category_id) {
    if (empty($category_id))
      return null;

    $category = $this->db->get_where('product_service_categories', ['id' => $category_id])->row_array();
    if (!$category)
      return null;

    return json_encode([
        'id' => $category['id'],
        'name' => $category['name'],
        'captured_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Capture snapshot of product data
   */
  private function capture_product_snapshot($product_id) {
    if (empty($product_id))
      return null;

    $product = $this->db->get_where('products', ['id' => $product_id])->row_array();
    if (!$product)
      return null;

    return json_encode([
        'id' => $product['id'],
        'name' => $product['name'],
        'rate_per_unit' => $product['rate_per_unit'] ?? 0,
        'captured_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Insert Main Quotation with Snapshots
   */
  public function insert($data) {
    // Capture snapshots before inserting
    if (isset($data['company_id'])) {
      $data['company_snapshot'] = $this->capture_company_snapshot($data['company_id']);
    }
    if (isset($data['client_id'])) {
      $data['client_snapshot'] = $this->capture_client_snapshot($data['client_id']);
    }
    if (isset($data['bank_id'])) {
      $data['bank_snapshot'] = $this->capture_bank_snapshot($data['bank_id']);
    }
    if (isset($data['mode_id'])) {
      $data['mode_snapshot'] = $this->capture_mode_snapshot($data['mode_id']);
    }

    $this->db->insert('quotations', $data);
    return $this->db->insert_id();
  }

  /**
   * Insert Items (Batch) - Updated to handle checkbox functionality and snapshots
   */
  public function insert_items($quotation_id, $items) {
    foreach ($items as $item) {
      $qty = isset($item['qty']) ? (int) $item['qty'] : 0;
      $rate = isset($item['rate']) ? (float) $item['rate'] : 0;
      $discount = isset($item['discount']) ? (float) $item['discount'] : 0;
      $use_dropdown = isset($item['use_dropdown']) ? (int) $item['use_dropdown'] : 1;

      // Calculate net amount (before GST)
      $amount = $qty * $rate;
      if ($discount > 0) {
        $amount -= ($amount * $discount / 100);
      }

      $data = [
          'quotation_id' => $quotation_id,
          'use_dropdown' => $use_dropdown,
          'qty' => $qty,
          'rate' => $rate,
          'discount' => $discount,
          'amount' => $amount
      ];

      // Handle dropdown vs description mode
      if ($use_dropdown) {
        $data['category_id'] = isset($item['category_id']) ? $item['category_id'] : null;
        $data['product_id'] = isset($item['product_id']) ? $item['product_id'] : null;
        $data['description'] = null;
      } else {
        $data['category_id'] = null;
        $data['product_id'] = null;
        $data['description'] = isset($item['description']) ? $item['description'] : '';
      }

      // Capture snapshots defensively when IDs are present
      // Category snapshot
      if (!empty($data['category_id'])) {
        $data['category_snapshot'] = $this->capture_category_snapshot($data['category_id']);
      } else {
        $data['category_snapshot'] = null;
      }
      // Product snapshot
      if (!empty($data['product_id'])) {
        $data['product_snapshot'] = $this->capture_product_snapshot($data['product_id']);
      } else {
        $data['product_snapshot'] = null;
      }

      // Optional debug logging to trace missing IDs during item insert
      if ($use_dropdown && (empty($data['product_id']) || empty($data['category_id']))) {
        log_message('debug', 'Quotation_items insert missing IDs. QuotationID=' . $quotation_id . ' item=' . json_encode($item));
      }

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
   * Get Quotation By ID (With Items) - Uses snapshots when available
   */
  public function get_by_id($id) {
    $this->db->select('q.*, c.name as company_name, cl.name as client_name, b.name as bank_name, m.name as mode_name');
    $this->db->from('quotations q');
    $this->db->join('companies c', 'c.id = q.company_id', 'left');
    $this->db->join('clients cl', 'cl.id = q.client_id', 'left');
    $this->db->join('banks b', 'b.id = q.bank_id', 'left');
    $this->db->join('modes m', 'm.id = q.mode_id', 'left');
    $this->db->where('q.id', $id);
    $quotation = $this->db->get()->row();

    if ($quotation) {
      // Use snapshot data if master data is missing
      $quotation = $this->merge_snapshot_data($quotation);
    }

    // Items with snapshot support
    $this->db->select('qi.*, pc.name as category_name, p.name as product_name');
    $this->db->from('quotation_items qi');
    $this->db->join('product_service_categories pc', 'pc.id = qi.category_id', 'left');
    $this->db->join('products_services p', 'p.id = qi.product_id', 'left');
    $this->db->where('qi.quotation_id', $id);
    $items = $this->db->get()->result();

    // Merge item snapshots (by reference)
    foreach ($items as &$item) {
      $this->merge_item_snapshot_data($item);
    }
    unset($item); // Break reference
    $quotation->items = $items;

    return $quotation;
  }

  /**
   * Update Quotation (For Edit Feature) - Updates snapshots
   */
  public function update($id, $data) {
    // Capture updated snapshots
    if (isset($data['company_id'])) {
      $data['company_snapshot'] = $this->capture_company_snapshot($data['company_id']);
    }
    if (isset($data['client_id'])) {
      $data['client_snapshot'] = $this->capture_client_snapshot($data['client_id']);
    }
    if (isset($data['bank_id'])) {
      $data['bank_snapshot'] = $this->capture_bank_snapshot($data['bank_id']);
    }
    if (isset($data['mode_id'])) {
      $data['mode_snapshot'] = $this->capture_mode_snapshot($data['mode_id']);
    }

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

    // Insert items with snapshots and dropdown/description handling
    if (!empty($items)) {
      $this->insert_items($quotation_id, $items);
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

    // Insert new items with snapshots and dropdown/description handling
    if (!empty($items)) {
      $this->insert_items($quotation_id, $items);
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
    $this->db->join('products_services p', 'p.id = qi.product_id', 'left');
    $this->db->where('qi.quotation_id', $quotation_id);
    $items = $this->db->get()->result();

    // Merge snapshot data
    foreach ($items as &$item) {
      $this->merge_item_snapshot_data($item);
    }
    unset($item);

    return $items;
  }

  /**
   * Get quotation with full details - Uses snapshots when master data is deleted
   */
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
    $quotation = $this->db->get()->row();

    if ($quotation) {
      // Use snapshot data if master data is missing
      $quotation = $this->merge_snapshot_data($quotation);
    }

    return $quotation;
  }

  /**
   * Get quotation items with snapshot support
   */
  public function getQuotationItems($quotation_id) {
    $this->db->select('qi.*, p.name as product_name, psc.name as category_name');
    $this->db->from('quotation_items qi');
    $this->db->join('products_services p', 'p.id = qi.product_id', 'left');
    $this->db->join('product_service_categories psc', 'psc.id = qi.category_id', 'left');
    $this->db->where('qi.quotation_id', $quotation_id);
    $items = $this->db->get()->result();

    // Merge snapshot data for each item (by reference)
    foreach ($items as &$item) {
      $this->merge_item_snapshot_data($item);
    }
    unset($item); // Break reference

    return $items;
  }

  /**
   * Merge snapshot data with quotation object
   * Uses snapshot if master data is missing (deleted)
   */
  private function merge_snapshot_data($quotation) {
    // Company data
    if (empty($quotation->company_name) && !empty($quotation->company_snapshot)) {
      $company = json_decode($quotation->company_snapshot, true);
      if ($company) {
        $quotation->company_name = $company['name'] ?? '';
        $quotation->company_address = $company['address'] ?? '';
        $quotation->company_email = $company['email'] ?? '';
        $quotation->company_state = $company['state'] ?? '';
        $quotation->company_gstin = $company['gst_no'] ?? '';
        $quotation->company_pan = $company['pan_card'] ?? '';
        $quotation->company_cin = $company['cin_no'] ?? '';
        $quotation->company_logo = $company['logo'] ?? '';
        $quotation->_company_from_snapshot = true;
      }
    }

    // Client data
    if (empty($quotation->client_name) && !empty($quotation->client_snapshot)) {
      $client = json_decode($quotation->client_snapshot, true);
      if ($client) {
        $quotation->client_name = $client['name'] ?? '';
        $quotation->client_address = $client['address'] ?? '';
        $quotation->client_email = $client['email'] ?? '';
        $quotation->client_state = $client['state'] ?? '';
        $quotation->client_gstin = $client['gst_no'] ?? '';
        $quotation->client_pan = $client['pan_card'] ?? '';
        $quotation->_client_from_snapshot = true;
      }
    }

    // Bank data
    if (empty($quotation->bank_name) && !empty($quotation->bank_snapshot)) {
      $bank = json_decode($quotation->bank_snapshot, true);
      if ($bank) {
        $quotation->bank_name = $bank['name'] ?? '';
        $quotation->bank_address = $bank['branch_address'] ?? '';
        $quotation->bank_account = $bank['ac_no'] ?? '';
        $quotation->bank_ifsc = $bank['ifsc_code'] ?? '';
        $quotation->_bank_from_snapshot = true;
      }
    }

    // Mode data
    if (empty($quotation->mode_name) && !empty($quotation->mode_snapshot)) {
      $mode = json_decode($quotation->mode_snapshot, true);
      if ($mode) {
        $quotation->mode_name = $mode['name'] ?? '';
        $quotation->_mode_from_snapshot = true;
      }
    }

    return $quotation;
  }

  /**
   * Merge snapshot data for quotation items
   * Modifies the item object directly (pass by reference)
   */
  private function merge_item_snapshot_data(&$item) {
    // Category data - use snapshot if master data is missing
    if (empty($item->category_name) && !empty($item->category_snapshot)) {
      $category = json_decode($item->category_snapshot, true);
      if ($category && is_array($category)) {
        $item->category_name = $category['name'] ?? '';
        $item->_category_from_snapshot = true;
      }
    }

    // Product data - use snapshot if master data is missing
    if (empty($item->product_name) && !empty($item->product_snapshot)) {
      $product = json_decode($item->product_snapshot, true);
      if ($product && is_array($product)) {
        $item->product_name = $product['name'] ?? '';
        $item->_product_from_snapshot = true;
      }
    }

    // Handle description mode (manual entry instead of dropdown)
    if (isset($item->use_dropdown) && $item->use_dropdown == 0 && !empty($item->description)) {
      $item->product_name = $item->description;
      $item->category_name = 'Manual Entry';
      $item->_using_description = true;
    }
  }

}
