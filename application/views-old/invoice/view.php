<!DOCTYPE html>
<html>
    <head>
        <title>Invoice #<?= $invoice->invoice_no ?></title>
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
        <style>
            body {
                font-size: 14px;
                padding: 30px;
            }
            .table th, .table td {
                padding: 6px;
            }
            .invoice-header {
                border-bottom: 2px solid #000;
                margin-bottom: 20px;
            }
            .company-logo {
                max-height: 60px;
            }
        </style>
    </head>
    <body>

        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <h4><?= $settings->company_name ?></h4>
                <p><?= $settings->company_address ?><br>GSTIN: <?= $settings->gst_number ?></p>
            </div>
            <div>
                <?php if ($settings->logo): ?>
                  <img src="<?= base_url('uploads/' . $settings->logo) ?>" class="company-logo">
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-4">
            <h5>Invoice To:</h5>
            <p>
                <?= $buyer->name ?><br>
                <?= $buyer->address ?><br>
                GSTIN: <?= $buyer->gst_number ?><br>
                State: <?= $buyer->state ?>
            </p>
        </div>

        <div class="mb-4">
            <strong>Invoice No:</strong> <?= $invoice->invoice_no ?><br>
            <strong>Invoice Date:</strong> <?= date('d-M-Y', strtotime($invoice->invoice_date)) ?><br>
            <strong>Place of Supply:</strong> <?= $settings->place_of_supply ?>
        </div>

        <a href="<?= base_url('invoice/pdf/' . $invoice->id) ?>" class="btn btn-info">Download PDF</a>
        <a href="<?= base_url('invoice/send/' . $invoice->id) ?>" class="btn btn-success">Send via Email</a>


        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Rate</th>
                    <th>Qty</th>
                    <th>GST %</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $sub = 0;
                $gst = 0;
                foreach ($items as $item):
                  $amount = $item->rate * $item->qty;
                  $gst_amt = $amount * ($item->gst / 100);
                  $line_total = $amount + $gst_amt;
                  $sub += $amount;
                  $gst += $gst_amt;
                  ?>
                  <tr>
                      <td><?= $i++ ?></td>
                      <td><?= $item->product_name ?></td>
                      <td>₹<?= number_format($item->rate, 2) ?></td>
                      <td><?= $item->qty ?></td>
                      <td><?= $item->gst ?>%</td>
                      <td>₹<?= number_format($line_total, 2) ?></td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row mt-4">
            <div class="col-md-6">
                <h6>Terms & Conditions:</h6>
                <p><?= nl2br($settings->terms_conditions) ?></p>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th>Subtotal</th><td>₹<?= number_format($sub, 2) ?></td></tr>
                    <tr><th>Total GST</th><td>₹<?= number_format($gst, 2) ?></td></tr>
                    <tr><th>Grand Total</th><td><strong>₹<?= number_format($invoice->total, 2) ?></strong></td></tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <h6>Bank Details:</h6>
            <p>
                <?= $settings->bank_details ?>
            </p>
        </div>

        <div class="mt-4 text-center d-print-none">
            <a href="<?= base_url('invoice') ?>" class="btn btn-secondary">Back</a>
            <a href="#" onclick="window.print()" class="btn btn-primary">Print</a>
        </div>

    </body>
</html>
