<?php

use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController {
    
    public function showRegister() {
        require_once __DIR__ . '/../Views/register.php';
    }
    
    public function register() {
        $request = Request::createFromGlobals();
        $response = new Response();
        
        // Verify CSRF token
        if (!hash_equals($_SESSION['csrf_token'], $request->request->get('csrf_token'))) {
            $_SESSION['error_message'] = 'Invalid CSRF token';
            $response = new RedirectResponse('/register');
            $response->send();
            return;
        }
        
        // Debug - Log environment variables for email configuration
        error_log("DEBUG - Email Configuration:");
        error_log("MAIL_HOST: " . ($_ENV['MAIL_HOST'] ?? 'Not set'));
        error_log("MAIL_PORT: " . ($_ENV['MAIL_PORT'] ?? 'Not set'));
        error_log("MAIL_USERNAME: " . ($_ENV['MAIL_USERNAME'] ?? 'Not set'));
        error_log("MAIL_PASSWORD: " . (isset($_ENV['MAIL_PASSWORD']) ? 'Is set (hidden)' : 'Not set'));
        error_log("MAIL_FROM_ADDRESS: " . ($_ENV['MAIL_FROM_ADDRESS'] ?? 'Not set'));
        error_log("MAIL_FROM_NAME: " . ($_ENV['MAIL_FROM_NAME'] ?? 'Not set'));
        
        // Validate input
        try {
            v::notEmpty()->length(3, 50)->alnum('_-')->check($request->request->get('username'));
            v::notEmpty()->email()->check($request->request->get('email'));
            v::notEmpty()->length(8, null)->check($request->request->get('password'));
            
            // Check if passwords match
            if ($request->request->get('password') !== $request->request->get('password_confirm')) {
                throw new \Exception('Passwords do not match');
            }
            
        } catch (\Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            $_SESSION['form_data'] = [
                'username' => $request->request->get('username'),
                'email' => $request->request->get('email')
            ];
            $response = new RedirectResponse('/register');
            $response->send();
            return;
        }
        
        // Connect to database
        try {
            $database = new \Medoo\Medoo([
                'type' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ]);
            
            // Check if username or email already exists
            $existing = $database->get('users', '*', [
                'OR' => [
                    'username' => $request->request->get('username'),
                    'email' => $request->request->get('email')
                ]
            ]);
            
            if ($existing) {
                $_SESSION['error_message'] = 'Username or email already exists';
                $_SESSION['form_data'] = [
                    'username' => $request->request->get('username'),
                    'email' => $request->request->get('email')
                ];
                $response = new RedirectResponse('/register');
                $response->send();
                return;
            }
            
            // Generate email verification token
            $verificationToken = bin2hex(random_bytes(32));
            
            // Hash password and create user
            $passwordHash = password_hash($request->request->get('password'), PASSWORD_DEFAULT);
            
            $database->insert('users', [
                'username' => $request->request->get('username'),
                'email' => $request->request->get('email'),
                'password_hash' => $passwordHash,
                'is_active' => 0, // Account inactive until email verified
                'created_at' => date('Y-m-d H:i:s'),
                'verification_token' => $verificationToken
            ]);
            
            $userId = $database->id();
            
            error_log("DEBUG - User registered successfully. User ID: " . $userId);
            
            // Send verification email
            $emailResult = $this->sendVerificationEmail(
                $request->request->get('email'),
                $request->request->get('username'),
                $verificationToken
            );
            
            error_log("DEBUG - Email sending result: " . ($emailResult ? "Success" : "Failed"));
            
            // Registration successful
            $_SESSION['success_message'] = 'Registration successful! Please check your email to verify your account.';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
            
        } catch (\Exception $e) {
            error_log("DEBUG - Registration error: " . $e->getMessage());
            $_SESSION['error_message'] = 'An error occurred during registration. Please try again later.';
            $response = new RedirectResponse('/register');
            $response->send();
            return;
        }
    }
    
    private function sendVerificationEmail($email, $username, $token) {
        $mail = new PHPMailer(true);
        
        try {
            error_log("DEBUG - Starting email sending process to: " . $email);
            
            // Server settings
            $mail->SMTPDebug = 3; // Enable verbose debug output
            $mail->Debugoutput = function($str, $level) {
                error_log("DEBUG - PHPMailer: " . $str);
            };
            
            $mail->isSMTP();
            error_log("DEBUG - Setting SMTP host: " . $_ENV['MAIL_HOST']);
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            error_log("DEBUG - Setting SMTP username: " . $_ENV['MAIL_USERNAME']);
            $mail->Username = $_ENV['MAIL_USERNAME'];
            error_log("DEBUG - Setting SMTP password: [hidden]");
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            
            // Gmail requires TLS
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            error_log("DEBUG - Setting SMTP port: " . $_ENV['MAIL_PORT']);
            $mail->Port = $_ENV['MAIL_PORT'];
            
            // Additional Gmail-specific settings
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Recipients
            error_log("DEBUG - Setting sender: " . $_ENV['MAIL_FROM_ADDRESS']);
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            error_log("DEBUG - Adding recipient: " . $email);
            $mail->addAddress($email, $username);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Challengify Account';
            
            $verificationLink = $_ENV['APP_URL'] . "/verify-email?token=$token";
            error_log("DEBUG - Verification link: " . $verificationLink);
            
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #4e73df; color: white; padding: 10px 20px; text-align: center; }
                        .content { padding: 20px; background-color: #f8f9fc; border: 1px solid #e3e6f0; }
                        .button { display: inline-block; background-color: #4e73df; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Welcome to Challengify!</h1>
                        </div>
                        <div class='content'>
                            <p>Hello $username,</p>
                            <p>Thank you for registering with Challengify. To complete your registration, please verify your email address by clicking the button below:</p>
                            <p style='text-align: center;'>
                                <a href='$verificationLink' class='button'>Verify Email Address</a>
                            </p>
                            <p>If the button doesn't work, you can also copy and paste this link into your browser:</p>
                            <p>$verificationLink</p>
                            <p>This link will expire in 24 hours.</p>
                            <p>If you did not create an account, no further action is required.</p>
                            <p>Best regards,<br>The Challengify Team</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            $mail->AltBody = "Hello $username,\n\nThank you for registering with Challengify. To complete your registration, please verify your email address by clicking the link below:\n\n$verificationLink\n\nThis link will expire in 24 hours.\n\nIf you did not create an account, no further action is required.\n\nBest regards,\nThe Challengify Team";
            
            error_log("DEBUG - Attempting to send email...");
            $mail->send();
            error_log("DEBUG - Email sent successfully!");
            return true;
        } catch (Exception $e) {
            // Log the error instead of showing it
            error_log("DEBUG - Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    public function verifyEmail() {
        $request = Request::createFromGlobals();
        $token = $request->query->get('token');
        
        if (empty($token)) {
            $_SESSION['error_message'] = 'Invalid verification link';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
        }
        
        try {
            $database = new \Medoo\Medoo([
                'type' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ]);
            
            // Find user with this token
            $user = $database->get('users', '*', [
                'verification_token' => $token
            ]);
            
            if (!$user) {
                $_SESSION['error_message'] = 'Invalid verification token';
                $response = new RedirectResponse('/login');
                $response->send();
                return;
            }
            
            // Update user account to active
            $database->update('users', [
                'is_active' => 1,
                'verification_token' => null,
                'email_verified_at' => date('Y-m-d H:i:s')
            ], [
                'user_id' => $user['user_id']
            ]);
            
            $_SESSION['success_message'] = 'Email verified successfully! You can now log in.';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
            
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred during email verification. Please try again later.';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
        }
    }
    
    public function showLogin() {
        require_once __DIR__ . '/../Views/login.php';
    }
    
    public function login() {
        $request = Request::createFromGlobals();
        $response = new Response();
        
        // Verify CSRF token
        if (!hash_equals($_SESSION['csrf_token'], $request->request->get('csrf_token'))) {
            $_SESSION['error_message'] = 'Invalid CSRF token';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
        }
        
        $loginId = $request->request->get('login_id');
        $password = $request->request->get('password');
        $remember = $request->request->get('remember', false);
        
        // Validate input
        if (empty($loginId) || empty($password)) {
            $_SESSION['error_message'] = 'Please enter both your email/username and password';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
        }
        
        // Connect to database
        try {
            $database = new \Medoo\Medoo([
                'type' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ]);
            
            // Check if user exists (by username or email)
            $user = $database->get('users', '*', [
                'OR' => [
                    'username' => $loginId,
                    'email' => $loginId
                ]
            ]);
            
            if (!$user || !password_verify($password, $user['password_hash'])) {
                $_SESSION['error_message'] = 'Invalid username/email or password';
                $response = new RedirectResponse('/login');
                $response->send();
                return;
            }
            
            // Check if email is verified
            if ($user['email_verified_at'] === null) {
                $_SESSION['error_message'] = 'Please verify your email address before logging in';
                $response = new RedirectResponse('/login');
                $response->send();
                return;
            }
            
            // Check if user is active
            if (!$user['is_active']) {
                $_SESSION['error_message'] = 'Your account has been deactivated. Please contact support.';
                $response = new RedirectResponse('/login');
                $response->send();
                return;
            }
            
            // Login successful - set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Set remember-me cookie if requested
            if ($remember) {
                // Create a remember token
                $token = bin2hex(random_bytes(32));
                $hashedToken = hash('sha256', $token);
                $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                
                // Store token in database
                $database->insert('user_tokens', [
                    'user_id' => $user['user_id'],
                    'token' => $hashedToken,
                    'expires_at' => date('Y-m-d H:i:s', $expiry)
                ]);
                
                // Set cookie
                setcookie(
                    'remember_token',
                    $user['user_id'] . ':' . $token,
                    [
                        'expires' => $expiry,
                        'path' => '/',
                        'domain' => '',
                        'secure' => isset($_SERVER['HTTPS']),
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]
                );
            }
            
            // Redirect to dashboard or home page
            $response = new RedirectResponse('/dashboard');
            $response->send();
            return;
            
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred during login. Please try again later.';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
        }
    }
    
    public function showForgotPassword() {
        require_once __DIR__ . '/../Views/forgot-password.php';
    }
    
    public function forgotPassword() {
        $request = Request::createFromGlobals();
        $response = new Response();
        
        // Verify CSRF token
        if (!hash_equals($_SESSION['csrf_token'], $request->request->get('csrf_token'))) {
            $_SESSION['error_message'] = 'Invalid CSRF token';
            $response = new RedirectResponse('/forgot-password');
            $response->send();
            return;
        }
        
        $email = $request->request->get('email');
        
        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = 'Please enter a valid email address';
            $response = new RedirectResponse('/forgot-password');
            $response->send();
            return;
        }
        
        try {
            $database = new \Medoo\Medoo([
                'type' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ]);
            
            // Check if user exists
            $user = $database->get('users', ['user_id', 'username', 'email'], [
                'email' => $email
            ]);
            
            if (!$user) {
                // Don't reveal that the user doesn't exist for security reasons
                $_SESSION['success_message'] = 'If your email is registered, you will receive a password reset link shortly.';
                $response = new RedirectResponse('/login');
                $response->send();
                return;
            }
            
            // Generate reset token and expiry
            $resetToken = bin2hex(random_bytes(32));
            $expiryTime = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry
            
            // Store token in database
            $database->insert('password_resets', [
                'email' => $email,
                'token' => $resetToken,
                'expires_at' => $expiryTime
            ]);
            
            // Send reset email
            $this->sendPasswordResetEmail($user['email'], $user['username'], $resetToken);
            
            $_SESSION['success_message'] = 'If your email is registered, you will receive a password reset link shortly.';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
            
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred. Please try again later.';
            $response = new RedirectResponse('/forgot-password');
            $response->send();
            return;
        }
    }
    
    private function sendPasswordResetEmail($email, $username, $token) {
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];
            
            // Recipients
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($email, $username);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Challengify Password';
            
            $resetLink = $_ENV['APP_URL'] . "/reset-password?token=$token";
            
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #4e73df; color: white; padding: 10px 20px; text-align: center; }
                        .content { padding: 20px; background-color: #f8f9fc; border: 1px solid #e3e6f0; }
                        .button { display: inline-block; background-color: #4e73df; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Reset Your Password</h1>
                        </div>
                        <div class='content'>
                            <p>Hello $username,</p>
                            <p>You are receiving this email because we received a password reset request for your account.</p>
                            <p style='text-align: center;'>
                                <a href='$resetLink' class='button'>Reset Password</a>
                            </p>
                            <p>If the button doesn't work, you can also copy and paste this link into your browser:</p>
                            <p>$resetLink</p>
                            <p>This password reset link will expire in 60 minutes.</p>
                            <p>If you did not request a password reset, no further action is required.</p>
                            <p>Best regards,<br>The Challengify Team</p>
                        </div>
                    </div>
                </body>
                </html>
            ";
            
            $mail->AltBody = "Hello $username,\n\nYou are receiving this email because we received a password reset request for your account.\n\nPlease reset your password by clicking the link below:\n\n$resetLink\n\nThis password reset link will expire in 60 minutes.\n\nIf you did not request a password reset, no further action is required.\n\nBest regards,\nThe Challengify Team";
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error instead of showing it
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    public function showResetPassword() {
        $request = Request::createFromGlobals();
        $token = $request->query->get('token');
        
        if (empty($token)) {
            $_SESSION['error_message'] = 'Invalid password reset link';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
        }
        
        try {
            $database = new \Medoo\Medoo([
                'type' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ]);
            
            // Check if token exists and is valid
            $reset = $database->get('password_resets', '*', [
                'token' => $token,
                'expires_at[>]' => date('Y-m-d H:i:s')
            ]);
            
            if (!$reset) {
                $_SESSION['error_message'] = 'This password reset link is invalid or has expired';
                $response = new RedirectResponse('/forgot-password');
                $response->send();
                return;
            }
            
            // Token is valid, show reset form
            $_SESSION['reset_token'] = $token; // Store token in session for form submission
            require_once __DIR__ . '/../Views/reset-password.php';
            
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred. Please try again later.';
            $response = new RedirectResponse('/forgot-password');
            $response->send();
            return;
        }
    }
    
    public function resetPassword() {
        $request = Request::createFromGlobals();
        $response = new Response();
        
        // Verify CSRF token
        if (!hash_equals($_SESSION['csrf_token'], $request->request->get('csrf_token'))) {
            $_SESSION['error_message'] = 'Invalid CSRF token';
            $response = new RedirectResponse('/forgot-password');
            $response->send();
            return;
        }
        
        $token = $request->request->get('token');
        $password = $request->request->get('password');
        $passwordConfirm = $request->request->get('password_confirm');
        
        // Validate input
        if (empty($password) || strlen($password) < 8) {
            $_SESSION['error_message'] = 'Password must be at least 8 characters long';
            $response = new RedirectResponse('/reset-password?token=' . $token);
            $response->send();
            return;
        }
        
        if ($password !== $passwordConfirm) {
            $_SESSION['error_message'] = 'Passwords do not match';
            $response = new RedirectResponse('/reset-password?token=' . $token);
            $response->send();
            return;
        }
        
        try {
            $database = new \Medoo\Medoo([
                'type' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'database' => $_ENV['DB_NAME'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ]);
            
            // Check if token exists and is valid
            $reset = $database->get('password_resets', '*', [
                'token' => $token,
                'expires_at[>]' => date('Y-m-d H:i:s')
            ]);
            
            if (!$reset) {
                $_SESSION['error_message'] = 'This password reset link is invalid or has expired';
                $response = new RedirectResponse('/forgot-password');
                $response->send();
                return;
            }
            
            // Update user's password
            $user = $database->get('users', '*', [
                'email' => $reset['email']
            ]);
            
            if (!$user) {
                $_SESSION['error_message'] = 'User not found';
                $response = new RedirectResponse('/forgot-password');
                $response->send();
                return;
            }
            
            // Hash new password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Update user password
            $database->update('users', [
                'password_hash' => $passwordHash
            ], [
                'user_id' => $user['user_id']
            ]);
            
            // Delete all password reset tokens for this user
            $database->delete('password_resets', [
                'email' => $reset['email']
            ]);
            
            $_SESSION['success_message'] = 'Your password has been reset successfully! You can now log in with your new password.';
            $response = new RedirectResponse('/login');
            $response->send();
            return;
            
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred. Please try again later.';
            $response = new RedirectResponse('/forgot-password');
            $response->send();
            return;
        }
    }
}
?> 