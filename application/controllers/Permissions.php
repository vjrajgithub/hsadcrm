<?php

class Permissions extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->authmiddleware->check();
    $this->authmiddleware->check_role(['super_admin']);
    $this->load->model('Permission_model');
  }

  public function index() {
    $data['roles'] = ['admin', 'viewer'];
    $data['modules'] = ['invoice', 'quotation'];
    $data['actions'] = ['view', 'create', 'edit', 'delete'];
    $data['permissions'] = $this->Permission_model->all();
    $this->load->view('layouts/header');
    $this->load->view('permissions/index', $data);
    $this->load->view('layouts/footer');
  }

  public function update() {
    $this->Permission_model->save($this->input->post());
    redirect('permissions');
  }

}
