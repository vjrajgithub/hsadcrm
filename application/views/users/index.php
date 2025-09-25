<div class="content-header d-flex justify-content-between align-items-center">

    <h1>User Management</h1>
    <?php if (isset($controller) && $controller->has_permission('add_users')): ?>
      <button class="btn btn-sm btn-success float-right" onclick="openUserForm()"><i class="fas fa-plus"></i> Add User</button>
    <?php endif; ?>

</div>

<div class="content">

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="userTable" class="table table-bordered table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="userForm" method="post">
                <input type="hidden" name="id" value="">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token">

                <div class="modal-body">

                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Role *</label>
                        <select name="role" id="roleSelect" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="super admin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="viewer">Viewer</option>
                        </select>
                        <small id="roleDebug" class="text-muted"></small>
                    </div>

                    <div class="form-group" id="passwordGroup">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
        </div>
        </form>
    </div>
</div>

<script>
  $(document).ready(function () {
      const table = $('#userTable').DataTable({
          ajax: '<?= site_url('user/list') ?>',
          columns: [
              {data: null},
              {data: 'name'},
              {data: 'email'},
              {data: 'role'},
              {
                  data: null,
                  render: function (data, type, row) {
                      let actions = '';
                      // Check permissions via PHP session data
<?php if (isset($controller) && $controller->has_permission('edit_users')): ?>
                        actions += `<button class="btn btn-sm btn-info" onclick="openUserForm(${row.id})"><i class="fas fa-edit"></i></button> `;
<?php endif; ?>

<?php if (isset($controller) && $controller->has_permission('delete_users')): ?>
                        actions += `<button class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})"><i class="fas fa-trash"></i></button>`;
<?php endif; ?>

                      return actions || '<span class="text-muted">No actions available</span>';
                  }
              }
          ],
          responsive: true,
          autoWidth: false,
          dom: 'Bfrtip',

          buttons: [
              {extend: 'copy', className: 'btn btn-sm btn-secondary'},
              {extend: 'csv', className: 'btn btn-sm btn-info'},
              {extend: 'excel', className: 'btn btn-sm btn-success'},
              {extend: 'pdf', className: 'btn btn-sm btn-danger'},
              {extend: 'print', className: 'btn btn-sm btn-warning'}
          ],
          order: [],
          columnDefs: [{targets: 0, orderable: false}],
          createdRow: function (row, data, index) {
              $('td:eq(0)', row).html(index + 1);
          }
      });

      $('#userForm').validate({
          rules: {
              name: 'required',
              email: {
                  required: true,
                  email: true
              },
              role: 'required',
              password: {
                  required: function () {
                      return $('input[name="id"]').val() === '';
                  },
                  minlength: 4
              }
          },
          submitHandler: function (form) {
              $.ajax({
                  url: '<?= site_url('user/ajax_save') ?>',
                  type: 'POST',
                  data: $(form).serialize(),
                  dataType: 'json',
                  beforeSend: function () {
                      Swal.fire({title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
                  },
                  success: function (data) {
                      Swal.close();
                      if (data.status) {
                          $('#userModal').modal('hide');
                          table.ajax.reload();
                          Swal.fire('Success!', data.message, 'success');
                          // Refresh CSRF token after successful submission
                          if (data.csrf_hash) {
                              $('#csrf_token').val(data.csrf_hash);
                          }
                      } else {
                          Swal.fire('Error!', data.message, 'error');
                      }
                  },
                  error: function () {
                      Swal.close();
                      Swal.fire('Error!', 'Something went wrong!', 'error');
                  }
              });
          }
      });
  }
  );

  function openUserForm(id = '') {
      $('#userForm')[0].reset();
      $('#userForm').validate().resetForm();
      $('#userForm input[name=id]').val('');
      $('#passwordGroup').show();
      $('.modal-title').text('Add User');

      if (id) {
          $('.modal-title').text('Edit User');
          $('#passwordGroup').hide();

          $.get('<?= site_url('user/get') ?>/' + id, function (user) {
              console.log('User data received:', user);
              $('#roleDebug').text('Loading role: ' + user.role);

              // Populate form fields
              $('#userForm input[name=name]').val(user.name);
              $('#userForm input[name=email]').val(user.email);
              $('#userForm input[name=id]').val(id);

              // Show modal after data is loaded
              $('#userModal').modal('show');

              // Use multiple approaches to set the role
              setTimeout(function () {
                  // Convert role to lowercase to match option values
                  var roleValue = user.role.toLowerCase();
                  console.log('Converting role from "' + user.role + '" to "' + roleValue + '"');

                  // Method 1: Direct value assignment
                  document.getElementById('roleSelect').value = roleValue;

                  // Method 2: jQuery val()
                  $('#roleSelect').val(roleValue);

                  // Method 3: Find and select the matching option
                  $('#roleSelect option').each(function () {
                      if (this.value === roleValue) {
                          this.selected = true;
                          $(this).attr('selected', 'selected');
                      } else {
                          this.selected = false;
                          $(this).removeAttr('selected');
                      }
                  });

                  // Debug output
                  var selectedValue = $('#roleSelect').val();
                  var selectedText = $('#roleSelect option:selected').text();
                  console.log('Final selected value:', selectedValue);
                  console.log('Final selected text:', selectedText);
                  $('#roleDebug').text('Set to: ' + user.role + ' | Selected: ' + selectedValue);

              }, 500);

          }, 'json').fail(function () {
              console.error('Failed to load user data');
              $('#userModal').modal('show');
          });
      } else {
          $('#userModal').modal('show');
  }
  }

  function deleteUser(id) {
      Swal.fire({
          title: 'Are you sure?',
          text: "This will permanently delete the user!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: '<?= site_url('user/delete') ?>/' + id,
                  type: 'GET',
                  dataType: 'json',
                  beforeSend: function () {
                      Swal.fire({title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
                  },
                  success: function (data) {
                      Swal.close();
                      if (data.status) {
                          $('#userTable').DataTable().ajax.reload();
                          Swal.fire('Deleted!', data.message, 'success');
                      } else {
                          Swal.fire('Error!', data.message, 'error');
                      }
                  },
                  error: function () {
                      Swal.close();
                      Swal.fire('Error!', 'Something went wrong!', 'error');
                  }
              });
          }
      });
  }
</script>
