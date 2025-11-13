<!-- CKEditor 4 CDN (Free Version) -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<style>
    .cke_notifications_area {
        display: none;
    }
</style>
<section class="content-header d-flex justify-content-between align-items-center">
    <h1>Create Quotation</h1>
</section>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-body">
            <form id="quotationForm" enctype="multipart/form-data">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="total_amount" id="total_amount">
                <input type="hidden" name="gst_type" id="gst_type">
                <input type="hidden" name="gst_amount" id="gst_amount">
                <div class="box box-primary">
                    <div class="box-body">

                        <div class="row">
                            <!-- Company -->
                            <div class="col-md-4 form-group">
                                <label for="company_id">Company <span class="text-danger">*</span></label>
                                <select name="company_id" id="company_id" class="form-control" required>
                                    <option value="">Select Company</option>
                                    <?php foreach ($companies as $company): ?>
                                      <option value="<?= $company->id ?>"
                                              data-state="<?= $company->state ?>"
                                              data-terms="<?= htmlspecialchars($company->terms_conditions) ?>"
                                              data-notes="<?= htmlspecialchars($company->note) ?>">
                                                  <?= $company->name ?>
                                      </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Client -->
                            <div class="col-md-4 form-group">
                                <label for="client_id">Client <span class="text-danger">*</span></label>
                                <select name="client_id" id="client_id" class="form-control" required>
                                    <option value="">Select Client</option>
                                </select>
                            </div>

                            <!-- Bank -->
                            <div class="col-md-4 form-group">
                                <label for="bank_id">Bank <span class="text-danger">*</span></label>
                                <select name="bank_id" id="bank_id" class="form-control" required>
                                    <option value="">Select Bank</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Contact Person -->
                            <div class="col-md-4 form-group">
                                <label for="contact_person">Contact Person <span class="text-danger">*</span></label>
                                <input type="text" name="contact_person" id="contact_person" class="form-control" required>
                            </div>

                            <!-- Department (from Client Categories) -->
                            <div class="col-md-4 form-group">
                                <label for="department">Department <span class="text-danger">*</span></label>
                                <select name="department" id="department" class="form-control" required>
                                    <option value="">Select Department</option>
                                    <?php if (!empty($departments)) : ?>
                                      <?php foreach ($departments as $dept): ?>
                                        <option value="<?= htmlspecialchars($dept->name) ?>"><?= htmlspecialchars($dept->name) ?></option>
                                      <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Place of Supply -->
                            <div class="col-md-4 form-group">
                                <label for="state">Place of Supply (State) <span class="text-danger">*</span></label>
                                <select name="state" id="state" class="form-control" required>
                                    <option value="">Select State</option>
                                    <?php foreach ($states as $state): ?>
                                      <option value="<?= $state ?>"><?= $state ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Mode -->
                            <div class="col-md-4 form-group">
                                <label for="mode_id">Mode <span class="text-danger">*</span></label>
                                <select name="mode_id" id="mode_id" class="form-control" required>
                                    <option value="">Select Mode</option>
                                    <?php foreach ($modes as $mode): ?>
                                      <option value="<?= $mode->id ?>"><?= $mode->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- HSN/SAC -->
                            <div class="col-md-4 form-group">
                                <label for="hsn_sac">HSN/SAC Code</label>
                                <input type="text" name="hsn_sac" id="hsn_sac" class="form-control">
                            </div>

                            <!-- Job No -->
                            <div class="col-md-4 form-group">
                                <label for="job_no">Job No</label>
                                <input type="text" name="job_no" id="job_no" class="form-control" maxlength="128" placeholder="Enter Job No">
                            </div>

                            <!-- Attachment -->
                            <div class="col-md-4 form-group">
                                <label for="attachment">Attachment</label>
                                <input type="file" name="attachment" id="attachment" class="form-control"
                                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            </div>
                        </div>

                        <hr>

                        <!-- Product/Service Items -->
                        <h4>Items</h4>
                        <table class="table table-bordered" id="itemsTable">
                            <thead class="bg-gray">
                                <tr>
                                    <th style="width: 80px;">Use Dropdown</th>
                                    <th style="width: 250px;">Category/Description</th>
                                    <th style="width: 200px;">Product/Service</th>
                                    <th style="width: 80px;">Qty</th>
                                    <th style="width: 100px;">Rate</th>
                                    <th style="width: 100px;">Discount (%)</th>
                                    <th style="width: 60px;"><button type="button" id="addRow" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <input type="checkbox" class="form-check-input use-dropdown-checkbox" checked style="width: 20px; height: 20px; cursor: pointer;">
                                        <input type="hidden" name="items[0][use_dropdown]" value="1" class="use-dropdown-hidden">
                                    </td>
                                    <td>
                                        <select name="items[0][category_id]" class="form-control category-select" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($product_categories as $cat): ?>
                                              <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <textarea name="items[0][description]" class="form-control description-field ckeditor-field" placeholder="Enter description..." style="display:none;" rows="5"></textarea>
                                    </td>
                                    <td>
                                        <select name="items[0][product_id]" class="form-control product-select" required>
                                            <option value="">Select Product</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="items[0][qty]" class="form-control qty" min="1" value="1" required></td>
                                    <td><input type="number" name="items[0][rate]" class="form-control rate" step="0.01" required></td>
                                    <td><input type="number" name="items[0][discount]" class="form-control discount" value="0" step="0.01"></td>
                                    <td style="text-align: center;"><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- GST + Total -->
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div id="gstDetails"></div>
                                <h3>Total Amount: <strong id="grandTotal">0.00</strong></h3>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Terms & Conditions</label>
                                <textarea name="terms" id="terms" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Note</label>
                                <textarea name="notes" id="notes" class="form-control" rows="4"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Quotation</button>
                        <a href="<?= base_url('quotation') ?>" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


