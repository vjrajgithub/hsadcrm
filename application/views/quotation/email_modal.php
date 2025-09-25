<!-- Enhanced Send Quotation Email Modal -->
<div class="modal fade" id="sendMailModal" tabindex="-1" role="dialog" aria-labelledby="sendMailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="sendMailModalLabel">
                    <i class="fa fa-envelope"></i> Send Quotation Email
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="sendMailForm" enctype="multipart/form-data">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="quotation_id" id="mailQuotationId">
                
                <div class="modal-body">
                    <!-- Email Configuration Status -->
                    <div class="alert alert-info" id="emailConfigStatus">
                        <i class="fa fa-info-circle"></i> 
                        <strong>Email Configuration:</strong> 
                        <span id="configStatusText">Checking email settings...</span>
                    </div>

                    <!-- Sender & Recipient Information -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fa fa-users"></i> Email Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="mailFrom">From</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                    </div>
                                    <input type="text" id="mailFrom" class="form-control" readonly 
                                           value="<?= get_setting('from_name', 'HSAD India') ?> <<?= get_setting('from_email', 'billing@hsadindia.com') ?>>">
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> Company email configured in system settings
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <label for="mailTo">To Email(s) <span class="text-danger">*</span></label>
                                <input type="text" name="to" id="mailTo" class="form-control" 
                                       placeholder="recipient@example.com, another@example.com" required>
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> Separate multiple emails with commas
                                </small>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mailCC">CC Email(s)</label>
                                <input type="text" name="cc" id="mailCC" class="form-control" 
                                       placeholder="cc@example.com (optional)">
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> Carbon copy recipients (optional)
                                </small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Content -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fa fa-edit"></i> Email Content</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="mailSubject">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="mailSubject" class="form-control" 
                                       value="Quotation from HSAD India - #" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mailMessage">Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="mailMessage" class="form-control" rows="6" required>Dear Valued Client,

We are pleased to share our quotation for your requirements. Please find the detailed quotation attached with this email.

The quotation includes all specifications and pricing as discussed. Should you have any questions or require clarifications, please feel free to contact us.

We look forward to your positive response and the opportunity to serve you.

