<?php

class AuthMiddleware {

  protected $CI;

  public function __construct() {
    $this->CI = & get_instance();
  }

  public function check() {
    if (!$this->CI->session->userdata('user')) {
      redirect('login');
    }
  }

  public function check_role($roles) {
    $user = $this->CI->session->userdata('user');
    if (!$user || !in_array($user['role'], $roles)) {
      show_error('Access Denied', 403);
    }
  }

  public function check_permission($module, $action) {
    $user = $this->CI->session->userdata('user');
    if ($user['role'] === 'super_admin')
      return;

    $this->CI->load->model('Permission_model');
    if (!$this->CI->Permission_model->has_permission($user['role'], $module, $action)) {
      show_error('Permission Denied', 403);
    }
  }

}
