<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 5px;
        text-align: left;
    }
</style>

<h2>Tax Invoice</h2>
<p><strong>Invoice No:</strong> <?= $invoice->invoice_no ?></p>
<p><strong>Date:</strong> <?= $invoice->invoice_date ?></p>
<p><strong>Buyer:</strong> <?= $buyer->name ?> | GSTIN: <?= $buyer->gstin ?></p>
<p><strong>Address:</strong> <?= $buyer->address ?></p>

<table>
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
              <td><?= number_format($item->qty * $item->price, 2) ?></td>
          </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Subtotal:</strong> ₹<?= number_format($invoice->total, 2) ?></p>
<p><strong>GST:</strong> ₹<?= number_format($invoice->gst_total, 2) ?></p>
<p><strong>Grand Total:</strong> ₹<?= number_format($invoice->grand_total, 2) ?></p>

<script>
  window.print();
</script>