Thank you for considering HSAD India for your business needs.</textarea>
                                <div class="invalid-feedback"></div>
                                <small class="form-text text-muted">
                                    <span id="messageCharCount">0</span> characters
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fa fa-paperclip"></i> Attachments</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Quotation PDF</label>
                                <div class="alert alert-success" id="quotationAttachment">
                                    <i class="fa fa-file-pdf-o"></i> 
                                    <span id="quotationFileName">Quotation PDF will be automatically attached</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="additionalAttachment">Additional Attachment (Optional)</label>
                                <input type="file" name="attachment" id="additionalAttachment" class="form-control-file">
                                <small class="form-text text-muted">
                                    <i class="fa fa-info-circle"></i> 
                                    Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max: 10MB)
                                </small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Preview -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fa fa-eye"></i> Email Preview
                                <button type="button" class="btn btn-sm btn-outline-primary float-right" id="refreshPreview">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="emailPreview" class="border p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <div class="text-muted">Email preview will appear here...</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="sendEmailBtn">
                        <i class="fa fa-paper-plane"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentQuotationId = null;

    // Check email configuration on modal show
    $('#sendMailModal').on('show.bs.modal', function(e) {
        checkEmailConfiguration();
        updateEmailPreview();
        updateCharacterCount();
    });

    // Character count for message
    $('#mailMessage').on('input', function() {
        updateCharacterCount();
        updateEmailPreview();
    });

    // Update preview on input changes
    $('#mailSubject, #mailTo, #mailCC').on('input', function() {
        updateEmailPreview();
    });

    // Refresh preview button
    $('#refreshPreview').click(function() {
        updateEmailPreview();
    });

    // File validation
    $('#additionalAttachment').change(function() {
        validateAttachment(this);
    });

    // Email validation
    $('#mailTo, #mailCC').on('blur', function() {
        validateEmails($(this));
    });

    // Form submission with enhanced validation
    $('#sendMailForm').on('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            sendEmail();
        }
    });

    function checkEmailConfiguration() {
        $.ajax({
            url: '<?= base_url('settings/check_email_config') ?>',
            type: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
                if (data.configured) {
                    $('#emailConfigStatus').removeClass('alert-warning alert-danger').addClass('alert-success');
                    $('#configStatusText').html('<i class="fa fa-check"></i> Email settings configured properly');
                    $('#sendEmailBtn').prop('disabled', false);
                } else {
                    $('#emailConfigStatus').removeClass('alert-success alert-info').addClass('alert-warning');
                    $('#configStatusText').html('<i class="fa fa-exclamation-triangle"></i> Email settings need configuration');
                    $('#sendEmailBtn').prop('disabled', true);
                }
            },
            error: function() {
                $('#emailConfigStatus').removeClass('alert-success alert-info').addClass('alert-danger');
                $('#configStatusText').html('<i class="fa fa-times"></i> Unable to check email configuration');
                $('#sendEmailBtn').prop('disabled', true);
            }
        });
    }

    function updateCharacterCount() {
        const count = $('#mailMessage').val().length;
        $('#messageCharCount').text(count);
        
        if (count < 10) {
            $('#messageCharCount').parent().removeClass('text-success').addClass('text-warning');
        } else {
            $('#messageCharCount').parent().removeClass('text-warning').addClass('text-success');
        }
    }

    function updateEmailPreview() {
        const to = $('#mailTo').val() || 'recipient@example.com';
        const cc = $('#mailCC').val();
        const subject = $('#mailSubject').val() || 'No Subject';
        const message = $('#mailMessage').val() || 'No message content';
        
        let preview = `
            <div class="mb-2"><strong>From:</strong> <?= get_setting('from_name', 'HSAD India') ?> &lt;<?= get_setting('from_email', 'billing@hsadindia.com') ?>&gt;</div>
            <div class="mb-2"><strong>To:</strong> ${to}</div>
            ${cc ? `<div class="mb-2"><strong>CC:</strong> ${cc}</div>` : ''}
            <div class="mb-2"><strong>Subject:</strong> ${subject}</div>
            <hr>
            <div style="white-space: pre-line;">${message}</div>
        `;
        
        $('#emailPreview').html(preview);
    }

    function validateEmails($input) {
        const emails = $input.val().split(',').map(email => email.trim()).filter(email => email);
        const invalidEmails = [];
        
        emails.forEach(email => {
            if (email && !isValidEmail(email)) {
                invalidEmails.push(email);
            }
        });
        
        if (invalidEmails.length > 0) {
            $input.addClass('is-invalid');
            $input.siblings('.invalid-feedback').text(`Invalid email(s): ${invalidEmails.join(', ')}`);
            return false;
        } else {
            $input.removeClass('is-invalid').addClass('is-valid');
            return true;
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateAttachment(input) {
        const file = input.files[0];
        if (!file) return true;
        
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                             'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                             'image/jpeg', 'image/png', 'text/plain'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        if (!allowedTypes.includes(file.type)) {
            $(input).addClass('is-invalid');
            $(input).siblings('.invalid-feedback').text('Invalid file type. Please select a valid document or image file.');
            return false;
        }
        
        if (file.size > maxSize) {
            $(input).addClass('is-invalid');
            $(input).siblings('.invalid-feedback').text('File size too large. Maximum allowed size is 10MB.');
            return false;
        }
        
        $(input).removeClass('is-invalid').addClass('is-valid');
        return true;
    }

    function validateForm() {
        let isValid = true;
        
        // Validate required fields
        const requiredFields = ['#mailTo', '#mailSubject', '#mailMessage'];
        requiredFields.forEach(field => {
            const $field = $(field);
            if (!$field.val().trim()) {
                $field.addClass('is-invalid');
                $field.siblings('.invalid-feedback').text('This field is required');
                isValid = false;
            } else {
                $field.removeClass('is-invalid');
            }
        });
        
        // Validate emails
        if (!validateEmails($('#mailTo'))) isValid = false;
        if ($('#mailCC').val() && !validateEmails($('#mailCC'))) isValid = false;
        
        // Validate message length
        if ($('#mailMessage').val().length < 10) {
            $('#mailMessage').addClass('is-invalid');
            $('#mailMessage').siblings('.invalid-feedback').text('Message must be at least 10 characters long');
            isValid = false;
        }
        
        // Validate attachment
        if (!validateAttachment(document.getElementById('additionalAttachment'))) isValid = false;
        
        return isValid;
    }

    function sendEmail() {
        const formData = new FormData(document.getElementById('sendMailForm'));
        
        // Show loading state
        $('#sendEmailBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
        
        Swal.fire({
            title: 'Sending Email...',
            text: 'Please wait while we send your quotation email.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '<?= base_url('quotation/send_mail') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                const result = JSON.parse(response);
                Swal.close();
                
                if (result.status) {
                    Swal.fire({
                        title: 'Success!',
                        text: result.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#sendMailModal').modal('hide');
                        // Reset form
                        document.getElementById('sendMailForm').reset();
                        $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: result.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                $('#sendEmailBtn').prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Send Email');
            }
        });
    }

    // Global function to open email modal
    window.openEmailModal = function(quotationId, clientEmail) {
        currentQuotationId = quotationId;
        $('#mailQuotationId').val(quotationId);
        $('#mailTo').val(clientEmail || '');
        $('#mailSubject').val(`Quotation from HSAD India - #${quotationId}`);
        
        // Clear previous validation states
        $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
        
        $('#sendMailModal').modal('show');
    };
});
</script>
