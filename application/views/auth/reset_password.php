<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | CRM System</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/admin-lte/dist/css/adminlte.min.css') ?>">
    
    <style>
        .reset-password-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .reset-password-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            margin: 2rem;
        }
        
        .reset-password-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }
        
        .logo-img {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .reset-password-logo h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .reset-password-logo p {
            color: #7f8c8d;
            font-size: 1rem;
            margin: 0;
        }
        
        .page-title {
            text-align: center;
            margin-top: 1rem;
        }
        
        .page-title h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .page-title p {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .email-info {
            background: rgba(102, 126, 234, 0.1);
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            color: #495057;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .password-requirements {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-to-login a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .back-to-login a:hover {
            color: #764ba2;
            text-decoration: none;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 15px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .reset-password-box {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 15px;
            }
            
            .reset-password-logo h1 {
                font-size: 1.8rem;
            }
            
            .reset-password-logo p {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            .reset-password-box {
                padding: 1.5rem 1rem;
                margin: 0.5rem;
            }
            
            .reset-password-logo h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body class="reset-password-page">
    
    <div class="reset-password-box">
        <div class="reset-password-logo">
            <div class="logo-container">
                <div class="logo-img">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h1>HSAD CRM</h1>
                    <p>Customer Relationship Management</p>
                </div>
            </div>
            <div class="page-title">
                <h2><i class="fas fa-lock"></i> Reset Password</h2>
                <p>Create a new password for your account</p>
            </div>
        </div>
        
        <!-- Email Info -->
        <div class="email-info">
            <i class="fas fa-user"></i>
            <strong>Account:</strong> <?= htmlspecialchars($email) ?>
        </div>
        
        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?= base_url('auth/reset_password/' . $token) ?>" id="resetForm">
            <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef; border-right: none; background: #f8f9fa;">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" name="password" class="form-control" style="border-radius: 0 10px 10px 0; border-left: none;" placeholder="Enter new password" required minlength="6">
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef; border-right: none; background: #f8f9fa;">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" name="confirm_password" class="form-control" style="border-radius: 0 10px 10px 0; border-left: none;" placeholder="Confirm new password" required minlength="6">
                </div>
            </div>
            
            <div class="password-requirements">
                <strong>Password Requirements:</strong><br>
                • Minimum 6 characters<br>
                • Both passwords must match<br>
                • Use a strong, unique password
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Reset Password
            </button>
        </form>
        
        <div class="back-to-login">
            <a href="<?= base_url('login') ?>">
                <i class="fas fa-arrow-left"></i>
                Back to Login
            </a>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="<?= base_url('assets/node_modules/admin-lte/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Password confirmation validation
            $('#resetForm').on('submit', function(e) {
                var password = $('input[name="password"]').val();
                var confirmPassword = $('input[name="confirm_password"]').val();
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long!');
                    return false;
                }
            });
            
            // Real-time password match validation
            $('input[name="confirm_password"]').on('keyup', function() {
                var password = $('input[name="password"]').val();
                var confirmPassword = $(this).val();
                
                if (confirmPassword && password !== confirmPassword) {
                    $(this).css('border-color', '#dc3545');
                } else {
                    $(this).css('border-color', '#e9ecef');
                }
            });
        });
    </script>
</body>
</html>
