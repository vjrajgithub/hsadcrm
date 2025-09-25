<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MailTest extends MY_Auth_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper(['url', 'form']);
  }

  public function index() {
    // Render a simple form
    $this->load->view('templates/header');
    $this->load->view('tools/mail_test');
    $this->load->view('templates/footer');
  }

  public function send() {
    // Validate inputs
    $this->form_validation->set_rules('profile', 'SMTP Profile', 'required|in_list[godaddy,gmail]');
    $this->form_validation->set_rules('to', 'Recipient Email', 'required|valid_email');
    $this->form_validation->set_rules('subject', 'Subject', 'required|min_length[3]|max_length[200]');
    $this->form_validation->set_rules('message', 'Message', 'required|min_length[3]');

    if (!$this->form_validation->run()) {
      $this->session->set_flashdata('error', strip_tags(validation_errors()));
      redirect('mail-test');
      return;
    }

    $profile = $this->input->post('profile');

    // Define the two requested SMTP profiles
    if ($profile === 'godaddy') {
      $email_config = [
        'protocol' => 'smtp',
        'smtp_host' => 'smtpout.secureserver.net',
        'smtp_user' => 'billing@hsad.co.in',
        'smtp_pass' => 'Sdla@8851',
        'smtp_port' => 465,
        'smtp_crypto' => 'ssl',
        'smtp_timeout' => 60,
        'mailtype' => 'html',
        'charset' => 'utf-8',
        'newline' => "\r\n",
        'crlf' => "\r\n",
        'wordwrap' => true,
      ];
      $from_email = 'billing@hsad.co.in';
      $from_name  = 'HSAD MailTest (GoDaddy)';
    } else { // gmail
      $email_config = [
        'protocol' => 'smtp',
        'smtp_host' => 'smtp.gmail.com',
        'smtp_user' => 'billing@hsadindia.com',
        'smtp_pass' => 'Sdla@8851',
        'smtp_port' => 587,
        'smtp_crypto' => 'tls',
        'smtp_timeout' => 60,
        'mailtype' => 'html',
        'charset' => 'utf-8',
        'newline' => "\r\n",
        'crlf' => "\r\n",
        'wordwrap' => true,
      ];
      $from_email = 'billing@hsadindia.com';
      $from_name  = 'HSAD MailTest (Gmail)';
    }

    // Quick TCP connectivity test before attempting SMTP
    $host = $email_config['smtp_host'];
    $port = (int)$email_config['smtp_port'];
    $timeout = 10; // seconds
    $errno = 0; $errstr = '';
    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if (!$fp) {
      $this->session->set_flashdata('error', 'TCP connectivity test failed to ' . $host . ':' . $port . ' — ' . ($errstr ?: 'connection timeout/blocked') . ' (errno ' . $errno . '). Please allow outbound port in firewall/hosting or try another port/profile.');
      redirect('mail-test');
      return;
    } else {
      fclose($fp);
    }

    $to = $this->input->post('to');
    $subject = $this->input->post('subject');
    $message = $this->input->post('message');

    // Send
    $this->load->library('email', $email_config);
    $this->email->clear(true);
    $this->email->from($from_email, $from_name);
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);

    if ($this->email->send()) {
      $this->session->set_flashdata('success', 'Email sent successfully to ' . htmlspecialchars($to));
    } else {
      $this->session->set_flashdata('error', 'Failed to send email. Debug: ' . $this->email->print_debugger(['headers'])) ;
    }

    redirect('mail-test');
  }
}
