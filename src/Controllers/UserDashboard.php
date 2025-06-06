<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Kpzsproductions\Challengify\Core\Database;
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
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            // Redirect to login page
            return $this->redirect('/login');
        }
        
        $db = Database::getInstance();
        $userId = $_SESSION['user_id'];
        
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
            } else {
                // Check if username is already taken
                $existingUser = $db->get('users', ['user_id'], ['username' => $username]);
                if ($existingUser && $existingUser['user_id'] != $userId) {
                    $errors['username'] = 'This username is already taken';
                } else {
                    $db->update('users', ['username' => $username], ['user_id' => $userId]);
                    $_SESSION['username'] = $username;
                    $success[] = 'Username updated successfully';
                }
            }
        }
        
        // Update email if changed
        if ($email !== $user['email']) {
            if (!v::email()->validate($email)) {
                $errors['email'] = 'Please enter a valid email address';
            } else {
                // Check if email is already registered
                $existingUser = $db->get('users', ['user_id'], ['email' => $email]);
                if ($existingUser && $existingUser['user_id'] != $userId) {
                    $errors['email'] = 'This email is already registered';
                } else {
                    $db->update('users', ['email' => $email], ['user_id' => $userId]);
                    $success[] = 'Email updated successfully';
                }
            }
        }
        
        // Update bio
        $db->update('users', ['bio' => $bio], ['user_id' => $userId]);
        
        // Update password if provided
        if (!empty($currentPassword) && !empty($newPassword)) {
            // Verify current password
            if (!password_verify($currentPassword, $user['password_hash'])) {
                $errors['current_password'] = 'Current password is incorrect';
            } else if ($newPassword !== $confirmPassword) {
                $errors['confirm_password'] = 'New passwords do not match';
            } else if (!v::notEmpty()->length(8, null)->validate($newPassword)) {
                $errors['new_password'] = 'New password must be at least 8 characters';
            } else {
                // Hash new password
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $db->update('users', ['password_hash' => $passwordHash], ['user_id' => $userId]);
                $success[] = 'Password updated successfully';
            }
        }
        
        // Handle profile image upload
        if ($request->files->has('profile_image')) {
            // Debug information
            $errors['debug'] = 'Files present: ' . ($request->files->has('profile_image') ? 'Yes' : 'No');
            
            $file = $request->files->get('profile_image');
            if ($file === null) {
                $errors['debug'] .= ' | File is null';
            } else {
                $errors['debug'] .= ' | File size: ' . $file->getSize();
                $errors['debug'] .= ' | File name: ' . $file->getClientOriginalName();
                
                if ($file->getError() !== UPLOAD_ERR_OK) {
                    $errorCodes = [
                        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in form',
                        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the upload'
                    ];
                    $errorCode = $file->getError();
                    $errorMessage = isset($errorCodes[$errorCode]) ? $errorCodes[$errorCode] : 'Unknown error';
                    $errors['debug'] .= ' | Upload error: ' . $errorMessage . ' (code: ' . $errorCode . ')';
                }
                
                if ($file->isValid()) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    
                    if (!in_array($file->getMimeType(), $allowedTypes)) {
                        $errors['profile_image'] = 'Only JPEG, PNG, and GIF images are allowed';
                        $errors['debug'] .= ' | Invalid mime type: ' . $file->getMimeType();
                    } else if ($file->getSize() > 2 * 1024 * 1024) {
                        $errors['profile_image'] = 'Image size must be less than 2MB';
                        $errors['debug'] .= ' | File too large: ' . $file->getSize();
                    } else {
                        // Generate unique filename
                        $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
                        $uploadDir = __DIR__ . '/../../public/assets/images/profiles/';
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                            $errors['debug'] .= ' | Created directory: ' . $uploadDir;
                        }
                        
                        try {
                            // Move uploaded file
                            $file->move($uploadDir, $filename);
                            
                            // Check if file was actually moved
                            if (file_exists($uploadDir . $filename)) {
                                // Update database
                                $profileImage = '/assets/images/profiles/' . $filename;
                                $db->update('users', ['profile_image' => $profileImage], ['user_id' => $userId]);
                                $success[] = 'Profile image updated successfully';
                                $errors['debug'] .= ' | File moved successfully to: ' . $uploadDir . $filename;
                            } else {
                                $errors['debug'] .= ' | File move failed, file not found at destination';
                            }
                        } catch (\Exception $e) {
                            $errors['debug'] .= ' | Exception: ' . $e->getMessage();
                        }
                    }
                } else {
                    $errors['debug'] .= ' | File is not valid';
                }
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
        }
        
        if (empty($errors)) {
            $success[] = 'Settings updated successfully';
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
                    
                    return true;
                }
            }
            
            // Remove invalid token
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        return false;
    }
}
