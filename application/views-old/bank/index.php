<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-university"></i> Bank Details</h3>
                <button class="btn btn-sm btn-success" onclick="openForm()">
                    <i class="fas fa-plus-circle"></i> Add Bank
                </button>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover table-striped" id="bankTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Bank Name</th>
                            <th>Branch</th>
                            <th>AC No</th>
                            <th>IFSC</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($banks as $row):
                          ?>
                          <tr>
                              <td><?= $i++ ?></td>
                              <td><?= $row->company_name ?></td>
                              <td><?= $row->name ?></td>
                              <td><?= $row->branch_address ?></td>
                              <td><?= $row->ac_no ?></td>
                              <td><?= $row->ifsc_code ?></td>
                              <td>
                                  <button class="btn btn-info btn-sm" onclick="editBank(<?= $row->id ?>)">
                                      <i class="fas fa-edit"></i>
                                  </button>
                                  <a href="<?= site_url('bank/delete/' . $row->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this bank?')">
                                      <i class="fas fa-trash"></i>
                                  </a>
                              </td>
                          </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal for Form -->
<div class="modal fade" id="bankFormModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="formContent"></div>
    </div>
</div>

<script>
  function openForm() {
      $.get('<?= site_url('bank/form') ?>', function (html) {
          $('#formContent').html(html);
          $('#bankFormModal').modal('show');
      });
  }

  function editBank(id) {
      $.get('<?= site_url('bank/form/') ?>' + id, function (html) {
          $('#formContent').html(html);
          $('#bankFormModal').modal('show');
      });
  }

  $(document).ready(function () {
      $('#bankTable').DataTable({
          responsive: true,
          autoWidth: false,
          dom: 'Bfrtip',
          buttons: ['excel', 'pdf', 'print']
      });

  });
</script>
