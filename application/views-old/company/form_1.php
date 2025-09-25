<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= $company ? 'Edit' : 'Add' ?> Company Profile</h3>
    </div>

    <form method="post" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">

                <!-- Basic Details -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" name="name" class="form-control" value="<?= set_value('name', @$company->name) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" name="mobile" class="form-control" value="<?= set_value('mobile', @$company->mobile) ?>">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= set_value('email', @$company->email) ?>">
                    </div>

                    <div class="form-group">
                        <label>Website</label>
                        <input type="text" name="website" class="form-control" value="<?= set_value('website', @$company->website) ?>">
                    </div>

                    <div class="form-group">
                        <label>Job No</label>
                        <input type="text" name="job_no" class="form-control" value="<?= set_value('job_no', @$company->job_no) ?>">
                    </div>
                </div>

                <!-- Address Details -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="3"><?= set_value('address', @$company->address) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Pin Code</label>
                        <input type="text" name="pin_code" class="form-control" value="<?= set_value('pin_code', @$company->pin_code) ?>">
                    </div>

                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control" value="<?= set_value('country', @$company->country) ?>">
                    </div>

                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" value="<?= set_value('state', @$company->state) ?>">
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="<?= set_value('city', @$company->city) ?>">
                    </div>
                </div>

                <!-- Legal Details -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>GST No</label>
                        <input type="text" name="gst_no" class="form-control" value="<?= set_value('gst_no', @$company->gst_no) ?>">
                    </div>

                    <div class="form-group">
                        <label>CIN No</label>
                        <input type="text" name="cin_no" class="form-control" value="<?= set_value('cin_no', @$company->cin_no) ?>">
                    </div>

                    <div class="form-group">
                        <label>PAN Card</label>
                        <input type="text" name="pan_card" class="form-control" value="<?= set_value('pan_card', @$company->pan_card) ?>">
                    </div>
                </div>

                <!-- Logo and Notes -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company Logo</label>
                        <div class="custom-file">
                            <input type="file" name="logo" class="custom-file-input" id="logoInput">
                            <label class="custom-file-label" for="logoInput">Choose file</label>
                        </div>
                        <?php if (!empty($company->logo)): ?>
                          <div class="mt-2">
                              <img src="<?= base_url('assets/uploads/logos/' . $company->logo) ?>" alt="Logo" width="100" class="img-thumbnail">
                          </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control" rows="3"><?= set_value('note', @$company->note) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Terms & Conditions</label>
                        <textarea name="terms_conditions" class="form-control" rows="3"><?= set_value('terms_conditions', @$company->terms_conditions) ?></textarea>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="<?= site_url('company') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
  // Update label when file selected
  document.querySelector('.custom-file-input').addEventListener('change', function (e) {
      var fileName = document.getElementById("logoInput").files[0].name;
      var nextSibling = e.target.nextElementSibling
      nextSibling.innerText = fileName
  });
</script>
