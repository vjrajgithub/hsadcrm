<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductCategory extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    
    // Load models and libraries for product category management
    $this->load->model('ProductCategory_model');
    $this->load->library('form_validation');
    $this->load->helper(['url', 'form']);
  }

  public function index() {
    $data['categories'] = $this->ProductCategory_model->get_all();
    $this->load->view('templates/header');
    $this->load->view('templates/main_sidebar');
    $this->load->view('product_category/index', $data);
    $this->load->view('templates/footer');
  }

  public function form($id = null) {
    $data['category'] = $id ? $this->ProductCategory_model->get($id) : null;
    $this->load->view('product_category/form', $data);
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
        $this->ProductCategory_model->update($id, $category_data);
        $message = 'Product category updated successfully';
      } else {
        $this->ProductCategory_model->insert($category_data);
        $message = 'Product category added successfully';
      }

      echo json_encode(['status' => 'success', 'message' => $message]);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  public function delete($id) {
    try {
      $this->ProductCategory_model->delete($id);
      echo json_encode(['status' => 'success', 'message' => 'Product category deleted successfully']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error deleting product category: ' . $e->getMessage()]);
    }
  }

}
