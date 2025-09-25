<!-- Category Form Modal -->
<form id="categoryForm" method="post">
    <input type="hidden" name="id" value="<?= isset($category->id) ? $category->id : '' ?>">

    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><?= isset($category) ? 'Edit' : 'Add' ?> Category</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$category->name) ?>" required>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    </div>
</form>

<script>
  $(function () {
      $('#categoryForm').validate({
          errorClass: 'is-invalid',
          validClass: 'is-valid',
          errorElement: 'div',
          errorPlacement: function (error, element) {
              error.addClass('invalid-feedback');
              error.insertAfter(element);
          },
          highlight: function (element) {
              $(element).addClass('is-invalid');
          },
          unhighlight: function (element) {
              $(element).removeClass('is-invalid');
          },
          submitHandler: function (form) {
              $.ajax({
                  url: '<?= site_url('product-category/save') ?>',
                  method: 'POST',
                  data: $(form).serialize(),
                  dataType: 'json',
                  success: function (res) {
                      if (res.status === 'success') {
                          $('#categoryModal').modal('hide');
                          Swal.fire('Success', res.message, 'success').then(() => {
                              location.reload();
                          });
                      } else {
                          Swal.fire('Error', res.message, 'error');
                      }
                  },
                  error: function () {
                      Swal.fire('Error', 'Something went wrong.', 'error');
                  }
              });
              return false;
          }
      });
  });
</script>
