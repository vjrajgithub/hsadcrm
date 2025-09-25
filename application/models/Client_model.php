<?php

class Client_model extends CI_Model {

  public function get_all() {
    $this->db->select('clients.*, companies.name as company_name');
    $this->db->from('clients');
    $this->db->join('companies', 'companies.id = clients.company_id', 'left');
    $this->db->order_by('clients.id', 'DESC');
    return $this->db->get()->result();
  }

  public function all() {
    return $this->get_all();
  }

  public function get($id) {
    return $this->db->get_where('clients', ['id' => $id])->row();
  }

  public function insert($data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    return $this->db->insert('clients', $data);
  }

  public function update($id, $data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    $this->db->where('id', $id);
    return $this->db->update('clients', $data);
  }

  public function delete($id) {
    return $this->db->delete('clients', ['id' => $id]);
  }

  public function get_clients_by_company($company_id) {
    return $this->db->get_where('clients', ['company_id' => $company_id])->result();
  }

  public function get_by_company($company_id) {
    return $this->db->get_where('clients', ['company_id' => $company_id])->result();
  }

  public function get_contacts($client_id) {
    return $this->db->get_where('client_contacts', ['client_id' => $client_id])->result();
  }

}
