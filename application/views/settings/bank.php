<h4>Bank Details</h4>

<form method="post">
    <div class="form-group">
        <label>Account Holder</label>
        <input name="bank_account_name" class="form-control" value="<?= $settings->bank_account_name ?>">
    </div>

    <div class="form-group mt-2">
        <label>Bank Name</label>
        <input name="bank_name" class="form-control" value="<?= $settings->bank_name ?>">
    </div>

    <div class="form-group mt-2">
        <label>IFSC Code</label>
        <input name="ifsc" class="form-control" value="<?= $settings->ifsc ?>">
    </div>

    <div class="form-group mt-2">
        <label>Account Number</label>
        <input name="account_number" class="form-control" value="<?= $settings->account_number ?>">
    </div>

    <button class="btn btn-primary mt-3">Save</button>
</form>
