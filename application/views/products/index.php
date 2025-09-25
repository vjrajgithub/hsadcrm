
<section class="content-header d-flex justify-content-between align-items-center">
    <h1>Product/Service Management</h1>
    <button class="btn btn-sm btn-success" onclick="openForm()"><i class="fas fa-plus"></i> Add New</button>
</section>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="productTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Rate Per Unit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div></div>
</section>


<!-- Modal -->
<div class="modal fade" id="productModal">
    <div class="modal-dialog">
        <form id="productForm">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Product / Service</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Category *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($categories as $cat): ?>
                              <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Rate Per Unit *</label>
                        <input type="number" step="0.01" name="rate_per_unit" class="form-control" required>
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
  $(function () {
      let table = $('#productTable').DataTable({
          ajax: '<?= site_url("productservice/list") ?>',
          columns: [
              {data: null},
              {data: 'category'},
              {data: 'name'},
              {data: 'rate_per_unit'},
              {
                  data: null,
                  render: function (data, type, row) {
                      let actions = '<div class="btn-group">';
                      actions += '<button type="button" class="btn btn-sm btn-primary edit-product" data-id="' + row.id + '" title="Edit"><i class="fas fa-edit"></i></button>';
                      actions += '<button type="button" class="btn btn-sm btn-danger delete-product" data-id="' + row.id + '" title="Delete"><i class="fas fa-trash"></i></button>';
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
      $('#productTable').on('click', '.edit-product', function () {
          var id = $(this).data('id');
          editForm(id);
      });

      $('#productTable').on('click', '.delete-product', function () {
          var id = $(this).data('id');
          deleteData(id);
      });

      $('#productForm').validate({
          errorElement: 'span',
          errorClass: 'invalid-feedback',
          highlight: function (element) {
              $(element).addClass('is-invalid');
          },
          unhighlight: function (element) {
              $(element).removeClass('is-invalid');
          },
          errorPlacement: function (error, element) {
              error.appendTo(element.closest('.form-group'));
          },
          rules: {
              category_id: {required: true},
              name: {required: true},
              rate_per_unit: {required: true, number: true}
          },
          messages: {
              category_id: "Please select a category",
              name: "Please enter product/service name",
              rate_per_unit: "Please enter valid rate per unit"
          },
          submitHandler: function (form) {
              $.ajax({
                  url: '<?= site_url("productservice/ajax_save") ?>',
                  type: 'POST',
                  data: $(form).serialize(),
                  dataType: 'json',
                  beforeSend: function () {
                      Swal.fire({title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
                  },
                  success: function (res) {
                      Swal.close();
                      if (res.status === 'success') {
                          Swal.fire('Success', 'Saved successfully!', 'success').then(() => {
                              $('#productModal').modal('hide');
                              table.ajax.reload();
                          });
                      } else {
                          Swal.fire('Error', res.message, 'error');
                      }
                  }
              });
          }
      });
  });

  function openForm() {
      $('#productForm')[0].reset();
      $('#productForm input[name=id]').val('');
      $('#productModal').modal('show');
  }

  function editForm(id) {
      $.get('<?= site_url("productservice/edit/") ?>' + id, function (data) {
          $('[name=id]').val(data.id);
          $('[name=category_id]').val(data.category_id);
          $('[name=name]').val(data.name);
          $('[name=rate_per_unit]').val(data.rate_per_unit);
          $('#productModal').modal('show');
      }, 'json');
  }

  function deleteData(id) {
      Swal.fire({
          title: 'Delete this product/service?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              $.get('<?= site_url("productservice/delete/") ?>' + id, function (res) {
                  $('#productTable').DataTable().ajax.reload();
                  Swal.fire('Deleted!', 'Product/Service has been removed.', 'success');
              });
          }
      });
  }
</script>

