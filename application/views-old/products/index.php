<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-box-open"></i> Products / Services</h1>
        <button class="btn btn-success" onclick="openForm()">
            <i class="fas fa-plus"></i> Add New
        </button>
    </section>

    <section class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                <table id="productTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Rate Per Unit</th>
                            <th class="text-center" style="width:140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="productModal">
    <div class="modal-dialog modal-lg">
        <form id="productForm">
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
          ajax: '<?= site_url("productservice/get_all") ?>',
          columns: [
              { data: null },
              { data: 'category' },
              { data: 'name' },
              { data: 'rate_per_unit', render: function(val){ return parseFloat(val).toFixed(2); } },
              {
                  data: 'id',
                  className: 'text-center',
                  render: function (id) {
                      return `
                        <button class=\"btn btn-sm btn-primary mr-1\" onclick=\"editForm(${id})\"><i class=\"fas fa-edit\"></i></button>
                        <button class=\"btn btn-sm btn-danger\" onclick=\"deleteData(${id})\"><i class=\"fas fa-trash\"></i></button>
                      `;
                  }
              }
          ],
          dom: 'Bfrtip',
          buttons: [
              {extend: 'copy', className: 'btn btn-sm btn-secondary'},
              {extend: 'csv', className: 'btn btn-sm btn-info'},
              {extend: 'excel', className: 'btn btn-sm btn-success'},
              {extend: 'pdf', className: 'btn btn-sm btn-danger'},
              {extend: 'print', className: 'btn btn-sm btn-warning'}
          ],
          responsive: true,
          autoWidth: false,
          order: [],
          columnDefs: [{targets: 0, orderable: false}],
          createdRow: function (row, data, index) {
              $('td:eq(0)', row).html(index + 1);
          }
      });

      $('#productForm').validate({
          errorClass: 'is-invalid',
          validClass: 'is-valid',
          errorElement: 'div',
          errorPlacement: function (error, element) {
              error.addClass('invalid-feedback');
              error.insertAfter(element);
          },
          highlight: function (element) { $(element).addClass('is-invalid'); },
          unhighlight: function (element) { $(element).removeClass('is-invalid'); },
          rules: {
              category_id: { required: true },
              name: { required: true, minlength: 2 },
              rate_per_unit: { required: true, number: true, min: 0 }
          },
          submitHandler: function (form) {
              $.post('<?= site_url("productservice/save") ?>', $(form).serialize(), function (res) {
                  if (res.status == 'success') {
                      $('#productModal').modal('hide');
                      table.ajax.reload();
                      Swal.fire('Saved!', 'Product/Service saved successfully', 'success');
                  } else {
                      Swal.fire('Error', res.message || 'Unable to save', 'error');
                  }
              }, 'json');
          }
      });
  });

  function openForm() {
      $('#productForm')[0].reset();
      $('#productForm').validate().resetForm();
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
          title: 'Are you sure?',
          text: 'This will delete the record permanently',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete'
      }).then((result) => {
          if (result.isConfirmed) {
              $.get('<?= site_url("productservice/delete/") ?>' + id, function () {
                  $('#productTable').DataTable().ajax.reload();
                  Swal.fire('Deleted!', '', 'success');
              });
          }
      });
  }
</script>

