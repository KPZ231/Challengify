<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Challengify</title>
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
                    <li class="nav-item ms-2"><a class="btn btn-light text-primary fw-bold" href="/login">Login</a></li>
                    <li class="nav-item ms-2"><a class="btn btn-outline-light fw-bold active" href="/register">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Registration Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card border-0 shadow-sm rounded-lg">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="text-center fw-bold mb-4">Create Your Account</h2>
                            <p class="text-center text-muted mb-4">Join Challengify to boost your creativity and skills with daily micro-challenges</p>
                            
                            <!-- Display Error Messages -->
                            <?php if (isset($_SESSION['error_message'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php 
                                        echo htmlspecialchars($_SESSION['error_message']); 
                                        unset($_SESSION['error_message']);
                                    ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Registration Form -->
                            <form id="registerForm" action="/register" method="post" class="needs-validation" novalidate>
                                <!-- CSRF Token -->
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                
                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>"
                                               required minlength="3" maxlength="50" pattern="^[a-zA-Z0-9_-]+$">
                                        <div class="invalid-feedback">
                                            Username must be 3-50 characters and can only contain letters, numbers, hyphens, and underscores.
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>"
                                               required>
                                        <div class="invalid-feedback">
                                            Please enter a valid email address.
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               required minlength="8">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="invalid-feedback">
                                            Password must be at least 8 characters long.
                                        </div>
                                    </div>
                                    <div class="password-strength mt-2 d-none">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <div class="mt-1">
                                            <small class="text-muted">Password strength: <span class="strength-text">Very weak</span></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirm" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                        <div class="invalid-feedback">
                                            Passwords do not match.
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Terms and Conditions -->
                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="/terms" class="text-primary">Terms of Service</a> and <a href="/privacy" class="text-primary">Privacy Policy</a>
                                    </label>
                                    <div class="invalid-feedback">
                                        You must agree to the terms and conditions.
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
                                </div>
                                
                                <!-- Login Link -->
                                <div class="text-center mt-4">
                                    <p class="mb-0">Already have an account? <a href="/login" class="text-primary">Login</a></p>
                                </div>
                            </form>
                            
                            <?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
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
        
        // Get password fields
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirm');
        const passwordStrengthBar = document.querySelector('.password-strength .progress-bar');
        const passwordStrengthText = document.querySelector('.password-strength .strength-text');
        const passwordStrengthContainer = document.querySelector('.password-strength');
        
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
        
        // Password strength checker
        if (password) {
            password.addEventListener('input', function() {
                const val = this.value;
                passwordStrengthContainer.classList.remove('d-none');
                
                // Check password strength
                let strength = 0;
                let text = '';
                let color = '';
                
                if (val.length >= 8) strength += 1;
                if (val.length >= 12) strength += 1;
                if (/[A-Z]/.test(val)) strength += 1;
                if (/[0-9]/.test(val)) strength += 1;
                if (/[^A-Za-z0-9]/.test(val)) strength += 1;
                
                switch (strength) {
                    case 0:
                    case 1:
                        text = 'Very weak';
                        color = '#dc3545'; // red
                        break;
                    case 2:
                        text = 'Weak';
                        color = '#ffc107'; // yellow
                        break;
                    case 3:
                        text = 'Medium';
                        color = '#fd7e14'; // orange
                        break;
                    case 4:
                        text = 'Strong';
                        color = '#20c997'; // teal
                        break;
                    case 5:
                        text = 'Very strong';
                        color = '#198754'; // green
                        break;
                }
                
                // Update UI
                passwordStrengthBar.style.width = (strength * 20) + '%';
                passwordStrengthBar.style.backgroundColor = color;
                passwordStrengthText.textContent = text;
                passwordStrengthText.style.color = color;
            });
        }
        
        // Check if passwords match
        if (confirmPassword) {
            confirmPassword.addEventListener('input', function() {
                if (this.value !== password.value) {
                    this.setCustomValidity('Passwords do not match');
                } else {
                    this.setCustomValidity('');
                }
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