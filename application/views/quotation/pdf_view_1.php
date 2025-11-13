<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Quotation PDF</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
                color: #333;
            }
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
            .company-logo img {
                width: 120px;
            }
            .title-box {
                text-align: right;
            }
            h1 {
                margin: 0;
                font-size: 20px;
                color: #1e88e5;
            }

            .info-table td {
                padding: 5px;
                vertical-align: top;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                border: 1px solid #ccc;
                padding: 6px;
                text-align: left;
            }
            th {
                background-color: #f5f5f5;
            }
            .no-border td {
                border: none;
            }
            .text-right {
                text-align: right;
            }

            .signature-block {
                margin-top: 40px;
                display: flex;
                justify-content: space-between;
            }
            .signature {
                width: 45%;
            }
            .signature-line {
                border-top: 1px solid #000;
                margin-top: 60px;
            }

            .footer-note {
                margin-top: 40px;
                font-size: 10px;
                color: #888;
                border-top: 1px dashed #ccc;
                padding-top: 10px;
                text-align: center;
            }

            @page {
                margin: 30px 30px 80px 30px;
            }

            .page-footer {
                position: fixed;
                bottom: 10px;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 10px;
                color: #999;
            }
        </style>
    </head>
    <body>

        <!-- Header -->
        <div class="header">
            <div class="company-logo">
                <img src="<?= base_url('assets/images/company-logo.png') ?>" alt="Company Logo">
            </div>
            <div class="title-box">
                <h1>Quotation</h1>
                <p><strong>Date:</strong> <?= isset($quotation->created_at) ? date('d-m-Y', strtotime($quotation->created_at)) : date('d-m-Y') ?></p>
                <p><strong>Quotation No:</strong> QT-<?= str_pad($quotation->id, 5, '0', STR_PAD_LEFT) ?></p>
            </div>
        </div>

        <!-- Client and Quotation Info -->
        <table class="info-table no-border">
            <tr>
                <td><strong>Client:</strong> <?= $quotation->client_name ?? '' ?></td>
                <td><strong>Contact Person:</strong> <?= $quotation->contact_person ?></td>
            </tr>
            <tr>
                <td><strong>Department:</strong> <?= $quotation->department ?></td>
                <td><strong>Mode:</strong> <?= $quotation->mode_name ?? '' ?></td>
            </tr>
            <tr>
                <td><strong>Bank:</strong> <?= $quotation->bank_name ?? '' ?></td>
                <td><strong>Place of Supply:</strong> <?= $quotation->state ?></td>
            </tr>
        </table>

        <!-- Items -->
        <h3 style="margin-top: 20px;">Quotation Items</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Discount (%)</th>
                    <th>GST (%)</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                foreach ($quotation->items as $index => $item):
                  $qty = isset($item->qty) ? (float)$item->qty : 0;
                  $rate = isset($item->rate) ? (float)$item->rate : 0;
                  $discount = isset($item->discount) ? (float)$item->discount : 0;
                  $computed_amount = ($qty * $rate);
                  if ($discount > 0) { $computed_amount -= ($computed_amount * $discount / 100); }
                  $line_amount = isset($item->amount) && $item->amount !== null && $item->amount !== '' ? (float)$item->amount : $computed_amount;
                  $grand_total += $line_amount;
                  ?>
                  <tr>
                      <td><?= $index + 1 ?></td>
                      <td><?= $item->category_name ?></td>
                      <td><?php
                        $parts = [];
                        $category_name = isset($item->category_name) ? trim($item->category_name) : '';
                        $product_name = isset($item->product_name) ? trim($item->product_name) : '';
                        if ($category_name !== '') { $parts[] = $category_name; }
                        if (!empty($item->description)) { $parts[] = trim($item->description); }
                        if ($product_name !== '') { $parts[] = $product_name; }
                        $combined = implode(', ', $parts);
                        echo htmlspecialchars($combined !== '' ? $combined : 'Product / Service');
                      ?></td>
                      <td><?= $item->qty ?></td>
                      <td><?= number_format($rate, 2) ?></td>
                      <td><?= number_format($discount, 2) ?></td>
                      <td><?= number_format($item->gst ?? 0, 2) ?></td>
                      <td><?= number_format($line_amount, 2) ?></td>
                  </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="7" class="text-right"><strong>Grand Total</strong></td>
                    <td><strong><?= number_format($grand_total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Terms and Notes -->
        <div style="margin-top: 20px;">
            <p><strong>Terms & Conditions:</strong></p>
            <p><?= nl2br($quotation->terms) ?></p>

            <p><strong>Note:</strong></p>
            <p><?= nl2br($quotation->notes) ?></p>
        </div>

        <!-- Signature Section -->
        <div class="signature-block">
            <div class="signature">
                <p><strong>Authorized Signatory</strong></p>
                <div class="signature-line"></div>
            </div>
            <div class="signature">
                <p><strong>Client Acknowledgement</strong></p>
                <div class="signature-line"></div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <?= $quotation->company_name ?? '' ?> | <?= $quotation->company_address ?? '' ?> | Contact: <?= $quotation->company_phone ?? '' ?>
            <br>
            <em>This is a system-generated document. Page {PAGE_NUM} of {PAGE_COUNT}</em>
        </div>

        <!-- Page footer (Dompdf) -->
        <div class="page-footer">
            Page {PAGE_NUM} of {PAGE_COUNT}
        </div>

    </body>
</html>
