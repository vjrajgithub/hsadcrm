<h4>Users</h4>
<a href="<?= base_url('users/create') ?>" class="btn btn-success btn-sm mb-3">➕ Add User</a>

<table class="table table-bordered table-sm">
    <thead>
        <tr><th>Username</th><th>Role</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
              <td><?= $u->username ?></td>
              <td><?= ucfirst($u->role) ?></td>
              <td><?= $u->active ? 'Active' : 'Inactive' ?></td>
              <td>
                  <a href="<?= base_url('users/edit/' . $u->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="<?= base_url('users/delete/' . $u->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</a>
              </td>
          </tr>
        <?php endforeach ?>
    </tbody>
</table>
