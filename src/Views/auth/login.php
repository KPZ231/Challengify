<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Challengify</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/tailwind.min.css">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full" data-aos="fade-up">
            <div class="text-center mb-8">
                <a href="/" class="flex items-center justify-center hover-scale">
                    <img class="h-12 w-12 mr-2 rounded-full shadow-sm" src="/assets/images/chellengify-logo.png" alt="Challengify Logo">
                    <span class="text-2xl font-bold text-gray-900">Challengify</span>
                </a>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Welcome back</h2>
                <p class="mt-2 text-gray-600">Login to your account</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md hover-card">
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form id="login-form" action="/login" method="post" class="space-y-6">
                    <div class="form-input-icon">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-envelope text-indigo-500 mr-2"></i>Email address
                        </label>
                        
                        <input type="email" id="email" name="email"
                            class="mt-1 block w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 input-focus-effect <?= isset($errors['email']) ? 'border-red-500' : '' ?>"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['email']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-input-icon">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-lock text-indigo-500 mr-2"></i>Password
                        </label>
              
                        <i class="fas fa-eye password-toggle" id="password-toggle"></i>
                        <input type="password" id="password" name="password"
                            class="mt-1 block w-full px-3 py-2 pl-10 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 input-focus-effect <?= isset($errors['password']) ? 'border-red-500' : '' ?>"
                            required>
                        <?php if (isset($errors['password'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['password']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 btn-gradient">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign in
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Register now</a>
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        <a href="/forgot-password" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                    </p>
                </div>
                
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="text-center text-sm text-gray-600">
                        <p>Sign in using your social accounts</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-google"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800
        });
        
        // Password visibility toggle
        document.getElementById('password-toggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
    <script src="/assets/javascript/validation.js"></script>
</body>
</html> 