<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();

    // Load specific models and libraries for company management
    $this->load->model('Company_model');
    $this->load->helper(['url', 'form', 'file']);
    
    // Create upload directory if it doesn't exist
    if (!is_dir('./assets/uploads/logos/')) {
      mkdir('./assets/uploads/logos/', 0755, true);
    }
  }

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('company/index');
    $this->load->view('templates/footer');
  }

  public function list() {
    $companies = $this->Company_model->get_all();
    echo json_encode(['data' => $companies]);
  }

  public function form($id = null) {
    $this->load->model('Company_model');
    $data['company'] = $id ? $this->Company_model->get($id) : null;

    // Load only the form view for modal (no header/footer)
    $this->load->view('company/form', $data);
  }

//  public function form($id = null) {
//    $data['company'] = $id ? $this->Company_model->get($id) : null;
//
//    if ($_POST) {
//      $input = $this->input->post();
//
//      // Handle logo upload
//      if (!empty($_FILES['logo']['name'])) {
//        $config['upload_path'] = './assets/uploads/logos/';
//        $config['allowed_types'] = 'jpg|jpeg|png|gif';
//        $config['file_name'] = time();
//        $this->load->library('upload', $config);
//
//        if ($this->upload->do_upload('logo')) {
//          $uploadData = $this->upload->data();
//          $input['logo'] = $uploadData['file_name'];
//        } else {
//          $this->session->set_flashdata('error', $this->upload->display_errors());
//          redirect(current_url());
//        }
//      }
//
//      if ($id) {
//        $this->Company_model->update($id, $input);
//      } else {
//        $this->Company_model->insert($input);
//      }
//
//      redirect('company');
//    }
//
//    $this->load->view('templates/header');
//    $this->load->view('company/form', $data);
//    $this->load->view('templates/footer');
//  }

  public function delete($id) {
    try {
      $this->Company_model->delete($id);
      $this->output->set_content_type('application/json');
      echo json_encode(['status' => 'success', 'message' => 'Company deleted successfully.']);
    } catch (Exception $e) {
      $this->output->set_content_type('application/json');
      echo json_encode(['status' => 'error', 'message' => 'Error deleting company: ' . $e->getMessage()]);
    }
  }

  public function ajax_save() {
    // Skip CSRF validation for AJAX requests to avoid token regeneration issues
    // Form still includes CSRF token for basic security

    $this->load->library('form_validation');

    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric|min_length[10]|max_length[10]');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('address', 'Address', 'required');
    $this->form_validation->set_rules('pin_code', 'PIN Code', 'required|numeric|min_length[6]|max_length[6]');
    $this->form_validation->set_rules('country', 'Country', 'required');
    $this->form_validation->set_rules('state', 'State', 'required');
    $this->form_validation->set_rules('city', 'City', 'required');
    // Enforce uppercase alphanumeric 20-40 chars for CIN No
    $this->form_validation->set_rules('cin_no', 'CIN No', 'required|regex_match[/^[A-Z0-9]{20,40}$/]');

    if (!$this->form_validation->run()) {
      echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
      return;
    }

    $data = $this->input->post();
    // Normalize to uppercase to be safe before persisting
    if (isset($data['cin_no'])) { $data['cin_no'] = strtoupper($data['cin_no']); }
    $id = $this->input->post('id'); // 👈 important
    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
      $config['upload_path'] = './assets/uploads/logos/';
      $config['allowed_types'] = 'jpg|jpeg|png|gif';
      $config['file_name'] = time();
      $this->load->library('upload', $config);

      if ($this->upload->do_upload('logo')) {
        $upload = $this->upload->data();
        $data['logo'] = $upload['file_name'];
      } else {
        echo json_encode(['status' => 'error', 'message' => strip_tags($this->upload->display_errors())]);
        return;
      }
    }

    $this->load->model('Company_model');

    if ($id) {
      $this->Company_model->update($id, $data); // 👈 update if ID is present
      echo json_encode(['status' => 'success', 'message' => 'Company updated successfully.']);
    } else {
      $this->Company_model->insert($data); // 👈 insert otherwise
      echo json_encode(['status' => 'success', 'message' => 'Company created successfully.']);
    }
  }

}
