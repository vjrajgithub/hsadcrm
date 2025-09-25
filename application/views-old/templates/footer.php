    <!-- /.content-wrapper -->
    <footer class="main-footer text-sm">
        <strong>&copy; <?= date('Y') ?> <a href="<?= site_url() ?>">CRM System</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
        </div>
    </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery is loaded in templates/header.php -->
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- OPTIONAL PLUGINS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(function() {
            // Enable sidebar toggle on mobile (hamburger menu)
            $('[data-widget="pushmenu"]').PushMenu();

            // Initialize DataTables with responsive options (only if not already initialized)
            $('.table:not(.dataTable)').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        responsive: {
                            details: {
                                type: 'column',
                                target: 'tr'
                            }
                        },
                        autoWidth: false,
                        columnDefs: [
                            { className: 'control', orderable: false, targets: 0 }
                        ],
                        order: [1, 'asc']
                    });
                }
            });

            // Mobile sidebar toggle enhancement
            $('[data-widget="pushmenu"]').on('click', function() {
                $('body').toggleClass('sidebar-open');
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() <= 768) {
                    if (!$(e.target).closest('.main-sidebar, [data-widget="pushmenu"]').length) {
                        $('body').removeClass('sidebar-open');
                    }
                }
            });
        });
    </script>

    </body>

    </html>