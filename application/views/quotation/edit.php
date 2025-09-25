    <section class="content-header d-flex justify-content-between align-items-center">
        <h1>Edit Quotation <small>#<?= $quotation->id ?></small></h1>
    </section>

    <section class="content">
         <div class="card shadow-sm">
            <div class="card-body">
        <form id="quotationEditForm" enctype="multipart/form-data">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="quotation_id" value="<?= $quotation->id ?>">
            <input type="hidden" name="old_attachment" value="<?= $quotation->attachment ?>">
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
                                <?php foreach ($clients as $client): ?>
                                  <option value="<?= $client->id ?>" <?= $client->id == $quotation->client_id ? 'selected' : '' ?>>
                                      <?= $client->name ?>
                                  </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Bank -->
                        <div class="col-md-4 form-group">
                            <label for="bank_id">Bank <span class="text-danger">*</span></label>
                            <select name="bank_id" id="bank_id" class="form-control" required>
                                <option value="">Select Bank</option>
                                <?php foreach ($banks as $bank): ?>
                                  <option value="<?= $bank->id ?>" <?= $bank->id == $quotation->bank_id ? 'selected' : '' ?>>
                                      <?= $bank->name ?>
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

                        <!-- Department (from Client Categories) -->
                        <div class="col-md-4 form-group">
                            <label for="department">Department <span class="text-danger">*</span></label>
                            <select name="department" id="department" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php if (!empty($departments)) : ?>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= htmlspecialchars($dept->name) ?>" <?= ($quotation->department === $dept->name) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($dept->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Place of Supply -->
                        <div class="col-md-4 form-group">
                            <label for="state">Place of Supply <span class="text-danger">*</span></label>
                            <select name="state" id="state" class="form-control" required>
                                <option value="">Select State</option>
                                <?php foreach ($states as $state): ?>
                                  <option value="<?= $state ?>" <?= $state == $quotation->state ? 'selected' : '' ?>>
                                      <?= $state ?>
                                  </option>
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
                                  <option value="<?= $mode->id ?>" <?= $mode->id == $quotation->mode_id ? 'selected' : '' ?>>
                                      <?= $mode->name ?>
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

                        <!-- Job No -->
                        <div class="col-md-4 form-group">
                            <label for="job_no">Job No</label>
                            <input type="text" name="job_no" id="job_no" class="form-control" maxlength="128" value="<?= htmlspecialchars($quotation->job_no) ?>" placeholder="Enter Job No">
                        </div>

                        <!-- Attachment -->
                        <div class="col-md-4 form-group">
                            <label for="attachment">Attachment</label>
                            <input type="file" name="attachment" id="attachment" class="form-control"
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <?php if ($quotation->attachment): ?>
                                <small class="text-muted">Current: <?= $quotation->attachment ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    <!-- Product/Service Items -->
                    <h4>Items</h4>
                    <table class="table table-bordered" id="itemsTable">
                        <thead class="bg-gray">
                            <tr>
                                <th>Category</th>
                                <th>Product/Service</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Discount (%)</th>
                                <th><button type="button" id="addRow" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></th>
                            </tr>
                        </thead>
                                <tbody>
                                    <?php if (!empty($quotation->items)): ?>
                                        <?php foreach ($quotation->items as $index => $item): ?>
                                            <tr>
                                                <td>
                                                    <select name="items[<?= $index ?>][category_id]" class="form-control category-select" required>
                                                        <option value="">Select Category</option>
                                                        <?php foreach ($product_categories as $cat): ?>
                                                            <option value="<?= $cat->id ?>" <?= $cat->id == $item->category_id ? 'selected' : '' ?>>
                                                                <?= $cat->name ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="items[<?= $index ?>][product_id]" class="form-control product-select" required>
                                                        <option value="">Select Product</option>
                                                        <?php if (isset($all_products[$item->category_id])): ?>
                                                            <?php foreach ($all_products[$item->category_id] as $product): ?>
                                                                <option value="<?= $product->id ?>" 
                                                                        data-rate="<?= $product->rate_per_unit ?>"
                                                                        <?= $product->id == $item->product_id ? 'selected' : '' ?>>
                                                                    <?= $product->name ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                                <td><input type="number" name="items[<?= $index ?>][qty]" class="form-control qty" min="1" value="<?= $item->qty ?>" required></td>
                                <td><input type="text" name="items[<?= $index ?>][rate]" class="form-control rate" value="<?= $item->rate ?>" readonly></td>
                                <td><input type="number" name="items[<?= $index ?>][discount]" class="form-control discount" value="<?= $item->discount ?>"></td>
                                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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

                    <!-- Terms and Notes -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="terms">Terms & Conditions</label>
                            <textarea name="terms" id="terms" class="form-control" rows="4"><?= $quotation->terms ?></textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4"><?= $quotation->notes ?></textarea>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td id="subtotal">0.00</td>
                                </tr>
                                <tr>
                                    <td><strong>GST (<span id="gst_type_display">18%</span>):</strong></td>
                                    <td id="gst_amount_display">0.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Grand Total:</strong></td>
                                    <td id="grand_total">0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update Quotation</button>
                <a href="<?= base_url('quotation') ?>" class="btn btn-default">Cancel</a>
            </div>
        </form>
            </div>
        </div>
    </section>

