
<section class="content-header  d-flex justify-content-between align-items-center">
    <h1>Quotation List</h1>
    <a href="<?= base_url('quotation/create') ?>" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Add New Quotation</a>
</section>

<section class="content">
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="quotationTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Estimate No</th>
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
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php $this->load->view('quotation/email_modal'); ?>

<!-- Scripts -->
<script>
  $(document).ready(function () {
      // 📨 Open Send Mail Modal - Updated to use new enhanced modal
      $(document).on('click', '.send-mail-btn', function () {
          const quotationId = $(this).data('id');
          const email = $(this).data('email') || '';
          openEmailModal(quotationId, email);
      });

      // 📊 Initialize DataTable
      var table = $('#quotationTable').DataTable({
          ajax: '<?php echo site_url('quotation/list'); ?>',
          columns: [
              {data: null},
              {data: 'estimate_no'},
              {data: 'client_name'},
              {data: 'contact_person'},
              {data: 'department'},
              {data: 'state'},
              {data: 'created_at', render: function(data) { 
                  return new Date(data).toLocaleDateString('en-GB');
              }},
              {
                  data: 'attachment',
                  render: function (data, type, row) {
                      if (data) {
                          return '<a href="<?php echo base_url('assets/uploads/quotations/'); ?>' + data + '" target="_blank"><i class="fa fa-paperclip"></i></a>';
                      }
                      return '';
                  }
              },
              {
                  data: null,
                  render: function (data, type, row) {
                      let actions = '<div class="btn-group">';
                      actions += '<a href="<?php echo base_url('quotation/view/'); ?>' + row.id + '" class="btn btn-sm btn-info" title="View"><i class="fa fa-eye"></i></a>';
                      actions += '<a href="<?php echo base_url('quotation/view_pdf/'); ?>' + row.id + '" target="_blank" class="btn btn-sm btn-primary" title="View PDF"><i class="fa fa-file-pdf-o"></i></a>';
                      actions += '<a href="<?php echo base_url('quotation/generate_pdf/'); ?>' + row.id + '" target="_blank" rel="noopener" class="btn btn-sm btn-success" title="Generate PDF (New Tab)"><i class="fa fa-download"></i></a>';
                      actions += '<a href="<?php echo base_url('quotation/edit/'); ?>' + row.id + '" class="btn btn-sm btn-warning" title="Edit"><i class="fa fa-pencil"></i></a>';
                      actions += '<button class="btn btn-sm btn-info send-mail-btn" data-id="' + row.id + '" data-email="' + (row.client_email || '') + '" data-client="' + row.client_name + '" title="Send Email"><i class="fa fa-envelope"></i></button>';
                      actions += '<a href="<?php echo base_url('quotation/duplicate/'); ?>' + row.id + '" class="btn btn-sm btn-secondary" title="Duplicate"><i class="fa fa-copy"></i></a>';
                      actions += '<button class="btn btn-sm btn-danger delete-quotation" data-id="' + row.id + '" title="Delete"><i class="fa fa-trash"></i></button>';
                      actions += '</div>';
                      return actions;
                  }
              }
          ],
          responsive: true,
          autoWidth: false,
          dom: 'Blfrtip',
          buttons: [
              {extend: 'copy', className: 'btn btn-sm btn-secondary'},
              {extend: 'csv', className: 'btn btn-sm btn-info'},
              {extend: 'excel', className: 'btn btn-sm btn-success'},
              {extend: 'pdf', className: 'btn btn-sm btn-danger'},
              {extend: 'print', className: 'btn btn-sm btn-warning'}
          ],
          order: [],
          columnDefs: [{targets: 0, orderable: false}],
          createdRow: function (row, data, index) {
              $('td:eq(0)', row).html(index + 1);
          },
          pageLength: 10,
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
      });

      // Event handlers for dynamically loaded buttons - Updated to use new enhanced modal
      $('#quotationTable').on('click', '.send-mail-btn', function () {
          const quotationId = $(this).data('id');
          const email = $(this).data('email') || '';
          openEmailModal(quotationId, email);
      });

      $('#quotationTable').on('click', '.delete-quotation', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          Swal.fire({
              title: 'Are you sure?',
              text: "This quotation will be deleted permanently!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, delete it!',
              cancelButtonText: 'Cancel'
          }).then((result) => {
              if (result.isConfirmed) {
                  $.get('<?php echo site_url('quotation/delete'); ?>/' + id, function(response) {
                      var result = JSON.parse(response);
                      if (result.status === 'success') {
                          Swal.fire('Deleted!', result.message, 'success').then(() => {
                              table.ajax.reload();
                          });
                      } else {
                          Swal.fire('Error!', result.message, 'error');
                      }
                  }).fail(function() {
                      Swal.fire('Error!', 'Failed to delete quotation.', 'error');
                  });
              }
          });
      });
  });

</script>

