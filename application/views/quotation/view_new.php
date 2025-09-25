<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quotation View</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-content { margin: 0; padding: 0; }
            body { font-size: 12px; }
            .quotation-view { box-shadow: none; border: none; margin: 0; }
        }

        .quotation-view {
            background: #ffffff;
            border: 1px solid #000;
            margin: 20px auto;
            max-width: 210mm;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        .btn-toolbar {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .header-table, .info-table, .items-table, .total-table, .terms-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table td, .info-table td, .items-table th, .items-table td, .total-table td, .terms-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
            font-size: 12px;
        }

        .items-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .items-table td {
            text-align: center;
        }

        .company-logo {
            max-width: 130px;
            height: auto;
        }

        .estimate-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
        }

        @media (max-width: 768px) {
            .quotation-view {
                margin: 10px;
                font-size: 12px;
                overflow-x: auto;
            }
            
            .header-table td, .info-table td, .items-table th, .items-table td, .total-table td, .terms-table td {
                padding: 4px;
                font-size: 10px;
                word-wrap: break-word;
            }
            
            .estimate-title {
                font-size: 18px;
                padding: 10px;
            }
            
            .company-logo {
                max-width: 100px;
            }
        }
    </style>
</head>
<body>

<!-- ACTION BUTTONS -->
<div class="btn-toolbar no-print" role="toolbar">
    <div class="btn-group mr-3" role="group">
        <a href="<?php echo base_url('quotation/create'); ?>" class="btn btn-success btn-sm shadow-sm">
            <i class="fa fa-plus"></i> Add New
        </a>
        <a href="<?php echo base_url('quotation/edit/' . (isset($quotation->id) ? $quotation->id : '1')); ?>" class="btn btn-primary btn-sm shadow-sm">
            <i class="fa fa-pencil"></i> Edit
        </a>
        <a href="<?php echo base_url('quotation/duplicate/' . (isset($quotation->id) ? $quotation->id : '1')); ?>" class="btn btn-info btn-sm shadow-sm">
            <i class="fa fa-clone"></i> Duplicate
        </a>
    </div>
    <div class="btn-group mr-3" role="group">
        <a href="<?php echo base_url('quotation/view_pdf/' . (isset($quotation->id) ? $quotation->id : '1')); ?>" target="_blank" class="btn btn-warning btn-sm shadow-sm">
            <i class="fa fa-file-pdf-o"></i> View PDF
        </a>
        <a href="<?php echo base_url('quotation/generate_pdf/' . (isset($quotation->id) ? $quotation->id : '1')); ?>" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fa fa-download"></i> Download PDF
        </a>
        <button onclick="window.print()" class="btn btn-dark btn-sm shadow-sm">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="btn-group" role="group">
        <button class="btn btn-warning btn-sm shadow-sm" id="sendMailBtn" 
                data-id="<?php echo isset($quotation->id) ? $quotation->id : '1'; ?>" 
                data-client="<?php echo isset($quotation->client_name) ? $quotation->client_name : 'Client'; ?>" 
                data-email="<?php echo isset($quotation->client_email) ? $quotation->client_email : ''; ?>">
            <i class="fa fa-envelope"></i> Send Mail
        </button>
        <a href="#" class="btn btn-danger btn-sm shadow-sm" onclick="confirmDelete(<?php echo isset($quotation->id) ? $quotation->id : '1'; ?>)">
            <i class="fa fa-trash"></i> Delete
        </a>
    </div>
</div>

