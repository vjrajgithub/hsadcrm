<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model(['Company_model', 'Bank_model', 'User_model']);
    // Load common helpers/libraries
    $this->load->helper(['url', 'form']);
    $this->load->model('Dashboard_model');
    $this->load->library('session');

    // Authentication check for all logged-in users
    if (!$this->session->userdata('logged_in')) {
      redirect('login');
    }
  }

  public function index() {
    $this->load->model('Dashboard_model');

    $data['total_users'] = $this->Dashboard_model->count_table('users');
    $data['total_clients'] = $this->Dashboard_model->count_table('clients');
    $data['total_companies'] = $this->Dashboard_model->count_table('companies');
    $data['total_products'] = $this->Dashboard_model->count_table('products'); // Adjust table if needed

    $user_roles = $this->Dashboard_model->get_users_by_role();
    $roles = [];
    foreach ($user_roles as $role) {
      $roles[$role->role] = $role->total;
    }
    $data['user_roles'] = $roles;

    // Clients by company
    $clients = $this->Dashboard_model->get_clients_by_company();
    $client_data = [];
    foreach ($clients as $c) {
      $client_data[$c->company_name] = $c->total;
    }
    $data['clients_by_company'] = $client_data;

    $this->load->view('templates/header', $data);
    $this->load->view('dashboard/index', $data);
    $this->load->view('templates/footer');
  }

//  public function index() {
//    $data['title'] = 'Dashboard';
//
//    // Count summaries
//    $data['total_users'] = $this->Dashboard_model->count_table('users');
//    $data['total_clients'] = $this->Dashboard_model->count_table('clients');
//    $data['total_contacts'] = $this->Dashboard_model->count_table('client_contacts');
//    $data['total_companies'] = $this->Dashboard_model->count_table('companies');
//    $data['total_banks'] = $this->Dashboard_model->count_table('bank_details');
//    $data['total_products'] = $this->Dashboard_model->count_table('products_services'); // <-- Add this line
//    // Charts
//    $data['users_by_role'] = $this->Dashboard_model->get_users_by_role();
//    $data['clients_by_company'] = $this->Dashboard_model->get_clients_by_company();
//    $user_roles = $this->Dashboard_model->get_users_by_role(); // [{role: 'Admin', total: 3}, ...]
//    // Convert array of objects into associative array like ['Admin' => 3, 'Viewer' => 1]
//    // User by Role
//    $user_roles_array = [];
//    foreach ($user_roles as $row) {
//      $user_roles_array[$row->role] = $row->total;
//    }
//
//    $data['user_roles'] = $user_roles_array;
//
//    // Clients by company
//    $clients = $this->Dashboard_model->get_clients_by_company();
//    $client_data = [];
//    foreach ($clients as $c) {
//      $client_data[$c->company_name] = $c->total;
//    }
//    $data['clients_by_company'] = $client_data;
////    echo '<pre>';
////    print_r($data);
////    die;
//
//    $this->load->view('templates/header', $data);
//    $this->load->view('dashboard/index', $data);
//    $this->load->view('templates/footer');
//  }
}
