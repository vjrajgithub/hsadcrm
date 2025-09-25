<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
        <span class="brand-text font-weight-light ml-2">Invoice Admin</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block"><?= ucfirst($this->session->userdata('user')['username']) ?></a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-item"><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>

                <?php if (in_array($this->session->userdata('user')['role'], ['super_admin', 'admin'])): ?>
                  <li class="nav-item"><a href="<?= base_url('invoice') ?>" class="nav-link"><i class="nav-icon fas fa-file-invoice"></i><p>Invoices</p></a></li>
                  <li class="nav-item"><a href="<?= base_url('quotation') ?>" class="nav-link"><i class="nav-icon fas fa-quote-right"></i><p>Quotations</p></a></li>
                <?php endif; ?>

                <?php if ($this->session->userdata('user')['role'] === 'super_admin'): ?>
                  <li class="nav-item"><a href="<?= base_url('users') ?>" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Users</p></a></li>
                  <li class="nav-item"><a href="<?= base_url('permissions') ?>" class="nav-link"><i class="nav-icon fas fa-key"></i><p>Permissions</p></a></li>
                  <li class="nav-item"><a href="<?= base_url('settings') ?>" class="nav-link"><i class="nav-icon fas fa-cogs"></i><p>Settings</p></a></li>
                            <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>
