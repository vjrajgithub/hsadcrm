<div class="content-wrapper">
    <section class="content-header d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clock"></i> Mode Management</h1>
        <button class="btn btn-success" onclick="openForm()">
            <i class="fas fa-plus"></i> Add Mode
        </button>
    </section>

    <section class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                <table id="modeTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Days</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modes as $i => $mode): ?>
                          <tr>
                              <td><?= $i + 1 ?></td>
                              <td><?= htmlspecialchars($mode->name) ?></td>
                              <td><?= $mode->days ?></td>
                              <td class="text-center">
                                  <button class="btn btn-sm btn-primary" onclick="openForm(<?= $mode->id ?>)">
                                      <i class="fas fa-edit"></i>
                                  </button>
                                  <button class="btn btn-sm btn-danger" onclick="deleteMode(<?= $mode->id ?>)">
                                      <i class="fas fa-trash-alt"></i>
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

<!-- Modal -->
<div class="modal fade" id="modeModal">
    <div class="modal-dialog">
        <div class="modal-content" id="formContainer"></div>
    </div>
</div>

<script>
  $(document).ready(function () {
      $('#modeTable').DataTable({
          responsive: true,
          autoWidth: false,
          dom: 'Bfrtip',
          buttons: [
              {extend: 'copy', className: 'btn btn-sm btn-secondary'},
              {extend: 'csv', className: 'btn btn-sm btn-info'},
              {extend: 'excel', className: 'btn btn-sm btn-success'},
              {extend: 'pdf', className: 'btn btn-sm btn-danger'},
              {extend: 'print', className: 'btn btn-sm btn-warning'}
          ]
      });
  });

  function openForm(id = null) {
      $.get('<?= site_url('mode/form') ?>/' + (id || ''), function (html) {
          $('#formContainer').html(html);
          $('#modeModal').modal('show');
      });
  }

  function deleteMode(id) {
      Swal.fire({
          title: 'Are you sure?',
          text: "This will permanently delete the mode.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              $.get('<?= site_url('mode/delete') ?>/' + id, function () {
                  Swal.fire('Deleted!', 'Mode has been deleted.', 'success').then(() => {
                      location.reload();
                  });
              });
          }
      });
  }
</script>
