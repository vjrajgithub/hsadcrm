<form id="bankForm">
    <input type="hidden" name="id" value="<?= @$bank->id ?>">
    <div class="modal-header">
        <h5 class="modal-title"><?= $bank ? 'Edit' : 'Add' ?> Bank</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body row">
        <div class="form-group col-md-6">
            <label>Company</label>
            <select name="company_id" class="form-control" required>
                <option value="">Select</option>
                <?php foreach ($companies as $c): ?>
                  <option value="<?= $c->id ?>" <?= @$bank->company_id == $c->id ? 'selected' : '' ?>><?= $c->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Bank Name</label>
            <input type="text" name="name" class="form-control" value="<?= @$bank->name ?>" required>
        </div>
        <div class="form-group col-md-6">
            <label>Branch Address</label>
            <input type="text" name="branch_address" class="form-control" value="<?= @$bank->branch_address ?>" required>
        </div>
        <div class="form-group col-md-6">
            <label>Account No.</label>
            <input type="text" name="ac_no" class="form-control" value="<?= @$bank->ac_no ?>" required>
        </div>
        <div class="form-group col-md-6">
            <label>IFSC Code</label>
            <input type="text" name="ifsc_code" class="form-control" value="<?= @$bank->ifsc_code ?>" required>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    </div>
</form>

<script>
  $('#bankForm').validate({
      submitHandler: function (form) {
          $.ajax({
              url: '<?= site_url('bank/save') ?>',
              type: 'POST',
              data: $(form).serialize(),
              dataType: 'json',
              success: function (res) {
                  if (res.status === 'success') {
                      Swal.fire('Saved!', 'Bank detail has been saved.', 'success').then(() => location.reload());
                  } else {
                      Swal.fire('Error!', res.message, 'error');
                  }
              }
          });
          return false;
      }
  });
</script>
