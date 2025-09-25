<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>User Management</h1>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-success float-sm-right" onclick="openUserForm()">
                    <i class="fas fa-plus"></i> Add User
                </button>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th class="d-none d-md-table-cell">Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="userForm" method="post">
            <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
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
                        <select name="role" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="super admin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="viewer">Viewer</option>
                        </select>
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
      if (!$.fn.DataTable.isDataTable('#userTable')) {
          const table = $('#userTable').DataTable({
              ajax: '<?= site_url('user/list') ?>',
              columns: [
                  {data: null},
                  {
                      data: 'name',
                      render: function(data, type, row) {
                          let html = '<strong>' + data + '</strong>';
                          if ($(window).width() <= 768) {
                              html += '<div class="d-md-none small text-muted">' + row.email + '</div>';
                          }
                          return html;
                      }
                  },
                  {
                      data: 'email',
                      className: 'd-none d-md-table-cell'
                  },
                  {data: 'role'},
                  {
                      data: null,
                      render: function (data, type, row) {
                          return `
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info" onclick="openUserForm(${row.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                        <span class="d-none d-sm-inline">Edit</span>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(${row.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                        <span class="d-none d-sm-inline">Delete</span>
                    </button>
                </div>
              `;
                      }
                  }
              ],
              dom: 'Bfrtip',
              buttons: [
                  {extend: 'csv', className: 'btn btn-sm btn-outline-info'},
                  {extend: 'excel', className: 'btn btn-sm btn-outline-success'},
                  {extend: 'print', className: 'btn btn-sm btn-outline-primary'}
              ],
              responsive: true,
              order: [],
              columnDefs: [
                  {targets: 0, orderable: false},
                  { responsivePriority: 1, targets: 1 },
                  { responsivePriority: 2, targets: -1 }
              ],
              createdRow: function (row, data, index) {
                  $('td:eq(0)', row).html(index + 1);
              }
          });
      }

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
              $.post('<?= site_url('user/save') ?>', $(form).serialize(), function (res) {
                  const data = JSON.parse(res);
                  if (data.status === 'success') {
                      $('#userModal').modal('hide');
                      table.ajax.reload();
                      Swal.fire('Success!', data.message, 'success');
                  } else {
                      Swal.fire('Error!', data.message, 'error');
                  }
              });
          }
      });
  });

  function openUserForm(id = '') {
      $('#userForm')[0].reset();
      if ($('#userForm').data('validator')) {
          $('#userForm').data('validator').resetForm();
      }
      $('#userForm input[name=id]').val('');
      $('#passwordGroup').show();
      $('.modal-title').text('Add User');

      if (id) {
          $.get('<?= site_url('user/get') ?>/' + id, function (userData) {
              const user = JSON.parse(userData);
              $('#userForm input[name=id]').val(user.id);
              $('#userForm input[name=name]').val(user.name);
              $('#userForm input[name=email]').val(user.email);
              $('#userForm select[name=role]').val(user.role);
              $('#passwordGroup').hide(); // Hide password field when editing
              $('.modal-title').text('Edit User');
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
              $.get('<?= site_url('user/delete') ?>/' + id, function (res) {
                  const data = JSON.parse(res);
                  if (data.status === 'success') {
                      $('#userTable').DataTable().ajax.reload();
                      Swal.fire('Deleted!', data.message, 'success');
                  }
              });
          }
      });
  }
</script>
