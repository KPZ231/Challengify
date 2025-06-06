<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Kpzsproductions\Challengify\Core\Database;
use Kpzsproductions\Challengify\Core\Session;
use Kpzsproductions\Challengify\Core\Security;
use Kpzsproductions\Challengify\Core\Logger;
use Kpzsproductions\Challengify\Components\Notification;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Display login form
     */
    public function login()
    {
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            return $this->redirect('/');
        }
        
        // Generate CSRF token for the form
        $csrfToken = Session::generateCsrfToken();
        
        return $this->render('auth/login', [
            'csrf_token' => $csrfToken
        ]);
    }
    
    /**
     * Process login form
     */
    public function processLogin(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $remember = $request->request->getBoolean('remember', false);
        $csrfToken = $request->request->get('csrf_token');
        $ipAddress = $request->getClientIp();
        
        // Validate CSRF token
        if (!Session::validateCsrfToken($csrfToken)) {
            Notification::show('Invalid form submission. Please try again.', 'error');
            return $this->redirect('/login');
        }
        
        // Check for rate limiting
        if (!Security::checkRateLimit("login_attempt:{$ipAddress}", 5, 15)) {
            Notification::show('Too many login attempts. Please try again later.', 'error');
            return $this->redirect('/login');
        }
        
        // Validate input
        $errors = [];
        if (!v::email()->validate($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }
        
        // If there are validation errors, return to login form
        if (!empty($errors)) {
            Notification::show('Please correct the form errors', 'error');
            return $this->render('auth/login', [
                'errors' => $errors,
                'old' => ['email' => $email],
                'csrf_token' => Session::generateCsrfToken()
            ]);
        }
        
        // Find user by email
        $db = Database::getInstance();
        $user = $db->get('users', '*', ['email' => $email]);
        
        // Check if user exists and password matches
        if (!$user || !password_verify($password, $user['password_hash'])) {
            // Log failed login attempt
            Logger::info("Failed login attempt", [
                'email' => $email,
                'ip' => $ipAddress,
                'user_agent' => $request->headers->get('User-Agent')
            ]);
            
            Notification::show('Invalid email or password', 'error');
            return $this->render('auth/login', [
                'error' => 'Invalid email or password',
                'old' => ['email' => $email],
                'csrf_token' => Session::generateCsrfToken()
            ]);
        }
        
        // Check if user is active
        if (!$user['is_active']) {
            Notification::show('Your account has been deactivated', 'error');
            return $this->render('auth/login', [
                'error' => 'Your account has been deactivated',
                'old' => ['email' => $email],
                'csrf_token' => Session::generateCsrfToken()
            ]);
        }
        
        // Start secure session and set user data
        Session::start();
        Session::regenerateId(); // Prevent session fixation
        
        // Set session variables
        Session::set('user_id', $user['user_id']);
        Session::set('username', $user['username']);
        Session::set('role', $user['role']);
        Session::set('profile_image', $user['profile_image']);
        Session::set('last_active', time());
        
        // Create remember me token if requested
        if ($remember) {
            $token = Security::generateToken(32);
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            $db->insert('user_tokens', [
                'user_id' => $user['user_id'],
                'token' => $token,
                'expires_at' => $expires,
                'ip_address' => $ipAddress,
                'user_agent' => substr($request->headers->get('User-Agent'), 0, 255)
            ]);
            
            // Set secure cookie
            setcookie(
                'remember_token', 
                $token, 
                [
                    'expires' => strtotime('+30 days'),
                    'path' => '/',
                    'domain' => '',
                    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );
        }
        
        // Log successful login
        Logger::info("Successful login", [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'ip' => $ipAddress
        ]);
        
        // Show success notification
        Notification::show('Successfully logged in! Welcome back ' . htmlspecialchars($user['username']), 'success');
        
        // Redirect to home page
        return $this->redirect('/');
    }
    
    /**
     * Display registration form
     */
    public function register()
    {
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            return $this->redirect('/');
        }
        
        // Generate CSRF token for the form
        $csrfToken = Session::generateCsrfToken();
        
        return $this->render('auth/register', [
            'csrf_token' => $csrfToken
        ]);
    }
    
    /**
     * Process registration form
     */
    public function processRegister(Request $request)
    {
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $passwordConfirm = $request->request->get('password_confirm');
        $csrfToken = $request->request->get('csrf_token');
        
        // Validate CSRF token
        if (!Session::validateCsrfToken($csrfToken)) {
            Notification::show('Invalid form submission. Please try again.', 'error');
            return $this->redirect('/register');
        }
        
        // Check for rate limiting
        $ipAddress = $request->getClientIp();
        if (!Security::checkRateLimit("registration_attempt:{$ipAddress}", 3, 30)) {
            Notification::show('Too many registration attempts. Please try again later.', 'error');
            return $this->redirect('/register');
        }
        
        // Sanitize inputs
        $username = Security::sanitizeString($username);
        $email = Security::sanitizeEmail($email);
        
        // Validate input
        $errors = [];
        
        if (!v::notEmpty()->alnum('-_')->length(3, 50)->validate($username)) {
            $errors['username'] = 'Username must be 3-50 characters and may contain alphanumeric characters, dashes and underscores';
        }
        
        if (!Security::validateEmail($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        // Enhanced password requirements
        if (!v::notEmpty()->length(8, null)->validate($password)) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif (!preg_match('/[A-Z]/', $password) || 
                  !preg_match('/[a-z]/', $password) || 
                  !preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Password must include at least one uppercase letter, one lowercase letter, and one number';
        }
        
        if ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Passwords do not match';
        }
        
        // If there are validation errors, return to registration form
        if (!empty($errors)) {
            Notification::show('Please correct the form errors', 'error');
            return $this->render('auth/register', [
                'errors' => $errors,
                'old' => [
                    'username' => $username,
                    'email' => $email
                ],
                'csrf_token' => Session::generateCsrfToken()
            ]);
        }
        
        // Check if username or email already exists
        $db = Database::getInstance();
        $existingUser = $db->get('users', ['user_id'], [
            'OR' => [
                'username' => $username,
                'email' => $email
            ]
        ]);
        
        if ($existingUser) {
            // Check which one exists
            $existingUsername = $db->get('users', ['user_id'], ['username' => $username]);
            $existingEmail = $db->get('users', ['user_id'], ['email' => $email]);
            
            if ($existingUsername) {
                $errors['username'] = 'This username is already taken';
            }
            
            if ($existingEmail) {
                $errors['email'] = 'This email is already registered';
            }
            
            Notification::show('Username or email already exists', 'error');
            return $this->render('auth/register', [
                'errors' => $errors,
                'old' => [
                    'username' => $username,
                    'email' => $email
                ],
                'csrf_token' => Session::generateCsrfToken()
            ]);
        }
        
        // Hash password with stronger algorithm and options
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64MB
            'time_cost' => 4,
            'threads' => 3,
        ]);
        
        // Insert new user
        $db->insert('users', [
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash,
            'created_at' => date('Y-m-d H:i:s'),
            'is_active' => true,
            'role' => 'user',
            'registration_ip' => $ipAddress
        ]);
        
        // Log registration
        Logger::info("New user registered", [
            'username' => $username,
            'email' => $email,
            'ip' => $ipAddress
        ]);
        
        // Display success notification
        Notification::show('Account created successfully! You can now log in.', 'success');
        
        // Redirect to login page
        return $this->redirect('/login');
    }
    
    /**
     * Logout user
     */
    public function logout()
    {
        // Remove remember token if exists
        if (isset($_COOKIE['remember_token'])) {
            $db = Database::getInstance();
            $db->delete('user_tokens', ['token' => $_COOKIE['remember_token']]);
            
            // Securely clear the cookie
            setcookie(
                'remember_token', 
                '', 
                [
                    'expires' => time() - 3600,
                    'path' => '/',
                    'domain' => '',
                    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );
        }
        
        // Destroy session
        Session::destroy();
        
        // Show logout notification
        Notification::show('Successfully logged out', 'info');
        
        // Redirect to login page
        return $this->redirect('/login');
    }
    
    /**
     * Check if user is logged in
     */
    private function isLoggedIn()
    {
        // Check session
        if (Session::has('user_id')) {
            // Update last activity time
            Session::set('last_active', time());
            return true;
        }
        
        // Check remember token
        if (isset($_COOKIE['remember_token'])) {
            $db = Database::getInstance();
            $token = $db->get('user_tokens', '*', [
                'token' => $_COOKIE['remember_token'],
                'expires_at[>]' => date('Y-m-d H:i:s')
            ]);
            
            if ($token) {
                // Get user
                $user = $db->get('users', '*', ['user_id' => $token['user_id']]);
                
                if ($user && $user['is_active']) {
                    // Set session data
                    Session::start();
                    Session::regenerateId(); // Prevent session fixation
                    
                    Session::set('user_id', $user['user_id']);
                    Session::set('username', $user['username']);
                    Session::set('role', $user['role']);
                    Session::set('profile_image', $user['profile_image']);
                    Session::set('last_active', time());
                    
                    return true;
                }
            }
            
            // Remove invalid token
            setcookie(
                'remember_token', 
                '', 
                [
                    'expires' => time() - 3600,
                    'path' => '/',
                    'domain' => '',
                    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );
        }
        
        return false;
    }
}
?>