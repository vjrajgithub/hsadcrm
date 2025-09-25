<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <!--<title>Estimate #<?= 'EST-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) ?></title>-->
        <style>
            .wrapper {
                width:1000px;
                margin: 50px auto;
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
            <table border="0" cellpadding="5" cellspacing="0" width="1000"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                <tr>
                    <td width="200">
                        <?php if (!empty($quotation->company_logo)) : ?>
                          <img src="http://localhost/crm/assets/uploads/logos/1754289061.jpeg" style="width:130px;" alt="Logo">
                        <?php else : ?>
                          <img src="http://localhost/crm/assets/uploads/logos/1754289061.jpeg" style="width:130px;">
                        <?php endif; ?>
                    </td>
                    <td align="left" valign="middle"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                        Corporate Office: <?= nl2br($quotation->company_address) ?><br>
                        Tel: <?= $quotation->company_phone ?? '+91-120-4624900' ?>,
                        Email: <?= $quotation->company_email ?? 'info@hsadindia.com' ?>,
                        Website: <?= $quotation->company_website ?? 'www.hsad.co.in' ?><br>
                        CIN: <?= $quotation->company_cin ?? 'U74300DL2010FTC197646' ?>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="2" style="font-size:30px; font-family:Arial, Helvetica, sans-serif;">ESTIMATE</td>
                </tr>
            </table>

            <table  border="1"  cellpadding="2" cellspacing="0" width="1000"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                <tr>
                    <td rowspan="4" width="33%"  valign="top">
                        <strong><?= $quotation->company_name ?></strong><br>
                        <?= nl2br($quotation->company_address) ?><br>
                        State: <?= $quotation->company_state ?>, State Code: <?= $quotation->company_state_code ?? '09' ?><br>
                        GSTIN/UIN: <?= $quotation->company_gstin ?><br>
                        CIN: <?= $quotation->company_cin ?><br>
                        PAN NO. <?= $quotation->company_pan ?><br><br>
                    </td>
                    <td width="33%"  valign="top">Estimate No.<br><strong><?= 'EST-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) ?></strong></td>
                    <td>Date<br><strong><?= date('d-m-y', strtotime($quotation->created_at)) ?></strong></td>
                </tr>
                <tr>
                    <td  valign="top">Job No.<br><strong><?= $quotation->job_no ?? '-' ?></strong></td>
                    <td> Mode/Terms of Payment<br><strong><?= $quotation->mode_name ?? 'NA' ?></strong></td>
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
            <table  border="1" cellpadding="2" cellspacing="0" width="1000"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                <thead>
                    <tr>
                        <th width="20">Sr.<br>No.</th>
                        <th width="280">Description of Goods & Services</th>
                        <th width="70">HSN/SAC <?= $quotation->hsn_sac ?></th>
                        <th width="20">Qty</th>
                        <th width="40">Rate</th>
                        <th width="70">Amount INR</th>
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
                        <td></td>
                        <td>
                            <table border="1" cellpadding="2" cellspacing="0" width="450" style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
					 	  <thead style=" background: #ccc">
						    <tr>
							 <th align="center"> Service Type</th>
							 <th align="center"> Unit </th>
							 <th align="center"> Rate </th>
							 <th align="center">Amount(INR)</th>
							  </tr>
						   </thead> 
						  <tbody>
						  <?php 
						  $detail_total = 0;
						  foreach ($items as $item): 
						      $detail_total += $item->amount;
						  ?>
						  <tr>
							  <td align="center"><?= $item->product_name ?></td>
							  <td align="center"><?= $item->qty ?></td>
						      <td align="center"><?= number_format($item->rate, 0) ?></td>
							  <td align="center"><?= number_format($item->amount, 0) ?></td>
						  </tr>
						  <?php endforeach; ?>
						  </tbody>
						  <tfoot style=" background: #ccc">
						    <tr>
							  <td align="center"> <strong>Total</strong></td>
							  <td align="center"></td>
						      <td align="center"></td>
							  <td align="center"><strong><?= number_format($detail_total, 0) ?></strong></td>
						  </tr>
							  </tfoot>
					   </table>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>

                    <tr><td></td>
                        <td ></td>
                        <td></td>
                        <td></td>
                        <td align="right"><strong>Total</strong></td>
                        <td align="right"><strong><?= number_format($sub_total, 2) ?></strong></td>
                    </tr>
                    <tr><td rowspan="3"></td><td colspan="2" rowspan="3"></td><td colspan="3">&nbsp;</td></tr>
                    <tr><td colspan="3" align="center">GST</td></tr>
                    <tr><td></td><td>Rate</td><td align="center">Amount</td></tr>
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
                        <td colspan="3" rowspan="3"   valign="top">
                            Terms & Conditions<br>
                            <?= nl2br($quotation->terms) ?>
                        </td>
                        <td colspan="3" style="padding:0px;border:0px;">
                            <table class="" border="1" width="100%" cellpadding="5" cellspacing="0"  style=" font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                                <?php if ($is_same_state): ?>
                                  <tr>
                                      <td  width="100">CGST @ <?= $cgst_percent ?>%</td>
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
                               
                            </table>
                        </td>
                    </tr>
                    <tr><td colspan="2" align="right"><strong>Text Total</strong></td><td align="right"><?= format_inr($gst_total) ?></td></tr>
                    <tr><td colspan="2" align="right"><strong>Estimate Total</strong></td><td align="right"><strong><?= format_inr($grand_total) ?></strong></td></tr>
                    <tr>
                        <td colspan="6"><strong><?= ucwords(convert_number_to_words(round($grand_total))) ?> Only</strong></td>
                    </tr>
                    <tr><td colspan="6">&nbsp;</td></tr>
                </tbody>
            </table>
            <table border="1" cellpadding="5" cellspacing="0" width="1000"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                <tr>
                    <td width="50%" valign="top">
                        <div style="margin-bottom:10px"><strong><u>Company's Bank Details</u></strong></div>
                        <table class="no-border-table" width="100%" cellpadding="2" cellspacing="0"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                            <tr><td>Bank Name</td><td><?= $quotation->bank_name ?></td></tr>
                            <tr><td>Bank Address</td><td><?= $quotation->bank_address ?></td></tr>
                            <tr><td>A/c No.</td><td><?= $quotation->bank_account ?></td></tr>
                            <tr><td>IFSC Code</td><td><?= $quotation->bank_ifsc ?></td></tr>
                        </table>
                    </td>
                    <td width="50%" valign="top">
                        <strong><u>Note</u></strong><br>
                        Kindly sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.<br>
                        If any discrepancy related to the Estimate; please revert on <?= $quotation->note ?? 'info@hsadindia.com' ?>
                    </td>
                </tr>
            </table>
            <table border="1" cellpadding="5" cellspacing="0" width="1000"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                <tr>
                    <td width="50%" style="border-right:1px solid #333;">
                        <div style="margin-bottom:10px"><strong>For <?= $quotation->company_name ?></strong></div>
                        <table class="no-border-table" cellpadding="5" cellspacing="0"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                            <tr>
                                <td align="center" valign="bottom" height="30"><div style="width:100px; border-bottom:2px dotted #000;"></div></td>
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
                    <td width="50%">
                        <table class="no-border-table" cellpadding="5" cellspacing="0"  style="font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                            <tr>
                                <td align="center"><div style="margin-bottom:10px"><strong><?= $quotation->client_name ?></strong></div></td>
                            </tr>
                            <tr><td align="center" height="30"></td></tr>
                            <tr><td align="center"><div>Received and Accepted By</div></td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
