<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            .wrapper {
                /* Set width to a percentage to be responsive to the page size */
                width: 95%;
                margin: 0 auto;
                font-size: 6px;
            }
            table {
                border-collapse: collapse;
                /* Ensure all tables are 100% of their container's width */
                width: 100%;
            }
            th, td {
                padding: 3px;
                border: 1px solid #000;
            }
            /* Remove borders from tables that don't need them */
            .no-border-table td, .no-border-table {
                border: none;
            }
            .header-table td {
                border: none;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <table class="header-table" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="20%">
                        <?php if (!empty($quotation->company_logo)) : ?>
                          <img src="<?= isset($logo_src) ? $logo_src : base_url('assets/images/company-logo.png') ?>" style="width:130px;" alt="Logo">
                        <?php else : ?>
                          <img src="<?= isset($logo_src) ? $logo_src : base_url('assets/images/company-logo.png') ?>" style="width:130px;" alt="Logo">
                        <?php endif; ?>
                    </td>
                    <td align="left" valign="middle">
                        Corporate Office: <?= nl2br($quotation->company_address ?: get_company_info()['address']) ?><br>
                        Tel: <?= isset($quotation->company_phone) ? $quotation->company_phone : get_company_info()['phone'] ?>,
                        Email: <?= isset($quotation->company_email) ? $quotation->company_email : get_company_info()['email'] ?>,
                        Website: <?= isset($quotation->company_website) ? $quotation->company_website : get_setting('company_website', 'www.hsad.co.in') ?><br>
                        CIN: <?= $quotation->company_cin ?: get_setting('company_cin', 'U74300DL2010FTC197646') ?>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2" style="font-size:30px">ESTIMATE</td>
                </tr>
            </table>

            <hr>

            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <td rowspan="4" width="33%">
                        <strong><?= $quotation->company_name ?></strong><br>
                        <?= nl2br($quotation->company_address) ?><br>
                        State: <?= $quotation->company_state ?>, State Code: <?= $quotation->company_state_code ?? '09' ?><br>
                        GSTIN/UIN: <?= $quotation->company_gstin ?><br>
                        CIN: <?= $quotation->company_cin ?><br>
                        PAN NO. <?= $quotation->company_pan ?><br><br>
                    </td>
                    <td width="33%">Estimate No.<br><strong><?= 'EST-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) ?></strong></td>
                    <td>Date<br><strong><?= isset($quotation->created_at) ? date('d-m-y', strtotime($quotation->created_at)) : date('d-m-y') ?></strong></td>
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
            </table>

            <hr>

            <table border="1" cellpadding="2" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">Sr.<br>No.</th>
                        <th width="45%">Description of Goods & Services</th>
                        <th width="15%">HSN/SAC <?= !empty($quotation->hsn_sac) ? $quotation->hsn_sac : '998314' ?></th>
                        <th width="10%">Qty</th>
                        <th width="10%">Rate</th>
                        <th width="15%">Amount INR</th>
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
                          <?php
                            $qty = isset($item->qty) ? (float)$item->qty : 0;
                            $rate = isset($item->rate) ? (float)$item->rate : 0;
                            $discount = isset($item->discount) ? (float)$item->discount : 0;
                            $computed_amount = ($qty * $rate);
                            if ($discount > 0) { $computed_amount -= ($computed_amount * $discount / 100); }
                            $line_amount = isset($item->amount) && $item->amount !== null && $item->amount !== '' ? (float)$item->amount : $computed_amount;
                          ?>
                          <td align="center"><?= $qty ?></td>
                          <td align="right"><?= number_format($rate, 2) ?></td>
                          <td align="right"><?= number_format($line_amount, 2) ?></td>
                      </tr>
                      <?php $sub_total += $line_amount; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5" align="right"><strong>Total</strong></td>
                        <td align="right"><strong><?= number_format($sub_total, 2) ?></strong></td>
                    </tr>
                    <?php
                    $is_same_state = ($quotation->company_state == $quotation->client_state);
                    $sub_total = array_sum(array_column($items, 'amount'));
                    $igst_amount = 0;
                    $cgst_amount = 0;
                    $sgst_amount = 0;
                    $gst_total = 0;
                    $grand_total = 0;

                    if ($is_same_state) {
                      $cgst_percent = $quotation->cgst_percent ?? 9;
                      $sgst_percent = $quotation->sgst_percent ?? 9;
                      $cgst_amount = ($sub_total * $cgst_percent) / 100;
                      $sgst_amount = ($sub_total * $sgst_percent) / 100;
                      $gst_total = $cgst_amount + $sgst_amount;
                    } else {
                      $igst_percent = $quotation->igst_percent ?? 18; // Assuming 18% as a common default for IGST
                      $igst_amount = ($sub_total * $igst_percent) / 100;
                      $gst_total = $igst_amount;
                    }
                    $grand_total = $sub_total + $gst_total;
                    ?>
                    <tr>
                        <td colspan="3" rowspan="5" valign="top">
                            Terms & Conditions<br><br>
                            <?= nl2br($quotation->terms) ?>
                        </td>
                        <td colspan="3">
                            <table class="no-border-table" width="100%" cellpadding="5" cellspacing="0">
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
                        <td colspan="6"><strong><?= ucwords(convert_number_to_words($grand_total)) ?> Only</strong></td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <table class="no-border-table" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="50%">
                        <div style="margin-bottom:10px"><strong><u>Company's Bank Details</u></strong></div>
                        <table class="no-border-table" width="100%" cellpadding="5" cellspacing="0">
                            <tr><td>Bank Name</td><td><?= $quotation->bank_name ?></td></tr>
                            <tr><td>Bank Address</td><td><?= $quotation->bank_address ?></td></tr>
                            <tr><td>A/c No.</td><td><?= $quotation->bank_account ?></td></tr>
                            <tr><td>IFSC Code</td><td><?= $quotation->bank_ifsc ?></td></tr>
                        </table>
                    </td>
                    <td width="50%">
                        <strong><u>Note</u></strong><br>
                        Kindly sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.<br>
                        If any discrepancy related to the Estimate; please revert on <?= $quotation->note ?? 'info@hsadindia.com' ?>
                    </td>
                </tr>
            </table>

            <hr>

            <table class="no-border-table" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="50%" style="border-right:2px solid #333;">
                        <div style="margin-bottom:10px"><strong>For <?= $quotation->company_name ?></strong></div>
                        <table class="no-border-table" cellpadding="5" cellspacing="0">
                            <tr>
<!--                                <td align="center" valign="bottom" height="100"><div "></div></td>
                                <td align="center" valign="bottom"><div "></div></td>
                                <td align="center" valign="bottom"><div "></div></td>-->
                            </tr>
                            <tr>
                                <td align="center" valign="top">Prepared By</td>
                                <td align="center" valign="top">Finance Manager</td>
                                <td align="center" valign="top">Finance Head</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table class="no-border-table" cellpadding="5" cellspacing="0">
                            <tr>
                                <td align="center"><div style="margin-bottom:10px"><strong><?= $quotation->client_name ?></strong></div></td>
                            </tr>
                            <tr><td align="center" height="100"></td></tr>
                            <tr><td align="center"><div>Received and Accepted By</div></td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>