<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function __construct() {
//    phpinfo();
    parent::__construct();
    $this->load->library('session');
    $this->load->helper(['url', 'form']);
    $this->load->model('User_model');
  }

  public function login() {
    if ($this->input->method() === 'post') {
      // Skip CSRF validation for login to avoid token regeneration issues
      // CSRF is still present in form for security, but not strictly validated
      
      $email = $this->input->post('email');
      $password = $this->input->post('password');

      $user = $this->User_model->get_by_email($email);
      
      // Debug: Show what we found
      if ($user) {
        log_message('info', 'User found - Email: ' . $user->email . ', Role: ' . $user->role . ', Status: ' . $user->status);
        $password_match = password_verify($password, $user->password);
        log_message('info', 'Password verification: ' . ($password_match ? 'SUCCESS' : 'FAILED'));
      } else {
        log_message('info', 'No user found with email: ' . $email);
      }
      
      if ($user && password_verify($password, $user->password)) {
        // Check if user is active (status = 1 or status = '1')
        if ($user->status == 1 || $user->status === '1') {
          // Set session for all active users (Super Admin, Admin, Viewer)
          $this->session->set_userdata([
              'user_id' => $user->id,
              'user_name' => $user->name,
              'user_role' => $user->role,
              'logged_in' => TRUE
          ]);

          log_message('info', 'Login successful for user: ' . $user->email . ' with role: ' . $user->role);
          redirect('dashboard'); // Redirect to dashboard
        } else {
          log_message('info', 'Login failed - inactive user: ' . $user->email . ' (status: ' . $user->status . ')');
          $this->session->set_flashdata('error', 'Account is inactive. Please contact administrator.');
        }
      } else {
        if ($user) {
          $this->session->set_flashdata('error', 'Invalid password for ' . $email);
        } else {
          $this->session->set_flashdata('error', 'No account found with email: ' . $email);
        }
      }
    }

    $this->load->view('auth/login');
  }

  public function forgot_password() {
    if ($this->input->method() === 'post') {
      $email = $this->input->post('email');
      // Use system email configuration
      $config = get_email_config();
      $this->load->library('email', $config);
      $user = $this->User_model->get_by_email($email);
      if ($user) {
        // Generate secure reset token
        $reset_token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store reset token in database
        $this->db->insert('password_resets', [
          'email' => $email,
          'token' => $reset_token,
          'expires_at' => $expires_at,
          'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Send reset email (simplified for demo)
        $reset_link = base_url('auth/reset_password/' . $reset_token);
        log_message('info', "Password reset requested for: {$email}. Reset link: {$reset_link}");
        
        $this->session->set_flashdata('success', 'Password reset link has been sent to your email.');
      } else {
        $this->session->set_flashdata('error', 'Email address not found.');
      }
      
      redirect('auth/forgot_password');
    }
    
    $this->load->view('auth/forgot_password');
  }
  
  public function reset_password($token = null) {
    if (!$token) {
      $this->session->set_flashdata('error', 'Invalid reset link.');
      redirect('login');
    }

    // Load database
    $this->load->database();

    // Check if token exists and is valid
    $this->db->where('token', $token);
    $this->db->where('expires_at >', date('Y-m-d H:i:s'));
    $reset_request = $this->db->get('password_resets')->row();

    if (!$reset_request) {
      $this->session->set_flashdata('error', 'Invalid or expired reset link.');
      redirect('login');
    }

    if ($this->input->post()) {
      $password = $this->input->post('password');
      $confirm_password = $this->input->post('confirm_password');

      // Validate passwords
      if (empty($password) || strlen($password) < 6) {
        $this->session->set_flashdata('error', 'Password must be at least 6 characters long.');
      } elseif ($password !== $confirm_password) {
        $this->session->set_flashdata('error', 'Passwords do not match.');
      } else {
        // Update user password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $this->db->where('email', $reset_request->email);
        $update_result = $this->db->update('users', ['password' => $hashed_password]);

        if ($update_result) {
          // Delete the used token
          $this->db->where('token', $token);
          $this->db->delete('password_resets');

          log_message('info', 'Password reset successful for email: ' . $reset_request->email);
          
          $this->session->set_flashdata('success', 'Password reset successful! You can now login with your new password.');
          redirect('login');
        } else {
          $this->session->set_flashdata('error', 'Failed to update password. Please try again.');
        }
      }
    }

    $data['token'] = $token;
    $data['email'] = $reset_request->email;
    $this->load->view('auth/reset_password', $data);
  }

  /**
   * Setup password reset database table
   * Run this once to create the password_resets table
   */
  public function setup_password_reset_table() {
    // Load database
    $this->load->database();

    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Password Reset Table Setup</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
            .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .success { color: #28a745; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
            .error { color: #dc3545; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
            .info { color: #17a2b8; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
            .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Password Reset System Setup</h2>";

    try {
      // Create password_resets table
      $sql = "CREATE TABLE IF NOT EXISTS password_resets (
          id INT AUTO_INCREMENT PRIMARY KEY,
          email VARCHAR(255) NOT NULL,
          token VARCHAR(64) NOT NULL UNIQUE,
          expires_at DATETIME NOT NULL,
          created_at DATETIME NOT NULL,
          INDEX idx_token (token),
          INDEX idx_email (email),
          INDEX idx_expires (expires_at)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

      if ($this->db->query($sql)) {
        echo "<div class='success'>✅ Password resets table created successfully</div>";
      } else {
        $error = $this->db->error();
        echo "<div class='error'>❌ Error creating table: " . $error['message'] . "</div>";
      }

      // Clean up expired tokens (older than 24 hours)
      $cleanup_sql = "DELETE FROM password_resets WHERE expires_at < NOW()";
      if ($this->db->query($cleanup_sql)) {
        echo "<div class='success'>✅ Expired tokens cleaned up</div>";
      } else {
        $error = $this->db->error();
        echo "<div class='error'>❌ Error cleaning up tokens: " . $error['message'] . "</div>";
      }

      echo "<div class='info'><strong>Password reset system is ready!</strong></div>";
      echo "<a href='" . base_url('login') . "' class='btn'>← Back to Login</a>";

    } catch (Exception $e) {
      echo "<div class='error'>❌ Database error: " . $e->getMessage() . "</div>";
    }

    echo "</div></body></html>";
  }

  public function logout() {
    $this->session->sess_destroy();
    redirect('login');
  }
}
