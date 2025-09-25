<div class="modal-header bg-primary text-white">
    <h4 class="modal-title">
        <i class="fas fa-box mr-2"></i>
        <?= isset($product) ? 'Edit Product/Service' : 'Add New Product/Service' ?>
    </h4>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="productForm" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="id" value="<?= @$product->id ?>">

    <div class="modal-body">
        <div class="form-group">
            <label><i class="fas fa-tags text-primary mr-1"></i> Category *</label>
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat->id ?>" <?= set_select('category_id', $cat->id, @$product->category_id == $cat->id) ?>>
                      <?= esc($cat->name) ?>
                  </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label><i class="fas fa-cube text-success mr-1"></i> Product/Service Name *</label>
            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$product->name) ?>" placeholder="Enter product/service name" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-rupee-sign text-warning mr-1"></i> Rate per Unit *</label>
            <input type="number" name="rate_per_unit" class="form-control" value="<?= set_value('rate_per_unit', @$product->rate_per_unit) ?>" step="0.01" min="0" placeholder="Enter rate per unit" required>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Save Product
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancel
        </button>
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
              url: '<?= site_url('productservice/ajax_save') ?>',
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
                              $('#productModal').modal('hide');
                              $('#productTable').DataTable().ajax.reload();
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
