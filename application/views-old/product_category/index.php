<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-list-alt"></i> Product/Service Categories</h1>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-success float-sm-right" onclick="openForm()">
                    <i class="fas fa-plus"></i> Add Category
                </button>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="productCategoryTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
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
                                      <div class="btn-group" role="group">
                                          <button class="btn btn-sm btn-primary" onclick="openForm(<?= $row->id ?>)" title="Edit">
                                              <i class="fas fa-edit"></i>
                                              <span class="d-none d-sm-inline">Edit</span>
                                          </button>
                                          <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?= $row->id ?>)" title="Delete">
                                              <i class="fas fa-trash-alt"></i>
                                              <span class="d-none d-sm-inline">Delete</span>
                                          </button>
                                      </div>
                                  </td>
                              </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
      if (!$.fn.DataTable.isDataTable('#productCategoryTable')) {
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
      }
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
