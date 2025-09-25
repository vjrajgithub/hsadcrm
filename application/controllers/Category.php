<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    
    // Load models and libraries for category management
    $this->load->model('Category_model');
    $this->load->library('form_validation');
    $this->load->helper(['url', 'form']);
  }

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('category/index');
    $this->load->view('templates/footer');
  }

  public function list() {
    $categories = $this->Category_model->get_all();
    echo json_encode(['data' => $categories]);
  }

  public function form($id = null) {
    $data['category'] = $id ? $this->Category_model->get($id) : null;
    $this->load->view('category/form', $data);
  }

  public function ajax_save() {
    // Skip CSRF validation for AJAX to avoid token regeneration issues
    $this->form_validation->set_rules('name', 'Category Name', 'required|trim');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode([
          'status' => 'error',
          'message' => validation_errors()
      ]);
      return;
    }

    try {
      // Filter data to only include valid fields
      $category_data = [
        'name' => $this->input->post('name')
      ];

      $id = $this->input->post('id');
      if (!empty($id)) {
        $this->Category_model->update($id, $category_data);
        $message = 'Category updated successfully';
      } else {
        $this->Category_model->insert($category_data);
        $message = 'Category added successfully';
      }

      echo json_encode(['status' => 'success', 'message' => $message]);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  public function delete($id) {
    try {
      $this->Category_model->delete($id);
      echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error deleting category: ' . $e->getMessage()]);
    }
  }

}
