<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Challengify</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS - Production Version -->
    <link href="/assets/css/vendor/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-trophy me-2"></i>Challengify
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#categories">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#how-it-works">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#community">Community</a></li>
                    <li class="nav-item ms-2"><a class="btn btn-light text-primary fw-bold active" href="/login">Login</a></li>
                    <li class="nav-item ms-2"><a class="btn btn-outline-light fw-bold" href="/register">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="text-center fw-bold mb-4">Welcome Back</h2>
                            <p class="text-center text-muted mb-4">Log in to continue your creative journey</p>
                            
                            <!-- Display Messages -->
                            <?php if (isset($_SESSION['error_message'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php 
                                        echo htmlspecialchars($_SESSION['error_message']); 
                                        unset($_SESSION['error_message']);
                                    ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['success_message'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php 
                                        echo htmlspecialchars($_SESSION['success_message']); 
                                        unset($_SESSION['success_message']);
                                    ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Login Form -->
                            <form id="loginForm" action="/login" method="post" class="needs-validation" novalidate>
                                <!-- CSRF Token -->
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                
                                <!-- Email/Username -->
                                <div class="mb-3">
                                    <label for="login_id" class="form-label">Email or Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="login_id" name="login_id" required>
                                        <div class="invalid-feedback">
                                            Please enter your email or username.
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="invalid-feedback">
                                            Please enter your password.
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Remember Me -->
                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="/forgot-password" class="text-primary small">Forgot password?</a>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                                </div>
                                
                                <!-- Register Link -->
                                <div class="text-center">
                                    <p class="mb-0">Don't have an account? <a href="/register" class="text-primary">Create one</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-3">Challengify</h5>
                    <p>Daily micro-challenges to boost creativity, build healthy habits, and develop new skills.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">About</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">How It Works</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Testimonials</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="fw-bold mb-3">Get Started</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/register" class="text-white text-decoration-none">Sign Up</a></li>
                        <li class="mb-2"><a href="/login" class="text-white text-decoration-none">Login</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Today's Challenges</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">© 2023 Challengify. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white text-decoration-none small me-3">Privacy</a>
                    <a href="#" class="text-white text-decoration-none small me-3">Terms</a>
                    <a href="#" class="text-white text-decoration-none small">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Client-side validation script -->
    <script>
    (function() {
        'use strict';
        
        // Get all forms that need validation
        const forms = document.querySelectorAll('.needs-validation');
        
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
        
        // Form validation loop
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    })();
    </script>
</body>
</html> 