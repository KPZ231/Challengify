<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Kpzsproductions\Challengify\Core\Database;
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
        
        return $this->render('auth/login');
    }
    
    /**
     * Process login form
     */
    public function processLogin(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $remember = $request->request->getBoolean('remember', false);
        
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
                'old' => ['email' => $email]
            ]);
        }
        
        // Find user by email
        $db = Database::getInstance();
        $user = $db->get('users', '*', ['email' => $email]);
        
        // Check if user exists and password matches
        if (!$user || !password_verify($password, $user['password_hash'])) {
            Notification::show('Invalid email or password', 'error');
            return $this->render('auth/login', [
                'error' => 'Invalid email or password',
                'old' => ['email' => $email]
            ]);
        }
        
        // Check if user is active
        if (!$user['is_active']) {
            Notification::show('Your account has been deactivated', 'error');
            return $this->render('auth/login', [
                'error' => 'Your account has been deactivated',
                'old' => ['email' => $email]
            ]);
        }
        
        // Start session and set user data
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Create remember me token if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            $db->insert('user_tokens', [
                'user_id' => $user['user_id'],
                'token' => $token,
                'expires_at' => $expires
            ]);
            
            // Set cookie
            setcookie('remember_token', $token, strtotime('+30 days'), '/', '', true, true);
        }
        
        // Show success notification
        Notification::show('Successfully logged in! Welcome back ' . $user['username'], 'success');
        
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
        
        return $this->render('auth/register');
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
        
        // Validate input
        $errors = [];
        
        if (!v::notEmpty()->alnum('-_')->length(3, 50)->validate($username)) {
            $errors['username'] = 'Username must be 3-50 characters and may contain alphanumeric characters, dashes and underscores';
        }
        
        if (!v::email()->validate($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        if (!v::notEmpty()->length(8, null)->validate($password)) {
            $errors['password'] = 'Password must be at least 8 characters';
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
                ]
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
                ]
            ]);
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $db->insert('users', [
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash,
            'created_at' => date('Y-m-d H:i:s'),
            'is_active' => true,
            'role' => 'user'
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
        // Start session
        session_start();
        
        // Remove remember token if exists
        if (isset($_COOKIE['remember_token'])) {
            $db = Database::getInstance();
            $db->delete('user_tokens', ['token' => $_COOKIE['remember_token']]);
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        // Destroy session
        session_unset();
        session_destroy();
        
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
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
                    // Start session and set user data
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    return true;
                }
            }
            
            // Remove invalid token
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        return false;
    }
}
?>