<!-- QUOTATION CONTENT -->
<div class="quotation-view print-content">
    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td style="width:25%;">
                <?php if (isset($quotation->company_logo) && $quotation->company_logo): ?>
                    <img src="<?php echo base_url('assets/uploads/logos/' . $quotation->company_logo); ?>" class="company-logo" alt="Company Logo">
                <?php else: ?>
                    <div style="width:130px; height:60px; border:1px dashed #ccc; display:flex; align-items:center; justify-content:center; color:#999;">
                        <i class="fa fa-building"></i> Logo
                    </div>
                <?php endif; ?>
            </td>
            <td style="width:75%;">
                Corporate Office: <?php echo nl2br(isset($quotation->company_address) ? $quotation->company_address : 'Company Address'); ?><br>
                Tel: <?php echo isset($quotation->company_phone) ? $quotation->company_phone : 'N/A'; ?>,
                Email: <?php echo isset($quotation->company_email) ? $quotation->company_email : 'N/A'; ?>,
                Website: <?php echo isset($quotation->company_website) ? $quotation->company_website : 'N/A'; ?><br>
                CIN: <?php echo isset($quotation->company_cin) ? $quotation->company_cin : 'N/A'; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="estimate-title">ESTIMATE</td>
        </tr>
    </table>

    <!-- COMPANY & CLIENT INFO -->
    <table class="info-table">
        <tr>
            <td rowspan="4" style="width:40%;">
                <strong><?php echo isset($quotation->company_name) ? $quotation->company_name : 'Company Name'; ?></strong><br>
                <?php echo nl2br(isset($quotation->company_address) ? $quotation->company_address : 'Company Address'); ?><br>
                State: <?php echo isset($quotation->company_state) ? $quotation->company_state : 'State'; ?>, State Code: <?php echo isset($quotation->company_state_code) ? $quotation->company_state_code : '09'; ?><br>
                GSTIN/UIN: <?php echo isset($quotation->company_gstin) ? $quotation->company_gstin : 'N/A'; ?><br>
                CIN: <?php echo isset($quotation->company_cin) ? $quotation->company_cin : 'N/A'; ?><br>
                PAN NO. <?php echo isset($quotation->company_pan) ? $quotation->company_pan : 'N/A'; ?><br><br>
            </td>
            <td style="width:30%;">Estimate No.<br><strong><?php echo 'EST-' . str_pad((isset($quotation->id) ? $quotation->id : 1), 4, '0', STR_PAD_LEFT); ?></strong></td>
            <td style="width:30%;">Date<br><strong><?php echo date('d-m-y', strtotime(isset($quotation->created_at) ? $quotation->created_at : date('Y-m-d'))); ?></strong></td>
        </tr>
        <tr>
            <td>Job No.<br><strong><?php echo isset($quotation->job_no) ? $quotation->job_no : '-'; ?></strong></td>
            <td>Mode/Terms of Payment<br><strong><?php echo isset($quotation->mode_name) ? $quotation->mode_name : 'NA'; ?></strong></td>
        </tr>
        <tr>
            <td>Category<br><strong><?php echo isset($quotation->department) ? $quotation->department : 'Department'; ?></strong></td>
            <td>Other<br><strong><?php echo isset($quotation->other_text) ? $quotation->other_text : '-'; ?></strong></td>
        </tr>
        <tr>
            <td>Contact Person<br><strong><?php echo isset($quotation->contact_person) ? $quotation->contact_person : 'Contact Person'; ?></strong></td>
            <td>Place of Supply<br><strong><?php echo isset($quotation->state) ? $quotation->state : 'State'; ?></strong></td>
        </tr>
        <tr>
            <td rowspan="4">
                <strong><u>Details of Buyer</u></strong><br>
                <strong><?php echo isset($quotation->client_name) ? $quotation->client_name : 'Client Name'; ?></strong><br>
                <?php echo nl2br(isset($quotation->client_address) ? $quotation->client_address : 'Client Address'); ?><br>
                State & St Code <?php echo isset($quotation->client_state) ? $quotation->client_state : 'State'; ?> <?php echo isset($quotation->client_state_code) ? $quotation->client_state_code : '09'; ?><br>
                Country India<br>
                GSTIN/Unique ID: <?php echo isset($quotation->client_gstin) ? $quotation->client_gstin : 'N/A'; ?><br>
                PAN No. <?php echo isset($quotation->client_pan) ? $quotation->client_pan : 'N/A'; ?>
            </td>
            <td rowspan="4">
                <strong><?php echo isset($quotation->client_name) ? $quotation->client_name : 'Client Name'; ?></strong><br>
                <?php echo nl2br(isset($quotation->client_address) ? $quotation->client_address : 'Client Address'); ?><br>
                State & St Code <?php echo isset($quotation->client_state) ? $quotation->client_state : 'State'; ?> <?php echo isset($quotation->client_state_code) ? $quotation->client_state_code : '09'; ?><br>
                Country India<br>
                GSTIN/Unique ID: <?php echo isset($quotation->client_gstin) ? $quotation->client_gstin : 'N/A'; ?><br>
                PAN No. <?php echo isset($quotation->client_pan) ? $quotation->client_pan : 'N/A'; ?>
            </td>
            <td>Advertising Service Category</td>
        </tr>
        <tr><td>Other professional, technical and business services</td></tr>
        <tr><td>Mode of transportation<br><strong>NA</strong></td></tr>
        <tr><td>Reverse Charge<br><strong>NA</strong></td></tr>
    </table>

    <!-- ITEMS TABLE -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:8%;">Sr.No.</th>
                <th style="width:40%;">Description of Goods & Services</th>
                <th style="width:15%;">HSN/SAC <?php echo isset($quotation->hsn_sac) ? $quotation->hsn_sac : '998314'; ?></th>
                <th style="width:10%;">Qty</th>
                <th style="width:12%;">Rate</th>
                <th style="width:15%;">Amount INR</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $sub_total = 0;
            if (isset($items) && is_array($items) && count($items) > 0):
                foreach ($items as $item):
                    $qty = isset($item->qty) ? (float)$item->qty : 0;
                    $rate = isset($item->rate) ? (float)$item->rate : 0;
                    $discount = isset($item->discount) ? (float)$item->discount : 0;
                    $amount = ($qty * $rate) - (($qty * $rate) * $discount / 100);
                    $sub_total += $amount;
            ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td style="text-align:left;"><?php echo isset($item->product_name) ? $item->product_name : 'Product Name'; ?></td>
                <td><?php echo isset($quotation->hsn_sac) ? $quotation->hsn_sac : '998314'; ?></td>
                <td><?php echo $qty; ?></td>
                <td style="text-align:right;">₹<?php echo number_format($rate, 2); ?></td>
                <td style="text-align:right;">₹<?php echo number_format($amount, 2); ?></td>
            </tr>
            <?php 
                endforeach; 
            else:
            ?>
            <tr>
                <td colspan="6" style="text-align:center; padding:20px; color:#666;">No items found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- TOTAL SECTION -->
    <table class="total-table">
        <tr>
            <td style="width:70%; text-align:right;"><strong>Total</strong></td>
            <td style="width:30%; text-align:right;"><strong>₹<?php echo number_format($sub_total, 2); ?></strong></td>
        </tr>
        <?php if (isset($quotation->gst_type) && isset($quotation->gst_amount) && $quotation->gst_type && $quotation->gst_amount): ?>
        <tr>
            <td style="text-align:right;">Add: GST (<?php echo strtoupper($quotation->gst_type); ?>)</td>
            <td style="text-align:right;">₹<?php echo number_format($quotation->gst_amount, 2); ?></td>
        </tr>
        <tr>
            <td style="text-align:right;"><strong>Total Amount After Tax</strong></td>
            <td style="text-align:right;"><strong>₹<?php echo number_format($sub_total + $quotation->gst_amount, 2); ?></strong></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;"><strong>Amount Chargeable (in words)</strong><br>
            INR <?php echo number_format($sub_total + $quotation->gst_amount, 2); ?> Only</td>
        </tr>
        <?php else: ?>
        <tr>
            <td colspan="2" style="text-align:center;"><strong>Amount Chargeable (in words)</strong><br>
            INR <?php echo number_format($sub_total, 2); ?> Only</td>
        </tr>
        <?php endif; ?>
    </table>

    <!-- TERMS & CONDITIONS -->
    <?php if ((isset($quotation->terms) && $quotation->terms) || (isset($quotation->notes) && $quotation->notes)): ?>
    <table class="terms-table">
        <tr>
            <?php if (isset($quotation->terms) && $quotation->terms): ?>
            <td style="width:50%;">
                <strong>Terms & Conditions:</strong><br>
                <?php echo nl2br($quotation->terms); ?>
            </td>
            <?php endif; ?>
            <?php if (isset($quotation->notes) && $quotation->notes): ?>
            <td style="width:50%;">
                <strong>Notes:</strong><br>
                <?php echo nl2br($quotation->notes); ?>
            </td>
            <?php endif; ?>
        </tr>
    </table>
    <?php endif; ?>

    <!-- ATTACHMENT INFO -->
    <?php if (isset($quotation->attachment) && $quotation->attachment): ?>
    <div style="margin:10px; padding:10px; background:#f0f8ff; border:1px solid #ccc; text-align:center;">
        <i class="fa fa-paperclip"></i> 
        <strong>Attachment:</strong> 
        <a href="<?php echo base_url('assets/uploads/quotations/' . $quotation->attachment); ?>" target="_blank">
            <?php echo $quotation->attachment; ?>
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
// SweetAlert delete confirmation
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this quotation?')) {
        window.location.href = "<?php echo base_url('quotation/delete/'); ?>" + id;
    }
}

// Send Mail Modal (simplified)
document.getElementById('sendMailBtn').addEventListener('click', function() {
    var quotationId = this.getAttribute('data-id');
    var email = this.getAttribute('data-email');
    
    if (email) {
        var subject = 'Quotation from CRM';
        var body = 'Dear Sir/Madam,\n\nPlease find the attached quotation for your reference.\n\nRegards,\nCRM Team';
        window.location.href = 'mailto:' + email + '?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
    } else {
        alert('No email address found for this client.');
    }
});
</script>

</body>
</html>
