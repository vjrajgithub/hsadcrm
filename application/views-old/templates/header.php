<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : 'CRM Admin Panel' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- jQuery Validate -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <!-- Custom Responsive & Dark Mode Styles -->
    <style>
        .invalid-feedback {
            display: block;
            font-size: 0.875rem;
        }

        input.is-invalid,
        textarea.is-invalid,
        select.is-invalid {
            border-color: #dc3545;
            background-image: none;
        }

        .dark-mode {
            background-color: #1f2d3d;
            color: #c2c7d0;
        }

        .dark-mode .card {
            background-color: #2f3e52;
            color: #fff;
        }

        /* Mobile Responsive Fixes */
        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0 !important;
            }
            
            .main-sidebar {
                transform: translateX(-250px);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar-open .main-sidebar {
                transform: translateX(0);
            }
            
            .table-responsive {
                border: none;
            }
            
            .btn-group-vertical .btn {
                margin-bottom: 2px;
            }
            
            .card-header .btn {
                font-size: 0.875rem;
                padding: 0.25rem 0.5rem;
            }
            
            .small-box .inner h3 {
                font-size: 1.5rem;
            }
            
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .form-row .col-md-4,
            .form-row .col-md-6 {
                margin-bottom: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .btn-group .btn {
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }
            
            .table td, .table th {
                padding: 0.5rem 0.25rem;
                font-size: 0.875rem;
            }
            
            .content-header h1 {
                font-size: 1.5rem;
            }
        }
        
        /* DataTable Mobile Optimization */
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .dataTables_wrapper .dataTables_filter {
                float: none !important;
                text-align: left;
            }
            
            .dataTables_wrapper .dataTables_length {
                float: none !important;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <!-- Sidebar toggle button -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= site_url('dashboard') ?>" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Dark/Light Mode Toggle -->
                <li class="nav-item">
                    <a class="nav-link" href="#" id="themeToggle" title="Toggle Light/Dark Mode">
                        <i class="fas fa-adjust"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Sidebar -->
        <?php $this->load->view('templates/main_sidebar'); ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">