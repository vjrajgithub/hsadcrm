<div class="container mt-4">
    <h2>Create Quotation</h2>
    <form id="quotationForm">
        <div class="row">
            <div class="col-md-4">
                <label for="client_id">Client</label>
                <select name="client_id" id="client_id" class="form-control" required>
                    <option value="">Select Client</option>
                    <?php foreach ($clients as $c): ?>
                      <option value="<?= $c->id ?>"><?= $c->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="bank_id">Bank</label>
                <select name="bank_id" id="bank_id" class="form-control" required>
                    <option value="">Select Bank</option>
                    <?php foreach ($banks as $b): ?>
                      <option value="<?= $b->id ?>"><?= $b->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" class="form-control" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <label for="mode_id">Mode</label>
                <select name="mode_id" id="mode_id" class="form-control" required>
                    <option value="">Select Mode</option>
                    <?php foreach ($modes as $m): ?>
                      <option value="<?= $m->id ?>"><?= $m->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="state">Place of Supply (State)</label>
                <select name="state" id="state" class="form-control" required>
                    <option value="">Select State</option>
                    <option value="UP">Uttar Pradesh</option>
                    <option value="MH">Maharashtra</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="department">Department</label>
                <select name="department" id="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <option value="ATL">ATL</option>
                    <option value="BTL">BTL</option>
                    <option value="Digital">Digital</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="terms">Terms & Conditions</label>
                <textarea name="terms" id="terms" class="form-control" rows="3"><?= $companyDetails->terms_conditions ?></textarea>
            </div>
            <div class="col-md-6">
                <label for="notes">Note</label>
                <textarea name="notes" id="notes" class="form-control" rows="3"><?= $companyDetails->note ?></textarea>
            </div>
        </div>

        <hr>
        <h5>Products / Services</h5>
        <table class="table table-bordered" id="productTable">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Discount %</th>
                    <th>GST %</th>
                    <th>Amount</th>
                    <th><button type="button" class="btn btn-sm btn-success" id="addRow"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="row">
            <div class="col-md-3 offset-md-6 text-right"><strong>Subtotal:</strong></div>
            <div class="col-md-3"><input type="text" id="subtotal" class="form-control" readonly></div>

            <div class="col-md-3 offset-md-6 text-right"><strong>GST Total:</strong></div>
            <div class="col-md-3"><input type="text" id="gst_total" class="form-control" readonly></div>

            <div class="col-md-3 offset-md-6 text-right"><strong>Grand Total:</strong></div>
            <div class="col-md-3"><input type="text" id="grand_total" class="form-control font-weight-bold" readonly></div>
        </div>

        <div class="text-right mt-4">
            <button type="submit" class="btn btn-primary">Save Quotation</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(function () {
      let rowIndex = 0;

      function addProductRow() {
          let categories = <?= json_encode($product_categories) ?>;
          let categoryOptions = '<option value="">Select</option>';
          categories.forEach(cat => {
              categoryOptions += `<option value="${cat.id}">${cat.name}</option>`;
          });

          let row = `
            <tr data-index="${rowIndex}">
                <td>
                    <select name="items[${rowIndex}][category_id]" class="form-control category" required>
                        ${categoryOptions}
                    </select>
                </td>
                <td>
                    <select name="items[${rowIndex}][product_id]" class="form-control product" required>
                        <option value="">Select Product</option>
                    </select>
                </td>
                <td><input type="number" class="form-control qty" min="1" value="1"></td>
                <td><input type="text" class="form-control rate" readonly></td>
                <td><input type="number" class="form-control discount" min="0" value="0"></td>
                <td><input type="number" class="form-control gst" min="0" value="18"></td>
                <td><input type="text" class="form-control amount" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger removeRow"><i class="fa fa-trash"></i></button></td>
            </tr>`;
          $('#productTable tbody').append(row);
          rowIndex++;
      }

      function calculateTotals() {
          let subtotal = 0, gstTotal = 0;
          $('#productTable tbody tr').each(function () {
              const qty = parseFloat($(this).find('.qty').val()) || 0;
              const rate = parseFloat($(this).find('.rate').val()) || 0;
              const discount = parseFloat($(this).find('.discount').val()) || 0;
              const gst = parseFloat($(this).find('.gst').val()) || 0;
              let amount = (qty * rate);
              amount -= (amount * discount / 100);
              const gstAmount = amount * gst / 100;
              const total = amount + gstAmount;
              $(this).find('.amount').val(total.toFixed(2));
              subtotal += amount;
              gstTotal += gstAmount;
          });
          $('#subtotal').val(subtotal.toFixed(2));
          $('#gst_total').val(gstTotal.toFixed(2));
          $('#grand_total').val((subtotal + gstTotal).toFixed(2));
      }

      $(document).on('change', '.category', function () {
          const row = $(this).closest('tr');
          const cat_id = $(this).val();
          const productSelect = row.find('.product');
          productSelect.html('<option>Loading...</option>');

          $.get(`<?= site_url('quotation/get_products_by_category/') ?>${cat_id}`, function (data) {
              const products = JSON.parse(data);
              let options = '<option value="">Select Product</option>';
              products.forEach(p => {
                  options += `<option value="${p.id}" data-rate="${p.rate}">${p.name}</option>`;
              });
              productSelect.html(options);
          });
      });

      $(document).on('change', '.product', function () {
          const rate = $(this).find('option:selected').data('rate') || 0;
          const row = $(this).closest('tr');
          row.find('.rate').val(rate);
          calculateTotals();
      });

      $(document).on('input', '.qty, .discount, .gst', function () {
          calculateTotals();
      });

      $(document).on('click', '.removeRow', function () {
          $(this).closest('tr').remove();
          calculateTotals();
      });

      $('#addRow').click(addProductRow);
      addProductRow(); // initial row

      $('#quotationForm').validate({
          submitHandler: function (form) {
              Swal.fire({
                  icon: 'success',
                  title: 'Quotation Saved',
                  text: 'The quotation has been successfully created!'
              });
              return false;
          }
      });
  });
</script>
