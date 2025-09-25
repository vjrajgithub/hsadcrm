<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Client Details</h1>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-primary float-sm-right" onclick="openForm()">
                    <i class="fas fa-plus-circle"></i> Add Client
                </button>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">All Clients</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="clientTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th class="d-none d-md-table-cell">Email</th>
                                <th class="d-none d-lg-table-cell">GST No</th>
                                <th class="d-none d-lg-table-cell">PAN</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($clients as $c):
                              ?>
                              <tr>
                                  <td><?= $i++ ?></td>
                                  <td>
                                      <strong><?= $c->name ?></strong>
                                      <div class="d-md-none small text-muted">
                                          <?= $c->email ?><br>
                                          <span class="d-lg-none">GST: <?= $c->gst_no ?></span>
                                      </div>
                                  </td>
                                  <td><?= $c->mobile ?></td>
                                  <td class="d-none d-md-table-cell"><?= $c->email ?></td>
                                  <td class="d-none d-lg-table-cell"><?= $c->gst_no ?></td>
                                  <td class="d-none d-lg-table-cell"><?= $c->pan_card ?></td>
                                  <td>
                                      <div class="btn-group" role="group">
                                          <button class="btn btn-info btn-sm" onclick="openForm(<?= $c->id ?>)" title="Edit">
                                              <i class="fas fa-edit"></i>
                                              <span class="d-none d-sm-inline">Edit</span>
                                          </button>
                                          <button class="btn btn-danger btn-sm" onclick="deleteClient(<?= $c->id ?>)" title="Delete">
                                              <i class="fas fa-trash"></i>
                                              <span class="d-none d-sm-inline">Delete</span>
                                          </button>
                                      </div>
                                  </td>
                              </tr>
            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="clientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="formContent"></div>
    </div>
</div>

<script>
  function openForm(id = '') {
      $.get('<?= site_url('clients/form/') ?>' + id, function (html) {
          $('#formContent').html(html);
          $('#clientModal').modal('show');
      });
  }

  function deleteClient(id) {
      Swal.fire({
          title: 'Delete this client?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed) {
              $.get('<?= site_url('clients/delete/') ?>' + id, function (res) {
                  Swal.fire('Deleted!', 'Client has been removed.', 'success').then(() => location.reload());
              });
          }
      });
  }

  $(document).ready(function () {
      if (!$.fn.DataTable.isDataTable('#clientTable')) {
          $('#clientTable').DataTable({
              responsive: true,
              dom: 'Bfrtip',
              buttons: [
                  {
                      extend: 'copyHtml5',
                      className: 'btn btn-sm btn-outline-secondary'
                  },
                  {
                      extend: 'excelHtml5',
                      className: 'btn btn-sm btn-outline-success'
                  },
                  {
                      extend: 'csvHtml5',
                      className: 'btn btn-sm btn-outline-info'
                  },
                  {
                      extend: 'pdfHtml5',
                      className: 'btn btn-sm btn-outline-danger'
                  },
                  {
                      extend: 'print',
                      className: 'btn btn-sm btn-outline-primary'
                  }
              ],
              columnDefs: [
                  { responsivePriority: 1, targets: 0 },
                  { responsivePriority: 2, targets: 1 },
                  { responsivePriority: 3, targets: -1 }
              ]
          });
      }
  });
</script>
