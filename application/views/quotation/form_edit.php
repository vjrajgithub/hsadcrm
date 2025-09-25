<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center">
        <h1><i class="fa fa-edit"></i> Edit Quotation <small class="text-muted">#<?= $quotation->id ?></small></h1>
        <div class="breadcrumb">
            <a href="<?= base_url('quotation') ?>" class="btn btn-default btn-sm">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
        </div>
    </section>

    <section class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="quotationEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="quotation_id" value="<?= $quotation->id ?>">
                    <input type="hidden" name="total_amount" id="total_amount">
                    <input type="hidden" name="gst_type" id="gst_type">
                    <input type="hidden" name="gst_amount" id="gst_amount">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-info-circle"></i> Quotation Details</h3>
                        </div>
                        <div class="box-body">

                            <div class="row">
                                <!-- Company -->
                                <div class="col-md-4 form-group">
                                    <label for="company_id">Company <span class="text-danger">*</span></label>
                                    <select name="company_id" id="company_id" class="form-control" required>
                                        <option value="">Select Company</option>
                                        <?php foreach ($companies as $company): ?>
                                          <option value="<?= $company->id ?>" 
                                                  <?= $company->id == $quotation->company_id ? 'selected' : '' ?>
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
                                        <?php foreach ($clients as $c): ?>
                                          <option value="<?= $c->id ?>" <?= $c->id == $quotation->client_id ? 'selected' : '' ?>>
                                              <?= $c->name ?>
                                          </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Bank -->
                                <div class="col-md-4 form-group">
                                    <label for="bank_id">Bank <span class="text-danger">*</span></label>
                                    <select name="bank_id" id="bank_id" class="form-control" required>
                                        <option value="">Select Bank</option>
                                        <?php foreach ($banks as $b): ?>
                                          <option value="<?= $b->id ?>" <?= $b->id == $quotation->bank_id ? 'selected' : '' ?>>
                                              <?= $b->bank_name ?>
                                          </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Contact Person -->
                                <div class="col-md-4 form-group">
                                    <label for="contact_person">Contact Person <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_person" id="contact_person" class="form-control" 
                                           value="<?= $quotation->contact_person ?>" required>
                                </div>

                                <!-- Department -->
                                <div class="col-md-4 form-group">
                                    <label for="department">Department <span class="text-danger">*</span></label>
                                    <input type="text" name="department" id="department" class="form-control" 
                                           value="<?= $quotation->department ?>" required>
                                </div>

                                <!-- Place of Supply -->
                                <div class="col-md-4 form-group">
                                    <label for="state">Place of Supply (State) <span class="text-danger">*</span></label>
                                    <select name="state" id="state" class="form-control" required>
                                        <option value="">Select State</option>
                                        <?php foreach ($states as $state): ?>
                                          <option value="<?= $state ?>" <?= $state == $quotation->state ? 'selected' : '' ?>><?= $state ?></option>
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
                                        <?php foreach ($modes as $m): ?>
                                          <option value="<?= $m->id ?>" <?= $m->id == $quotation->mode_id ? 'selected' : '' ?>>
                                              <?= $m->name ?>
                                          </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- HSN/SAC -->
                                <div class="col-md-4 form-group">
                                    <label for="hsn_sac">HSN/SAC Code</label>
                                    <input type="text" name="hsn_sac" id="hsn_sac" class="form-control" 
                                           value="<?= $quotation->hsn_sac ?>">
                                </div>

                                <!-- Attachment -->
                                <div class="col-md-4 form-group">
                                    <label for="attachment">Attachment</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control"
                                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                    <?php if ($quotation->attachment): ?>
                                      <small class="text-muted mt-1 d-block">
                                          <i class="fa fa-file"></i> Current: 
                                          <a href="<?= base_url('assets/uploads/quotations/' . $quotation->attachment) ?>" target="_blank">
                                              <?= $quotation->attachment ?>
                                          </a>
                                      </small>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-shopping-cart"></i> Items</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-hover" id="itemsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="20%">Category <span class="text-danger">*</span></th>
                                        <th width="25%">Product/Service <span class="text-danger">*</span></th>
                                        <th width="10%">Qty <span class="text-danger">*</span></th>
                                        <th width="15%">Rate</th>
                                        <th width="10%">Discount (%)</th>
                                        <th width="10%">Amount</th>
                                        <th width="10%">
                                            <button type="button" class="btn btn-success btn-sm" id="addItemRow">
                                                <i class="fa fa-plus"></i> Add
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $i => $item): ?>
                                      <tr>
                                          <td>
                                              <select name="items[<?= $i ?>][category_id]" class="form-control category-select" required>
                                                  <option value="">Select Category</option>
                                                  <?php foreach ($product_categories as $cat): ?>
                                                    <option value="<?= $cat->id ?>" <?= $cat->id == $item->category_id ? 'selected' : '' ?>><?= $cat->name ?></option>
                                                  <?php endforeach; ?>
                                              </select>
                                          </td>
                                          <td>
                                              <select name="items[<?= $i ?>][product_id]" class="form-control product-select" required>
                                                  <option value="<?= $item->product_id ?>"><?= $item->product_name ?></option>
                                              </select>
                                          </td>
                                          <td><input type="number" name="items[<?= $i ?>][qty]" class="form-control qty" value="<?= $item->qty ?>" min="1" required></td>
                                          <td><input type="text" name="items[<?= $i ?>][rate]" class="form-control rate" value="<?= $item->rate ?>" readonly></td>
                                          <td><input type="number" name="items[<?= $i ?>][discount]" class="form-control discount" value="<?= $item->discount ?>" min="0" max="100"></td>
                                          <td><input type="text" class="form-control amount" readonly></td>
                                          <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
                                      </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- GST + Total -->
                            <div class="row mt-3">
                                <div class="col-md-12 text-right">
                                    <div id="gstDetails" class="mb-2"></div>
                                    <h3 class="text-primary">Total Amount: <strong id="grandTotal">₹0.00</strong></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Notes Section -->
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-file-text"></i> Additional Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="terms">Terms & Conditions</label>
                                    <textarea name="terms" id="terms" class="form-control" rows="4" 
                                              placeholder="Enter terms and conditions..."><?= $quotation->terms ?></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="4" 
                                              placeholder="Enter additional notes..."><?= $quotation->notes ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer text-right">
                        <a href="<?= base_url('quotation') ?>" class="btn btn-default">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-save"></i> Update Quotation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
