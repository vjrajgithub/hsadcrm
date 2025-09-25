<form id="categoryForm" method="post">
    <input type="hidden" name="id" value="<?= @$category->id ?>">

    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><?= @$category ? 'Edit' : 'Add' ?> Category</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$category->name) ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
              url: '<?= site_url('category/save') ?>',
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
                          $('#categoryModal').modal('hide');
                          location.reload();
                      });
                  } else {
                      Swal.fire('Error', res.message, 'error');
                  }
              }
          });
      }
  });
</script>
