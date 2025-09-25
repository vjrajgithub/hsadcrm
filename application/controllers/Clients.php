<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();

    // Load models and libraries for client management
    $this->load->model('Client_model');
    $this->load->model('Company_model');
    $this->load->library('form_validation');
    $this->load->helper(['url', 'form']);
  }

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('clients/index');
    $this->load->view('templates/footer');
  }

  public function list() {
    $clients = $this->Client_model->get_all();
    echo json_encode(['data' => $clients]);
  }

  public function form($id = null) {
    $data['companies'] = $this->Company_model->get_all();
    $data['client'] = $id ? $this->Client_model->get($id) : null;
    $this->load->view('clients/form', $data);
  }

  public function ajax_save() {
    // Enhanced validation rules matching frontend validation
    $this->form_validation->set_rules('company_id', 'Company', 'required');
    $this->form_validation->set_rules('name', 'Client Name', 'required|min_length[2]|max_length[100]|trim');
    $this->form_validation->set_rules('mobile', 'Mobile Number', 'exact_length[10]|numeric|trim');
    $this->form_validation->set_rules('email', 'Email Address', 'valid_email|max_length[100]|trim');
    $this->form_validation->set_rules('gst_no', 'GST Number', 'max_length[15]|alpha_numeric|trim');
    $this->form_validation->set_rules('pan_card', 'PAN Card', 'max_length[10]|alpha_numeric|trim');
    $this->form_validation->set_rules('address', 'Address', 'max_length[500]|trim');
    $this->form_validation->set_rules('pincode', 'Pin Code', 'exact_length[6]|numeric|trim');
    $this->form_validation->set_rules('country', 'Country', 'max_length[50]|trim');
    $this->form_validation->set_rules('state', 'State', 'max_length[50]|trim');
    $this->form_validation->set_rules('state_code', 'State Code', 'max_length[2]|numeric|trim');
    $this->form_validation->set_rules('city', 'City', 'max_length[50]|trim');

    // Custom error messages
    $this->form_validation->set_message('required', 'The {field} field is required.');
    $this->form_validation->set_message('min_length', 'The {field} must be at least {param} characters long.');
    $this->form_validation->set_message('max_length', 'The {field} cannot exceed {param} characters.');
    $this->form_validation->set_message('exact_length', 'The {field} must be exactly {param} characters long.');
    $this->form_validation->set_message('numeric', 'The {field} must contain only numbers.');
    $this->form_validation->set_message('alpha_numeric', 'The {field} must contain only letters and numbers.');
    $this->form_validation->set_message('valid_email', 'Please enter a valid email address.');

    if ($this->form_validation->run() === FALSE) {
      echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
      return;
    }

    try {
      // Filter data to include all valid fields matching database schema
      $client_data = [
          'name' => $this->input->post('name'),
          'mobile' => $this->input->post('mobile'),
          'email' => $this->input->post('email'),
          'company_id' => $this->input->post('company_id'),
          'gst_no' => $this->input->post('gst_no'),
          'pan_card' => $this->input->post('pan_card'),
          'address' => $this->input->post('address'),
          'pin_code' => $this->input->post('pincode'),
          'country' => $this->input->post('country'),
          'state' => $this->input->post('state'),
          'state_code' => $this->input->post('state_code'),
          'city' => $this->input->post('city')
      ];

      // Remove empty values to avoid database issues
//      $client_data = array_filter($client_data, function ($value) {
//        return $value !== '' && $value !== null;
//      });
//      print_r($client_data);
//      die;

      $id = $this->input->post('id');
      if (!empty($id)) {
        $this->Client_model->update($id, $client_data);
        $message = 'Client updated successfully';
      } else {
        $this->Client_model->insert($client_data);
        $message = 'Client added successfully';
      }

      echo json_encode(['status' => 'success', 'message' => $message]);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  public function delete($id) {
    try {
      $this->Client_model->delete($id);
      echo json_encode(['status' => 'success', 'message' => 'Client deleted successfully']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error deleting client: ' . $e->getMessage()]);
    }
  }

}
