<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Kpzsproductions\Challengify\Core\Database;
use Symfony\Component\HttpFoundation\Response;

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
