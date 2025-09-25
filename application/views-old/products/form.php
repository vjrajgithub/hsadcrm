<form id="productForm" method="post">
    <input type="hidden" name="id" value="<?= @$product->id ?>">

    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><?= @$product ? 'Edit' : 'Add' ?> Product / Service</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Category *</label>
            <select name="category_id" class="form-control">
                <option value="">-- Select --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat->id ?>" <?= set_select('category_id', $cat->id, @$product->category_id == $cat->id) ?>>
                      <?= esc($cat->name) ?>
                  </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$product->name) ?>">
        </div>

        <div class="form-group">
            <label>Rate per Unit *</label>
            <input type="number" name="rate_per_unit" class="form-control" value="<?= set_value('rate_per_unit', @$product->rate_per_unit) ?>" step="0.01" min="0">
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    </div>
</form>

<script>
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
          name: "Please enter name",
          rate_per_unit: "Enter valid rate"
      },
      submitHandler: function (form) {
          $.ajax({
              url: '<?= site_url('products/save') ?>',
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
