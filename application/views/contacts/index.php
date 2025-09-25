
<section class="content-header d-flex justify-content-between align-items-center">
    <h1>Client Contact Persons</h1>
    <button class="btn btn-sm btn-success float-right" onclick="openForm()">+ Add New</button>
</section>

<section class="content">
    <div class="card">
        <div class="card-body">
            <table id="contactsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Contact Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $i => $c): ?>
                      <tr>
                          <td><?= $i + 1 ?></td>
                          <td><?= $c->client_name ?></td>
                          <td><?= $c->name ?></td>
                          <td><?= $c->mobile ?></td>
                          <td><?= $c->email ?></td>
                          <td>
                              <button class="btn btn-sm btn-info" onclick="openForm(<?= $c->id ?>)">  <i class="fas fa-edit"></i></button>
                              <button class="btn btn-sm btn-danger" onclick="deleteContact(<?= $c->id ?>)"> <i class="fas fa-trash"></i></button>
                          </td>
                      </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>


<!-- Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="formContainer">
            <!-- AJAX Form will load here -->
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
  $(document).ready(function () {
      $('#contactsTable').DataTable({
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
      $.get(`<?= site_url('contacts/form') ?>/${id || ''}`, function (html) {
          $('#formContainer').html(html);
          $('#contactModal').modal('show');
      });
  }

  function deleteContact(id) {
      Swal.fire({
          title: 'Are you sure?',
          text: "This contact will be deleted permanently.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed) {
              $.get(`<?= site_url('contacts/delete') ?>/${id}`, function () {
                  Swal.fire('Deleted!', 'Contact deleted successfully.', 'success').then(() => location.reload());
              });
          }
      });
  }
</script>
