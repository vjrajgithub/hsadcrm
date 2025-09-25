
<?php //print_r($companies);
//die;
?>
<form id="clientForm" method="post">
    <input type="hidden" name="id" value="<?= @$client->id ?>">

    <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><?= @$client ? 'Edit' : 'Add' ?> Client</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="company_id">Company <span class="text-danger">*</span></label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        <option value="">Select Company</option>
                        <?php foreach ($companies as $company): ?>
                          <option value="<?= $company->id ?>" <?= (isset($client->company_id) && $client->company_id == $company->id) ? 'selected' : '' ?>>
                              <?= $company->name ?>
                          </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Client Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= set_value('name', @$client->name) ?>">
                </div>

                <div class="form-group">
                    <label>Mobile *</label>
                    <input type="text" name="mobile" class="form-control" value="<?= set_value('mobile', @$client->mobile) ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= set_value('email', @$client->email) ?>">
                </div>

                <div class="form-group">
                    <label>GST No</label>
                    <input type="text" name="gst_no" class="form-control" value="<?= set_value('gst_no', @$client->gst_no) ?>">
                </div>

                <div class="form-group">
                    <label>PAN Card</label>
                    <input type="text" name="pan_card" class="form-control" value="<?= set_value('pan_card', @$client->pan_card) ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control"><?= set_value('address', @$client->address) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Pin Code</label>
                    <input type="text" name="pin_code" class="form-control" value="<?= set_value('pin_code', @$client->pin_code) ?>">
                </div>

                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" class="form-control" value="<?= set_value('country', @$client->country) ?>">
                </div>

                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" class="form-control" value="<?= set_value('state', @$client->state) ?>">
                </div>

                <div class="form-group">
                    <label>State Code</label>
                    <input type="text" name="state_code" class="form-control" value="<?= set_value('state_code', @$client->state_code) ?>">
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" value="<?= set_value('city', @$client->city) ?>">
                </div>
            </div>

        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    </div>
</form>

<!-- Client Validation + AJAX -->
<script>
  $(document).ready(function () {
      $('#clientForm').validate({
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
              mobile: {required: true, digits: true, minlength: 10},
              email: {email: true},
              gst_no: {minlength: 15},
              pan_card: {minlength: 10}
          },
          messages: {
              name: "Please enter the client's name",
              mobile: {
                  required: "Mobile number is required",
                  digits: "Only digits allowed",
                  minlength: "Enter at least 10 digits"
              },
              email: "Enter a valid email address",
              gst_no: "Enter at least 15 characters",
              pan_card: "Enter at least 10 characters"
          },
          submitHandler: function (form) {
              $.ajax({
                  url: '<?= site_url('clients/save') ?>',
                  type: 'POST',
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
                              title: 'Client saved successfully!',
                              timer: 1500,
                              showConfirmButton: false
                          }).then(() => {
                              $('#clientModal').modal('hide');
                              location.reload();
                          });
                      } else {
                          Swal.fire('Validation Failed', res.message, 'error');
                      }
                  },
                  error: function () {
                      Swal.fire('Error', 'Something went wrong while saving.', 'error');
                  }
              });
          }
      });
  });
</script>

<style>
    /* Optional: Boost error styling further */
    .invalid-feedback {
        display: block;
        font-size: 0.85rem;
        color: #dc3545;
    }
</style>
