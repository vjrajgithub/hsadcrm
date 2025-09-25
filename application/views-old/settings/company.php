<h4>Company Settings</h4>

<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Company Name</label>
        <input type="text" name="company_name" class="form-control" value="<?= $settings->company_name ?>" required>
    </div>

    <div class="form-group mt-2">
        <label>Address</label>
        <textarea name="address" class="form-control" required><?= $settings->address ?></textarea>
    </div>

    <div class="form-group mt-2">
        <label>GSTIN</label>
        <input name="gstin" class="form-control" value="<?= $settings->gstin ?>">
    </div>

    <div class="form-group mt-2">
        <label>Logo (optional)</label>
        <?php if ($settings->logo): ?>
          <div><img src="<?= base_url('uploads/' . $settings->logo) ?>" height="60" class="my-2"></div>
        <?php endif ?>
        <input type="file" name="logo" class="form-control">
    </div>

    <button class="btn btn-primary mt-3">Save</button>
</form>
