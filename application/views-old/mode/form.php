<form id="modeForm" method="post">
    <input type="hidden" name="id" value="<?= @$mode->id ?>">

    <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><?= @$mode ? 'Edit Mode' : 'Add Mode' ?></h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label>Mode Name *</label>
            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$mode->name) ?>">
        </div>

        <div class="form-group">
            <label>Days *</label>
            <input type="number" name="days" class="form-control" value="<?= set_value('days', @$mode->days) ?>">
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
              url: '<?= site_url('mode/save') ?>',
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
                          $('#modeModal').modal('hide');
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
