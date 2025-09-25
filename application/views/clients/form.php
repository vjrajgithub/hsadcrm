
<div class="modal-header bg-primary text-white">
    <h4 class="modal-title">
        <i class="fas fa-users mr-2"></i>
        <?= isset($client) ? 'Edit Client' : 'Add New Client' ?>
    </h4>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="clientForm" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="id" value="<?= @$client->id ?>">

    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="clientTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab">
                    <i class="fas fa-info-circle mr-1"></i> Basic Info
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab">
                    <i class="fas fa-map-marker-alt mr-1"></i> Address Details
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content mt-3" id="clientTabContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-building text-primary mr-1"></i> Company *</label>
                            <select name="company_id" class="form-control" required>
                                <option value="">Select Company</option>
                                <?php foreach ($companies as $company): ?>
                                  <option value="<?= $company->id ?>" <?= (@$client->company_id == $company->id) ? 'selected' : '' ?>><?= $company->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-user text-success mr-1"></i> Client Name *</label>
                            <input type="text" name="name" class="form-control" value="<?= set_value('name', @$client->name) ?>" placeholder="Enter client name" >
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-phone text-info mr-1"></i> Mobile </label>
                            <input type="text" name="mobile" class="form-control" value="<?= set_value('mobile', @$client->mobile) ?>" placeholder="Enter mobile number" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-envelope text-warning mr-1"></i> Email </label>
                            <input type="email" name="email" class="form-control" value="<?= set_value('email', @$client->email) ?>" placeholder="Enter email address" >
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-receipt text-success mr-1"></i> GST No</label>
                            <input type="text" name="gst_no" class="form-control" value="<?= set_value('gst_no', @$client->gst_no) ?>" placeholder="Enter GST number">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-id-card text-primary mr-1"></i> PAN Card</label>
                            <input type="text" name="pan_card" class="form-control" value="<?= set_value('pan_card', @$client->pan_card) ?>" placeholder="Enter PAN number">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Tab -->
            <div class="tab-pane fade" id="address" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Enter complete address"><?= set_value('address', @$client->address) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-mail-bulk text-info mr-1"></i> Pin Code</label>
                            <input type="text" name="pincode" class="form-control" value="<?= set_value('pincode', @$client->pincode) ?>" placeholder="Enter pin code">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-flag text-primary mr-1"></i> Country</label>
                            <input type="text" name="country" class="form-control" value="<?= set_value('country', @$client->country) ?>" placeholder="Enter country">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-map text-success mr-1"></i> State</label>
                            <input type="text" name="state" class="form-control" value="<?= set_value('state', @$client->state) ?>" placeholder="Enter state">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-code text-warning mr-1"></i> State Code</label>
                            <input type="text" name="state_code" class="form-control" value="<?= set_value('state_code', @$client->state_code) ?>" placeholder="Enter state code">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-city text-secondary mr-1"></i> City</label>
                            <input type="text" name="city" class="form-control" value="<?= set_value('city', @$client->city) ?>" placeholder="Enter city">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Save Client
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancel
        </button>
    </div>
</form>

<!-- Client Validation + AJAX -->
<script>
  $(document).ready(function () {
      // Add custom alphanumeric validation method
      $.validator.addMethod("alphanumeric", function (value, element) {
          return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
      }, "Please enter only letters and numbers");

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
              company_id: {required: true},
              name: {required: true, minlength: 2, maxlength: 100},
              mobile: {digits: true, minlength: 10, maxlength: 10},
              email: {email: true, maxlength: 100},
              gst_no: {maxlength: 15, alphanumeric: true},
              pan_card: {maxlength: 10, alphanumeric: true},
              address: {maxlength: 500},
              pincode: {digits: true, },
              country: {maxlength: 50},
              state: {maxlength: 50},
              state_code: {digits: true, maxlength: 2},
              city: {maxlength: 50}
          },
          messages: {
              company_id: "Please select a company",
              name: {
                  required: "Client name is required",
                  minlength: "Client name must be at least 2 characters",
                  maxlength: "Client name cannot exceed 100 characters"
              },
              mobile: {
                  //required: "Mobile number is required",
                  digits: "Mobile number must contain only digits",
                  minlength: "Mobile number must be exactly 10 digits",
                  maxlength: "Mobile number must be exactly 10 digits"
              },
              email: {
                  //required: "Email address is required",
                  email: "Please enter a valid email address",
                  maxlength: "Email cannot exceed 100 characters"
              },
              gst_no: {
                  maxlength: "GST number cannot exceed 15 characters",
                  alphanumeric: "GST number must contain only letters and numbers"
              },
              pan_card: {
                  maxlength: "PAN card cannot exceed 10 characters",
                  alphanumeric: "PAN card must contain only letters and numbers"
              },
              address: {
                  maxlength: "Address cannot exceed 500 characters"
              },
              pincode: {
                  digits: "Pin code must contain only digits",
                  //minlength: "Pin code must be exactly 6 digits",
                  // maxlength: "Pin code must be exactly 6 digits"
              },
              country: {
                  maxlength: "Country name cannot exceed 50 characters"
              },
              state: {
                  maxlength: "State name cannot exceed 50 characters"
              },
              state_code: {
                  digits: "State code must contain only digits",
                  maxlength: "State code cannot exceed 2 digits"
              },
              city: {
                  maxlength: "City name cannot exceed 50 characters"
              }
          },
          submitHandler: function (form) {
              $.ajax({
                  url: '<?= site_url('clients/ajax_save') ?>',
                  type: 'POST',
                  data: $(form).serialize(),
                  dataType: 'json',
                  beforeSend: function () {
                      Swal.fire({
                          title: 'Saving Client Details...',
                          text: 'Please wait while we save the client information.',
                          icon: 'info',
                          showConfirmButton: false,
                          allowOutsideClick: false
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
                              if (typeof refreshTable === 'function') {
                                  refreshTable();
                              } else {
                                  $('#clientModal').modal('hide');
                                  $('#clientTable').DataTable().ajax.reload();
                              }
                          });
                      } else {
                          Swal.fire('Validation Failed', res.message, 'error');
                      }
                  },
                  error: function () {
                      Swal.close();
                      Swal.fire('Error', 'Server error occurred while saving client details', 'error');
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
