<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login | CRM</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
        <!-- AdminLTE -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo"><b>CRM</b> Login</div>
            <div class="card">
                <div class="card-body login-card-body">
                    <?php if ($this->session->flashdata('error')): ?>
                      <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>
                    <form action="<?= base_url('login') ?>" method="post">
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                            <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE -->
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    </body>
</html>
