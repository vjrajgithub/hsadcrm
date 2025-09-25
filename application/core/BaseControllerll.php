<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends CI_Controller {

  public function __construct() {
    parent::__construct();

    // Load common helpers/libraries
    $this->load->helper(['url', 'form']);
    $this->load->library('session');

    // Super Admin Access Control
    if (!$this->session->userdata('logged_in') || $this->session->userdata('user_role') !== 'Super Admin') {
      redirect('login');
    }
  }

}
