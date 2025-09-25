<section class="content-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-building"></i> Company Management</h1>
    <button class="btn btn-sm btn-success" onclick="openForm()">
        <i class="fas fa-plus"></i> Add Company
    </button>
</section>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="companyTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Logo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="companyModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="formContainer"></div>
    </div>
</div>

<script>
  $(document).ready(function () {
      // Store DataTable instance in a wider scope for later reloads
      window.companyTable = $('#companyTable').DataTable({
          ajax: '<?php echo site_url('company/list'); ?>',
          columns: [
              {data: null},
              {data: 'name'},
              {data: 'email'},
              {data: 'mobile'},
              {
                  data: 'logo',
                  render: function (data, type, row) {
                      if (data) {
                          return '<img src="<?php echo base_url('assets/uploads/logos/'); ?>' + data + '" width="50" class="img-thumbnail">';
                      }
                      return '<span class="text-muted">No logo</span>';
                  }
              },
              {
                  data: null,
                  render: function (data, type, row) {
                      let actions = '<div class="btn-group">';
                      actions += '<button type="button" class="btn btn-sm btn-primary edit-company" data-id="' + row.id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                      actions += '<button type="button" class="btn btn-sm btn-danger delete-company" data-id="' + row.id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                      actions += '</div>';
                      return actions;
                  }
              }
          ],
          responsive: true,
          autoWidth: false,
          dom: 'Blfrtip',
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
          },
          pageLength: 10,
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
      });

      // Event handlers for dynamically loaded buttons
      $('#companyTable').on('click', '.edit-company', function () {
          var id = $(this).data('id');
          openForm(id);
      });

      $('#companyTable').on('click', '.delete-company', function () {
          var id = $(this).data('id');
          deleteCompany(id);
      });
  });

  function openForm(id = null) {
      $.get('<?php echo site_url('company/form'); ?>/' + (id || ''), function (html) {
          $('#formContainer').html(html);
          $('#companyModal').modal('show');
      });
  }

  function deleteCompany(id) {
      Swal.fire({
          title: 'Are you sure?',
          text: "This will permanently delete the company.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: '<?php echo site_url('company/delete'); ?>/' + id,
                  type: 'GET',
                  dataType: 'json',
                  success: function (res) {
                      if (res && res.status === 'success') {
                          Swal.fire('Deleted!', res.message, 'success').then(() => {
                              if (window.companyTable) {
                                  window.companyTable.ajax.reload(null, false);
                              } else {
                                  $('#companyTable').DataTable().ajax.reload();
                              }
                          });
                      } else {
                          const msg = (res && res.message) ? res.message : 'Unexpected server response.';
                          Swal.fire('Error!', msg, 'error');
                      }
                  },
                  error: function (xhr) {
                      let msg = 'Failed to delete company.';
                      if (xhr && xhr.responseText) {
                          try { const j = JSON.parse(xhr.responseText); if (j.message) msg = j.message; } catch(e) {}
                      }
                      Swal.fire('Error!', msg, 'error');
                  }
              });
          }
      });
  }

  // Function to handle form submission and refresh table
  function refreshTable() {
      if (window.companyTable) {
          window.companyTable.ajax.reload(null, false);
      } else {
          $('#companyTable').DataTable().ajax.reload();
      }
      $('#companyModal').modal('hide');
  }
</script>
