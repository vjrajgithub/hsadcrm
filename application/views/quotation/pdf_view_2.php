<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Quotation #<?= $quotation->id ?></title>
        <style>
            body {
                font-size: 12px;
                font-family: Arial, sans-serif;
            }
            .wrapper {
                width: 1200px;
                margin: 0 auto;
            }
            table {
                border-collapse: collapse;
            }
            th, td {
                padding: 5px;
            }
            .text-right {
                text-align: right;
            }
            .text-center {
                text-align: center;
            }
            .bordered {
                border: 1px solid #000;
            }
        </style>
    </head>

    <body>
        <div class="wrapper">
            <!-- HEADER -->
            <table border="0" cellpadding="5" cellspacing="0" width="1000" style="margin: 0 auto">
                <tr>
                    <td width="200"></td>
                    <td align="left" valign="middle">
                        Corporate Office: C-001B, 12th Floor, KP Tower D, Sector-16B, Noida, Gautam Buddha Nagar, U.P. 201301, India.<br>
                        Tel:+91-120-4624900 Email: info@hsadindia.com Website: www.hsad.co.in CIN: U74300DL2010FTC197646
                    </td>
                </tr>
                <tr><td align="center" colspan="2" style="font-size:30px">ESTIMATE</td></tr>
            </table>

            <!-- HEADER DETAILS -->
            <table border="1" cellpadding="5" cellspacing="0" width="1000" style="margin: 0 auto">
                <tr>
                    <td rowspan="4" width="335" valign="top">
                        <strong><?= $quotation->company_name ?></strong><br/>
                        <?= $quotation->company_address ?><br/>
                        State: <?= $quotation->company_state ?><br/>
                        GSTIN/UIN: <?= $quotation->company_gstin ?><br/>
                        PAN NO: <?= $quotation->company_pan ?><br/><br/>
                    </td>
                    <td width="332">Estimate No.<br/><strong><?= $quotation->estimate_no ?? 'EST-' . $quotation->id ?></strong></td>
                    <td>Date<br/><strong><?= date('d-m-y', strtotime($quotation->created_at)) ?></strong></td>
                </tr>
                <tr>
                    <td>Job No.<br/><strong><?= $quotation->job_no ?? 'N/A' ?></strong></td>
                    <td>Mode/Terms of Payment<br/><strong><?= $quotation->mode_name ?></strong></td>
                </tr>
                <tr>
                    <td>Category<br/><strong><?= $quotation->category_name ?? 'N/A' ?></strong></td>
                    <td>Other<br/><strong></strong></td>
                </tr>
                <tr>
                    <td>Contact Person<br/><strong><?= $quotation->contact_person ?></strong></td>
                    <td>Place of Supply<br/><strong><?= $quotation->state ?></strong></td>
                </tr>

                <tr>
                    <td rowspan="4" valign="top">
                        <strong><u>Details of Buyer</u></strong><br/>
                        <strong><?= $quotation->client_name ?></strong><br/>
                        <?= $quotation->client_address ?><br/>
                        State: <?= $quotation->client_state ?><br/>
                        GSTIN: <?= $quotation->client_gstin ?><br/>
                        PAN: <?= $quotation->client_pan ?><br/>
                    </td>
                    <td rowspan="4" valign="top">
                        <strong><?= $quotation->client_name ?></strong><br/>
                        <?= $quotation->client_address ?><br/>
                        State: <?= $quotation->client_state ?><br/>
                        GSTIN: <?= $quotation->client_gstin ?><br/>
                        PAN: <?= $quotation->client_pan ?><br/>
                    </td>
                    <td>Advertising Service Category</td>
                </tr>
                <tr><td>Other professional, technical and business services</td></tr>
                <tr><td>Mode of transportation<br/><strong>NA</strong></td></tr>
                <tr><td>Reverse Charge<br/><strong>NA</strong></td></tr>

                <tr>
                    <td colspan="6" valign="top" style="padding: 0px; border: 0px;">
                        <table border="1" cellpadding="2" cellspacing="0" width="1000">
                            <thead>
                                <tr>
                                    <th width="30">Sr.<br/>No.</th>
                                    <th width="520">Description of Goods &amp; Services</th>
                                    <th width="150">HSN/SAC</th>
                                    <th width="100"></th>
                                    <th width="50"></th>
                                    <th width="150">Amount INR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $total = 0;
                                foreach ($items as $item):
                                  $amount = ($item->qty * $item->rate) - (($item->qty * $item->rate) * $item->discount / 100);
                                  $total += $amount;
                                  ?>
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
                                      <td valign="top" align="center"><?= $quotation->hsn_sac ?></td>
                                      <td></td>
                                      <td></td>
                                      <td align="right"><?= number_format($amount, 2) ?></td>
                                  </tr>
                                <?php endforeach; ?>

                                <tr>
                                    <td colspan="5" class="text-right"><strong>Total</strong></td>
                                    <td align="right"><strong><?= number_format($total, 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <td rowspan="3"></td><td colspan="2" rowspan="3"></td><td colspan="3">&nbsp;</td>
                                </tr>
                                <tr><td colspan="3" align="center">GST</td></tr>
                                <tr><td></td><td>Rate</td><td align="center">Amount</td></tr>

                                <tr>
                                    <td colspan="3" rowspan="5" valign="top">
                                        Terms &amp; Conditions<br/><br/>
                                        <?= nl2br($quotation->terms) ?>
                                    </td>
                                    <td colspan="2" rowspan="3">
                                        <table border="0" cellpadding="2" cellspacing="0" width="100%">
                                            <tr><td align="center"><?= strtoupper($quotation->gst_type) ?></td><td align="right"><?= $quotation->gst_rate ?? '18%' ?></td></tr>
                                        </table>
                                    </td>
                                    <td align="right">Amount</td>
                                </tr>
                                <tr><td align="right"><?= number_format($quotation->gst_amount ?? 0, 2) ?></td></tr>
                                <tr><td align="right"><?= number_format($total + ($quotation->gst_amount ?? 0), 2) ?></td></tr>
                                <tr><td colspan="2" align="right"><strong>Tax Total</strong></td><td align="right"><?= number_format($total, 2) ?></td></tr>
                                <tr><td colspan="2" align="right"><strong>Estimate Total</strong></td><td align="right"><strong><?= number_format($total + ($quotation->gst_amount ?? 0), 2) ?></strong></td></tr>
                                <tr>
                                    <td colspan="6"><strong><?= ucfirst(convert_number_to_words($total + ($quotation->gst_amount ?? 0))) ?> Only</strong></td>
                                </tr>

                                <tr><td colspan="6" valign="top" align="left">
                                        <!-- Bank Details & Notes -->
                                        <table border="0" cellpadding="5" cellspacing="0" width="1000">
                                            <tr>
                                                <td width="500">
                                                    <div style="margin-bottom: 10px"><strong><u>Company's Bank Details</u></strong></div>
                                                    <table width="300" border="0" cellpadding="5" cellspacing="0">
                                                        <tr><td>Bank Name</td><td><?= $quotation->bank_name ?></td></tr>
                                                        <tr><td>Bank Address</td><td><?= $quotation->branch_address ?></td></tr>
                                                        <tr><td>A/c No.</td><td><?= $quotation->bank_account ?></td></tr>
                                                        <tr><td>IFSC CODE</td><td><?= $quotation->bank_ifsc ?></td></tr>
                                                    </table>
                                                </td>
                                                <td width="500">
                                                    <strong><u>Note</u></strong><br/>
                                                    <?= nl2br($quotation->notes) ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td></tr>

                                <!-- Signature -->
                                <tr><td colspan="6" valign="top" align="left">
                                        <table border="0" cellpadding="5" cellspacing="0" width="1000">
                                            <tr>
                                                <td width="500" style="border-right: 2px solid #333">
                                                    <div style="margin-bottom: 10px"><strong>For <?= $quotation->company_name ?></strong></div>
                                                    <table border="0" cellpadding="5" cellspacing="0" width="500">
                                                        <tr>
                                                            <td align="center" valign="bottom" height="100"><div style="width: 100px; border-bottom:2px dotted #000"></div></td>
                                                            <td align="center" valign="bottom"><div style="width: 100px; border-bottom:2px dotted #000"></div></td>
                                                            <td align="center" valign="bottom"><div style="width: 100px; border-bottom:2px dotted #000"></div></td>
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
                                                        <tr><td align="center"><div style="margin-bottom: 10px"><strong><?= $quotation->client_name ?></strong></div></td></tr>
                                                        <tr><td align="center" valign="bottom" height="100"></td></tr>
                                                        <tr><td align="center" valign="top"><div>Received and Accepted By</div></td></tr>
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
