<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Estimate #<?= 'EST-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) ?></title>
        <style>
            .wrapper {
                width:1200px;
                margin: 0 auto;
                font-size: 12px;
            }
            table {
                border-collapse: collapse;
            }
            th, td {
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <!-- HEADER -->
            <table border="0" cellpadding="5" cellspacing="0" width="1000">
                <tr>
                    <td width="200">
                        <?php if (!empty($quotation->company_logo)) : ?>
                          <img src="<?= base_url('assets/uploads/logos/' . $quotation->company_logo) ?>" style="width:130px;">
                        <?php else : ?>
                          <img src="<?= base_url('assets/default-logo.png') ?>" style="width:130px;">
                        <?php endif; ?>
                    </td>
                    <td align="left" valign="middle">
                        Corporate Office: <?= nl2br($quotation->company_address) ?><br>
                        Tel: <?= $quotation->company_phone ?? '+91-120-4624900' ?>,
                        Email: <?= $quotation->company_email ?? 'info@hsadindia.com' ?>,
                        Website: <?= $quotation->company_website ?? 'www.hsad.co.in' ?><br>
                        CIN: <?= $quotation->company_cin ?? 'U74300DL2010FTC197646' ?>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2" style="font-size:30px">ESTIMATE</td>
                </tr>
            </table>

            <!-- BASIC INFO TABLE -->
            <table border="1" cellpadding="5" cellspacing="0" width="1000">
                <tr>
                    <td rowspan="4" width="335" valign="top">
                        <strong><?= $quotation->company_name ?></strong><br>
                        <?= nl2br($quotation->company_address) ?><br>
                        State: <?= $quotation->company_state ?>, State Code: <?= $quotation->company_state_code ?? '09' ?><br>
                        GSTIN/UIN: <?= $quotation->company_gstin ?><br>
                        CIN: <?= $quotation->company_cin ?><br>
                        PAN NO. <?= $quotation->company_pan ?><br><br>
                    </td>
                    <td width="332">Estimate No.<br><strong><?= 'EST-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) ?></strong></td>
                    <td>Date<br><strong><?= date('d-m-y', strtotime($quotation->created_at)) ?></strong></td>
                </tr>
                <tr>
                    <td>Job No.<br><strong><?= $quotation->job_no ?? '-' ?></strong></td>
                    <td>Mode/Terms of Payment<br><strong><?= $quotation->mode_name ?? 'NA' ?></strong></td>
                </tr>
                <tr>
                    <td>Category<br><strong><?= $quotation->department ?></strong></td>
                    <td>Other<br><strong><?= $quotation->other_text ?? '-' ?></strong></td>
                </tr>
                <tr>
                    <td>Contact Person<br><strong><?= $quotation->contact_person ?></strong></td>
                    <td>Place of Supply<br><strong><?= $quotation->state ?></strong></td>
                </tr>
                <tr>
                    <td rowspan="4" valign="top">
                        <strong><u>Details of Buyer</u></strong><br>
                        <strong><?= $quotation->client_name ?></strong><br>
                        <?= nl2br($quotation->client_address) ?><br>
                        State & St Code <?= $quotation->client_state ?> <?= $quotation->client_state_code ?? '09' ?><br>
                        Country India<br>
                        GSTIN/Unique ID: <?= $quotation->client_gstin ?><br>
                        PAN No. <?= $quotation->client_pan ?>
                    </td>
                    <td rowspan="4" valign="top">
                        <strong><?= $quotation->client_name ?></strong><br>
                        <?= nl2br($quotation->client_address) ?><br>
                        State & St Code <?= $quotation->client_state ?> <?= $quotation->client_state_code ?? '09' ?><br>
                        Country India<br>
                        GSTIN/Unique ID: <?= $quotation->client_gstin ?><br>
                        PAN No. <?= $quotation->client_pan ?>
                    </td>
                    <td>Advertising Service Category</td>
                </tr>
                <tr><td>Other professional, technical and business services</td></tr>
                <tr><td>Mode of transportation<br><strong>NA</strong></td></tr>
                <tr><td>Reverse Charge<br><strong>NA</strong></td></tr>

                <!-- ITEMS TABLE -->
                <tr>
                    <td colspan="6" valign="top" style="padding: 0px; border: 0px;">
                        <table border="1" cellpadding="2" cellspacing="0" width="1000">
                            <thead>
                                <tr>
                                    <th width="30">Sr.<br>No.</th>
                                    <th width="520">Description of Goods & Services</th>
                                    <th width="150">HSN/SAC</th>
                                    <th width="100">Qty</th>
                                    <th width="50">Rate</th>
                                    <th width="150">Amount INR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $sub_total = 0;
                                ?>
                                <?php foreach ($items as $item): ?>
                                  <tr>
                                      <td align="center"><?= $i++ ?></td>
                                      <td valign="top"><?php
                                        $parts = [];
                                        $category_name = isset($item->category_name) ? trim($item->category_name) : '';
                                        $product_name = isset($item->product_name) ? trim($item->product_name) : '';
                                        if ($category_name !== '') { $parts[] = $category_name; }
                                        if (!empty($item->description)) { $parts[] = trim($item->description); }
                                        if ($product_name !== '') { $parts[] = $product_name; }
                                        $combined = implode(', ', $parts);
                                        echo htmlspecialchars($combined !== '' ? $combined : 'Product / Service');
                                      ?></td>
                                      <td align="center"><?= $quotation->hsn_sac ?></td>
                                      <td align="center"><?= $item->qty ?></td>
                                      <td align="right"><?= number_format($item->rate, 2) ?></td>
                                      <td align="right"><?= number_format($item->amount, 2) ?></td>
                                  </tr>
                                  <?php $sub_total += $item->amount; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="5" align="right"><strong>Total</strong></td>
                                    <td align="right"><strong><?= number_format($sub_total, 2) ?></strong></td>
                                </tr>
                                <tr><td colspan="6" height="20"></td></tr>

                                <!-- GST Summary -->
                                <tr><td colspan="3" rowspan="5" valign="top">
                                        Terms & Conditions<br><br>
                                        <?= nl2br($quotation->terms) ?>
                                    </td>
                                    <td colspan="2" rowspan="3">
                                        <table width="100%" border="0" cellpadding="2" cellspacing="0">
                                            <tr><td align="center">IGST</td><td align="right"><?= $quotation->igst_percent ?? 0 ?>%</td></tr>
                                            <tr><td align="center">CGST</td><td align="right"><?= $quotation->cgst_percent ?? 0 ?>%</td></tr>
                                            <tr><td align="center">SGST</td><td align="right"><?= $quotation->sgst_percent ?? 0 ?>%</td></tr>
                                        </table>
                                    </td>
                                    <td align="right">Amount</td></tr>
                                <tr><td align="right">Amount</td></tr>
                                <tr><td align="right">Amount</td></tr>
                                <tr><td colspan="2" align="right"><strong>Tax Total</strong></td><td align="right"><?= number_format($quotation->gst_total ?? 0, 2) ?></td></tr>
                                <tr><td colspan="2" align="right"><strong>Estimate Total</strong></td><td align="right"><strong><?= number_format($quotation->total_amount, 2) ?></strong></td></tr>

                                <!-- In Words -->
                                <tr>
                                    <td colspan="6"><strong><?= ucwords(convert_number_to_words($quotation->total_amount)) ?> Only</strong></td>
                                </tr>

                                <!-- Bank Details -->
                                <tr><td colspan="6" valign="top">
                                        <table border="0" cellpadding="5" cellspacing="0" width="1000">
                                            <tr>
                                                <td width="500">
                                                    <div style="margin-bottom:10px"><strong><u>Company's Bank Details</u></strong></div>
                                                    <table width="300" border="0" cellpadding="5" cellspacing="0">
                                                        <tr><td>Bank Name</td><td><?= $quotation->bank_name ?></td></tr>
                                                        <tr><td>Bank Address</td><td><?= $quotation->bank_address ?></td></tr>
                                                        <tr><td>A/c No.</td><td><?= $quotation->bank_account ?></td></tr>
                                                        <tr><td>IFSC Code</td><td><?= $quotation->bank_ifsc ?></td></tr>
                                                    </table>
                                                </td>
                                                <td width="500">
                                                    <strong><u>Note</u></strong><br>
                                                    Kindly sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.<br>
                                                    If any discrepancy related to the Estimate; please revert on <?= $quotation->company_email ?? 'info@hsadindia.com' ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td></tr>

                                <!-- Signature -->
                                <tr><td colspan="6" valign="top">
                                        <table border="0" cellpadding="5" cellspacing="0" width="1000">
                                            <tr>
                                                <td width="500" style="border-right:2px solid #333;">
                                                    <div style="margin-bottom:10px"><strong>For <?= $quotation->company_name ?></strong></div>
                                                    <table border="0" cellpadding="5" cellspacing="0" width="500">
                                                        <tr>
                                                            <td align="center" valign="bottom" height="100"><div style="width:100px; border-bottom:2px dotted #000;"></div></td>
                                                            <td align="center" valign="bottom"><div style="width:100px; border-bottom:2px dotted #000;"></div></td>
                                                            <td align="center" valign="bottom"><div style="width:100px; border-bottom:2px dotted #000;"></div></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" valign="top">Prepared By</td>
                                                            <td align="center" valign="top">Finance Manager</td>
                                                            <td align="center" valign="top">Finance Head</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="500">
                                                    <table border="0" cellpadding="5" cellspacing="0" width="500">
                                                        <tr>
                                                            <td align="center"><div style="margin-bottom:10px"><strong><?= $quotation->client_name ?></strong></div></td>
                                                        </tr>
                                                        <tr><td align="center" height="100"></td></tr>
                                                        <tr><td align="center"><div>Received and Accepted By</div></td></tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
