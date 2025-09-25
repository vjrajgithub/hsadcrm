<?php $this->load->view('layouts/main_header'); ?>
<?php $this->load->view('layouts/navbar'); ?>
<?php $this->load->view('layouts/sidebar'); ?>
<?php $this->load->view('layouts/content_wrapper_open'); ?>

<h4>Create New Invoice</h4>

<form action="<?= base_url('invoice/store') ?>" method="post" id="invoiceForm">
    <div class="row">
        <div class="col-md-4">
            <label>Invoice No</label>
            <input type="text" name="invoice[invoice_no]" class="form-control" required value="INV<?= date('YmdHis') ?>">
        </div>
        <div class="col-md-4">
            <label>Invoice Date</label>
            <input type="date" name="invoice[invoice_date]" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-4">
            <label>Buyer</label>
            <select name="invoice[buyer_id]" class="form-control" required>
                <option value="">Select Buyer</option>
                <?php foreach ($buyers as $b): ?>
                  <option value="<?= $b->id ?>"><?= $b->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <hr>
    <h5>Items</h5>
    <table class="table table-bordered" id="itemTable">
        <thead class="thead-light">
            <tr>
                <th>Product</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>GST %</th>
                <th>Total</th>
                <th><button type="button" class="btn btn-sm btn-success" onclick="addItem()"><i class="fas fa-plus"></i></button></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="row">
        <div class="col-md-3 ml-auto">
            <label>Subtotal</label>
            <input type="text" id="subtotal" name="invoice[subtotal]" class="form-control" readonly>
        </div>
        <div class="col-md-3">
            <label>Total GST</label>
            <input type="text" id="total_gst" name="invoice[total_gst]" class="form-control" readonly>
        </div>
        <div class="col-md-3">
            <label>Grand Total</label>
            <input type="text" id="grand_total" name="invoice[total]" class="form-control" readonly>
        </div>
    </div>

    <div class="mt-4 text-right">
        <button type="submit" class="btn btn-primary">Save Invoice</button>
        <a href="<?= base_url('invoice') ?>" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php $this->load->view('layouts/content_wrapper_close'); ?>
<?php $this->load->view('layouts/footer'); ?>
