<div class="modal-header bg-primary text-white">
    <h4 class="modal-title">
        <i class="fas fa-tags mr-2"></i>
        <?= isset($category) ? 'Edit Category' : 'Add New Category' ?>
    </h4>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="categoryForm" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="id" value="<?= @$category->id ?>">

    <div class="modal-body">
        <div class="form-group">
            <label><i class="fas fa-tag text-primary mr-1"></i> Category Name *</label>
            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$category->name) ?>" placeholder="Enter category name" required>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Save Category
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancel
        </button>
    </div>
</form>

<script>
  $('#categoryForm').validate({
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
          name: {required: true}
      },
      messages: {
          name: "Please enter category name"
      },
      submitHandler: function (form) {
          $.ajax({
              url: '<?= site_url('category/ajax_save') ?>',
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
                          if (typeof refreshTable === 'function') {
                              refreshTable();
                          } else {
                              $('#categoryModal').modal('hide');
                              $('#categoryTable').DataTable().ajax.reload();
                          }
                      });
                  } else {
                      Swal.fire('Error', res.message, 'error');
                  }
              }
          });
      }
  });
</script>
