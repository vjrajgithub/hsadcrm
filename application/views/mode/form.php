<div class="modal-header bg-primary text-white">
    <h4 class="modal-title">
        <i class="fas fa-cogs mr-2"></i>
        <?= isset($mode) ? 'Edit Mode' : 'Add New Mode' ?>
    </h4>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="modeForm" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="id" value="<?= @$mode->id ?>">

    <div class="modal-body">
        <div class="form-group">
            <label><i class="fas fa-tag text-primary mr-1"></i> Mode Name *</label>
            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$mode->name) ?>" placeholder="Enter mode name">
        </div>

        <div class="form-group">
            <label><i class="fas fa-calendar-day text-success mr-1"></i> Days *</label>
            <input type="number" name="days" class="form-control" value="<?= set_value('days', @$mode->days) ?>" placeholder="Enter number of days" min="1">
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Save Mode
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancel
        </button>
    </div>
</form>

<script>
  $('#modeForm').validate({
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
          name: {required: true},
          days: {required: true, digits: true, min: 1}
      },
      messages: {
          name: "Please enter mode name",
          days: {
              required: "Please enter days",
              digits: "Only numeric value allowed",
              min: "Must be at least 1 day"
          }
      },
      submitHandler: function (form) {
          $.ajax({
              url: '<?= site_url('mode/ajax_save') ?>',
              type: 'POST',
              data: $(form).serialize(),
              dataType: 'json',
              beforeSend: function () {
                  Swal.fire({title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});
              },
              success: function (res) {
                  Swal.close();
                  if (res.status === 'success') {
                      Swal.fire('Success', res.message, 'success').then(() => {
                          if (typeof refreshTable === 'function') {
                              refreshTable();
                          } else {
                              $('#modeModal').modal('hide');
                              $('#modeTable').DataTable().ajax.reload();
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
