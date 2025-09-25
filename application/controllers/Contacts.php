<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    
    // Load models and libraries for contact management
    $this->load->model('Contact_model');
    $this->load->model('Client_model');
    $this->load->library('form_validation');
    $this->load->helper(['url', 'form']);
  }

  public function index() {
    $data['contacts'] = $this->Contact_model->get_all();
    $this->load->view('templates/header');
    $this->load->view('contacts/index', $data);
    $this->load->view('templates/footer');
  }

  public function form($id = null) {
    $data['clients'] = $this->Client_model->get_all();
    $data['contact'] = $id ? $this->Contact_model->get($id) : null;
    $this->load->view('contacts/form', $data);
  }

  public function ajax_save() {
    // Skip CSRF validation for AJAX to avoid token regeneration issues
    $this->form_validation->set_rules('client_id', 'Client', 'required');
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');

    if ($this->form_validation->run() === FALSE) {
      echo json_encode(['status' => 'error', 'message' => validation_errors()]);
      return;
    }

    try {
      // Filter data to only include valid fields
      $contact_data = [
        'client_id' => $this->input->post('client_id'),
        'name' => $this->input->post('name'),
        'mobile' => $this->input->post('mobile'),
        'email' => $this->input->post('email')
      ];

      $id = $this->input->post('id');
      if (!empty($id)) {
        $this->Contact_model->update($id, $contact_data);
        $message = 'Contact updated successfully';
      } else {
        $this->Contact_model->insert($contact_data);
        $message = 'Contact added successfully';
      }
      
      echo json_encode(['status' => 'success', 'message' => $message]);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  public function delete($id) {
    try {
      $this->Contact_model->delete($id);
      echo json_encode(['status' => 'success', 'message' => 'Contact deleted successfully']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error deleting contact: ' . $e->getMessage()]);
    }
  }

}
