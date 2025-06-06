<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Kpzsproductions\Challengify\Core\Database;
use Kpzsproductions\Challengify\Core\Logger;
use Kpzsproductions\Challengify\Core\FileUploader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Respect\Validation\Validator as v;

class UserDashboard extends Controller {

    /**
     * Display user dashboard
     */
    public function index() {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            // Redirect to login page
            return $this->redirect('/login');
        }
        
        // Get user data from database
        $db = Database::getInstance();
        $userId = $_SESSION['user_id'];
        
        // Get user details
        $user = $db->get('users', [
            'username', 
            'email', 
            'profile_image', 
            'bio', 
            'reputation_points', 
            'role'
        ], ['user_id' => $userId]);
        
        // Get stats
        $challengesCompleted = $db->count('submissions', ['user_id' => $userId]);
        $pointsEarned = $user['reputation_points'] ?? 0;
        $achievementCount = $db->count('user_badges', ['user_id' => $userId]);
        
        // Get recent activity (submissions)
        $recentActivity = $db->select('submissions', [
            '[>]challenges' => ['challenge_id' => 'challenge_id']
        ], [
            'submissions.submission_id',
            'submissions.title',
            'submissions.created_at',
            'challenges.title(challenge_title)'
        ], [
            'submissions.user_id' => $userId,
            'ORDER' => ['submissions.created_at' => 'DESC'],
            'LIMIT' => 5
        ]);
        
        // Render dashboard view
        ob_start();
        require_once __DIR__ . '/../Views/auth/user-dashboard.php';
        $content = ob_get_clean();
        
