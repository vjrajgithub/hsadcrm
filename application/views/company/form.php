<div class="modal-header bg-primary text-white">
    <h4 class="modal-title">
        <i class="fas fa-building mr-2"></i>
        <?= isset($company) ? 'Edit Company' : 'Add New Company' ?>
    </h4>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="companyForm" enctype="multipart/form-data">
    <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
    <?php if (!empty($company->id)): ?>
      <input type="hidden" name="id" value="<?= $company->id ?>">
    <?php endif; ?>

    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="companyTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab">
                    <i class="fas fa-info-circle mr-1"></i> Basic Info
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab">
                    <i class="fas fa-map-marker-alt mr-1"></i> Address
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="legal-tab" data-toggle="tab" href="#legal" role="tab">
                    <i class="fas fa-file-contract mr-1"></i> Legal & Other
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content mt-3" id="companyTabContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-building text-primary mr-1"></i> Company Name *</label>
                            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$company->name) ?>" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-phone text-success mr-1"></i> Mobile *</label>
                            <input type="text" name="mobile" class="form-control" value="<?= set_value('mobile', @$company->mobile) ?>" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-envelope text-info mr-1"></i> Email *</label>
                            <input type="email" name="email" class="form-control" value="<?= set_value('email', @$company->email) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-globe text-primary mr-1"></i> Website</label>
                            <input type="url" name="website" class="form-control" value="<?= set_value('website', @$company->website) ?>">
                        </div>

                        

                        <div class="form-group">
                            <label><i class="fas fa-image text-warning mr-1"></i> Company Logo</label>
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
                    </div>
                </div>
            </div>

            <!-- Address Tab -->
            <div class="tab-pane fade" id="address" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Address *</label>
                            <textarea name="address" class="form-control" rows="3" required><?= set_value('address', @$company->address) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-mail-bulk text-info mr-1"></i> Pin Code *</label>
                            <input type="text" name="pin_code" class="form-control" value="<?= set_value('pin_code', @$company->pin_code) ?>" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-flag text-primary mr-1"></i> Country *</label>
                            <input type="text" name="country" class="form-control" value="<?= set_value('country', @$company->country) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-map text-success mr-1"></i> State *</label>
                            <input type="text" name="state" class="form-control" value="<?= set_value('state', @$company->state) ?>" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-city text-warning mr-1"></i> City *</label>
                            <input type="text" name="city" class="form-control" value="<?= set_value('city', @$company->city) ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legal & Other Tab -->
            <div class="tab-pane fade" id="legal" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-receipt text-success mr-1"></i> GST No</label>
                            <input type="text" name="gst_no" class="form-control" value="<?= set_value('gst_no', @$company->gst_no) ?>">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-certificate text-primary mr-1"></i> CIN No <small class="text-muted">(A–Z, 0–9, 20–40 chars)</small></label>
                            <input type="text" name="cin_no" id="cinNo" class="form-control" value="<?= set_value('cin_no', @$company->cin_no) ?>" maxlength="40">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-id-card text-warning mr-1"></i> PAN Card</label>
                            <input type="text" name="pan_card" class="form-control" value="<?= set_value('pan_card', @$company->pan_card) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-sticky-note text-info mr-1"></i> Note</label>
                            <textarea name="note" class="form-control" rows="3"><?= set_value('note', @$company->note) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-file-contract text-secondary mr-1"></i> Terms & Conditions</label>
                            <textarea name="terms_conditions" class="form-control" rows="3"><?= set_value('terms_conditions', @$company->terms_conditions) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Save Company
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancel
        </button>
    </div>
</form>
<script>
  // Show selected file name
  $('.custom-file-input').on('change', function () {
      let fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').addClass("selected").html(fileName);
  });



  // Auto-uppercase for CIN No while typing/pasting
  $('#cinNo').on('input', function() {
      this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 40);
  });

  // jQuery Validate + AJAX submit
  // Add custom validator for uppercase alphanumeric 20-40 chars
  if ($.validator) {
      $.validator.addMethod('alnumCaps2040', function(value, element) {
          return this.optional(element) || /^[A-Z0-9]{20,40}$/.test(value);
      }, 'Use only A–Z and 0–9, length 20 to 40 characters.');
  }
  $('#companyForm').validate({
      rules: {
          name: {required: true},
          mobile: {required: true, digits: true, minlength: 10, maxlength: 10},
          email: {required: true, email: true},
          website: {required: true, url: true},
          address: {required: true},
          pin_code: {required: true, digits: true, minlength: 6, maxlength: 6},
          country: {required: true},
          state: {required: true},
          city: {required: true},
          gst_no: {required: true, maxlength: 20},
          cin_no: {required: true, alnumCaps2040: true},
          pan_card: {required: true, maxlength: 20},
          note: {required: true, maxlength: 1000},
          terms_conditions: {required: true, maxlength: 1000}
      },
      messages: {
          name: "Company name is required",
          mobile: "Enter a valid 10-digit mobile number",
          email: "Enter a valid email",
          cin_no: "CIN No must be A–Z and 0–9 only, length 20–40",
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
                          if (typeof refreshTable === 'function') {
                              refreshTable();
                          } else {
                              $('#companyModal').modal('hide');
                              $('#companyTable').DataTable().ajax.reload();
                          }
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
