<div class="content-wrapper">
    <!-- HEADER -->
    <section class="content-header">
        <h1>
            View Quotation <small>#<?= $quotation->id ?></small>
            <div class="btn-group pull-right">
                <a href="<?= base_url('quotation/create') ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Add New
                </a>
                <a href="<?= base_url('quotation/edit/' . $quotation->id) ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <a href="<?= base_url('quotation/duplicate/' . $quotation->id) ?>" class="btn btn-info btn-sm">
                    <i class="fa fa-clone"></i> Duplicate
                </a>
                <a href="<?= base_url('quotation/view_pdf/' . $quotation->id) ?>" target="_blank" class="btn btn-warning btn-sm">
                    <i class="fa fa-file-pdf-o"></i> View PDF
                </a>
                <a href="<?= base_url('quotation/generate_pdf/' . $quotation->id) ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-download"></i> Download PDF
                </a>
                <button
                    class="btn btn-sm btn-warning"
                    id="sendMailBtn"
                    data-id="<?= $quotation->id ?>"
                    data-client="<?= $quotation->client_name ?>"
                    data-email="<?= $quotation->client_email ?>">
                    <i class="fa fa-envelope"></i> Send Mail
                </button>
                <a href="#" class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $quotation->id ?>)">
                    <i class="fa fa-trash"></i> Delete
                </a>
                <button onclick="window.print()" class="btn btn-secondary btn-sm">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </h1>
    </section>

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <!-- COMPANY & CLIENT INFO -->
                <div class="row">
                    <div class="col-md-6">
                        <h4><strong>Company:</strong> <?= $quotation->company_name ?></h4>
                        <p><strong>State:</strong> <?= $quotation->company_state ?></p>
                        <p><strong>Bank:</strong> <?= $quotation->bank_name ?></p>
                        <p><strong>Mode:</strong> <?= $quotation->mode_name ?></p>
                    </div>
                    <div class="col-md-6 text-right">
                        <h4><strong>Client:</strong> <?= $quotation->client_name ?></h4>
                        <p><strong>Contact Person:</strong> <?= $quotation->contact_person ?></p>
                        <p><strong>Department:</strong> <?= $quotation->department ?></p>
                        <p><strong>Place of Supply:</strong> <?= $quotation->state ?></p>
                        <p><strong>Date:</strong> <?= date('d-m-Y', strtotime($quotation->created_at)) ?></p>
                    </div>
                </div>

                <hr>

                <!-- ITEMS TABLE -->
                <h4>Items</h4>
                <table class="table table-bordered table-striped">
                    <thead class="bg-gray">
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Product/Service</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>Discount</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $total = 0;
                        foreach ($items as $item):
                          $amount = ($item->qty * $item->rate) - (($item->qty * $item->rate) * $item->discount / 100);
                          $total += $amount;
                          ?>
                          <tr>
                              <td><?= $i++ ?></td>
                              <td><?= $item->category_name ?></td>
                              <td><?= $item->product_name ?></td>
                              <td><?= $item->qty ?></td>
                              <td>₹<?= number_format($item->rate, 2) ?></td>
                              <td><?= $item->discount ?>%</td>
                              <td>₹<?= number_format($amount, 2) ?></td>
                          </tr>
<?php endforeach; ?>
                    </tbody>
                </table>

                <!-- TOTAL & GST -->
                <div class="text-right">
                    <h4><strong>Total Amount:</strong> ₹<?= number_format($total, 2) ?></h4>
                    <?php if ($quotation->gst_type && $quotation->gst_amount): ?>
                      <p><strong>GST (<?= strtoupper($quotation->gst_type) ?>):</strong> ₹<?= number_format($quotation->gst_amount, 2) ?></p>
                      <h3><strong>Grand Total:</strong> ₹<?= number_format($total + $quotation->gst_amount, 2) ?></h3>
<?php endif; ?>
                </div>

                <hr>

                <!-- TERMS & NOTES -->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Terms & Conditions</h4>
                        <p><?= nl2br($quotation->terms) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Notes</h4>
                        <p><?= nl2br($quotation->notes) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- 📧 Send Mail Modal -->
<div class="modal fade" id="sendMailModal" tabindex="-1" role="dialog" aria-labelledby="sendMailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="sendMailForm" enctype="multipart/form-data">
            <input type="hidden" name="quotation_id" id="mailQuotationId">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">📧 Send Quotation Mail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>To (comma-separated)</label>
                        <input type="text" name="to" id="mailTo" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>CC</label>
                        <input type="text" name="cc" id="mailCC" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" value="Quotation from CRM" />
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="4">Dear Sir/Madam,

Please find the attached quotation for your reference.

Regards,
CRM Team</textarea>
                    </div>
                    <div class="form-group">
                        <label>Attachment (optional)</label>
                        <input type="file" name="attachment" class="form-control-file" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">📨 Send Mail</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">❌ Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
  // SweetAlert delete confirmation
  function confirmDelete(id) {
      Swal.fire({
          title: "Are you sure?",
          text: "This quotation will be deleted permanently!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Yes, delete it!"
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = "<?= base_url('quotation/delete/') ?>" + id;
          }
      });
  }

  // Send Mail Modal
  $('#sendMailBtn').click(function () {
      const quotationId = $(this).data('id');
      const email = $(this).data('email');
      $('#mailQuotationId').val(quotationId);
      $('#mailTo').val(email);
      $('#mailCC').val('');
      $('#sendMailModal').modal('show');
  });

  // Send Mail AJAX
  $('#sendMailForm').submit(function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      $.ajax({
          url: '<?= base_url('quotation/send_mail') ?>',
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          beforeSend: function () {
              Swal.fire({
                  title: 'Sending Email...',
                  text: 'Please wait while the email is being sent.',
                  icon: 'info',
                  showConfirmButton: false,
                  allowOutsideClick: false
              });
          },
          success: function (res) {
              Swal.close();
              const response = JSON.parse(res);
              if (response.status) {
                  Swal.fire('✅ Success', response.message || 'Email sent successfully!', 'success');
                  $('#sendMailModal').modal('hide');
              } else {
                  Swal.fire('❌ Error', response.message || 'Failed to send email.', 'error');
              }
          },
          error: function () {
              Swal.close();
              Swal.fire('❌ Error', 'Unexpected error occurred while sending mail.', 'error');
          }
      });
  });
</script>
