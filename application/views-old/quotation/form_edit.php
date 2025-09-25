<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Quotation <small>#<?= $quotation->id ?></small></h1>
    </section>

    <section class="content">
        <form id="quotationEditForm" enctype="multipart/form-data">
            <input type="hidden" name="quotation_id" value="<?= $quotation->id ?>">

            <div class="box box-primary">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Client</label>
                            <select name="client_id" class="form-control" required>
                                <option value="">Select Client</option>
                                <?php foreach ($clients as $c): ?>
                                  <option value="<?= $c->id ?>" <?= $c->id == $quotation->client_id ? 'selected' : '' ?>>
                                      <?= $c->name ?>
                                  </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Bank</label>
                            <select name="bank_id" class="form-control" required>
                                <option value="">Select Bank</option>
                                <?php foreach ($banks as $b): ?>
                                  <option value="<?= $b->id ?>" <?= $b->id == $quotation->bank_id ? 'selected' : '' ?>>
                                      <?= $b->bank_name ?>
                                  </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" value="<?= $quotation->contact_person ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Mode</label>
                            <select name="mode_id" class="form-control" required>
                                <option value="">Select Mode</option>
                                <?php foreach ($modes as $m): ?>
                                  <option value="<?= $m->id ?>" <?= $m->id == $quotation->mode_id ? 'selected' : '' ?>>
                                      <?= $m->mode_name ?>
                                  </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Place of Supply</label>
                            <input type="text" name="state" class="form-control" value="<?= $quotation->state ?>" required>
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Department</label>
                            <input type="text" name="department" class="form-control" value="<?= $quotation->department ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Terms & Conditions</label>
                            <textarea name="terms" class="form-control" rows="3"><?= $quotation->terms ?></textarea>
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Note</label>
                            <textarea name="notes" class="form-control" rows="3"><?= $quotation->notes ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Attachment</label>
                        <input type="file" name="attachment" class="form-control">
                        <?php if ($quotation->attachment): ?>
                          <p class="mt-2">Existing File:
                              <a href="<?= base_url('uploads/quotations/' . $quotation->attachment) ?>" target="_blank">
                                  <?= $quotation->attachment ?>
                              </a>
                          </p>
                        <?php endif; ?>
                    </div>

                    <!-- 🧾 ITEM TABLE -->
                    <hr>
                    <h4>Quotation Items</h4>
                    <table class="table table-bordered" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Discount (%)</th>
                                <th>GST (%)</th>
                                <th>Total</th>
                                <th><button type="button" class="btn btn-success btn-sm" id="addItemRow">+</button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $i => $item): ?>
                              <tr>
                                  <td>
                                      <select name="items[<?= $i ?>][category_id]" class="form-control category-dropdown" required>
                                          <option value="">Select</option>
                                          <?php foreach ($product_categories as $cat): ?>
                                            <option value="<?= $cat->id ?>" <?= $cat->id == $item->category_id ? 'selected' : '' ?>><?= $cat->name ?></option>
                                          <?php endforeach; ?>
                                      </select>
                                  </td>
                                  <td>
                                      <select name="items[<?= $i ?>][product_id]" class="form-control product-dropdown" required>
                                          <option value="<?= $item->product_id ?>"><?= $item->product_name ?></option>
                                      </select>
                                  </td>
                                  <td><input type="number" name="items[<?= $i ?>][qty]" class="form-control qty" value="<?= $item->qty ?>" required></td>
                                  <td><input type="number" name="items[<?= $i ?>][rate]" class="form-control rate" value="<?= $item->rate ?>" step="0.01" required></td>
                                  <td><input type="number" name="items[<?= $i ?>][discount]" class="form-control discount" value="<?= $item->discount ?>" step="0.01"></td>
                                  <td><input type="number" name="items[<?= $i ?>][gst]" class="form-control gst" value="<?= $item->gst ?>" step="0.01"></td>
                                  <td><input type="text" class="form-control total" readonly></td>
                                  <td><button type="button" class="btn btn-danger btn-sm removeRow">×</button></td>
                              </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-primary">💾 Update Quotation</button>
                </div>
            </div>
        </form>
    </section>