<script>
  $(document).ready(function () {

      // Disable auto inline editing and LTS warning check
      CKEDITOR.disableAutoInline = true;
      delete CKEDITOR.env.versionCheck;

      // Add custom alphanumeric validation method
      $.validator.addMethod("alphanumeric", function (value, element) {
          return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
      }, "Please enter only letters and numbers");

      // Auto-fill terms & notes
      $('#company_id').change(function () {
          const selected = $(this).find('option:selected');
          $('#terms').val(selected.data('terms') || '');
          $('#notes').val(selected.data('notes') || '');
      });

      // Load clients and banks
      $('#company_id').change(function () {
          const companyId = $(this).val();
          if (!companyId)
              return;

          // Clients
          $.getJSON('<?= base_url('quotation/get_clients_by_company/') ?>' + companyId, function (data) {
              let options = '<option value="">Select Client</option>';
              $.each(data, function (i, client) {
                  options += `<option value="${client.id}">${client.name} (${client.city})</option>`;
              });
              $('#client_id').html(options);
          });

          // Banks
          $.getJSON('<?= base_url('quotation/get_banks_by_company/') ?>' + companyId, function (data) {
              let options = '<option value="">Select Bank</option>';
              $.each(data, function (i, bank) {
                  options += `<option value="${bank.id}">${bank.name} (${bank.ac_no})</option>`;
              });
              $('#bank_id').html(options);
          });
      });

      // Load products by category
      $(document).on('change', '.category-select', function () {
          const row = $(this).closest('tr');
          const categoryId = $(this).val();
          if (!categoryId)
              return;

          $.getJSON('<?= base_url('quotation/get_products_by_category/') ?>' + categoryId, function (data) {
              let options = '<option value="">Select Product</option>';
              $.each(data, function (i, product) {
                  options += `<option value="${product.id}" data-rate="${product.rate_per_unit}">${product.name}</option>`;
              });
              row.find('.product-select').html(options);
          });
      });

      // Set rate
      $(document).on('change', '.product-select', function () {
          const rate_per_unit = $(this).find('option:selected').data('rate') || 0;
          const rateField = $(this).closest('tr').find('.rate');
          rateField.val(rate_per_unit);

          // Make rate field editable when using dropdowns
          rateField.prop('readonly', false);
          calculateTotal();
      });

      // Handle rate input for description mode
      $(document).on('input', '.rate', function () {
          calculateTotal();
      });

      // Handle checkbox toggle for dropdown/description
      $(document).on('change', '.use-dropdown-checkbox', function () {
          const row = $(this).closest('tr');
          const isChecked = $(this).is(':checked');
          const hiddenField = row.find('.use-dropdown-hidden');

          console.log('Checkbox changed:', isChecked, 'Hidden field:', hiddenField.attr('name'));

          if (isChecked) {
              // Show dropdowns, hide description
              row.find('.category-select').show().prop('required', true);
              row.find('.product-select').show().prop('required', true);
              row.find('.description-field').hide().prop('required', false);

              // Clear description and set hidden field value
              row.find('.description-field').val('');
              hiddenField.val('1');
              console.log('Set to dropdown mode, hidden field value:', hiddenField.val());
          } else {
              // Hide dropdowns, show description
              row.find('.category-select').hide().prop('required', false);
              row.find('.product-select').hide().prop('required', false);
              row.find('.description-field').show().prop('required', true);

              // Clear dropdown selections and set hidden field value
              row.find('.category-select').val('');
              row.find('.product-select').val('');
              row.find('.rate').val('');
              hiddenField.val('0');
              console.log('Set to description mode, hidden field value:', hiddenField.val());
          }
      });

      // Add row
      $('#addRow').click(function () {
          const index = $('#itemsTable tbody tr').length;
          const rowHtml = $('#itemsTable tbody tr:first').clone();
          rowHtml.find('input, select, textarea').each(function () {
              const name = $(this).attr('name');
              if (name) {
                  const newName = name.replace(/\d+/, index);
                  $(this).attr('name', newName);

                  // Reset values
                  if ($(this).is('input[type="checkbox"]')) {
                      $(this).prop('checked', true); // default to dropdown mode
                  } else if ($(this).is('input[type="hidden"]') && $(this).hasClass('use-dropdown-hidden')) {
                      $(this).val('1'); // sync hidden to dropdown mode
                  } else if ($(this).hasClass('qty')) {
                      $(this).val('1');
                  } else if ($(this).hasClass('discount')) {
                      $(this).val('0');
                  } else {
                      $(this).val('');
                  }
              }
          });

          // Ensure proper visibility and requirements for new row
          rowHtml.find('.category-select').show().prop('required', true).val('');
          rowHtml.find('.product-select').show().prop('required', true).val('');
          rowHtml.find('.description-field').hide().prop('required', false).val('');
          rowHtml.find('.rate').prop('readonly', false);

          // Ensure the hidden field has the correct class and value
          rowHtml.find('input[type="hidden"][name*="use_dropdown"]').addClass('use-dropdown-hidden').val('1');

          // Make sure the visible checkbox shows as checked and fire change to sync UI logic
          rowHtml.find('.use-dropdown-checkbox').prop('checked', true).trigger('change');

          $('#itemsTable tbody').append(rowHtml);
          calculateTotal();
      });

      // Remove row
      $(document).on('click', '.removeRow', function () {
          if ($('#itemsTable tbody tr').length > 1) {
              $(this).closest('tr').remove();
              calculateTotal();
          }
      });

      // Recalculate
      $(document).on('input', '.qty, .discount', function () {
          calculateTotal();
      });
      $('#state, #company_id').change(function () {
          calculateTotal();
      });

//      function calculateTotal() {
//          let total = 0;
//          $('#itemsTable tbody tr').each(function () {
//              const qty = parseFloat($(this).find('.qty').val()) || 0;
//              const rate = parseFloat($(this).find('.rate').val()) || 0;
//              const discount = parseFloat($(this).find('.discount').val()) || 0;
//              let amount = qty * rate;
//              amount -= amount * discount / 100;
//              total += amount;
//          });
//
//          const companyState = $('#company_id option:selected').data('state');
//          const supplyState = $('#state').val();
//          const gstRate = 18;
//          let gstHTML = '', gstAmount = 0;
//
//          if (companyState && supplyState) {
//              if (companyState === supplyState) {
//                  const cgst = gstRate / 2;
//                  const cgstAmt = (total * cgst) / 100;
//                  const sgstAmt = cgstAmt;
//                  gstHTML = `<p>CGST ${cgst}% : ₹${cgstAmt.toFixed(2)}</p>
//                           <p>SGST ${cgst}% : ₹${sgstAmt.toFixed(2)}</p>`;
//                  gstAmount = cgstAmt + sgstAmt;
//              } else {
//                  const igstAmt = (total * gstRate) / 100;
//                  gstHTML = `<p>IGST ${gstRate}% : ₹${igstAmt.toFixed(2)}</p>`;
//                  gstAmount = igstAmt;
//              }
//          }
//
//          $('#gstDetails').html(gstHTML);
//          $('#grandTotal').text((total + gstAmount).toFixed(2));
//      }
      function calculateTotal() {
          let total = 0;
          $('#itemsTable tbody tr').each(function () {
              const qty = parseFloat($(this).find('.qty').val()) || 0;
              const rate = parseFloat($(this).find('.rate').val()) || 0;
              const discount = parseFloat($(this).find('.discount').val()) || 0;
              let amount = qty * rate;
              amount -= amount * discount / 100;
              total += amount;
          });

          const companyState = $('#company_id option:selected').data('state');
          const supplyState = $('#state').val();
          const gstRate = 18;
          let gstHTML = '', gstAmount = 0, gstType = '';

          if (companyState && supplyState) {
              if (companyState === supplyState) {
                  const cgst = gstRate / 2;
                  const cgstAmt = (total * cgst) / 100;
                  const sgstAmt = cgstAmt;
                  gstHTML = `<p>CGST ${cgst}% : ₹${cgstAmt.toFixed(2)}</p>
                       <p>SGST ${cgst}% : ₹${sgstAmt.toFixed(2)}</p>`;
                  gstAmount = cgstAmt + sgstAmt;
                  gstType = 'CGST+SGST';
              } else {
                  const igstAmt = (total * gstRate) / 100;
                  gstHTML = `<p>IGST ${gstRate}% : ₹${igstAmt.toFixed(2)}</p>`;
                  gstAmount = igstAmt;
                  gstType = 'IGST';
              }
          }

          $('#gstDetails').html(gstHTML);
          $('#grandTotal').text((total + gstAmount).toFixed(2));

          // Update hidden fields
          $('#total_amount').val((total + gstAmount).toFixed(2));
          $('#gst_type').val(gstType);
          $('#gst_amount').val(gstAmount.toFixed(2));
      }

      // Enhanced validation and submit
      $('#quotationForm').validate({
          rules: {
              company_id: {required: true},
              client_id: {required: true},
              bank_id: {required: true},
              contact_person: {required: true, minlength: 2, maxlength: 100},
              department: {required: true,
                  //minlength: 2,
                  // maxlength: 30
              },
              state: {required: true},
              mode_id: {required: true},
              hsn_sac: {maxlength: 20, alphanumeric: true},
              terms: {maxlength: 1000},
              notes: {maxlength: 1000}
          },
          messages: {
              company_id: "Please select a company",
              client_id: "Please select a client",
              bank_id: "Please select a bank",
              contact_person: {
                  required: "Contact person is required",
                  minlength: "Contact person must be at least 2 characters",
                  maxlength: "Contact person cannot exceed 100 characters"
              },
              department: {
                  required: "Please select a department"
              },
              state: "Please select a state",
              mode_id: "Please select a mode",
              hsn_sac: {
                  maxlength: "HSN/SAC code cannot exceed 20 characters",
                  alphanumeric: "HSN/SAC code must contain only letters and numbers"
              },
              terms: {
                  maxlength: "Terms & conditions cannot exceed 1000 characters"
              },
              notes: {
                  maxlength: "Notes cannot exceed 1000 characters"
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
              // Validate items
              let hasItems = false;
              let validationError = '';

              $('#itemsTable tbody tr').each(function () {
                  const useDropdown = $(this).find('.use-dropdown-checkbox').is(':checked');
                  const qty = $(this).find('.qty').val();
                  const rate = $(this).find('.rate').val();

                  if (useDropdown) {
                      const categoryId = $(this).find('.category-select').val();
                      const productId = $(this).find('.product-select').val();

                      if (categoryId && productId && qty && rate) {
                          hasItems = true;
                          return false; // break loop
                      }
                  } else {
                      const description = $(this).find('.description-field').val().trim();

                      if (description && qty && rate) {
                          hasItems = true;
                          return false; // break loop
                      }
                  }
              });

              if (!hasItems) {
                  Swal.fire('Validation Error', 'Please add at least one complete item with quantity and rate', 'error');
                  return false;
              }

              const formData = new FormData(form);

              // Debug: Log form data
              console.log('Form data being sent:');
              for (let pair of formData.entries()) {
                  if (pair[0].includes('use_dropdown') || pair[0].includes('description')) {
                      console.log(pair[0] + ': ' + pair[1]);
                  }
              }

              $.ajax({
                  url: '<?= base_url('quotation/store') ?>',
                  type: 'POST',
                  data: formData,
                  contentType: false,
                  processData: false,
                  beforeSend: function () {
                      Swal.fire({
                          title: 'Saving Quotation...',
                          text: 'Please wait while we save the quotation.',
                          icon: 'info',
                          showConfirmButton: false,
                          allowOutsideClick: false
                      });
                  },
                  success: function (res) {
                      Swal.close();
                      res = JSON.parse(res);
                      if (res.status) {
                          Swal.fire('Success', 'Quotation saved successfully!', 'success')
                                  .then(() => window.location.href = '<?= base_url('quotation') ?>');
                      } else {
                          Swal.fire('Error', res.message || 'Something went wrong!', 'error');
                      }
                  },
                  error: function () {
                      Swal.close();
                      Swal.fire('Error', 'Server error occurred while saving quotation', 'error');
                  }
              });
          }
      });

      // CKEditor instances tracker
      var editorInstances = {};

      // Handle checkbox toggle with CKEditor
      $(document).on('change', '.use-dropdown-checkbox', function () {
          const row = $(this).closest('tr');
          const isChecked = $(this).is(':checked');
          const hiddenField = row.find('.use-dropdown-hidden');
          const descField = row.find('.description-field');

          if (isChecked) {
              // Destroy CKEditor instance for this field
              const fieldName = descField.attr('name');
              if (fieldName && CKEDITOR.instances[fieldName]) {
                  CKEDITOR.instances[fieldName].destroy();
                  delete editorInstances[fieldName];
              }

              // Show dropdowns, hide description
              row.find('.category-select').show().prop('required', true);
              row.find('.product-select').show().prop('required', true);
              descField.hide().prop('required', false);

              // Clear description and set hidden field value
              descField.val('');
              hiddenField.val('1');
          } else {
              // Hide dropdowns, show description
              row.find('.category-select').hide().prop('required', false);
              row.find('.product-select').hide().prop('required', false);
              descField.show().prop('required', true);

              // Clear dropdown selections and set hidden field value
              row.find('.category-select').val('');
              row.find('.product-select').val('');
              row.find('.rate').val('');
              hiddenField.val('0');

              // Initialize CKEditor for this specific field
              setTimeout(function () {
                  const fieldName = descField.attr('name');
                  if (fieldName && !CKEDITOR.instances[fieldName]) {
                      const editor = CKEDITOR.replace(fieldName, {
                          height: 150,
                          toolbar: [
                              {name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike']},
                              {name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent']},
                              {name: 'links', items: ['Link', 'Unlink']},
                              {name: 'insert', items: ['Table', 'HorizontalRule']},
                              {name: 'styles', items: ['Format']},
                              {name: 'colors', items: ['TextColor', 'BGColor']},
                              {name: 'tools', items: ['Maximize']}
                          ],
                          removePlugins: 'elementspath',
                          resize_enabled: false
                      });

                      editor.on('change', function () {
                          editor.updateElement();
                          calculateTotal();
                      });

                      editorInstances[fieldName] = editor;
                  }
              }, 100);
          }
      });

      // Update CKEditor data before form submission
      $('#quotationForm').on('submit', function () {
          for (var instance in CKEDITOR.instances) {
              CKEDITOR.instances[instance].updateElement();
          }
      });

  });
</script>
