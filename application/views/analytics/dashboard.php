<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - CRM System</title>
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/admin/css/all.min.css') ?>">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    
    <!-- Content Wrapper -->
    <div class="content-wrapper" style="margin-left: 0;">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Analytics Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                            <li class="breadcrumb-item active">Analytics</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                
                <!-- System Health Overview -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= isset($stats['total_users']) ? $stats['total_users'] : '0' ?></h3>
                                <p>Total Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= isset($stats['total_clients']) ? $stats['total_clients'] : '0' ?></h3>
                                <p>Total Clients</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?= isset($stats['total_quotations']) ? $stats['total_quotations'] : '0' ?></h3>
                                <p>Total Quotations</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= isset($security['failed_logins']) ? $security['failed_logins'] : '0' ?></h3>
                                <p>Failed Logins (24h)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-tachometer-alt mr-1"></i>
                                    Performance Metrics
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage text-success">
                                                <i class="fas fa-caret-up"></i> 
                                                <?= isset($performance['avg_response_time']) ? number_format($performance['avg_response_time'], 2) : '0.00' ?>ms
                                            </span>
                                            <h5 class="description-header">Avg Response Time</h5>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="description-block">
                                            <span class="description-percentage text-info">
                                                <i class="fas fa-database"></i> 
                                                <?= isset($performance['db_queries']) ? $performance['db_queries'] : '0' ?>
                                            </span>
                                            <h5 class="description-header">DB Queries/Page</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-lock mr-1"></i>
                                    Security Status
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="description-block border-right">
                                            <span class="description-percentage text-success">
                                                <i class="fas fa-check-circle"></i> 
                                                <?= isset($security['security_score']) ? $security['security_score'] : '10' ?>/10
                                            </span>
                                            <h5 class="description-header">Security Score</h5>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="description-block">
                                            <span class="description-percentage text-warning">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                <?= isset($security['threats_blocked']) ? $security['threats_blocked'] : '0' ?>
                                            </span>
                                            <h5 class="description-header">Threats Blocked</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Health Report -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-heartbeat mr-1"></i>
                                    System Health Report
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if (isset($health_report) && is_array($health_report)): ?>
                                    <?php foreach ($health_report as $check => $status): ?>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <strong><?= ucfirst(str_replace('_', ' ', $check)) ?>:</strong>
                                            </div>
                                            <div class="col-6">
                                                <?php if ($status === 'OK' || $status === true): ?>
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> OK
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times"></i> <?= is_string($status) ? $status : 'Error' ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        Analytics data will be available after running the database optimization script.
                                        <br><br>
                                        <strong>Next Steps:</strong>
                                        <ol>
                                            <li>Run <code>database_optimization_minimal.sql</code> in phpMyAdmin</li>
                                            <li>Refresh this page to see full analytics</li>
                                        </ol>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-tools mr-1"></i>
                                    Quick Actions
                                </h3>
                            </div>
                            <div class="card-body">
                                <a href="<?= base_url('system_test') ?>" class="btn btn-primary">
                                    <i class="fas fa-vial"></i> Run System Test
                                </a>
                                <a href="<?= base_url() ?>" class="btn btn-secondary">
                                    <i class="fas fa-home"></i> Back to CRM
                                </a>
                                <button class="btn btn-info" onclick="location.reload()">
                                    <i class="fas fa-sync-alt"></i> Refresh Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<script src="<?= base_url('assets/admin/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/adminlte.min.js') ?>"></script>
</body>
</html>
