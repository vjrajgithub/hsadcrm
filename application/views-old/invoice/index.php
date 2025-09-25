<?php $this->load->view('layouts/main_header'); ?>
<?php $this->load->view('layouts/navbar'); ?>
<?php $this->load->view('layouts/sidebar'); ?>
<?php $this->load->view('layouts/content_wrapper_open'); ?>

<div class="d-flex justify-content-between mb-3">
    <h4>Invoices</h4>
    <a href="<?= base_url('invoice/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> New Invoice</a>
</div>

<form method="get" class="row mb-4">
    <div class="col-md-3">
        <label>From</label>
        <input type="date" name="from" value="<?= set_value('from', $this->input->get('from')) ?>" class="form-control">
    </div>
    <div class="col-md-3">
        <label>To</label>
        <input type="date" name="to" value="<?= set_value('to', $this->input->get('to')) ?>" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Buyer</label>
        <select name="buyer_id" class="form-control">
            <option value="">All</option>
            <?php foreach ($buyers as $b): ?>
              <option value="<?= $b->id ?>" <?= $this->input->get('buyer_id') == $b->id ? 'selected' : '' ?>>
                  <?= $b->name ?>
              </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-success mr-2">Filter</button>
        <a href="<?= base_url('export/invoices') ?>" class="btn btn-info">Export Excel</a>
    </div>
</form>

<table class="table table-bordered table-sm">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Invoice No</th>
            <th>Buyer</th>
            <th>Total</th>
            <th>GST</th>
            <th>Grand Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($invoices)): ?>
          <?php foreach ($invoices as $i): ?>
            <tr>
                <td><?= $i->id ?></td>
                <td><?= date('d-M-Y', strtotime($i->invoice_date)) ?></td>
                <td><?= $i->invoice_no ?></td>
                <td><?= $i->buyer_name ?></td>
                <td>₹<?= number_format($i->subtotal, 2) ?></td>
                <td>₹<?= number_format($i->total_gst, 2) ?></td>
                <td>₹<?= number_format($i->total, 2) ?></td>
                <td>
                    <a href="<?= base_url('invoice/view/' . $i->id) ?>" class="btn btn-sm btn-info">View</a>
                    <a href="<?= base_url('invoice/edit/' . $i->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= base_url('invoice/delete/' . $i->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this invoice?')">Delete</a>
                </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8" class="text-center text-muted">No invoices found</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->load->view('layouts/content_wrapper_close'); ?>
<?php $this->load->view('layouts/footer'); ?>
