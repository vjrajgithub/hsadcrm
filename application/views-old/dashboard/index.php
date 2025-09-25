<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <small class="text-muted">Welcome back, <?= $this->session->userdata('user_name') ?? 'Admin' ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        <!-- Summary Cards -->
        <div class="row">
            <!-- Companies -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $total_companies ?></h3>
                        <p>Companies</p>
                    </div>
                    <div class="icon"><i class="fas fa-building"></i></div>
                    <a href="<?= site_url('company') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Clients -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $total_clients ?></h3>
                        <p>Clients</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <a href="<?= site_url('client') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Products -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $total_products ?></h3>
                        <p>Products/Services</p>
                    </div>
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <a href="<?= site_url('product-service') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Users -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $total_users ?></h3>
                        <p>Users</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-cog"></i></div>
                    <a href="<?= site_url('user') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Users by Role -->
            <div class="col-lg-6 col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Users by Role</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-responsive">
                            <canvas id="userRoleChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clients by Company -->
            <div class="col-lg-6 col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Clients by Company</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-responsive">
                            <canvas id="clientsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
      // Users by Role Chart
      const userCtx = document.getElementById('userRoleChart').getContext('2d');
      const userRoles = <?= json_encode($user_roles) ?>;
      new Chart(userCtx, {
          type: 'bar',
          data: {
              labels: Object.keys(userRoles),
              datasets: [{
                      label: 'User Count',
                      data: Object.values(userRoles),
                      backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#dc3545']
                  }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: {display: false}
              },
              scales: {
                  y: {
                      beginAtZero: true,
                      ticks: {
                          stepSize: 1
                      }
                  }
              }
          }
      });

      // Clients by Company Chart
      const clientCtx = document.getElementById('clientsChart').getContext('2d');
      const clientsByCompany = <?= json_encode($clients_by_company) ?>;
      new Chart(clientCtx, {
          type: 'pie',
          data: {
              labels: Object.keys(clientsByCompany),
              datasets: [{
                      data: Object.values(clientsByCompany),
                      backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8']
                  }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: {
                      position: 'bottom',
                      labels: {
                          usePointStyle: true,
                          padding: 20
                      }
                  }
              }
          }
      });
  });
</script>

