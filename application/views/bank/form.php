<div class="modal-header bg-primary text-white">
    <h4 class="modal-title">
        <i class="fas fa-university mr-2"></i>
        <?= isset($bank) ? 'Edit Bank Details' : 'Add New Bank' ?>
    </h4>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="bankForm">
    <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
    <input type="hidden" name="id" value="<?= @$bank->id ?>">

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><i class="fas fa-building text-primary mr-1"></i> Company *</label>
                    <select name="company_id" class="form-control" required>
                        <option value="">Select Company</option>
                        <?php foreach ($companies as $c): ?>
                          <option value="<?= $c->id ?>" <?= @$bank->company_id == $c->id ? 'selected' : '' ?>><?= $c->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-university text-success mr-1"></i> Bank Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= @$bank->name ?>" placeholder="Enter bank name" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt text-danger mr-1"></i> Branch Address *</label>
                    <textarea name="branch_address" class="form-control" rows="2" placeholder="Enter branch address" required><?= @$bank->branch_address ?></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><i class="fas fa-credit-card text-info mr-1"></i> Account Number *</label>
                    <input type="text" name="ac_no" class="form-control" value="<?= @$bank->ac_no ?>" placeholder="Enter account number" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-code text-warning mr-1"></i> IFSC Code *</label>
                    <input type="text" name="ifsc_code" class="form-control" value="<?= @$bank->ifsc_code ?>" placeholder="Enter IFSC code" required style="text-transform: uppercase;">
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Save Bank
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancel
        </button>
    </div>
</form>

<script>
  // Ensure 'alphanumeric' validator exists (required by IFSC validation)
  if (typeof $.validator !== 'undefined' && typeof $.validator.methods.alphanumeric === 'undefined') {
      $.validator.addMethod('alphanumeric', function (value, element) {
          return this.optional(element) || /^[a-z0-9]+$/i.test(value);
      }, 'Please enter letters and numbers only.');
  }

  $('#bankForm').validate({
      rules: {
          company_id: {required: true},
          name: {required: true, minlength: 2, maxlength: 100},
          branch_address: {required: true, minlength: 5, maxlength: 255},
          ac_no: {required: true, minlength: 8, maxlength: 20, digits: true},
          ifsc_code: {required: true, minlength: 11, maxlength: 11, alphanumeric: true}
      },
      messages: {
          company_id: "Please select a company",
          name: {
              required: "Bank name is required",
              minlength: "Bank name must be at least 2 characters",
              maxlength: "Bank name cannot exceed 100 characters"
          },
          branch_address: {
              required: "Branch address is required",
              minlength: "Branch address must be at least 5 characters",
              maxlength: "Branch address cannot exceed 255 characters"
          },
          ac_no: {
              required: "Account number is required",
              minlength: "Account number must be at least 8 digits",
              maxlength: "Account number cannot exceed 20 digits",
              digits: "Account number must contain only digits"
          },
          ifsc_code: {
              required: "IFSC code is required",
              minlength: "IFSC code must be exactly 11 characters",
              maxlength: "IFSC code must be exactly 11 characters",
              alphanumeric: "IFSC code must contain only letters and numbers"
          }
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
          error.insertAfter(element);
      },
      submitHandler: function (form) {
          $.ajax({
              url: '<?= site_url('bank/ajax_save') ?>',
              type: 'POST',
              data: $(form).serialize(),
              dataType: 'json',
              beforeSend: function () {
                  Swal.fire({
                      title: 'Saving Bank Details...',
                      text: 'Please wait while we save the bank information.',
                      icon: 'info',
                      showConfirmButton: false,
                      allowOutsideClick: false
                  });
              },
              success: function (res) {
                  Swal.close();
                  if (res.status === 'success') {
                      Swal.fire('Saved!', res.message, 'success').then(() => {
                          if (typeof refreshTable === 'function') {
                              refreshTable();
                          } else {
                              $('#bankModal').modal('hide');
                              $('#bankTable').DataTable().ajax.reload();
                          }
                      });
                  } else {
                      Swal.fire('Error!', res.message, 'error');
                  }
              },
              error: function () {
                  Swal.close();
                  Swal.fire('Error!', 'Server error occurred while saving bank details', 'error');
              }
          });
          return false;
      }
  });

  // Auto-uppercase IFSC code
  $('input[name="ifsc_code"]').on('input', function() {
      this.value = this.value.toUpperCase();
  });
</script>
