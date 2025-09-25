<?php

class Setting_model extends CI_Model {

  public function all() {
    $settings = $this->db->get('settings')->result();
    $result = [];
    foreach ($settings as $row) {
      $result[$row->key] = $row->value;
    }
    return $result;
  }

  public function update($key, $value) {
    return $this->db->update('settings', ['value' => $value], ['key' => $key]);
  }

  public function update_all($data) {
    foreach ($data as $key => $value) {
      $this->update($key, $value);
    }
  }

}
