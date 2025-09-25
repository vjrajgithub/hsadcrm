<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductService extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    
    // Load models and libraries for product service management
    $this->load->model('ProductService_model');
    $this->load->model('ProductCategory_model');
    $this->load->library('form_validation');
    $this->load->helper(['url', 'form']);
  }

  public function index() {
    $data['categories'] = $this->ProductCategory_model->get_all();
    $this->load->view('templates/header');
    $this->load->view('products/index', $data);
    $this->load->view('templates/footer');
  }

  public function get_all() {
    $products = $this->ProductService_model->get_all_with_category();
    echo json_encode(['data' => $products]);
  }

  public function list() {
    $products = $this->ProductService_model->get_all_with_category();
    echo json_encode(['data' => $products]);
  }

  public function form($id = null) {
    $data['categories'] = $this->ProductCategory_model->get_all();
    $data['product'] = $id ? $this->ProductService_model->get($id) : null;
    // Load only the form view for modal (no header/footer)
    $this->load->view('products/form', $data);
  }

  public function ajax_save() {
    // Skip CSRF validation for AJAX to avoid token regeneration issues
    $this->form_validation->set_rules('category_id', 'Category', 'required');
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    $this->form_validation->set_rules('rate_per_unit', 'Rate per Unit', 'required|numeric');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode([
          'status' => 'error',
          'message' => validation_errors()
      ]);
      return;
    }

    try {
      // Filter data to only include valid fields
      $product_data = [
        'category_id' => $this->input->post('category_id'),
        'name' => $this->input->post('name'),
        'rate_per_unit' => $this->input->post('rate_per_unit')
      ];

      $id = $this->input->post('id');
      if (!empty($id)) {
        $this->ProductService_model->update($id, $product_data);
        $message = 'Product/Service updated successfully';
      } else {
        $this->ProductService_model->insert($product_data);
        $message = 'Product/Service added successfully';
      }

      echo json_encode(['status' => 'success', 'message' => $message]);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
  }

  public function edit($id) {
    $data = $this->ProductService_model->get($id);
    echo json_encode($data);
  }

  public function delete($id) {
    try {
      $this->ProductService_model->delete($id);
      echo json_encode(['status' => 'success', 'message' => 'Product/Service deleted successfully']);
    } catch (Exception $e) {
      echo json_encode(['status' => 'error', 'message' => 'Error deleting product/service: ' . $e->getMessage()]);
    }
  }

}
