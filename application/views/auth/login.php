<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | HSAD CRM System</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/admin-lte/dist/css/adminlte.min.css') ?>">
    
    <style>
        .login-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            margin: 2rem;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo h1 {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .login-logo p {
            color: #7f8c8d;
            font-size: 1rem;
            margin: 0;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 1rem;
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
        
        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-password a:hover {
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
            .login-box {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 15px;
            }
            
            .login-logo h1 {
                font-size: 2rem;
            }
            
            .login-logo p {
                font-size: 0.9rem;
            }
            
            .logo-img {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .login-box {
                padding: 1.5rem 1rem;
                margin: 0.5rem;
            }
            
            .login-logo h1 {
                font-size: 1.8rem;
            }
            
            .logo-container {
                gap: 10px;
            }
        }
    </style>
</head>
<body class="login-page">
    
    <div class="login-box">
        <div class="login-logo">
            <div class="logo-container">
                <div class="logo-img">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h1>HSAD CRM</h1>
                    <p>Customer Relationship Management</p>
                </div>
            </div>
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
        
        <form method="post" action="<?= base_url('login') ?>">
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef; border-right: none; background: #f8f9fa;">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                    <input type="email" name="email" class="form-control" style="border-radius: 0 10px 10px 0; border-left: none;" placeholder="Enter your email address" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef; border-right: none; background: #f8f9fa;">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <input type="password" name="password" class="form-control" style="border-radius: 0 10px 10px 0; border-left: none;" placeholder="Enter your password" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>
        
        <div class="forgot-password">
            <a href="<?= base_url('auth/forgot_password') ?>">
                <i class="fas fa-key"></i>
                I forgot my password
            </a>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="<?= base_url('assets/node_modules/admin-lte/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
