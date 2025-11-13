<!-- Main Sidebar Container -->

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- <a href="<?= site_url('dashboard') ?>" class="brand-link text-center">
        <span class="brand-text font-weight-light">CRM</span>
    </a> -->

    <a href="<?= site_url('dashboard') ?>" class="brand-link">
        <img src="<?= base_url('assets/img/logo.svg') ?>" alt="CRM Logo" class="brand-image  logo-d">

        <span class="brand-text font-weight-light">CRM Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <?php if ($this->session->userdata('logged_in')): ?>
          <!-- User panel -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
              <div class="image">
                  <img src="<?= base_url('assets/img/user.png') ?>" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
                  <a href="#" class="d-block font-weight-bold"><?= $this->session->userdata('user_name') ?></a>
                  <small class="text-white-50"><?= ucfirst($this->session->userdata('user_role')) ?></small>
              </div>
          </div>
        <?php endif; ?>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= site_url('dashboard') ?>"
                       class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Company -->
                <li class="nav-header text-uppercase text-muted">Company</li>
                <li class="nav-item">
                    <a href="<?= site_url('company') ?>"
                       class="nav-link <?= uri_string() == 'company' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Company Profile</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('bank') ?>"
                       class="nav-link <?= uri_string() == 'bank' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-university"></i>
                        <p>Bank Details</p>
                    </a>
                </li>

                <!-- Client -->
                <li class="nav-header text-uppercase text-muted">Client</li>
                <li class="nav-item">
                    <a href="<?= site_url('clients') ?>"
                       class="nav-link <?= $this->uri->segment(1) === 'clients' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Client Details</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('contacts') ?>"
                       class="nav-link <?= $this->uri->segment(1) === 'contacts' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>Client Contacts</p>
                    </a>
                </li>

                <!-- Master Setup -->
                <li class="nav-header text-uppercase text-muted">Master Setup</li>
                <li class="nav-item">
                    <a href="<?= site_url('mode') ?>"
                       class="nav-link <?= $this->uri->segment(1) === 'mode' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Modes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('category') ?>"
                       class="nav-link <?= $this->uri->segment(1) === 'category' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Client Categories</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('product-category') ?>"
                       class="nav-link <?= $this->uri->segment(1) === 'product-category' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Product/Service Categories</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('productservice') ?>"
                       class="nav-link <?= $this->uri->segment(1) === 'productservice' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>Product/Service</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('quotation') ?>"
                       class="nav-link <?= $this->uri->segment(1) === 'quotation' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Quotations</p>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-header text-uppercase text-muted">Settings</li>
                <!--<li class="nav-item">-->
                <!--    <a href="<?= site_url('user') ?>"-->
                <!--       class="nav-link <?= uri_string() == 'user' ? 'active' : '' ?>">-->
                <!--        <i class="nav-icon fas fa-user-cog"></i>-->
                <!--        <p>User Management</p>-->
                <!--    </a>-->
                <!--</li>-->

                <?php if ($this->session->userdata('user_role') === 'Super Admin'): ?>
                  <!-- User Management (Super Admin Only) -->
                  <li class="nav-item">
                      <a href="<?= base_url('user') ?>" class="nav-link <?= $this->uri->segment(1) == 'user' ? 'active' : '' ?>">
                          <i class="nav-icon fas fa-users"></i>
                          <p>User Management</p>
                      </a>
                  </li>
                  <!-- System Settings (Super Admin Only) -->
                  <li class="nav-item">
                      <a href="<?= base_url('settings') ?>" class="nav-link <?= $this->uri->segment(1) == 'settings' ? 'active' : '' ?>">
                          <i class="nav-icon fas fa-cogs"></i>
                          <p>System Settings</p>
                      </a>
                  </li>
                <?php endif; ?>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="<?= site_url('auth/logout') ?>" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>