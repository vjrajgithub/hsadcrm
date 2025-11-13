<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();

    // Load models for quotation management
    $this->load->model('Quotation_model');
    $this->load->model('Company_model');
    $this->load->model('Client_model');
    $this->load->model('Bank_model');
    $this->load->model('Mode_model');
    $this->load->model('Category_model'); // Client categories used as departments
    $this->load->model('Product_model');
    $this->load->model('ProductCategory_model');
    $this->load->model('ProductService_model');
    $this->load->library('form_validation');
    $this->load->helper(['form', 'url']);
  }

  /**
   * Build estimate number like GIIR-EST-25-26-0450 based on FY and ID
   */
  private function format_estimate_no($id, $date_time) {
    $ts = strtotime($date_time);
    $year = (int) date('Y', $ts);
    $month = (int) date('n', $ts);
    if ($month >= 4) {
      $start = $year;
      $end = $year + 1;
    } else {
      $start = $year - 1;
      $end = $year;
    }
    $fy = substr((string) $start, -2) . '-' . substr((string) $end, -2);
    $seq = str_pad((string) $id, 4, '0', STR_PAD_LEFT);
    return 'GIIR-EST-' . $fy . '-' . $seq;
  }

  /**
   * Quotation List View
   */
  public function index() {
    $this->load->view('templates/header');
    $this->load->view('quotation/index');
    $this->load->view('templates/footer');
  }

  /**
   * AJAX List for DataTable
   */
  public function list() {
    $quotations = $this->Quotation_model->get_all_with_details();
    // Attach formatted estimate number for each row
    foreach ($quotations as &$q) {
      $q->estimate_no = $this->format_estimate_no($q->id, $q->created_at ?? date('Y-m-d H:i:s'));
    }
    echo json_encode(['data' => $quotations]);
  }

  /**
   * Quotation Create Form
   */
  public function create() {
    $data['companies'] = $this->Company_model->get_all();
    $data['clients'] = $this->Client_model->get_all();
    $data['banks'] = $this->Bank_model->get_all();
    $data['modes'] = $this->Mode_model->get_all();
    $data['product_categories'] = $this->ProductCategory_model->get_all();
    $data['states'] = $this->Company_model->get_states();
    $data['departments'] = $this->Category_model->get_all();

    $this->load->view('templates/header');
    $this->load->view('quotation/form', $data);
    $this->load->view('templates/footer');
  }

  /**
   * Store Quotation
   */
  public function store() {
    // Enhanced validation rules matching frontend validation
    $this->form_validation->set_rules('company_id', 'Company', 'required');
    $this->form_validation->set_rules('client_id', 'Client', 'required');
    $this->form_validation->set_rules('bank_id', 'Bank', 'required');
    $this->form_validation->set_rules('contact_person', 'Contact Person', 'required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('department', 'Department', 'required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('state', 'Place of Supply', 'required');
    $this->form_validation->set_rules('mode_id', 'Mode', 'required');
    $this->form_validation->set_rules('hsn_sac', 'HSN/SAC Code', 'max_length[20]|alpha_numeric');
    $this->form_validation->set_rules('job_no', 'Job No', 'trim|max_length[128]');
    $this->form_validation->set_rules('terms', 'Terms & Conditions', 'max_length[1000]');
    $this->form_validation->set_rules('notes', 'Notes', 'max_length[1000]');

    // Custom error messages
    $this->form_validation->set_message('required', 'The {field} field is required.');
    $this->form_validation->set_message('min_length', 'The {field} must be at least {param} characters long.');
    $this->form_validation->set_message('max_length', 'The {field} cannot exceed {param} characters.');
    $this->form_validation->set_message('alpha_numeric', 'The {field} must contain only letters and numbers.');

    if ($this->form_validation->run() == false) {
      echo json_encode([
          'status' => false,
          'message' => strip_tags(validation_errors())
      ]);
      return;
    }

    // Items validation and preparation
    $items = $this->_prepare_items();
    if (empty($items)) {
      echo json_encode([
          'status' => false,
          'message' => 'Please add at least one item.'
      ]);
      return;
    }
    
    // Validate each item based on use_dropdown setting
    foreach ($items as $item) {
      if ($item['use_dropdown']) {
        if (empty($item['category_id']) || empty($item['product_id'])) {
          echo json_encode([
              'status' => false,
              'message' => 'Please select category and product for dropdown items.'
          ]);
          return;
        }
      } else {
        if (empty($item['description'])) {
          echo json_encode([
              'status' => false,
              'message' => 'Please provide description for custom items.'
          ]);
          return;
        }
      }
    }

    // File upload (optional)
    $uploaded_file = null;
    if (!empty($_FILES['attachment']['name'])) {
      $config['upload_path'] = './assets/uploads/quotations/';
      $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
      $config['encrypt_name'] = true;

      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('attachment')) {
        echo json_encode([
            'status' => false,
            'message' => $this->upload->display_errors()
        ]);
        return;
      } else {
        $uploaded_file = $this->upload->data('file_name');
      }
    }

    // Calculate total amount (sum of item amounts)
    $total = 0;
    foreach ($items as $item) {
      $total += $item['amount'];
    }

    // GST Calculation
    $company_state = $this->Company_model->get_state($this->input->post('company_id'));
    $place_of_supply = $this->input->post('state');

    $gst_amount = 0;
    $gst_type = null;
    if ($company_state && $place_of_supply) {
      if ($company_state == $place_of_supply) {
        $gst_type = 'CGST+SGST';
        $gst_amount = ($total * 18) / 100; // Example: 18% GST
      } else {
        $gst_type = 'IGST';
        $gst_amount = ($total * 18) / 100;
      }
    }

    // Final total
    $grand_total = $total + $gst_amount;

    // Insert into quotations table
    $data = [
        'company_id' => $this->input->post('company_id'),
        'client_id' => $this->input->post('client_id'),
        'bank_id' => $this->input->post('bank_id'),
        'contact_person' => $this->input->post('contact_person'),
        'department' => $this->input->post('department'),
        'state' => $place_of_supply,
        'mode_id' => $this->input->post('mode_id'),
        'hsn_sac' => $this->input->post('hsn_sac'),
        'job_no' => $this->input->post('job_no'),
        'terms' => $this->input->post('terms'),
        'notes' => $this->input->post('notes'),
        'attachment' => $uploaded_file,
        'total_amount' => $grand_total,
        'gst_type' => $gst_type,
        'gst_amount' => $gst_amount,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Use model's insert method to capture snapshots
    $quotation_id = $this->Quotation_model->insert($data);

    // Insert items with snapshot capture
    $this->Quotation_model->insert_items($quotation_id, $items);

    // Success response
    echo json_encode([
        'status' => true,
        'message' => 'Quotation saved successfully!'
    ]);
  }

//  public function store() {
////    echo '<pre>';
////    print_r($this->input->post());
////    die;
//    $this->_validate_form();
//
//    // Handle file upload (if any)
//    $attachment = null;
//    if (!empty($_FILES['attachment']['name'])) {
//      $config['upload_path'] = './assets/uploads/quotations';
//      $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
//      $config['encrypt_name'] = TRUE;
//
//      $this->load->library('upload', $config);
//      if (!$this->upload->do_upload('attachment')) {
//        echo json_encode(['status' => false, 'message' => $this->upload->display_errors()]);
//        return;
//      } else {
//        $attachment = $this->upload->data('file_name');
//      }
//    }
//
//    // Master data
//    $quotation_data = [
//        'company_id' => $this->input->post('company_id'),
//        'client_id' => $this->input->post('client_id'),
//        'bank_id' => $this->input->post('bank_id'),
//        'contact_person' => $this->input->post('contact_person'),
//        'department' => $this->input->post('department'),
//        'state' => $this->input->post('state'),
//        'mode_id' => $this->input->post('mode_id'),
//        'hsn_sac' => $this->input->post('hsn_sac'),
//        'terms' => $this->input->post('terms'),
//        'notes' => $this->input->post('notes'),
//        'attachment' => $attachment,
//        'total_amount' => $this->input->post('total_amount'), // now will not be null
//        'gst_type' => $this->input->post('gst_type'),
//        'gst_amount' => $this->input->post('gst_amount'),
//        'created_at' => date('Y-m-d H:i:s')
//    ];
//
//    if (empty($quotation_data['total_amount'])) {
//      echo json_encode(['status' => false, 'message' => 'Total amount could not be calculated.']);
//      exit;
//    }
//
//    // Items
//    $items = $this->_prepare_items();
//
//    // Save to DB
//    $quotation_id = $this->Quotation_model->save_quotation($quotation_data, $items);
//
//    echo json_encode(['status' => true, 'id' => $quotation_id]);
//  }

  /**
   * Edit Quotation Form
   */
  public function edit($id) {
    $data['quotation'] = $this->Quotation_model->get_quotation($id);
    $data['companies'] = $this->Company_model->get_all();
    $data['clients'] = $this->Client_model->get_all();
    $data['banks'] = $this->Bank_model->get_all();
    $data['modes'] = $this->Mode_model->get_all();
    $data['product_categories'] = $this->ProductCategory_model->get_all();
    $data['states'] = $this->Company_model->get_states();
    $data['departments'] = $this->Category_model->get_all();

    // Get all products for each category to populate dropdowns
    $data['all_products'] = [];
    foreach ($data['product_categories'] as $category) {
      $data['all_products'][$category->id] = $this->ProductService_model->get_by_category($category->id);
    }

    $this->load->view('templates/header');
    $this->load->view('quotation/edit', $data);
    $this->load->view('templates/footer');
  }

  /**
   * Update Quotation - Enhanced for checkbox functionality
   */
  public function update($id) {
    // Enhanced validation rules matching frontend validation
    $this->form_validation->set_rules('company_id', 'Company', 'required');
    $this->form_validation->set_rules('client_id', 'Client', 'required');
    $this->form_validation->set_rules('bank_id', 'Bank', 'required');
    $this->form_validation->set_rules('contact_person', 'Contact Person', 'required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('department', 'Department', 'required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('state', 'Place of Supply', 'required');
    $this->form_validation->set_rules('mode_id', 'Mode', 'required');
    $this->form_validation->set_rules('hsn_sac', 'HSN/SAC Code', 'max_length[20]|alpha_numeric');
    $this->form_validation->set_rules('job_no', 'Job No', 'trim|max_length[128]');
    $this->form_validation->set_rules('terms', 'Terms & Conditions', 'max_length[1000]');
    $this->form_validation->set_rules('notes', 'Notes', 'max_length[1000]');

    if ($this->form_validation->run() == false) {
      echo json_encode([
          'status' => false,
          'message' => strip_tags(validation_errors())
      ]);
      return;
    }

    // Items validation and preparation
    $items = $this->_prepare_items();
    if (empty($items)) {
      echo json_encode([
          'status' => false,
          'message' => 'Please add at least one item.'
      ]);
      return;
    }
    
    // Validate each item based on use_dropdown setting
    foreach ($items as $item) {
      if ($item['use_dropdown']) {
        if (empty($item['category_id']) || empty($item['product_id'])) {
          echo json_encode([
              'status' => false,
              'message' => 'Please select category and product for dropdown items.'
          ]);
          return;
        }
      } else {
        if (empty($item['description'])) {
          echo json_encode([
              'status' => false,
              'message' => 'Please provide description for custom items.'
          ]);
          return;
        }
      }
    }

    // Handle file upload (if any)
    $attachment = $this->input->post('old_attachment');
    if (!empty($_FILES['attachment']['name'])) {
      $config['upload_path'] = './assets/uploads/quotations/';
      $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
      $config['encrypt_name'] = true;

      $this->load->library('upload', $config);
      if (!$this->upload->do_upload('attachment')) {
        echo json_encode([
            'status' => false,
            'message' => $this->upload->display_errors()
        ]);
        return;
      } else {
        $attachment = $this->upload->data('file_name');
      }
    }

    // Calculate total amount (sum of item amounts)
    $total = 0;
    foreach ($items as $item) {
      $total += $item['amount'];
    }

    // GST Calculation
    $company_state = $this->Company_model->get_state($this->input->post('company_id'));
    $place_of_supply = $this->input->post('state');

    $gst_amount = 0;
    $gst_type = null;
    if ($company_state && $place_of_supply) {
      if ($company_state == $place_of_supply) {
        $gst_type = 'CGST+SGST';
        $gst_amount = ($total * 18) / 100; // Example: 18% GST
      } else {
        $gst_type = 'IGST';
        $gst_amount = ($total * 18) / 100;
      }
    }

    // Final total
    $grand_total = $total + $gst_amount;

    // Master data
    $quotation_data = [
        'company_id' => $this->input->post('company_id'),
        'client_id' => $this->input->post('client_id'),
        'bank_id' => $this->input->post('bank_id'),
        'contact_person' => $this->input->post('contact_person'),
        'department' => $this->input->post('department'),
        'state' => $place_of_supply,
        'mode_id' => $this->input->post('mode_id'),
        'hsn_sac' => $this->input->post('hsn_sac'),
        'job_no' => $this->input->post('job_no'),
        'terms' => $this->input->post('terms'),
        'notes' => $this->input->post('notes'),
        'attachment' => $attachment,
        'total_amount' => $grand_total,
        'gst_type' => $gst_type,
        'gst_amount' => $gst_amount,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Update DB
    $this->Quotation_model->update_quotation($id, $quotation_data, $items);

    echo json_encode([
        'status' => true,
        'message' => 'Quotation updated successfully!'
    ]);
  }

  /**
   * Delete quotation
   */
  public function delete($id) {
    try {
      $this->Quotation_model->delete_quotation($id);
      $this->session->set_flashdata('success', 'Quotation deleted successfully!');
      redirect('quotation');
    } catch (Exception $e) {
      $this->session->set_flashdata('error', 'Error deleting quotation: ' . $e->getMessage());
      redirect('quotation');
    }
  }

  /**
   * Load clients by company (AJAX)
   */
  public function get_clients_by_company($company_id) {
    $clients = $this->Client_model->get_by_company($company_id);
//    print_r($clients);
//    die;
    echo json_encode($clients);
  }

  /**
   * Load banks by company (AJAX)
   */
  public function get_banks_by_company($company_id) {
    $banks = $this->Bank_model->get_by_company($company_id);
    echo json_encode($banks);
  }

  /**
   * Load products by category (AJAX)
   */
  public function get_products_by_category($category_id) {
//    $products = $this->Product_model->get_by_category($category_id);

    $products = $this->ProductService_model->get_by_category($category_id);
    $data = [];
    foreach ($products as $p) {
      $data[] = [
          'id' => $p->id,
          'name' => $p->name,
          'rate_per_unit' => $p->rate_per_unit
      ];
    }
    echo json_encode($data);
  }

  /**
   * Validate form
   */
  private function _validate_form() {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('company_id', 'Company', 'required');
    $this->form_validation->set_rules('client_id', 'Client', 'required');
    $this->form_validation->set_rules('bank_id', 'Bank', 'required');
    $this->form_validation->set_rules('contact_person', 'Contact Person', 'required');
    $this->form_validation->set_rules('department', 'Department', 'required');
    $this->form_validation->set_rules('state', 'Place of Supply', 'required');
    $this->form_validation->set_rules('mode_id', 'Mode', 'required');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode(['status' => false, 'message' => validation_errors()]);
      exit;
    }
  }

  /**
   * Prepare items array from POST - Updated for checkbox functionality
   */
  private function _prepare_items() {
    $items = [];
    if ($this->input->post('items')) {
      foreach ($this->input->post('items') as $row) {
        $qty = (float) $row['qty'];
        $rate = (float) $row['rate'];
        $discount = (float) $row['discount'];
        $use_dropdown = isset($row['use_dropdown']) ? (int) $row['use_dropdown'] : 1;
        
        $amount = $qty * $rate;
        $amount -= ($amount * $discount / 100);

        $item_data = [
            'use_dropdown' => $use_dropdown,
            'qty' => $qty,
            'rate' => $rate,
            'discount' => $discount,
            'amount' => $amount
        ];

        // Handle dropdown vs description mode
        if ($use_dropdown) {
          $item_data['category_id'] = isset($row['category_id']) ? $row['category_id'] : null;
          $item_data['product_id'] = isset($row['product_id']) ? $row['product_id'] : null;
          $item_data['description'] = null;
        } else {
          $item_data['category_id'] = null;
          $item_data['product_id'] = null;
          $item_data['description'] = isset($row['description']) ? trim($row['description']) : '';
        }

        $items[] = $item_data;
      }
    }
    return $items;
  }

  /** DELETE QUOTATION * */
//  public function delete($id) {
//    if ($this->Quotation_model->delete($id)) {
//      $this->session->set_flashdata('success', 'Quotation deleted successfully!');
//    } else {
//      $this->session->set_flashdata('error', 'Failed to delete quotation.');
//    }
//    redirect('quotation');
//  }

  /** DUPLICATE QUOTATION * */
  public function duplicate($id) {
    if ($new_id = $this->Quotation_model->duplicate($id)) {
      $this->session->set_flashdata('success', 'Quotation duplicated successfully!');
      redirect('quotation/edit/' . $new_id);
    } else {
      $this->session->set_flashdata('error', 'Failed to duplicate quotation.');
      redirect('quotation');
    }
  }

  /** SEND MAIL (AJAX) with Enhanced Validation * */
  public function send_mail() {
    try {
      // Ensure JSON content type for AJAX consumers
      $this->output->set_content_type('application/json');

      // Explicit auth check to avoid any redirect behavior
      if (!$this->session->userdata('logged_in')) {
        $this->output->set_status_header(401);
        echo json_encode(['status' => false, 'message' => 'Session expired. Please log in again.']);
        return;
      }

      // Input validation
      $this->form_validation->set_rules('quotation_id', 'Quotation ID', 'required|numeric');
      $this->form_validation->set_rules('to', 'To Email', 'required|trim');
      $this->form_validation->set_rules('subject', 'Subject', 'required|trim|min_length[3]|max_length[200]');
      $this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[10]');
      $this->form_validation->set_rules('cc', 'CC Email', 'trim');

      if (!$this->form_validation->run()) {
        echo json_encode(['status' => false, 'message' => validation_errors()]);
        return;
      }

      $quotation_id = $this->input->post('quotation_id');
      $to_emails = $this->input->post('to');
      $cc_emails = $this->input->post('cc');
      $subject = $this->input->post('subject');
      $message = $this->input->post('message');

      // Validate quotation exists
      $quotation = $this->Quotation_model->get($quotation_id);
      if (!$quotation) {
        echo json_encode(['status' => false, 'message' => 'Quotation not found']);
        return;
      }

      // Validate and clean email addresses
      $to = $this->validate_emails($to_emails);
      $cc = !empty($cc_emails) ? $this->validate_emails($cc_emails) : [];

      if (empty($to)) {
        echo json_encode(['status' => false, 'message' => 'Please provide valid recipient email addresses']);
        return;
      }

      // Check email settings
      $email_config = get_email_config();
      if (empty($email_config['smtp_host']) || empty($email_config['smtp_user'])) {
        echo json_encode(['status' => false, 'message' => 'Email settings not configured. Please contact administrator.']);
        return;
      }

      // Handle file upload for additional attachment
      $customAttachment = null;
      if (!empty($_FILES['attachment']['name'])) {
        $upload_result = $this->handle_email_attachment();
        if ($upload_result['status']) {
          $customAttachment = $upload_result['file_path'];
        } else {
          echo json_encode(['status' => false, 'message' => $upload_result['message']]);
          return;
        }
      }

      // Initialize email library with settings
      $this->load->library('email', $email_config);
      $this->email->clear();

      // Set email parameters
      $from_email = get_setting('from_email', 'billing@hsadindia.com');
      $from_name = get_setting('from_name', 'HSAD India');
      $reply_to = get_setting('reply_to_email', $from_email);

      $this->email->from($from_email, $from_name);
      $this->email->reply_to($reply_to, $from_name);
      $this->email->to($to);

      if (!empty($cc)) {
        $this->email->cc($cc);
      }

      $this->email->subject($subject);
      $this->email->message($this->format_email_message($message, $quotation));

      // Attach quotation PDF if exists
      $quotation_attachment = FCPATH . 'assets/uploads/quotations/' . $quotation->attachment;
      if (!empty($quotation->attachment) && file_exists($quotation_attachment)) {
        $this->email->attach($quotation_attachment);
      }

      // Attach custom file if uploaded
      if ($customAttachment && file_exists($customAttachment)) {
        $this->email->attach($customAttachment);
      }

      // Send email
      if ($this->email->send()) {
        // Log successful email
        $this->Quotation_model->log_mail($quotation_id, $to, $cc, $subject, $message);

        // Clean up temporary attachment
        if ($customAttachment && file_exists($customAttachment)) {
          unlink($customAttachment);
        }

        log_message('info', "Quotation #{$quotation_id} email sent successfully to: " . implode(', ', $to));
        echo json_encode([
            'status' => true,
            'message' => 'Email sent successfully to ' . count($to) . ' recipient(s)!'
        ]);
      } else {
        $error = $this->email->print_debugger();
        log_message('error', "Failed to send quotation #{$quotation_id} email. Error: {$error}");
        echo json_encode([
            'status' => false,
            'message' => 'Failed to send email. Please check email configuration or try again later.'
        ]);
      }
    } catch (Exception $e) {
      log_message('error', "Email sending exception: " . $e->getMessage());
      echo json_encode(['status' => false, 'message' => 'An error occurred while sending email']);
    }
  }

  /**
   * Validate and clean email addresses
   */
  private function validate_emails($email_string) {
    $emails = array_map('trim', explode(',', $email_string));
    $valid_emails = [];

    foreach ($emails as $email) {
      if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $valid_emails[] = $email;
      }
    }

    return $valid_emails;
  }

  /**
   * Handle email attachment upload
   */
  private function handle_email_attachment() {
    // Create upload directory if not exists
    $upload_path = './assets/uploads/mail_attachments/';
    if (!is_dir($upload_path)) {
      mkdir($upload_path, 0755, true);
    }

    $config = [
        'upload_path' => $upload_path,
        'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx|txt|xls|xlsx',
        'max_size' => 10240, // 10MB
        'encrypt_name' => true,
        'remove_spaces' => true
    ];

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('attachment')) {
      $upload_data = $this->upload->data();
      return [
          'status' => true,
          'file_path' => $upload_data['full_path'],
          'file_name' => $upload_data['file_name']
      ];
    } else {
      return [
          'status' => false,
          'message' => 'File upload failed: ' . $this->upload->display_errors('', '')
      ];
    }
  }

  /**
   * Format email message with quotation details
   */
  private function format_email_message($message, $quotation) {
    $company_name = get_setting('company_name', 'HSAD India');

    $formatted_message = "Dear Valued Client,\n\n";
    $formatted_message .= $message . "\n\n";
    $formatted_message .= "Quotation Details:\n";
    $formatted_message .= "- Quotation ID: #{$quotation->id}\n";
    $formatted_message .= "- Date: " . date('d-M-Y', strtotime($quotation->created_at)) . "\n";
    $formatted_message .= "- Contact Person: {$quotation->contact_person}\n";
    $formatted_message .= "- Department: {$quotation->department}\n\n";
    $formatted_message .= "Please find the detailed quotation attached with this email.\n\n";
    $formatted_message .= "For any queries, please feel free to contact us.\n\n";
    $formatted_message .= "Best Regards,\n";
    $formatted_message .= $company_name . "\n";
    $formatted_message .= get_setting('company_email', 'billing@hsadindia.com') . "\n";
    $formatted_message .= get_setting('company_phone', '');

    return $formatted_message;
  }

  public function view($id) {
    $data['quotation'] = $this->Quotation_model->getQuotationWithDetails($id);
    $data['items'] = $this->Quotation_model->getQuotationItems($id);

    if (!$data['quotation']) {
      show_404();
    }

    // Compute estimate number
    $data['estimate_no'] = $this->format_estimate_no($data['quotation']->id, $data['quotation']->created_at ?? date('Y-m-d H:i:s'));

    $this->load->view('templates/header');
    $this->load->view('quotation/view', $data);
    $this->load->view('templates/footer');
  }

  public function view_pdf($id) {
    $this->load->model('Quotation_model');
    $data['quotation'] = $this->Quotation_model->getQuotationWithDetails($id);
    $data['items'] = $this->Quotation_model->getQuotationItems($id);

    if (!$data['quotation']) {
      show_404();
    }

    // Load PDF library
    $this->load->library('pdf');

    // Logo Base64 encoding for PDF
    $logo_filename = $data['quotation']->company_logo;
    $logo_file_path = FCPATH . 'assets/uploads/logos/' . $logo_filename;

    $logo_src = '';
    if (file_exists($logo_file_path)) {
      $logo_data = file_get_contents($logo_file_path);
      $mime_type = mime_content_type($logo_file_path);
      $logo_src = 'data:' . $mime_type . ';base64,' . base64_encode($logo_data);
    } else {
      // Fallback placeholder
      $logo_src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
    }
    $data['logo_src'] = $logo_src;
    // Compute estimate number
    $data['estimate_no'] = $this->format_estimate_no($data['quotation']->id, $data['quotation']->created_at ?? date('Y-m-d H:i:s'));

    // Load the view as HTML and render PDF
    $html = $this->load->view('quotation/pdf_view', $data, true);
    // Sanitize filename from estimate_no
    $safe_estimate = preg_replace('/[^A-Za-z0-9\-]/', '_', $data['estimate_no']);
    $filename = $safe_estimate . '.pdf';

    // Dompdf settings
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'portrait');
    $this->pdf->render();

    // Stream PDF for viewing in browser
    $this->pdf->stream($filename, array("Attachment" => false));
  }

  public function generate_pdf($id) {
    $this->load->model('Quotation_model');
    $data['quotation'] = $this->Quotation_model->getQuotationWithDetails($id);
    $data['items'] = $this->Quotation_model->getQuotationItems($id);

    if (!$data['quotation']) {
      show_404();
    }

    // Load PDF library
    $this->load->library('pdf');

    // Logo Base64 encoding for PDF
    $logo_filename = $data['quotation']->company_logo;
    $logo_file_path = FCPATH . 'assets/uploads/logos/' . $logo_filename;

    $logo_src = '';
    if (file_exists($logo_file_path)) {
      $logo_data = file_get_contents($logo_file_path);
      $mime_type = mime_content_type($logo_file_path);
      $logo_src = 'data:' . $mime_type . ';base64,' . base64_encode($logo_data);
    } else {
      // Fallback placeholder
      error_log("Logo file not found: " . $logo_file_path);
      $logo_src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
    }
    $data['logo_src'] = $logo_src;

    // Compute estimate number for filename and view
    $data['estimate_no'] = $this->format_estimate_no($data['quotation']->id, $data['quotation']->created_at ?? date('Y-m-d H:i:s'));

    // Load the view as HTML and render PDF
    $html = $this->load->view('quotation/pdf_view', $data, true);
    // Sanitize filename from estimate_no
    $safe_estimate = preg_replace('/[^A-Za-z0-9\-]/', '_', $data['estimate_no']);
    $filename = $safe_estimate . '.pdf';

    // Dompdf settings
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'portrait');
    $this->pdf->render();

    // Stream PDF inline (open in new tab)
    $this->pdf->stream($filename, array("Attachment" => false));
  }

}
