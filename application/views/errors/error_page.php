<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : 'Error' ?> | CRM System</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/admin-lte/dist/css/adminlte.min.css') ?>">
    <!-- Custom Error Styles -->
    <style>
        .error-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .error-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 600px;
            width: 90%;
            margin: 2rem;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.25rem;
            color: #5a6c7d;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .error-description {
            font-size: 1rem;
            color: #7f8c8d;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-error {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary-error {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary-error:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-secondary-error {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary-error:hover {
            background: #667eea;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .error-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        
        .icon-403 { color: #e74c3c; }
        .icon-404 { color: #f39c12; }
        .icon-500 { color: #e67e22; }
        .icon-503 { color: #9b59b6; }
        
        /* Enhanced Mobile Responsiveness */
        @media (max-width: 768px) {
            .error-page {
                padding: 1rem;
                min-height: 100vh;
            }
            
            .error-content {
                padding: 2rem 1.5rem;
                margin: 1rem;
                max-width: 95%;
                width: 95%;
            }
            
            .error-code { 
                font-size: 4rem; 
                margin-bottom: 0.5rem;
            }
            
            .error-title { 
                font-size: 1.8rem; 
                margin-bottom: 0.8rem;
                line-height: 1.2;
            }
            
            .error-message {
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }
            
            .error-description {
                font-size: 0.95rem;
                margin-bottom: 2rem;
                line-height: 1.5;
            }
            
            .error-actions { 
                flex-direction: column;
                gap: 0.8rem;
            }
            
            .btn-error {
                width: 100%;
                justify-content: center;
                padding: 14px 20px;
                font-size: 1rem;
            }
            
            .error-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
            }
            
            /* Role-specific info responsive */
            .role-info-mobile {
                margin-top: 1.5rem;
                padding: 1rem;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 12px;
                border-left: 4px solid #667eea;
            }
            
            .role-badge-mobile {
                background: linear-gradient(45deg, #667eea, #764ba2);
                color: white;
                padding: 0.4rem 0.8rem;
                border-radius: 15px;
                font-weight: 600;
                font-size: 0.8rem;
                display: inline-block;
                margin-bottom: 0.8rem;
            }
            
            .permissions-list-mobile {
                color: #34495e;
                line-height: 1.5;
                font-size: 0.9rem;
            }
        }
        
        /* Tablet Responsiveness */
        @media (max-width: 1024px) and (min-width: 769px) {
            .error-content {
                padding: 2.5rem;
                max-width: 80%;
            }
            
            .error-code { 
                font-size: 6rem; 
            }
            
            .error-title { 
                font-size: 2.2rem; 
            }
            
            .error-actions {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .btn-error {
                min-width: 140px;
            }
        }
        
        /* Small Mobile Devices */
        @media (max-width: 480px) {
            .error-content {
                padding: 1.5rem 1rem;
                margin: 0.5rem;
                border-radius: 15px;
            }
            
            .error-code { 
                font-size: 3.5rem; 
            }
            
            .error-title { 
                font-size: 1.5rem; 
            }
            
            .error-message {
                font-size: 1rem;
            }
            
            .error-description {
                font-size: 0.9rem;
            }
            
            .btn-error {
                padding: 12px 16px;
                font-size: 0.95rem;
            }
            
            .error-icon {
                font-size: 2.5rem;
            }
            
            .floating-shapes {
                display: none; /* Hide decorative elements on very small screens */
            }
        }
        
        /* Landscape Mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            .error-page {
                padding: 0.5rem;
            }
            
            .error-content {
                padding: 1.5rem;
                margin: 0.5rem;
            }
            
            .error-code { 
                font-size: 3rem; 
                margin-bottom: 0.5rem;
            }
            
            .error-title { 
                font-size: 1.4rem; 
                margin-bottom: 0.5rem;
            }
            
            .error-message {
                font-size: 1rem;
                margin-bottom: 0.8rem;
            }
            
            .error-description {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }
            
            .error-icon {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }
        }
        
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { top: 20%; right: 10%; animation-delay: 2s; }
        .shape:nth-child(3) { bottom: 10%; left: 20%; animation-delay: 4s; }
        .shape:nth-child(4) { bottom: 20%; right: 20%; animation-delay: 1s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body class="error-page">
    
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"><i class="fas fa-circle" style="font-size: 3rem;"></i></div>
        <div class="shape"><i class="fas fa-square" style="font-size: 2.5rem;"></i></div>
        <div class="shape"><i class="fas fa-triangle" style="font-size: 3.5rem;"></i></div>
        <div class="shape"><i class="fas fa-star" style="font-size: 2rem;"></i></div>
    </div>

    <div class="error-content">
        <!-- Error Icon -->
        <?php
        $icon_class = 'fas fa-exclamation-triangle icon-500';
        switch($error_code) {
            case '403':
                $icon_class = 'fas fa-lock icon-403';
                break;
            case '404':
                $icon_class = 'fas fa-search icon-404';
                break;
            case '500':
                $icon_class = 'fas fa-server icon-500';
                break;
            case '503':
                $icon_class = 'fas fa-tools icon-503';
                break;
        }
        ?>
        <div class="error-icon">
            <i class="<?= $icon_class ?>"></i>
        </div>
        
        <!-- Error Code -->
        <div class="error-code"><?= $error_code ?></div>
        
        <!-- Error Title -->
        <h1 class="error-title"><?= $error_title ?></h1>
        
        <!-- Error Message -->
        <p class="error-message"><?= $error_message ?></p>
        
        <!-- Error Description -->
        <p class="error-description"><?= $error_description ?></p>
        
        <!-- Action Buttons -->
        <div class="error-actions">
            <?php if (isset($show_back_button) && $show_back_button): ?>
                <button onclick="history.back()" class="btn-error btn-secondary-error">
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </button>
            <?php endif; ?>
            
            <a href="<?= base_url('dashboard') ?>" class="btn-error btn-primary-error">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            
            <?php if ($error_code === '403'): ?>
                <a href="<?= base_url('auth/logout') ?>" class="btn-error btn-secondary-error">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Role-specific Information -->
        <?php if (isset($user_role) && $user_role): ?>
            <div class="role-info-container" style="margin-top: 2rem; padding: 1.5rem; background: rgba(255, 255, 255, 0.8); border-radius: 15px; border-left: 5px solid #667eea;">
                <div class="role-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                    <div class="role-badge" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                        <?= ucwords($user_role) ?>
                    </div>
                    <?php if (isset($user_name) && $user_name): ?>
                        <span class="user-name" style="color: #5a6c7d; font-weight: 500; word-break: break-word;"><?= htmlspecialchars($user_name) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="permissions-list" style="color: #34495e; line-height: 1.6;">
                    <strong>Your Current Permissions:</strong><br>
                    <div class="permissions-content">
                        <?php
                        switch(strtolower($user_role)) {
                            case 'viewer':
                                echo "<div class='permission-item'>• View dashboard and analytics</div>";
                                echo "<div class='permission-item'>• View companies, clients, and products</div>";
                                echo "<div class='permission-item'>• View quotations and reports</div>";
                                echo "<div class='permission-item denied'>• <span style='color: #e74c3c;'>✗ Cannot modify or delete data</span></div>";
                                echo "<div class='permission-item denied'>• <span style='color: #e74c3c;'>✗ Cannot access user management</span></div>";
                                break;
                            case 'admin':
                                echo "<div class='permission-item'>• Full CRUD access to most modules</div>";
                                echo "<div class='permission-item'>• Manage companies, clients, products</div>";
                                echo "<div class='permission-item'>• Create and manage quotations</div>";
                                echo "<div class='permission-item'>• View and add users</div>";
                                echo "<div class='permission-item denied'>• <span style='color: #e74c3c;'>✗ Cannot delete users</span></div>";
                                echo "<div class='permission-item denied'>• <span style='color: #e74c3c;'>✗ Limited system configuration access</span></div>";
                                break;
                            case 'super admin':
                                echo "<div class='permission-item'>• Full system access</div>";
                                echo "<div class='permission-item'>• Complete user management</div>";
                                echo "<div class='permission-item'>• System configuration</div>";
                                echo "<div class='permission-item'>• All administrative functions</div>";
                                break;
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <style>
                @media (max-width: 768px) {
                    .role-info-container {
                        margin-top: 1.5rem !important;
                        padding: 1rem !important;
                        border-radius: 12px !important;
                    }
                    
                    .role-header {
                        flex-direction: column !important;
                        align-items: flex-start !important;
                        gap: 0.5rem !important;
                    }
                    
                    .role-badge {
                        padding: 0.4rem 0.8rem !important;
                        font-size: 0.8rem !important;
                        border-radius: 15px !important;
                    }
                    
                    .user-name {
                        font-size: 0.9rem !important;
                    }
                    
                    .permissions-list {
                        font-size: 0.9rem !important;
                    }
                    
                    .permission-item {
                        margin-bottom: 0.3rem;
                        word-wrap: break-word;
                    }
                }
                
                @media (max-width: 480px) {
                    .role-info-container {
                        padding: 0.8rem !important;
                        margin-top: 1rem !important;
                    }
                    
                    .role-badge {
                        padding: 0.3rem 0.6rem !important;
                        font-size: 0.75rem !important;
                    }
                    
                    .permissions-list {
                        font-size: 0.85rem !important;
                    }
                    
                    .permission-item {
                        font-size: 0.85rem;
                        line-height: 1.4;
                    }
                }
            </style>
        <?php endif; ?>
        
        <!-- Additional Info for Development -->
        <?php if (ENVIRONMENT === 'development'): ?>
            <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; text-align: left;">
                <small style="color: #6c757d;">
                    <strong>Debug Info:</strong><br>
                    User Role: <?= $this->session->userdata('user_role') ?? 'Not logged in' ?><br>
                    User ID: <?= $this->session->userdata('user_id') ?? 'N/A' ?><br>
                    Current URL: <?= current_url() ?><br>
                    Timestamp: <?= date('Y-m-d H:i:s') ?>
                </small>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/node_modules/admin-lte/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
