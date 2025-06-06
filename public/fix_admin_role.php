<?php

require __DIR__ . '/../vendor/autoload.php';

use Kpzsproductions\Challengify\Core\Database;
use Kpzsproductions\Challengify\Core\Logger;

// Initialize logger
Logger::init(__DIR__ . '/../logs/system.log');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>Fix Admin Role</h1>";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>No active session. Please log in first.</p>";
    echo "<p><a href='/login'>Go to login page</a></p>";
    exit;
}

try {
    $db = Database::getInstance();
    $userId = $_SESSION['user_id'];
    
    // Get user data from database
    $user = $db->get('users', '*', ['user_id' => $userId]);
    
    if (!$user) {
        echo "<p>User not found in database. Please log in again.</p>";
        echo "<p><a href='/logout'>Logout</a></p>";
        exit;
    }
    
    // Log current state
    Logger::info("Fix admin role - Current state", [
        'user_id' => $userId,
        'username' => $user['username'],
        'db_role' => $user['role'],
        'session_role' => $_SESSION['role'] ?? 'not set'
    ]);
    
    // Update role in session
    $_SESSION['role'] = $user['role'];
    
    echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>";
    echo "<h3>✅ Session Updated Successfully</h3>";
    echo "<p>Your session has been updated with the correct role.</p>";
    echo "</div>";
    
    // Display user information
    echo "<h2>User Information:</h2>";
    echo "<ul>";
    echo "<li><strong>User ID:</strong> " . $user['user_id'] . "</li>";
    echo "<li><strong>Username:</strong> " . htmlspecialchars($user['username']) . "</li>";
    echo "<li><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</li>";
    echo "<li><strong>Role:</strong> " . htmlspecialchars($user['role']) . "</li>";
    echo "<li><strong>Active:</strong> " . ($user['is_active'] ? 'Yes' : 'No') . "</li>";
    echo "</ul>";
    
    // Log update
    Logger::info("Fix admin role - Role updated in session", [
        'user_id' => $userId,
        'username' => $user['username'],
        'role' => $user['role']
    ]);
    
    // Provide links
    echo "<h2>Links:</h2>";
    echo "<ul>";
    
    if ($user['role'] === 'admin') {
        echo "<li><a href='/admin'>Go to Admin Dashboard</a></li>";
    }
    
    echo "<li><a href='/'>Go to Home Page</a></li>";
    echo "<li><a href='/dashboard'>Go to User Dashboard</a></li>";
    echo "<li><a href='/check_session'>Check Session</a></li>";
    echo "<li><a href='/logout'>Logout</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>";
    echo "<h3>❌ Error</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    
    Logger::error("Fix admin role - Error", [
        'user_id' => $userId ?? 'unknown',
        'error' => $e->getMessage()
    ]);
} 