<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mode extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    
    // Load models and libraries for mode management
    $this->load->model('Mode_model');
    $this->load->library('form_validation');
    $this->load->helper(['url', 'form']);
  }

  public function index() {
    // No need to load data here anymore - will be loaded via AJAX
    $this->load->view('templates/header');
//    $this->load->view('templates/sidebar');
    $this->load->view('mode/index');
    $this->load->view('templates/footer');
  }

  public function list() {
    // Simple client-side DataTable data (like User controller)
    $modes = $this->Mode_model->get_all();
    echo json_encode(['data' => $modes]);
  }

  public function form($id = null) {
    $data['mode'] = $id ? $this->Mode_model->get($id) : null;
    // Load only the form view for modal (no header/footer)
    $this->load->view('mode/form', $data);
  }

  public function ajax_save() {
    // Skip CSRF validation for AJAX to avoid token regeneration issues
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    $this->form_validation->set_rules('days', 'Days', 'required|integer|greater_than[0]');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode([
          'status' => 'error',
          'message' => validation_errors()
      ]);
      return;
    }

    try {
      // Filter data to only include valid fields
      $mode_data = [
          'name' => $this->input->post('name'),
          'days' => $this->input->post('days')
      ];

      $id = $this->input->post('id');
      if (!empty($id)) {
        $this->Mode_model->update($id, $mode_data);
        $message = 'Mode updated successfully';
      } else {
        $this->Mode_model->insert($mode_data);
        $message = 'Mode added successfully';
      }

      echo json_encode(['status' => 'success', 'message' => $message]);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  public function delete($id) {
    try {
      $this->Mode_model->delete($id);
      echo json_encode(['status' => 'success', 'message' => 'Mode deleted successfully']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error deleting mode: ' . $e->getMessage()]);
    }
  }

}