$(document).ready(function () {

    // Add custom alphanumeric validation method
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }, "Please enter only letters and numbers");

    let rowIndex = $('#itemsTable tbody tr').length;

    // Auto-fill terms & notes when company changes
    $('#company_id').change(function () {
        const selected = $(this).find('option:selected');
        $('#terms').val(selected.data('terms') || '');
        $('#notes').val(selected.data('notes') || '');
        calculateTotal();
    });

    // Load clients and banks when company changes
    $('#company_id').change(function () {
        const companyId = $(this).val();
        if (!companyId) return;

        // Load Clients
        $.getJSON('<?= base_url('quotation/get_clients_by_company/') ?>' + companyId, function (data) {
            let options = '<option value="">Select Client</option>';
            $.each(data, function (i, client) {
                options += `<option value="${client.id}">${client.name}</option>`;
            });
            $('#client_id').html(options);
        });

        // Load Banks
        $.getJSON('<?= base_url('quotation/get_banks_by_company/') ?>' + companyId, function (data) {
            let options = '<option value="">Select Bank</option>';
            $.each(data, function (i, bank) {
                options += `<option value="${bank.id}">${bank.name}</option>`;
            });
            $('#bank_id').html(options);
        });
    });

    // Load products by category
    $(document).on('change', '.category-select', function () {
        const row = $(this).closest('tr');
        const categoryId = $(this).val();
        if (!categoryId) return;

        $.getJSON('<?= base_url('quotation/get_products_by_category/') ?>' + categoryId, function (data) {
            let options = '<option value="">Select Product</option>';
            $.each(data, function (i, product) {
                options += `<option value="${product.id}" data-rate="${product.rate_per_unit}">${product.name}</option>`;
            });
            row.find('.product-select').html(options);
        });
    });

    // Set rate when product is selected
    $(document).on('change', '.product-select', function () {
        const rate_per_unit = $(this).find('option:selected').data('rate') || 0;
        $(this).closest('tr').find('.rate').val(rate_per_unit);
        calculateTotal();
    });

    // Add new item row
    $('#addItemRow').click(function () {
        const index = $('#itemsTable tbody tr').length;
        const rowHtml = `
            <tr>
                <td>
                    <select name="items[${index}][category_id]" class="form-control category-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($product_categories as $cat): ?>
                          <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="items[${index}][product_id]" class="form-control product-select" required>
                        <option value="">Select Product</option>
                    </select>
                </td>
                <td><input type="number" name="items[${index}][qty]" class="form-control qty" min="1" required></td>
                <td><input type="text" name="items[${index}][rate]" class="form-control rate" readonly></td>
                <td><input type="number" name="items[${index}][discount]" class="form-control discount" value="0" min="0" max="100"></td>
                <td><input type="text" class="form-control amount" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
            </tr>`;
        $('#itemsTable tbody').append(rowHtml);
        rowIndex++;
    });

    // Remove item row
    $(document).on('click', '.removeRow', function () {
        if ($('#itemsTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotal();
        } else {
            Swal.fire('Warning', 'At least one item is required', 'warning');
        }
    });

    // Recalculate totals on input changes
    $(document).on('input', '.qty, .discount', function () {
        calculateTotal();
    });
    
    $('#state, #company_id').change(function () {
        calculateTotal();
    });

    // Calculate total function with GST
    function calculateTotal() {
        let total = 0;
        $('#itemsTable tbody tr').each(function () {
            const qty = parseFloat($(this).find('.qty').val()) || 0;
            const rate = parseFloat($(this).find('.rate').val()) || 0;
            const discount = parseFloat($(this).find('.discount').val()) || 0;
            let amount = qty * rate;
            amount -= amount * discount / 100;
            $(this).find('.amount').val(amount.toFixed(2));
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
        $('#grandTotal').text('₹' + (total + gstAmount).toFixed(2));

        // Update hidden fields
        $('#total_amount').val((total + gstAmount).toFixed(2));
        $('#gst_type').val(gstType);
        $('#gst_amount').val(gstAmount.toFixed(2));
    }

    // Enhanced validation and submit
    $('#quotationEditForm').validate({
        rules: {
            company_id: {required: true},
            client_id: {required: true},
            bank_id: {required: true},
            contact_person: {required: true, minlength: 2, maxlength: 100},
            department: {required: true, minlength: 2, maxlength: 100},
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
                required: "Department is required",
                minlength: "Department must be at least 2 characters",
                maxlength: "Department cannot exceed 100 characters"
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
            $('#itemsTable tbody tr').each(function() {
                const categoryId = $(this).find('.category-select').val();
                const productId = $(this).find('.product-select').val();
                const qty = $(this).find('.qty').val();
                
                if (categoryId && productId && qty) {
                    hasItems = true;
                    return false; // break loop
                }
            });
            
            if (!hasItems) {
                Swal.fire('Validation Error', 'Please add at least one item with category, product, and quantity', 'error');
                return false;
            }
            
            const formData = new FormData(form);
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to update this quotation.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('quotation/update') ?>',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Updating Quotation...',
                                text: 'Please wait while we update the quotation.',
                                icon: 'info',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function (res) {
                            Swal.close();
                            res = JSON.parse(res);
                            if (res.status) {
                                Swal.fire('Success', 'Quotation updated successfully!', 'success')
                                        .then(() => window.location.href = '<?= base_url('quotation') ?>');
                            } else {
                                Swal.fire('Error', res.message || 'Something went wrong!', 'error');
                            }
                        },
                        error: function () {
                            Swal.close();
                            Swal.fire('Error', 'Server error occurred while updating quotation', 'error');
                        }
                    });
                }
            });
        }
    });

    // Initial calculation on page load
    calculateTotal();

});

</script>

