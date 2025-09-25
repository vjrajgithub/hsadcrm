<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library('form_validation');
  }

  public function index() {
    $this->require_permission('view_users');
    $data['controller'] = $this; // Pass controller instance to view
    $this->load->view('templates/header');
    $this->load->view('templates/main_sidebar');
    $this->load->view('users/index', $data); // includes DataTable and modal
    $this->load->view('templates/footer');
  }

  public function list() {
    $this->require_permission('view_users');
    $users = $this->User_model->get_all();
    echo json_encode(['data' => $users]);
  }

  public function form($id = null) {
    $data['user'] = $id ? $this->User_model->get($id) : null;
    $this->load->view('users/form', $data);
  }

  public function get($id) {
    $user = $this->User_model->get($id);
    header('Content-Type: application/json');
    echo json_encode($user);
  }

  public function ajax_save() {
    // Check permissions
    $id = $this->input->post('id');
    if ($id) {
      $this->require_permission('edit_users');
    } else {
      $this->require_permission('add_users');
    }
    
    try {

      // Validation rules
      $this->form_validation->set_rules('name', 'Name', 'required|trim|max_length[100]');
      $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim|max_length[150]');
      $this->form_validation->set_rules('role', 'Role', 'required|in_list[super admin,admin,viewer]');

      if (!$id) {
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
      } else {
        if ($this->input->post('password')) {
          $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        }
      }

      if ($this->form_validation->run() == FALSE) {
        echo json_encode([
          'status' => false,
          'message' => validation_errors()
        ]);
        return;
      }

      // Check for duplicate email
      $email = $this->input->post('email');
      $existing_user = $this->User_model->get_by_email($email);
      if ($existing_user && (!$id || $existing_user->id != $id)) {
        echo json_encode([
          'status' => false,
          'message' => 'Email already exists!'
        ]);
        return;
      }

      $data = [
        'name' => $this->input->post('name'),
        'email' => $email,
        'role' => $this->input->post('role'),
        'status' => 1  // Set status to active by default
      ];

      if ($this->input->post('password')) {
        $data['password'] = $this->input->post('password');
      }

      if ($id) {
        $result = $this->User_model->update($id, $data);
        $message = 'User updated successfully!';
      } else {
        $result = $this->User_model->insert($data);
        $message = 'User added successfully!';
      }

      if ($result) {
        echo json_encode([
          'status' => true, 
          'message' => $message,
          'csrf_hash' => $this->security->get_csrf_hash()
        ]);
      } else {
        echo json_encode(['status' => false, 'message' => 'Failed to save user!']);
      }

    } catch (Exception $e) {
      echo json_encode(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
  }

  public function save() {
    $this->ajax_save();
  }

  public function delete($id) {
    $this->require_permission('delete_users');
    
    try {
      // Prevent self-deletion
      if ($id == $this->session->userdata('user_id')) {
        echo json_encode(['status' => false, 'message' => 'You cannot delete your own account!']);
        return;
      }

      $result = $this->User_model->delete($id);
      
      if ($result) {
        echo json_encode(['status' => true, 'message' => 'User deleted successfully!']);
      } else {
        echo json_encode(['status' => false, 'message' => 'Failed to delete user!']);
      }
    } catch (Exception $e) {
      echo json_encode(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
  }

}
