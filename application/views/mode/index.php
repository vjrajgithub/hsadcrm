
<section class="content-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-clock"></i> Mode Management</h1>
    <button class="btn btn-sm btn-success" onclick="openForm()">
        <i class="fas fa-plus"></i> Add Mode
    </button>
</section>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="modeTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Days</th>
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
</div>

<!-- Modal -->
<div class="modal fade" id="modeModal">
    <div class="modal-dialog">
        <div class="modal-content" id="formContainer"></div>
    </div>
</div>

<script>
  $(document).ready(function () {
      var table = $('#modeTable').DataTable({
          ajax: '<?php echo site_url('mode/list'); ?>',
          columns: [
              {data: null},
              {data: 'name'},
              {data: 'days'},
              {
                  data: null,
                  render: function (data, type, row) {
                      let actions = '<div class="btn-group">';
                      actions += '<button type="button" class="btn btn-sm btn-primary edit-mode" data-id="' + row.id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                      actions += '<button type="button" class="btn btn-sm btn-danger delete-mode" data-id="' + row.id + '" title="Delete"><i class="fas fa-trash"></i></button>';
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
      $('#modeTable').on('click', '.edit-mode', function () {
          var id = $(this).data('id');
          openForm(id);
      });

      $('#modeTable').on('click', '.delete-mode', function () {
          var id = $(this).data('id');
          deleteMode(id);
      });
  });

  function openForm(id = null) {
      $.get('<?= site_url('mode/form') ?>/' + (id || ''), function (html) {
          $('#formContainer').html(html);
          $('#modeModal').modal('show');
      });
  }

  function deleteMode(id) {
      Swal.fire({
          title: 'Are you sure?',
          text: "This will permanently delete the mode.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              $.get('<?php echo site_url('mode/delete'); ?>/' + id, function (response) {
                  var result = JSON.parse(response);
                  if (result.status === 'success') {
                      Swal.fire('Deleted!', result.message, 'success').then(() => {
                          $('#modeTable').DataTable().ajax.reload();
                      });
                  } else {
                      Swal.fire('Error!', result.message, 'error');
                  }
              }).fail(function () {
                  Swal.fire('Error!', 'Failed to delete mode.', 'error');
              });
          }
      });
  }

  // Function to handle form submission and refresh table
  function refreshTable() {
      $('#modeTable').DataTable().ajax.reload();
      $('#modeModal').modal('hide');
  }
</script>
