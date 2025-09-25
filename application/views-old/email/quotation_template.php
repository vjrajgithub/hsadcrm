<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Quotation Email</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
            }
            .email-wrapper {
                width: 100%;
                background-color: #f8f9fa;
                padding: 20px;
            }
            .email-content {
                max-width: 700px;
                margin: auto;
                background-color: #ffffff;
                border-radius: 5px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }
            .email-header {
                background-color: #007bff;
                color: #ffffff;
                padding: 20px;
                text-align: center;
            }
            .email-body {
                padding: 20px;
                color: #343a40;
                font-size: 15px;
            }
            .email-footer {
                padding: 20px;
                text-align: center;
                font-size: 13px;
                color: #6c757d;
                background-color: #f1f1f1;
            }
            .btn {
                display: inline-block;
                padding: 10px 15px;
                background-color: #28a745;
                color: #fff !important;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 15px;
            }
        </style>
    </head>
    <body>
        <div class="email-wrapper">
            <div class="email-content">
                <div class="email-header">
                    <h2><?= $company_name ?? 'Your Company Name' ?></h2>
                    <p>Quotation Details</p>
                </div>
                <div class="email-body">
                    <p>Dear <?= $client_name ?? 'Client' ?>,</p>

                    <?php if (!empty($custom_message)) : ?>
                      <p><?= nl2br($custom_message) ?></p>
                    <?php else: ?>
                      <p>Thank you for your interest. Please find the attached quotation as requested.</p>
                    <?php endif; ?>

                    <p>If you have any questions, feel free to reach out.</p>

                    <a href="<?= $pdf_link ?>" class="btn">View Quotation PDF</a>

                    <p>Regards,<br><?= $company_name ?? 'Your Company' ?></p>
                </div>
                <div class="email-footer">
                    <p>This is an automated email. Please do not reply.</p>
                </div>
            </div>
        </div>
    </body>
</html>
