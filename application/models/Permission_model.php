<?php

class Permission_model extends CI_Model {

  public function all() {
    return $this->db->get('permissions')->result();
  }

  public function save($data) {
    $this->db->truncate('permissions');
    foreach ($data['roles'] as $role => $modules) {
      foreach ($modules as $module => $actions) {
        foreach ($actions as $action) {
          $this->db->insert('permissions', [
              'role' => $role,
              'module' => $module,
              'action' => $action
          ]);
        }
      }
    }
  }

  public function has_permission($role, $module, $action) {
    return $this->db->get_where('permissions', [
                'role' => $role,
                'module' => $module,
                'action' => $action
            ])->num_rows() > 0;
  }

}
