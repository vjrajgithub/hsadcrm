<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model {

  protected $table = 'users';

  public function get_all() {
    $this->db->order_by('id', 'DESC');
    return $this->db->get($this->table)->result();
  }

  public function get_by_email($email) {
    return $this->db->get_where('users', ['email' => $email])->row();
  }

  public function get($id) {
    return $this->db->get_where($this->table, ['id' => $id])->row();
  }

  public function insert($data) {
    // Remove CSRF and id fields before insert
    unset($data['csrf_test_name'], $data['id']);
    
    if (isset($data['password'])) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    
    if ($this->db->insert($this->table, $data)) {
      return $this->db->insert_id();
    }
    return false;
  }

  public function update($id, $data) {
    // Remove CSRF and id fields before update
    unset($data['csrf_test_name'], $data['id']);
    
    if (!empty($data['password'])) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    } else {
      unset($data['password']); // Don't update if not provided
    }
    
    return $this->db->update($this->table, $data, ['id' => $id]);
  }

  public function delete($id) {
    return $this->db->delete($this->table, ['id' => $id]);
  }

  public function get_datatables() {
    $query = $this->db->get($this->table);
    return $query->result();
  }

}
