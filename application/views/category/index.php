
    <section class="content-header d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tags"></i> Category Management</h1>
        <button class="btn btn-sm btn-success" onclick="openForm()">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </section>

    <section class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                <table id="categoryTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Category Name</th>
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
<div class="modal fade" id="categoryModal">
    <div class="modal-dialog">
        <div class="modal-content" id="formContainer"></div>
    </div>
</div>

<script>
  $(document).ready(function () {
      var table = $('#categoryTable').DataTable({
          ajax: '<?php echo site_url('category/list'); ?>',
          columns: [
              {data: null},
              {data: 'name'},
              {
                  data: null,
                  render: function (data, type, row) {
                      let actions = '<div class="btn-group">';
                      actions += '<button type="button" class="btn btn-sm btn-primary edit-category" data-id="' + row.id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                      actions += '<button type="button" class="btn btn-sm btn-danger delete-category" data-id="' + row.id + '" title="Delete"><i class="fas fa-trash"></i></button>';
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
      $('#categoryTable').on('click', '.edit-category', function () {
          var id = $(this).data('id');
          openForm(id);
      });

      $('#categoryTable').on('click', '.delete-category', function () {
          var id = $(this).data('id');
          deleteCategory(id);
      });
  });

  function openForm(id = null) {
      $.get('<?php echo site_url('category/form'); ?>/' + (id || ''), function (html) {
          $('#formContainer').html(html);
          $('#categoryModal').modal('show');
      });
  }

  function deleteCategory(id) {
      Swal.fire({
          title: 'Are you sure?',
          text: "This will permanently delete the category.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              $.get('<?php echo site_url('category/delete'); ?>/' + id, function (response) {
                  var result = JSON.parse(response);
                  if (result.status === 'success') {
                      Swal.fire('Deleted!', result.message, 'success').then(() => {
                          $('#categoryTable').DataTable().ajax.reload();
                      });
                  } else {
                      Swal.fire('Error!', result.message, 'error');
                  }
              }).fail(function () {
                  Swal.fire('Error!', 'Failed to delete category.', 'error');
              });
          }
      });
  }

  // Function to handle form submission and refresh table
  function refreshTable() {
      $('#categoryTable').DataTable().ajax.reload();
      $('#categoryModal').modal('hide');
  }
</script>