<script>
$(document).ready(function() {
    let itemIndex = <?= count($quotation->items ?? []) ?>;
    
    // Calculate totals on page load
    calculateTotal();
    
    // Add row
    $('#addRow').click(function () {
        const index = $('#itemsTable tbody tr').length;
        const rowHtml = $('#itemsTable tbody tr:first').clone();
        rowHtml.find('input, select').each(function () {
            const name = $(this).attr('name').replace(/\d+/, index);
            $(this).attr('name', name).val('');
        });
        $('#itemsTable tbody').append(rowHtml);
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
    
    // Load products when category changes
    $(document).on('change', '.category-select', function() {
        const categoryId = $(this).val();
        const productSelect = $(this).closest('tr').find('.product-select');
        
        if (categoryId) {
            $.get('<?= base_url('quotation/get_products_by_category/') ?>' + categoryId, function(data) {
                productSelect.html('<option value="">Select Product</option>');
                $.each(data, function(i, product) {
                    productSelect.append('<option value="' + product.id + '" data-rate="' + product.rate_per_unit + '">' + product.name + '</option>');
                });
            }, 'json');
        }
    });
    
    // Set rate when product changes
    $(document).on('change', '.product-select', function() {
        const rate_per_unit = $(this).find('option:selected').data('rate') || 0;
        $(this).closest('tr').find('.rate').val(rate_per_unit);
        calculateTotal();
    });
    $('#state, #company_id').change(function () {
        calculateTotal();
    });
    
    // Add custom alphanumeric validation method
    $.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }, "Please enter only letters and numbers");

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
            $.ajax({
                url: '<?= base_url('quotation/update/' . $quotation->id) ?>',
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
    
    function addItemRow() {
        const row = `
            <tr>
                <td>
                    <select name="items[${itemIndex}][category_id]" class="form-control category-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($product_categories as $cat): ?>
                            <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="items[${itemIndex}][product_id]" class="form-control product-select" required>
                        <option value="">Select Product</option>
                    </select>
                </td>
                <td><input type="number" name="items[${itemIndex}][qty]" class="form-control qty-input" value="1" required></td>
                <td><input type="number" step="0.01" name="items[${itemIndex}][rate]" class="form-control rate-input" required></td>
                <td><input type="number" step="0.01" name="items[${itemIndex}][discount]" class="form-control discount-input" value="0"></td>
                <td><input type="number" step="0.01" name="items[${itemIndex}][amount]" class="form-control amount-input" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
            </tr>
        `;
        $('#itemsTable tbody').append(row);
        itemIndex++;
    }
    
    function calculateRowAmount(row) {
        const qty = parseFloat(row.find('.qty-input').val()) || 0;
        const rate = parseFloat(row.find('.rate-input').val()) || 0;
        const discount = parseFloat(row.find('.discount-input').val()) || 0;
        
        let amount = qty * rate;
        if (discount > 0) {
            amount -= (amount * discount / 100);
        }
        
        row.find('.amount-input').val(amount.toFixed(2));
    }
    
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
});
</script>
