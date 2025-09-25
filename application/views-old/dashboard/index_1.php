<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <div class="row">

            <!-- Companies -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $company_count ?></h3>
                        <p>Companies</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="<?= site_url('company') ?>" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Clients -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $client_count ?></h3>
                        <p>Clients</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="<?= site_url('client') ?>" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Users -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $user_count ?></h3>
                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <a href="<?= site_url('user') ?>" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Bank Details -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $bank_count ?></h3>
                        <p>Bank Details</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <a href="<?= site_url('bank') ?>" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>
    </div>
</section>
