<?php

class Contact_model extends CI_Model {

  public function get_all() {
    $this->db->select('client_contacts.*, clients.name AS client_name');
    $this->db->from('client_contacts');
    $this->db->join('clients', 'clients.id = client_contacts.client_id', 'left');
    $this->db->order_by('client_contacts.id', 'DESC');
    return $this->db->get()->result();
  }

  public function get($id) {
    return $this->db->get_where('client_contacts', ['id' => $id])->row();
  }

  public function insert($data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    return $this->db->insert('client_contacts', $data);
  }

  public function update($id, $data) {
    // Remove any fields that shouldn't be in the database
    unset($data['id']);
    unset($data['csrf_test_name']);
    
    $this->db->where('id', $id);
    return $this->db->update('client_contacts', $data);
  }

  public function delete($id) {
    return $this->db->where('id', $id)->delete('client_contacts');
  }

  public function get_contacts_by_client($client_id) {
    return $this->db->get_where('client_contacts', ['client_id' => $client_id])->result();
  }

}
