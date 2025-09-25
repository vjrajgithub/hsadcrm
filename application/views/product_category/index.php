
    <section class="content-header d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-list-alt"></i> Product/Service Categories</h1>
        <button class="btn btn-success btn-sm" onclick="openForm()">
            <i class="fas fa-plus"></i> Add Category
        </button>
    </section>

    <section class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                <table id="productCategoryTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $i => $row): ?>
                          <tr>
                              <td><?= $i + 1 ?></td>
                              <td><?= htmlspecialchars($row->name) ?></td>
                              <td class="text-center">
                                  <button class="btn btn-sm btn-primary" onclick="openForm(<?= $row->id ?>)">
                                      <i class="fas fa-edit"></i>
                                  </button>
                                  <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?= $row->id ?>)">
                                      <i class="fas fa-trash-alt"></i>
                                  </button>
                              </td>
                          </tr>
                        <?php endforeach; ?>
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
      $('#productCategoryTable').DataTable({
          responsive: true,
          autoWidth: false,
          dom: 'Bfrtip',
          buttons: [
              {extend: 'copy', className: 'btn btn-sm btn-secondary'},
              {extend: 'csv', className: 'btn btn-sm btn-info'},
              {extend: 'excel', className: 'btn btn-sm btn-success'},
              {extend: 'pdf', className: 'btn btn-sm btn-danger'},
              {extend: 'print', className: 'btn btn-sm btn-warning'}
          ]
      });
  });

  function openForm(id = null) {
      $.get('<?= site_url('product-category/form') ?>/' + (id || ''), function (html) {
          $('#formContainer').html(html);
          $('#categoryModal').modal('show');
      });
  }

  function deleteCategory(id) {
      Swal.fire({
          title: 'Delete this category?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              $.get('<?= site_url('product-category/delete') ?>/' + id, function () {
                  Swal.fire('Deleted!', 'Category has been removed.', 'success').then(() => {
                      location.reload();
                  });
              });
          }
      });
  }
</script>
