<?php
//print_r($companies);
//die;
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Create Quotation</h1>
    </section>

    <section class="content">
        <form id="quotationForm" enctype="multipart/form-data">
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

                        <!-- Department -->
                        <div class="col-md-4 form-group">
                            <label for="department">Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" id="department" class="form-control" required>
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
                                <th>Category</th>
                                <th>Product/Service</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Discount (%)</th>
                                <th><button type="button" id="addRow" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][category_id]" class="form-control category-select" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($product_categories as $cat): ?>
                                          <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="items[0][product_id]" class="form-control product-select" required>
                                        <option value="">Select Product</option>
                                    </select>
                                </td>
                                <td><input type="number" name="items[0][qty]" class="form-control qty" min="1" required></td>
                                <td><input type="text" name="items[0][rate]" class="form-control rate" readonly></td>
                                <td><input type="number" name="items[0][discount]" class="form-control discount" value="0"></td>
                                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fa fa-trash"></i></button></td>
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
    </section>
</div>
<script>
  $(document).ready(function () {

      // Auto-fill terms & notes from company
      $('#company_id').change(function () {
          const selected = $(this).find('option:selected');
          $('#terms').val(selected.data('terms') || '');
          $('#notes').val(selected.data('notes') || '');
      });

      // Load clients and banks dynamically
      $('#company_id').change(function () {
          const companyId = $(this).val();
          if (!companyId)
              return;

          // Clients
          $.getJSON('<?= base_url('quotation/get_clients_by_company/') ?>' + companyId, function (data) {
              let options = '<option value="">Select Client</option>';
              $.each(data, function (i, client) {
                  options += `<option value="${client.id}">${client.name}</option>`;
              });
              $('#client_id').html(options);
          });

          // Banks
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

      // Set rate on product select
      $(document).on('change', '.product-select', function () {
          const rate_per_unit = $(this).find('option:selected').data('rate') || 0;
          $(this).closest('tr').find('.rate').val(rate_per_unit);
          calculateTotal();
      });

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

      // Recalculate totals on change
      $(document).on('input', '.qty, .discount', function () {
          calculateTotal();
      });

      $('#state, #company_id').change(function () {
          calculateTotal();
      });

      // Calculate GST and total
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
          const gstRate = 18; // default 18%
          let gstHTML = '', gstAmount = 0;

          if (companyState && supplyState) {
              if (companyState === supplyState) {
                  const cgst = gstRate / 2;
                  const cgstAmt = (total * cgst) / 100;
                  const sgstAmt = cgstAmt;

                  gstHTML = `<p>CGST ${cgst}% : ₹${cgstAmt.toFixed(2)}</p>
                           <p>SGST ${cgst}% : ₹${sgstAmt.toFixed(2)}</p>`;
                  gstAmount = cgstAmt + sgstAmt;
              } else {
                  const igstAmt = (total * gstRate) / 100;
                  gstHTML = `<p>IGST ${gstRate}% : ₹${igstAmt.toFixed(2)}</p>`;
                  gstAmount = igstAmt;
              }
          }

          $('#gstDetails').html(gstHTML);
          $('#grandTotal').text((total + gstAmount).toFixed(2));
      }

      // Validate and submit
      $('#quotationForm').validate({
          submitHandler: function (form) {
              const formData = new FormData(form);
              $.ajax({
                  url: '<?= base_url('quotation/store') ?>',
                  type: 'POST',
                  data: formData,
                  contentType: false,
                  processData: false,
                  success: function (res) {
                      res = JSON.parse(res);
                      if (res.status) {
                          Swal.fire('Success', 'Quotation saved successfully!', 'success')
                                  .then(() => window.location.href = '<?= base_url('quotation') ?>');
                      } else {
                          Swal.fire('Error', res.message || 'Something went wrong!', 'error');
                      }
                  }
              });
          }
      });

  });
</script>
