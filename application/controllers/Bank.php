<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    
    // Load models and libraries for bank management
    $this->load->model('Bank_model');
    $this->load->model('Company_model');
    $this->load->library('form_validation');
    $this->load->helper(['url', 'form']);
  }

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('bank/index');
    $this->load->view('templates/footer');
  }

  public function list() {
    $banks = $this->Bank_model->get_all_with_company();
    echo json_encode(['data' => $banks]);
  }

  public function form($id = null) {
    $data['companies'] = $this->Company_model->get_all();
    $data['bank'] = $id ? $this->Bank_model->get($id) : null;

    // Load form without header/footer for modal display
    $this->load->view('bank/form', $data);
  }

  public function ajax_save() {
    // Enhanced validation rules matching frontend validation
    $this->form_validation->set_rules('company_id', 'Company', 'required');
    $this->form_validation->set_rules('name', 'Bank Name', 'required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('branch_address', 'Branch Address', 'required|min_length[5]|max_length[255]');
    $this->form_validation->set_rules('ac_no', 'Account Number', 'required|min_length[8]|max_length[20]|numeric');
    $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'required|exact_length[11]|alpha_numeric');

    // Custom error messages
    $this->form_validation->set_message('required', 'The {field} field is required.');
    $this->form_validation->set_message('min_length', 'The {field} must be at least {param} characters long.');
    $this->form_validation->set_message('max_length', 'The {field} cannot exceed {param} characters.');
    $this->form_validation->set_message('exact_length', 'The {field} must be exactly {param} characters long.');
    $this->form_validation->set_message('numeric', 'The {field} must contain only numbers.');
    $this->form_validation->set_message('alpha_numeric', 'The {field} must contain only letters and numbers.');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
      return;
    }

    $data = array(
      'company_id' => $this->input->post('company_id'),
      'name' => $this->input->post('name'),
      'branch_address' => $this->input->post('branch_address'),
      'ac_no' => $this->input->post('ac_no'),
      'ifsc_code' => $this->input->post('ifsc_code')
    );
    
    $id = $this->input->post('id');
    
    try {
      if ($id) {
        $this->Bank_model->update($id, $data);
        echo json_encode(['status' => 'success', 'message' => 'Bank details updated successfully.']);
      } else {
        $this->Bank_model->insert($data);
        echo json_encode(['status' => 'success', 'message' => 'Bank details added successfully.']);
      }
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  public function delete($id) {
    try {
      $this->Bank_model->delete($id);
      echo json_encode(['status' => 'success', 'message' => 'Bank deleted successfully.']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error deleting bank: ' . $e->getMessage()]);
    }
  }

}
