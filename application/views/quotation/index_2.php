<div class="content-wrapper">
    <section class="content-header">
        <h1>Quotation List</h1>
        <a href="<?= base_url('quotation/create') ?>" class="btn btn-success pull-right">➕ Add New Quotation</a>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <table id="quotationTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Contact Person</th>
                            <th>Department</th>
                            <th>Place of Supply</th>

                            <th>Date</th>
                            <th>Attachment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quotations as $index => $quotation): ?>
                          <tr>
                              <td><?= $index + 1 ?></td>
                              <td><?= $quotation->client_name ?></td>
                              <td><?= $quotation->contact_person ?></td>
                              <td><?= $quotation->department ?></td>
                              <td><?= $quotation->state ?></td>
                              <td><?= date('d-m-Y', strtotime($quotation->created_at)) ?></td>
                              <td>
                                  <?php if ($quotation->attachment): ?>
                                    <a href="<?= base_url("assets/uploads/quotations/{$quotation->attachment}") ?>" target="_blank">
                                        <i class="fa fa-paperclip"></i>
                                    </a>
                                  <?php endif; ?>
                              </td>
                              <td>
                                  <a href="<?= base_url('quotation/view/' . $quotation->id) ?>" class="btn btn-sm btn-info" title="View"><i class="fa fa-eye"></i></a>
                                  <a href="<?= base_url('quotation/view_pdf/' . $quotation->id) ?>" target="_blank" class="btn btn-sm btn-primary" title="View PDF"><i class="fa fa-file-pdf-o"></i></a>
                                  <a href="<?= base_url('quotation/generate_pdf/' . $quotation->id) ?>" class="btn btn-sm btn-success" title="Generate PDF"><i class="fa fa-download"></i></a>
                                  <a href="<?= base_url('quotation/edit/' . $quotation->id) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-pencil"></i></a>
                                  <a href="<?= base_url('quotation/delete/' . $quotation->id) ?>" class="btn btn-sm btn-danger delete-quotation" data-id="<?= $quotation->id ?>" title="Delete"><i class="fa fa-trash"></i></a>

                                  <a href="<?= base_url('quotation/duplicate/' . $quotation->id) ?>" class="btn btn-sm btn-secondary" title="Duplicate"><i class="fa fa-copy"></i></a>
                                  <!--                                  <button class="btn btn-sm btn-info send-mail-btn"
                                                                            data-id="<?= $quotation->id ?>"
                                                                            data-client="<?= htmlspecialchars($quotation->client_name) ?>"
                                                                            data-email="<?= htmlspecialchars($quotation->client_email ?? '') ?>"
                                                                            title="Send Mail">
                                                                        📧 Send Mail
                                                                    </button>-->
                                  <button class="btn btn-sm btn-info send-mail-btn"
                                          data-id="<?= $quotation->id ?>"
                                          data-email="<?= $quotation->client_email ?>"
                                          data-client="<?= $quotation->client_name ?>">
                                      <i class="fa fa-envelope"></i>
                                  </button>
                              </td>
                          </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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

<!-- Scripts -->
<script>
  $(document).ready(function () {
      // 📨 Open Send Mail Modal
      $('.send-mail-btn').click(function () {
          const quotationId = $(this).data('id');
          const email = $(this).data('email') || '';
          $('#mailQuotationId').val(quotationId);
          $('#mailTo').val(email);
          $('#mailCC').val('');
          $('#sendMailModal').modal('show');
      });

      // 📨 Send Mail via AJAX
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

      // 📊 Initialize DataTable
      $('#quotationTable').DataTable({
          dom: 'Bfrtip',
          buttons: ['copy', 'excel', 'pdf', 'print'],
          responsive: true
      });
  });

  $('.delete-quotation').click(function (e) {
      e.preventDefault();
      var url = $(this).attr('href');
      Swal.fire({
          title: 'Are you sure?',
          text: "This quotation will be deleted permanently!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = url;
          }
      });
  });

</script>

