<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= site_url('dashboard') ?>" class="brand-link">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="CRM Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light d-none d-sm-inline">CRM System</span>
        <span class="brand-text font-weight-light d-sm-none">CRM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('assets/img/user.jpg') ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info d-none d-sm-block">
                <a href="#" class="d-block"><?= $this->session->userdata('user_name') ?? 'Admin User' ?></a>
                <small class="text-light"><?= $this->session->userdata('user_role') ?? 'Super Admin' ?></small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= site_url('dashboard') ?>" class="nav-link <?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <span class="d-sm-none"></span>
                        </p>
                    </a>
                </li>

                <!-- Company Management -->
                <li class="nav-item">
                    <a href="<?= site_url('company') ?>" class="nav-link <?= $this->uri->segment(1) == 'company' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            <span class="d-none d-sm-inline">Company Management</span>
                            <span class="d-sm-none">Companies</span>
                        </p>
                    </a>
                </li>

                <!-- Client Management -->
                <li class="nav-item">
                    <a href="<?= site_url('clients') ?>" class="nav-link <?= $this->uri->segment(1) == 'clients' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            <span class="d-none d-sm-inline">Client Management</span>
                            <span class="d-sm-none">Clients</span>
                        </p>
                    </a>
                </li>

                <!-- Bank Management -->
                <li class="nav-item">
                    <a href="<?= site_url('bank') ?>" class="nav-link <?= $this->uri->segment(1) == 'bank' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-university"></i>
                        <p>
                            <span class="d-none d-sm-inline">Bank Management</span>
                            <span class="d-sm-none">Banks</span>
                        </p>
                    </a>
                </li>

                <!-- Master Setup -->
                <li class="nav-header text-uppercase text-muted">Master Setup</li>
                <li class="nav-item">
                    <a href="<?= site_url('mode') ?>" class="nav-link <?= $this->uri->segment(1) === 'mode' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Modes</p>
                    </a>
                </li>

                <!-- Product/Service Categories -->
                <li class="nav-item">
                    <a href="<?= site_url('product-category') ?>" class="nav-link <?= $this->uri->segment(1) == 'product-category' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            <span class="d-none d-sm-inline">Product Categories</span>
                            <span class="d-sm-none">Categories</span>
                        </p>
                    </a>
                </li>

                <!-- Product/Service Management -->
                <li class="nav-item">
                    <a href="<?= site_url('product-service') ?>" class="nav-link <?= $this->uri->segment(1) == 'product-service' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            <span class="d-none d-sm-inline">Products/Services</span>
                            <span class="d-sm-none">Products</span>
                        </p>
                    </a>
                </li>

                <!-- User Management -->
                <li class="nav-item">
                    <a href="<?= site_url('user') ?>" class="nav-link <?= $this->uri->segment(1) == 'user' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            <span class="d-none d-sm-inline">User Management</span>
                            <span class="d-sm-none">Users</span>
                        </p>
                    </a>
                </li>

                <!-- Quotation Management -->
                <li class="nav-item">
                    <a href="<?= site_url('quotation') ?>" class="nav-link <?= $this->uri->segment(1) == 'quotation' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Quotations</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="<?= site_url('auth/logout') ?>" class="nav-link" onclick="return confirm('Are you sure you want to logout?')">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            <span class="d-none d-sm-inline">Logout</span>
                            <span class="d-sm-none">Exit</span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>