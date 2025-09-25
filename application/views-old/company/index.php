<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Company Profiles</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?= site_url('company/form') ?>" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus"></i> Add Company
                </a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="companyTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th class="d-none d-md-table-cell">Email</th>
                                <th>Mobile</th>
                                <th class="d-none d-lg-table-cell">Logo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $c): ?>
                              <tr>
                                  <td>
                                      <strong><?= $c->name ?></strong>
                                      <div class="d-md-none small text-muted"><?= $c->email ?></div>
                                  </td>
                                  <td class="d-none d-md-table-cell"><?= $c->email ?></td>
                                  <td><?= $c->mobile ?></td>
                                  <td class="d-none d-lg-table-cell">
                                      <?php if ($c->logo): ?>
                                        <img src="<?= base_url('assets/uploads/logos/' . $c->logo) ?>" width="50" class="img-thumbnail">
                                      <?php endif; ?>
                                  </td>
                                  <td>
                                      <div class="btn-group" role="group">
                                          <a href="<?= site_url('company/form/' . $c->id) ?>" class="btn btn-sm btn-warning" title="Edit">
                                              <i class="fas fa-edit"></i>
                                              <span class="d-none d-sm-inline">Edit</span>
                                          </a>
                                          <a href="<?= site_url('company/delete/' . $c->id) ?>" class="btn btn-sm btn-danger" 
                                             onclick="return confirm('Delete this company?')" title="Delete">
                                              <i class="fas fa-trash"></i>
                                              <span class="d-none d-sm-inline">Delete</span>
                                          </a>
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

<script>
$(document).ready(function() {
    $('#companyTable').DataTable({
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
            }
        ],
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ]
    });
});
</script>
