<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Kpzsproductions\Challengify\Core\Database;
use Kpzsproductions\Challengify\Core\Logger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller {

    /**
     * Display admin dashboard
     */
    public function index() {
        // Check if user is admin
        if (!$this->isAdmin()) {
            return $this->redirect('/login');
        }
        
        // Get stats for admin dashboard
        $db = Database::getInstance();
        
        $totalUsers = $db->count('users');
        $totalChallenges = $db->count('challenges');
        $totalSubmissions = $db->count('submissions');
        
        // Render admin dashboard view
        ob_start();
        require_once __DIR__ . '/../Views/admin/dashboard.php';
        $content = ob_get_clean();
        
        return new Response($content);
    }
    
    /**
     * Display application logs
     */
    public function viewLogs(Request $request) {
        // Check if user is admin
        if (!$this->isAdmin()) {
            return $this->redirect('/login');
        }
        
        // Validate and sanitize log type parameter
        $logType = $request->query->get('type', 'app');
        $validLogTypes = ['app', 'profile', 'system', 'contact_form'];
        
        if (!in_array($logType, $validLogTypes)) {
            $logType = 'app'; // Default to app log if invalid type
        }
        
        $lines = min($request->query->getInt('lines', 100), 1000); // Limit max lines
        
        // Determine which log file to view (with explicit mapping to prevent path traversal)
        $logFiles = [
            'app' => __DIR__ . '/../../logs/app.log',
            'profile' => __DIR__ . '/../../logs/profile_updates.log',
            'system' => __DIR__ . '/../../logs/system.log',
            'contact_form' => __DIR__ . '/../../logs/contact_form.log'
        ];
        
        $logFile = $logFiles[$logType];
        
        // Initialize logger with specific log file
        Logger::init($logFile);
        
        // Get log contents
        $logContents = Logger::getLogContents($lines);
        
        // Render logs view
        ob_start();
        require_once __DIR__ . '/../Views/admin/logs.php';
        $content = ob_get_clean();
        
        return new Response($content);
    }
    
    /**
     * Clear application logs
     */
    public function clearLogs(Request $request) {
        // Check if user is admin
        if (!$this->isAdmin()) {
            return $this->redirect('/login');
        }
        
        // Validate and sanitize log type parameter
        $logType = $request->query->get('type', 'app');
        $validLogTypes = ['app', 'profile', 'system', 'contact_form'];
        
        if (!in_array($logType, $validLogTypes)) {
            $logType = 'app'; // Default to app log if invalid type
        }
        
        // Determine which log file to clear (with explicit mapping to prevent path traversal)
        $logFiles = [
            'app' => __DIR__ . '/../../logs/app.log',
            'profile' => __DIR__ . '/../../logs/profile_updates.log',
            'system' => __DIR__ . '/../../logs/system.log',
            'contact_form' => __DIR__ . '/../../logs/contact_form.log'
        ];
        
        $logFile = $logFiles[$logType];
        
        // Initialize logger with specific log file
        Logger::init($logFile);
        
        // Clear log
        Logger::clearLog();
        
        // Log the clearing action in the app log
        Logger::init(__DIR__ . '/../../logs/app.log');
        Logger::info('Logs cleared', [
            'log_type' => $logType,
            'admin_id' => $_SESSION['user_id'],
            'admin_username' => $_SESSION['username']
        ]);
        
        // Redirect back to logs page
        return $this->redirect('/admin/logs?type=' . urlencode($logType));
    }
    
    /**
     * Check if the current user is an admin
     */
    private function isAdmin() {
        // Check session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if session has role and user_id
        if (isset($_SESSION['user_id'])) {
            // If role is missing or doesn't match database, try to fix it
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                return $this->checkAndRepairAdminRole();
            }
            
            // Regular check
            if ($_SESSION['role'] === 'admin') {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check user role in database and update session if needed
     */
    private function checkAndRepairAdminRole() {
        $db = Database::getInstance();
        $userId = $_SESSION['user_id'];
        
        // Get user from database
        $user = $db->get('users', ['role'], ['user_id' => $userId]);
        
        // Check if user exists and is admin
        if ($user && $user['role'] === 'admin') {
            // Update session with correct role
            $_SESSION['role'] = $user['role'];
            
            // Log the role repair
            Logger::init(__DIR__ . '/../../logs/system.log');
            Logger::info("Admin role repaired automatically", [
                'user_id' => $userId,
                'role' => $user['role']
            ]);
            
            return true;
        }
        
        return false;
    }
} 