        return new Response($content);
    }
    
    /**
     * Display user settings page
     */
    public function settings(Request $request = null) {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            // Redirect to login page
            return $this->redirect('/login');
        }
        
        // Get user data from database
        $db = Database::getInstance();
        $userId = $_SESSION['user_id'];
        
        // Get user details
        $user = $db->get('users', [
            'user_id',
            'username', 
            'email', 
            'profile_image', 
            'bio', 
            'role'
        ], ['user_id' => $userId]);
        
        // Get user settings
        $settings = $db->get('user_settings', '*', ['user_id' => $userId]);
        
        // If settings don't exist, create default settings
        if (!$settings) {
            $settings = [
                'email_notifications' => true,
                'push_notifications' => true,
                'preferred_categories' => '',
                'language' => 'en',
                'theme' => 'system'
            ];
            
            // Insert default settings
            $db->insert('user_settings', [
                'user_id' => $userId,
                'email_notifications' => true,
                'push_notifications' => true,
                'language' => 'en',
                'theme' => 'system'
            ]);
        }
        
        // Get all categories for preferences
        $categories = $db->select('categories', [
            'category_id',
            'name'
        ], ['is_active' => true]);
        
        // Parse preferred categories if they exist
        $preferredCategories = [];
        if (!empty($settings['preferred_categories'])) {
            $preferredCategories = explode(',', $settings['preferred_categories']);
        }
        
        // Render settings view
        ob_start();
        require_once __DIR__ . '/../Views/auth/user-settings.php';
        $content = ob_get_clean();
        
        return new Response($content);
    }
    
    /**
     * Process user settings form
     */
    public function updateSettings(Request $request) {
        // Initialize logger for better tracking
        Logger::init(__DIR__ . '/../../logs/profile_updates.log');
        
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            // Redirect to login page
            return $this->redirect('/login');
        }
        
        $db = Database::getInstance();
        $userId = $_SESSION['user_id'];
        
        Logger::info("Starting settings update for user", ['user_id' => $userId]);
        
        // Get form data
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $bio = $request->request->get('bio');
        $currentPassword = $request->request->get('current_password');
        $newPassword = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');
        
        // Get notification settings
        $emailNotifications = $request->request->getBoolean('email_notifications', false);
        $pushNotifications = $request->request->getBoolean('push_notifications', false);
        $preferredCategories = $request->request->all('preferred_categories') ?: [];
        $language = $request->request->get('language', 'en');
        $theme = $request->request->get('theme', 'system');
        
        // Validate input
        $errors = [];
        $success = [];
        
        // Get current user data
        $user = $db->get('users', '*', ['user_id' => $userId]);
        
        // Update username if changed
        if ($username !== $user['username']) {
            if (!v::notEmpty()->alnum('-_')->length(3, 50)->validate($username)) {
                $errors['username'] = 'Username must be 3-50 characters and may contain alphanumeric characters, dashes and underscores';
                Logger::warning("Invalid username format", ['username' => $username]);
            } else {
                // Check if username is already taken
                $existingUser = $db->get('users', ['user_id'], ['username' => $username]);
                if ($existingUser && $existingUser['user_id'] != $userId) {
                    $errors['username'] = 'This username is already taken';
                    Logger::warning("Username already taken", ['username' => $username]);
                } else {
                    $db->update('users', ['username' => $username], ['user_id' => $userId]);
                    $_SESSION['username'] = $username;
                    $success[] = 'Username updated successfully';
                    Logger::info("Username updated", ['user_id' => $userId, 'new_username' => $username]);
                }
            }
        }
        
        // Update email if changed
        if ($email !== $user['email']) {
            if (!v::email()->validate($email)) {
                $errors['email'] = 'Please enter a valid email address';
                Logger::warning("Invalid email format", ['email' => $email]);
            } else {
                // Check if email is already registered
                $existingUser = $db->get('users', ['user_id'], ['email' => $email]);
                if ($existingUser && $existingUser['user_id'] != $userId) {
                    $errors['email'] = 'This email is already registered';
                    Logger::warning("Email already registered", ['email' => $email]);
                } else {
                    $db->update('users', ['email' => $email], ['user_id' => $userId]);
                    $success[] = 'Email updated successfully';
                    Logger::info("Email updated", ['user_id' => $userId, 'new_email' => $email]);
                }
            }
        }
        
        // Update bio
        $db->update('users', ['bio' => $bio], ['user_id' => $userId]);
        Logger::info("Bio updated", ['user_id' => $userId]);
        
        // Update password if provided
        if (!empty($currentPassword) && !empty($newPassword)) {
            // Verify current password
            if (!password_verify($currentPassword, $user['password_hash'])) {
                $errors['current_password'] = 'Current password is incorrect';
                Logger::warning("Incorrect current password provided", ['user_id' => $userId]);
            } else if ($newPassword !== $confirmPassword) {
                $errors['confirm_password'] = 'New passwords do not match';
                Logger::warning("New passwords do not match", ['user_id' => $userId]);
            } else if (!v::notEmpty()->length(8, null)->validate($newPassword)) {
                $errors['new_password'] = 'New password must be at least 8 characters';
                Logger::warning("New password too short", ['user_id' => $userId]);
            } else {
                // Hash new password
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $db->update('users', ['password_hash' => $passwordHash], ['user_id' => $userId]);
                $success[] = 'Password updated successfully';
                Logger::info("Password updated successfully", ['user_id' => $userId]);
            }
        }
        
        // Handle profile image upload with improved error handling
        if ($request->files->has('profile_image')) {
            $file = $request->files->get('profile_image');
            
            if ($file !== null && $file->getSize() > 0) {
                Logger::info("Profile image upload started", [
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]);
                
                // Basic validation before attempting to upload
                $mimeType = $file->getMimeType();
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxFileSize = 2 * 1024 * 1024; // 2MB
                
                if (!in_array($mimeType, $allowedTypes)) {
                    $errors['profile_image'] = 'Only JPEG, PNG, GIF, and WebP images are allowed';
                    Logger::warning("Invalid file type uploaded", [
                        'user_id' => $userId,
                        'mime_type' => $mimeType
                    ]);
                } elseif ($file->getSize() > $maxFileSize) {
                    $errors['profile_image'] = 'Image size must be less than 2MB';
                    Logger::warning("File too large", [
                        'user_id' => $userId,
                        'size' => $file->getSize()
                    ]);
                } else {
                    // Use our FileUploader class
                    $uploadDir = realpath(__DIR__ . '/../../public/assets/images/profiles') . DIRECTORY_SEPARATOR;
                    
                    $uploader = new FileUploader($uploadDir, $allowedTypes, $maxFileSize);
                    $filename = 'profile_' . $userId . '_' . time();
                    
                    // Log diagnostic information
                    Logger::info("Upload directory info", [
                        'upload_dir' => $uploadDir,
                        'upload_dir_exists' => is_dir($uploadDir) ? 'yes' : 'no',
                        'upload_dir_writable' => is_writable($uploadDir) ? 'yes' : 'no'
                    ]);
                    
                    if ($uploadedFilename = $uploader->upload($file, $filename)) {
                        // Sanitize path before storing in database
                        $profileImage = '/assets/images/profiles/' . basename($uploadedFilename);
                        
                        // Verify the file exists at the expected location
                        $fullPath = realpath(__DIR__ . '/../../public') . $profileImage;
                        if (!file_exists($fullPath)) {
                            $errors['profile_image'] = 'Error verifying uploaded file';
                            Logger::error("File not found after upload", [
                                'expected_path' => $fullPath
                            ]);
                        } else {
                            // Update database with the new profile image path
                            $db->update('users', ['profile_image' => $profileImage], ['user_id' => $userId]);
                            
                            // Update session with new profile image path
                            if (session_status() === PHP_SESSION_NONE) {
                                session_start();
                            }
                            $_SESSION['profile_image'] = $profileImage;
                            
                            $success[] = 'Profile image updated successfully';
                            Logger::info("Profile image updated successfully", [
                                'user_id' => $userId,
                                'file_path' => $profileImage
                            ]);
                        }
                    } else {
                        // Add upload errors to the errors array
                        $errors['profile_image'] = $uploader->getLastError();
                        Logger::error("Profile image upload failed", [
                            'user_id' => $userId,
                            'errors' => $uploader->getErrors()
                        ]);
                    }
                }
            } else {
                Logger::warning("Empty or invalid profile image file uploaded", [
                    'file' => $file ? 'present' : 'null',
                    'size' => $file ? $file->getSize() : 0
                ]);
            }
        }
        
        // Try to update user settings first
        $db->update('user_settings', [
            'email_notifications' => $emailNotifications,
            'push_notifications' => $pushNotifications,
            'preferred_categories' => implode(',', $preferredCategories),
            'language' => $language,
            'theme' => $theme
        ], ['user_id' => $userId]);
        
        // Check if settings exist by trying to get them
        $checkSettings = $db->get('user_settings', '*', ['user_id' => $userId]);
        
        // If no settings exist, create them
        if (!$checkSettings) {
            $db->insert('user_settings', [
                'user_id' => $userId,
                'email_notifications' => $emailNotifications,
                'push_notifications' => $pushNotifications,
                'preferred_categories' => implode(',', $preferredCategories),
                'language' => $language,
                'theme' => $theme
            ]);
            Logger::info("Created new user settings", ['user_id' => $userId]);
        } else {
            Logger::info("Updated user settings", ['user_id' => $userId]);
        }
        
        if (empty($errors)) {
            $success[] = 'Settings updated successfully';
            Logger::info("Settings update completed successfully", ['user_id' => $userId]);
        } else {
            Logger::warning("Settings update completed with errors", [
                'user_id' => $userId,
                'errors' => $errors
            ]);
        }
        
        // Render settings view with success/error messages
        return $this->settings($request);
    }
    
    /**
     * Check if user is logged in
     */
    private function isLoggedIn() {
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
                    
                    // Store profile image in session
                    $_SESSION['profile_image'] = $user['profile_image'];
                    
                    return true;
                }
            }
            
            // Remove invalid token
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        return false;
    }
}