</div>
<script>$(document).ready(function () {
      let rowIndex = $('#itemsTable tbody tr').length;

      function calculateRowTotal(row) {
          const qty = parseFloat(row.find('.qty').val()) || 0;
          const rate = parseFloat(row.find('.rate').val()) || 0;
          const discount = parseFloat(row.find('.discount').val()) || 0;
          const gst = parseFloat(row.find('.gst').val()) || 0;

          let subtotal = qty * rate;
          let discountAmount = subtotal * (discount / 100);
          let gstAmount = (subtotal - discountAmount) * (gst / 100);
          let total = subtotal - discountAmount + gstAmount;

          row.find('.total').val(total.toFixed(2));
      }

      $('#itemsTable').on('input', '.qty, .rate, .discount, .gst', function () {
          const row = $(this).closest('tr');
          calculateRowTotal(row);
      });

      $('#itemsTable').on('click', '.removeRow', function () {
          $(this).closest('tr').remove();
      });

      $('#addItemRow').click(function () {
          const newRow = `
        <tr>
            <td>
                <select name="items[${rowIndex}][category_id]" class="form-control category-dropdown" required>
                    <option value="">Select</option>
                    ${window.categoryOptionsHtml || ''}
                </select>
            </td>
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-control product-dropdown" required>
                    <option value="">Select</option>
                </select>
            </td>
            <td><input type="number" name="items[${rowIndex}][qty]" class="form-control qty" required></td>
            <td><input type="number" name="items[${rowIndex}][rate]" class="form-control rate" step="0.01" required></td>
            <td><input type="number" name="items[${rowIndex}][discount]" class="form-control discount" step="0.01"></td>
            <td><input type="number" name="items[${rowIndex}][gst]" class="form-control gst" step="0.01"></td>
            <td><input type="text" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">×</button></td>
        </tr>`;
          $('#itemsTable tbody').append(newRow);
          rowIndex++;
      });

      // Optional: Load products on category change
      $('#itemsTable').on('change', '.category-dropdown', function () {
          const categoryId = $(this).val();
          const productDropdown = $(this).closest('tr').find('.product-dropdown');

          if (categoryId) {
              $.get(BASE_URL + 'quotation/get_products_by_category/' + categoryId, function (data) {
                  const options = JSON.parse(data);
                  productDropdown.html('<option value="">Select</option>');
                  $.each(options, function (i, obj) {
                      productDropdown.append(`<option value="${obj.id}">${obj.name}</option>`);
                  });
              });
          } else {
              productDropdown.html('<option value="">Select</option>');
          }
      });

      // jQuery Validate + AJAX Submit
      $('#quotationEditForm').validate({
          submitHandler: function (form) {
              const formData = new FormData(form);

              Swal.fire({
                  title: 'Updating...',
                  text: 'Please wait while the quotation is updated.',
                  icon: 'info',
                  allowOutsideClick: false,
                  showConfirmButton: false
              });

              $.ajax({
                  url: BASE_URL + 'quotation/update',
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function (response) {
                      Swal.close();
                      const res = JSON.parse(response);
                      if (res.status) {
                          Swal.fire('Success', res.message, 'success').then(() => {
                              window.location.href = BASE_URL + 'quotation';
                          });
                      } else {
                          Swal.fire('Error', res.message || 'Update failed', 'error');
                      }
                  },
                  error: function () {
                      Swal.close();
                      Swal.fire('Error', 'Something went wrong', 'error');
                  }
              });

              return false;
          }
      });

      // Initial total calculation
      $('#itemsTable tbody tr').each(function () {
          calculateRowTotal($(this));
      });
  });
  $(document).ready(function () {

      let rowIndex = $('#productRows .product-row').length;

      // Add new product row
      $('#addRowBtn').click(function () {
          $.ajax({
              url: BASE_URL + 'quotation/get_blank_item_row/' + rowIndex,
              type: 'GET',
              success: function (html) {
                  $('#productRows').append(html);
                  rowIndex++;
              }
          });
      });

      // Remove row
      $(document).on('click', '.remove-row', function () {
          $(this).closest('.product-row').remove();
      });

      // Load product on category change
      $(document).on('change', '.category-dropdown', function () {
          const categoryId = $(this).val();
          const $productDropdown = $(this).closest('.product-row').find('.product-dropdown');
          $productDropdown.html('<option value="">Loading...</option>');

          $.ajax({
              url: BASE_URL + 'quotation/get_products_by_category/' + categoryId,
              type: 'GET',
              success: function (res) {
                  const data = JSON.parse(res);
                  let options = '<option value="">-- Select --</option>';
                  data.forEach(product => {
                      options += `<option value="${product.id}">${product.name}</option>`;
                  });
                  $productDropdown.html(options);
              }
          });
      });

      // Auto calculate total on qty, rate, gst, discount change
      $(document).on('input', '.qty, .rate, .discount, .gst', function () {
          const $row = $(this).closest('.product-row');
          const qty = parseFloat($row.find('.qty').val()) || 0;
          const rate = parseFloat($row.find('.rate').val()) || 0;
          const discount = parseFloat($row.find('.discount').val()) || 0;
          const gst = parseFloat($row.find('.gst').val()) || 0;

          let amount = qty * rate;
          amount -= amount * (discount / 100);
          amount += amount * (gst / 100);

          $row.find('.total').val(amount.toFixed(2));
      });

      // jQuery Validate + SweetAlert + AJAX
      $('#editQuotationForm').validate({
          submitHandler: function (form) {
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
                          url: BASE_URL + 'quotation/update',
                          type: 'POST',
                          data: formData,
                          contentType: false,
                          processData: false,
                          success: function (res) {
                              const response = JSON.parse(res);
                              if (response.status) {
                                  Swal.fire('Updated!', response.message, 'success')
                                          .then(() => window.location.href = BASE_URL + 'quotation');
                              } else {
                                  Swal.fire('Error!', response.message, 'error');
                              }
                          },
                          error: function () {
                              Swal.fire('Error!', 'Something went wrong.', 'error');
                          }
                      });
                  }
              });
          }
      });

  });

</script>

