<h4>Invoice #<?= $invoice->invoice_no ?></h4>

<table class="table table-sm">
    <tr><th>Buyer</th><td><?= $buyer->name ?></td></tr>
    <tr><th>Address</th><td><?= $buyer->address ?></td></tr>
    <tr><th>GSTIN</th><td><?= $buyer->gstin ?></td></tr>
    <tr><th>Date</th><td><?= $invoice->invoice_date ?></td></tr>
</table>

<h5>Products</h5>
<table class="table table-bordered table-sm">
    <thead>
        <tr><th>Product</th><th>Qty</th><th>Price</th><th>GST%</th><th>Total</th></tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
              <td><?= $item->product_name ?></td>
              <td><?= $item->qty ?></td>
              <td><?= $item->price ?></td>
              <td><?= $item->gst ?></td>
              <td><?= $item->qty * $item->price ?></td>
          </tr>
        <?php endforeach ?>
    </tbody>
</table>

<table class="table table-bordered w-50">
    <tr><th>Subtotal</th><td>₹<?= number_format($invoice->total, 2) ?></td></tr>
    <tr><th>GST Total</th><td>₹<?= number_format($invoice->gst_total, 2) ?></td></tr>
    <tr><th>Grand Total</th><td>₹<?= number_format($invoice->grand_total, 2) ?></td></tr>
</table>

<a href="<?= base_url('invoice/pdf/' . $invoice->id) ?>" class="btn btn-sm btn-secondary">Download PDF</a>
<a href="<?= base_url('invoice/email/' . $invoice->id) ?>" class="btn btn-sm btn-dark">Email PDF</a>
