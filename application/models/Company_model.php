<?php

class Company_model extends CI_Model {

  private $table = 'companies';

  public function get_all() {
    return $this->db->get($this->table)->result();
  }

  public function get($id) {
    return $this->db->where('id', $id)->get($this->table)->row();
  }

  public function insert($data) {
    return $this->db->insert($this->table, $data);
  }

  public function update($id, $data) {
    return $this->db->where('id', $id)->update($this->table, $data);
  }

  public function delete($id) {
    return $this->db->where('id', $id)->delete($this->table);
  }

  public function count_all() {
    return $this->db->count_all($this->table);
  }

  public function all() {
    return $this->db->get('companies')->result();
  }

  public function get_by_id($id) {
    return $this->db->get_where('companies', ['id' => $id])->row();
  }

//  public function get($id) {
//    return $this->db->get_where('companies', ['id' => $id])->row();
//  }
//  public function update($id, $data) {
//    $this->db->where('id', $id);
//    return $this->db->update('companies', $data);
//  }
//
//  public function insert($data) {
//    return $this->db->insert('companies', $data);
//  }


  public function get_state($company_id) {
    return $this->db->select('state')
                    ->from('companies')
                    ->where('id', $company_id)
                    ->get()
                    ->row('state');
  }

  public function get_states() {
    return [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
        'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
        'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana',
        'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Andaman & Nicobar Islands',
        'Chandigarh', 'Dadra & Nagar Haveli and Daman & Diu', 'Delhi', 'Jammu & Kashmir',
        'Ladakh', 'Lakshadweep', 'Puducherry'
    ];
  }

}
