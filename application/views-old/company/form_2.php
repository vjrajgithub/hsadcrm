<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= isset($company) ? 'Edit' : 'Add' ?> Company Profile</h3>
    </div>

    <form id="companyForm" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">

                <!-- Basic Info -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company Name *</label>
                        <input type="text" name="name" class="form-control" value="<?= set_value('name', @$company->name) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Mobile *</label>
                        <input type="text" name="mobile" class="form-control" value="<?= set_value('mobile', @$company->mobile) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" value="<?= set_value('email', @$company->email) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Website</label>
                        <input type="url" name="website" class="form-control" value="<?= set_value('website', @$company->website) ?>">
                    </div>

                    <div class="form-group">
                        <label>Job No</label>
                        <input type="text" name="job_no" class="form-control" value="<?= set_value('job_no', @$company->job_no) ?>">
                    </div>
                </div>

                <!-- Address Info -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address *</label>
                        <textarea name="address" class="form-control" required><?= set_value('address', @$company->address) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Pin Code *</label>
                        <input type="text" name="pin_code" class="form-control" value="<?= set_value('pin_code', @$company->pin_code) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Country *</label>
                        <input type="text" name="country" class="form-control" value="<?= set_value('country', @$company->country) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>State *</label>
                        <input type="text" name="state" class="form-control" value="<?= set_value('state', @$company->state) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" name="city" class="form-control" value="<?= set_value('city', @$company->city) ?>" required>
                    </div>
                </div>

                <!-- Legal Info -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>GST No</label>
                        <input type="text" name="gst_no" class="form-control" value="<?= set_value('gst_no', @$company->gst_no) ?>">
                    </div>

                    <div class="form-group">
                        <label>CIN No</label>
                        <input type="text" name="cin_no" class="form-control" value="<?= set_value('cin_no', @$company->cin_no) ?>">
                    </div>

                    <div class="form-group">
                        <label>PAN Card</label>
                        <input type="text" name="pan_card" class="form-control" value="<?= set_value('pan_card', @$company->pan_card) ?>">
                    </div>
                </div>

                <!-- Logo + Note -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company Logo</label>
                        <div class="custom-file">
                            <input type="file" name="logo" class="custom-file-input" id="logoInput">
                            <label class="custom-file-label" for="logoInput">Choose file</label>
                        </div>
                        <?php if (!empty($company->logo)): ?>
                          <div class="mt-2">
                              <img src="<?= base_url('assets/uploads/logos/' . $company->logo) ?>" width="100" class="img-thumbnail">
                          </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control" rows="3"><?= set_value('note', @$company->note) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Terms & Conditions</label>
                        <textarea name="terms_conditions" class="form-control" rows="3"><?= set_value('terms_conditions', @$company->terms_conditions) ?></textarea>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="<?= site_url('company') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
  // Show selected file name
  $('.custom-file-input').on('change', function () {
      let fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').addClass("selected").html(fileName);
  });



  // jQuery Validate + AJAX submit
  $('#companyForm').validate({
      rules: {
          name: {required: true},
          mobile: {required: true, digits: true, minlength: 10, maxlength: 10},
          email: {required: true, email: true},
          website: {url: true},
          job_no: {maxlength: 50},
          address: {required: true},
          pin_code: {required: true, digits: true, minlength: 6, maxlength: 6},
          country: {required: true},
          state: {required: true},
          city: {required: true},
          gst_no: {maxlength: 20},
          cin_no: {maxlength: 20},
          pan_card: {maxlength: 20},
          note: {maxlength: 1000},
          terms_conditions: {maxlength: 1000}
      },
      messages: {
          name: "Company name is required",
          mobile: "Enter a valid 10-digit mobile number",
          email: "Enter a valid email",
          address: "Address is required",
          pin_code: "Enter a 6-digit PIN code",
          country: "Country is required",
          state: "State is required",
          city: "City is required",
          website: "Enter a valid URL"
      },
      errorElement: 'span',
      errorClass: 'invalid-feedback',
      highlight: function (element) {
          $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function (element) {
          $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorPlacement: function (error, element) {
          if (element.closest('.custom-file').length) {
              error.insertAfter(element.closest('.custom-file'));
          } else {
              error.insertAfter(element);
          }
      },
      submitHandler: function (form) {
          var formData = new FormData(form);
          $.ajax({
              url: "<?= site_url('company/ajax_save') ?>",
              type: "POST",
              data: formData,
              contentType: false,
              processData: false,
              success: function (res) {
                  const data = JSON.parse(res);
                  if (data.status === 'success') {
                      Swal.fire("Saved!", data.message, "success").then(() => {
                          window.location.href = "<?= site_url('company') ?>";
                      });
                  } else {
                      Swal.fire("Error", data.message, "error");
                  }
              },
              error: function () {
                  Swal.fire("Error", "Server error occurred", "error");
              }
          });
          return false;
      }
  });


</script>
