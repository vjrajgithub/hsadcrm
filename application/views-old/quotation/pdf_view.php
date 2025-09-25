<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <!--<title>Estimate #<?= 'EST-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) ?></title>-->
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
                          <img src="http://localhost/crm/assets/uploads/logos/1754289061.jpeg" style="width:130px;" alt="Logo">
                        <?php else : ?>
                          <img src="http://localhost/crm/assets/uploads/logos/1754289061.jpeg" style="width:130px;">
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
                                      <td valign="top"><?= $item->product_name ?></td>
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
                                <?php
                                $igst_amount = 0;
                                $cgst_amount = 0;
                                $sgst_amount = 0;
                                $gst_total = 0;
                                $grand_total = 0;

// Determine if IGST applies
                                $is_same_state = ($quotation->company_state == $quotation->client_state);
                                $sub_total = array_sum(array_column($items, 'amount'));

// Calculate GST
                                if ($is_same_state) {
                                  $cgst_percent = $quotation->cgst_percent ?? 9;
                                  $sgst_percent = $quotation->sgst_percent ?? 9;

                                  $cgst_amount = ($sub_total * $cgst_percent) / 100;
                                  $sgst_amount = ($sub_total * $sgst_percent) / 100;

                                  $gst_total = $cgst_amount + $sgst_amount;
                                } else {
                                  $igst_percent = $quotation->igst_percent ?? 0;
                                  $igst_amount = ($sub_total * $igst_percent) / 100;
                                  $gst_total = $igst_amount;
                                }

                                $grand_total = $sub_total + $gst_total;
                                ?>

                                <!-- GST Summary -->
                                <tr>
                                    <td colspan="3" rowspan="5" valign="top">
                                        Terms & Conditions<br><br>
                                        <?= nl2br($quotation->terms) ?>
                                    </td>
                                    <td colspan="3">
                                        <table width="100%" border="1" cellpadding="5" cellspacing="0">
                                            <?php if ($is_same_state): ?>
                                              <tr>
                                                  <td>CGST @ <?= $cgst_percent ?>%</td>
                                                  <td align="right"><?= format_inr($cgst_amount) ?></td>
                                              </tr>
                                              <tr>
                                                  <td>SGST @ <?= $sgst_percent ?>%</td>
                                                  <td align="right"><?= format_inr($sgst_amount) ?></td>
                                              </tr>
                                            <?php else: ?>
                                              <tr>
                                                  <td>IGST @ <?= $igst_percent ?>%</td>
                                                  <td align="right"><?= format_inr($igst_amount) ?></td>
                                              </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <td><strong>Tax Total</strong></td>
                                                <td align="right"><strong><?= format_inr($gst_total) ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Estimate Total</strong></td>
                                                <td align="right"><strong><?= format_inr($grand_total) ?></strong></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6"><strong><?= ucwords(convert_number_to_words(round($grand_total))) ?> Only</strong></td>
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
