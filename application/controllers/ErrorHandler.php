<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ErrorHandler extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
    }

    public function access_denied() {
        $user_role = $this->session->userdata('user_role');
        $user_name = $this->session->userdata('user_name');
        
        $data['title'] = 'Access Denied';
        $data['error_code'] = '403';
        $data['error_title'] = 'Access Denied';
        $data['error_message'] = 'You do not have permission to access this resource.';
        
        // Role-specific error messages
        switch(strtolower($user_role)) {
            case 'viewer':
                $data['error_description'] = "As a <strong>Viewer</strong>, you have read-only access to most modules. You cannot modify user accounts, company settings, or perform administrative tasks. Contact your administrator to upgrade your permissions.";
                break;
            case 'admin':
                $data['error_description'] = "As an <strong>Admin</strong>, you have extensive permissions but some Super Admin functions are restricted. You cannot delete users or access certain system-level configurations. Contact your Super Admin for elevated access.";
                break;
            case 'super admin':
                $data['error_description'] = "This is unexpected - Super Admins should have access to all resources. Please check the system configuration or contact technical support.";
                break;
            default:
                $data['error_description'] = "Your current role does not have the required permissions to view this page. Please contact your administrator if you believe this is an error.";
        }
        
        $data['user_role'] = $user_role;
        $data['user_name'] = $user_name;
        $data['show_back_button'] = true;
        
        // Log the access attempt
        log_message('info', "Access denied for user: {$user_name} (Role: {$user_role}) to URL: " . current_url());
        
        $this->output->set_status_header(403);
        $this->load->view('errors/error_page', $data);
    }

    public function not_found() {
        $data['title'] = 'Page Not Found';
        $data['error_code'] = '404';
        $data['error_title'] = 'Page Not Found';
        $data['error_message'] = 'The page you are looking for could not be found.';
        $data['error_description'] = 'The requested URL was not found on this server. Please check the URL and try again.';
        $data['show_back_button'] = true;
        
        $this->output->set_status_header(404);
        $this->load->view('errors/error_page', $data);
    }

    public function server_error() {
        $data['title'] = 'Server Error';
        $data['error_code'] = '500';
        $data['error_title'] = 'Internal Server Error';
        $data['error_message'] = 'Something went wrong on our end.';
        $data['error_description'] = 'We are experiencing technical difficulties. Please try again later or contact support if the problem persists.';
        $data['show_back_button'] = true;
        
        $this->output->set_status_header(500);
        $this->load->view('errors/error_page', $data);
    }

    public function csrf_error() {
        $data['title'] = 'Security Error';
        $data['error_code'] = '403';
        $data['error_title'] = 'Security Token Error';
        $data['error_message'] = 'The security token has expired or is invalid.';
        $data['error_description'] = 'For your security, this form has expired. Please refresh the page and try again.';
        $data['show_back_button'] = true;
        
        $this->output->set_status_header(403);
        $this->load->view('errors/error_page', $data);
    }

    public function maintenance() {
        $data['title'] = 'Under Maintenance';
        $data['error_code'] = '503';
        $data['error_title'] = 'Site Under Maintenance';
        $data['error_message'] = 'We are currently performing scheduled maintenance.';
        $data['error_description'] = 'Our system is temporarily unavailable while we perform important updates. Please check back shortly.';
        $data['show_back_button'] = false;
        
        $this->output->set_status_header(503);
        $this->load->view('errors/error_page', $data);
    }
}
