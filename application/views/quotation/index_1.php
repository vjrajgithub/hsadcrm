<div class="content-wrapper">
    <section class="content-header">
        <h1>Quotation List</h1>
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
                                  <a href="<?= base_url('quotation/view/' . $quotation->id) ?>" class="btn btn-sm btn-info" title="View"><i class="fa fa-eye"></i></a>
                                  <a href="<?= base_url('quotation/view_pdf/' . $quotation->id) ?>" target="_blank" class="btn btn-sm btn-primary" title="View PDF"><i class="fa fa-file-pdf-o"></i></a>
                                  <a href="<?= base_url('quotation/generate_pdf/' . $quotation->id) ?>" class="btn btn-sm btn-success" title="Generate PDF"><i class="fa fa-download"></i></a>
                                  <a href="<?= base_url('quotation/send_mail/' . $quotation->id) ?>" class="btn btn-sm btn-warning" title="Send Mail"><i class="fa fa-envelope"></i></a>
                                  <a href="<?= base_url('quotation/edit/' . $quotation->id) ?>" class="btn btn-sm btn-secondary" title="Edit"><i class="fa fa-pencil"></i></a>
                                  <button class="btn btn-sm btn-info send-mail-btn"
                                          data-id="<?= $quotation->id ?>"
                                          data-client="<?= $quotation->client_name ?>"
                                          data-email="<?= $quotation->client_email ?>">
                                      📧 Send Mail
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
<div class="modal fade" id="sendMailModal" tabindex="-1" role="dialog" aria-labelledby="sendMailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="sendMailForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="sendMailLabel">📧 Send Quotation Mail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="quotation_id" id="quotationId">

                    <div class="form-group">
                        <label for="to">To (comma-separated)</label>
                        <input type="text" class="form-control" name="to" id="mailTo" required>
                    </div>

                    <div class="form-group">
                        <label for="cc">CC (optional)</label>
                        <input type="text" class="form-control" name="cc" id="mailCc">
                    </div>

                    <div class="form-group">
                        <label for="message">Custom Message</label>
                        <textarea class="form-control" name="message" id="mailMessage" rows="5">Dear Sir/Madam, please find the attached quotation.</textarea>
                    </div>

                    <div class="form-group">
                        <label for="attachment">Attach a File (optional)</label>
                        <input type="file" class="form-control-file" name="attachment" id="attachment">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Mail</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
  $(document).ready(function () {
      let quotationId = null;

      $('.send-mail-btn').click(function () {
          quotationId = $(this).data('id');
          const clientEmail = $(this).data('email') || '';
          $('#quotationId').val(quotationId);
          $('#mailTo').val(clientEmail);
          $('#mailCc').val('');
          $('#mailMessage').val('Dear Sir/Madam, please find the attached quotation.');
          $('#attachment').val('');
          $('#sendMailModal').modal('show');
      });

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
                      title: 'Sending...',
                      text: 'Please wait while we send the email.',
                      icon: 'info',
                      showConfirmButton: false,
                      allowOutsideClick: false
                  });
              },
              success: function (response) {
                  Swal.close();
                  let res = JSON.parse(response);
                  if (res.status) {
                      Swal.fire('Success', res.message || 'Email sent successfully.', 'success');
                      $('#sendMailModal').modal('hide');
                  } else {
                      Swal.fire('Error', res.message || 'Failed to send email.', 'error');
                  }
              },
              error: function () {
                  Swal.close();
                  Swal.fire('Error', 'An unexpected error occurred.', 'error');
              }
          });
      });
  });
</script>


<script>
  $(document).ready(function () {
      $('#quotationTable').DataTable({
          dom: 'Bfrtip',
          buttons: ['copy', 'excel', 'pdf', 'print'],
          responsive: true
      });
  });
</script>
