<section class="content-header d-flex justify-content-between align-items-center">
  <h1>SMTP Mail Test</h1>
</section>

<section class="content">
  <div class="card shadow-sm">
    <div class="card-body">

      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
      <?php endif; ?>

      <form method="post" action="<?= site_url('mail-test/send') ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

        <div class="row">
          <div class="col-md-4 form-group">
            <label>SMTP Profile</label>
            <select name="profile" class="form-control" required>
              <option value="">Select Profile</option>
              <option value="godaddy">GoDaddy (smtpout.secureserver.net, 465 SSL)</option>
              <option value="gmail">Gmail (smtp.gmail.com, 587 TLS)</option>
            </select>
          </div>
          <div class="col-md-4 form-group">
            <label>To (Recipient Email)</label>
            <input type="email" name="to" class="form-control" placeholder="recipient@example.com" required>
          </div>
          <div class="col-md-4 form-group">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" value="SMTP Test - HSAD" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 form-group">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="6" required>Hi,

This is a test email from HSAD CRM.

- Environment: <?= ENVIRONMENT ?>
- Time: <?= date('Y-m-d H:i:s') ?>

Regards,
HSAD CRM</textarea>
          </div>
        </div>

        <div class="text-right">
          <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send Test Email</button>
        </div>
      </form>

      <hr>
      <p class="text-muted small">
        Notes:
        <br>- GoDaddy profile uses: smtpout.secureserver.net, user: billing@hsad.co.in, port 465 SSL
        <br>- Gmail profile uses: smtp.gmail.com, user: billing@hsadindia.com, port 587 TLS
        <br>- If Gmail fails with authentication, use an App Password and ensure less secure access is allowed by policy.
      </p>

    </div>
  </div>
</section>
