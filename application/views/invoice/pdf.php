<!DOCTYPE html>
<html>
    <head>
        <style>
            body {
                font-family: Arial;
                font-size: 12px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
            }
            th, td {
                border: 1px solid #000;
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <h2>Invoice #<?= $invoice->invoice_no ?></h2>
        <p>Date: <?= $invoice->invoice_date ?></p>

        <p><strong>Buyer:</strong> <?= $buyer->name ?><br>
            <strong>Address:</strong> <?= $buyer->address ?><br>
            <strong>GSTIN:</strong> <?= $buyer->gstin ?></p>

        <table>
            <thead>
                <tr>
                    <th>Product</th><th>Qty</th><th>Price</th><th>GST%</th><th>Total</th>
                </tr>
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
                <?php endforeach ?>
            </tbody>
        </table>

        <p><strong>Total:</strong> ₹<?= number_format($invoice->total, 2) ?><br>
            <strong>GST:</strong> ₹<?= number_format($invoice->gst_total, 2) ?><br>
            <strong>Grand Total:</strong> ₹<?= number_format($invoice->grand_total, 2) ?></p>

        <p><strong>Amount in words:</strong>
            <?= ucwords(convert_number_to_words($invoice->grand_total)) ?> Only
        </p>
    </body>
</html>
