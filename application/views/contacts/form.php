<form id="contactForm" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="id" value="<?= @$contact->id ?>">

    <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><?= @$contact ? 'Edit' : 'Add' ?> Contact Person</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Client *</label>
                    <select name="client_id" class="form-control">
                        <option value="">-- Select Client --</option>
                        <?php foreach ($clients as $c): ?>
                          <option value="<?= $c->id ?>" <?= set_select('client_id', $c->id, @$contact->client_id == $c->id) ?>>
                              <?= $c->name ?>
                          </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Contact Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= set_value('name', @$contact->name) ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Mobile *</label>
                    <input type="text" name="mobile" class="form-control" value="<?= set_value('mobile', @$contact->mobile) ?>">
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= set_value('email', @$contact->email) ?>" required>
                </div>
            </div>


        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    </div>
</form>

<script>
  $(document).ready(function () {
      $('#contactForm').validate({
          errorElement: 'span',
          errorClass: 'invalid-feedback',
          highlight: function (el) {
              $(el).addClass('is-invalid');
          },
          unhighlight: function (el) {
              $(el).removeClass('is-invalid');
          },
          errorPlacement: function (error, element) {
              error.appendTo(element.closest('.form-group'));
          },
          rules: {
              client_id: {required: true},
              name: {required: true},
              mobile: {required: true, digits: true, minlength: 10},
              email: {required: true, email: true}
          },
          messages: {
              client_id: "Please select a client",
              name: "Contact name is required",
              mobile: {
                  required: "Mobile number is required",
                  digits: "Only digits allowed",
                  minlength: "At least 10 digits"
              },
              email: {
                  required: "Email is required",
                  email: "Enter a valid email address"
              }
          },
          submitHandler: function (form) {
              $.ajax({
                  url: '<?= site_url('contacts/ajax_save') ?>',
                  method: 'POST',
                  data: $(form).serialize(),
                  dataType: 'json',
                  beforeSend: function () {
                      Swal.fire({
                          title: 'Saving...',
                          allowOutsideClick: false,
                          didOpen: () => Swal.showLoading()
                      });
                  },
                  success: function (res) {
                      Swal.close();
                      if (res.status === 'success') {
                          Swal.fire({
                              icon: 'success',
                              title: 'Saved successfully!',
                              timer: 1500,
                              showConfirmButton: false
                          }).then(() => {
                              $('#contactModal').modal('hide');
                              location.reload();
                          });
                      } else {
                          Swal.fire('Error', res.message || 'Validation failed.', 'error');
                      }
                  },
                  error: function () {
                      Swal.fire('Error', 'Could not save contact.', 'error');
                  }
              });
          }
      });
  });
</script>

<style>
    .invalid-feedback {
        display: block;
        font-size: 0.875rem;
    }
</style>
