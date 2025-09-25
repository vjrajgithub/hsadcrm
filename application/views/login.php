<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link href="<?= base_url('assets/bootstrap.min.css') ?>" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-4 card p-4 shadow-sm">
                    <h4 class="mb-4 text-center">Login</h4>

                    <?php if ($this->session->flashdata('error')): ?>
                      <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="form-group">
                            <label>Username</label>
                            <input name="username" class="form-control" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary mt-3 w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
