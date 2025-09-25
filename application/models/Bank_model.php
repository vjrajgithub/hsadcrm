<?php

class Bank_model extends CI_Model {

  public function get_all() {
    $this->db->select('banks.*, companies.name as company_name');
    $this->db->from('banks');
    $this->db->join('companies', 'companies.id = banks.company_id');
    return $this->db->get()->result();
  }

  public function get_all_with_company() {
    $this->db->select('banks.*, companies.name as company_name');
    $this->db->from('banks');
    $this->db->join('companies', 'companies.id = banks.company_id', 'left');
    $this->db->order_by('banks.id', 'DESC');
    return $this->db->get()->result();
  }

  public function all() {
    $this->db->select('banks.*, companies.name as company_name');
    $this->db->from('banks');
    $this->db->join('companies', 'companies.id = banks.company_id');
    return $this->db->get()->result();
  }

  public function get($id) {
    return $this->db->get_where('banks', ['id' => $id])->row();
  }

  public function insert($data) {
    return $this->db->insert('banks', $data);
  }

  public function update($id, $data) {
    return $this->db->update('banks', $data, ['id' => $id]);
  }

  public function delete($id) {
    return $this->db->delete('banks', ['id' => $id]);
  }

  public function get_by_company($company_id) {
    return $this->db->get_where('banks', ['company_id' => $company_id])->result();
  }

  public function get_banks_by_company($company_id) {
    return $this->db->get_where('banks', ['company_id' => $company_id])->result();
  }
}
