<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    
    // Additional base controller initialization if needed
  }

}
