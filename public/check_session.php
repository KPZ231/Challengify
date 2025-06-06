<?php

require __DIR__ . '/../vendor/autoload.php';

use Kpzsproductions\Challengify\Core\Database;

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>Session Debugger</h1>";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>No active session. Please log in first.</p>";
    echo "<p><a href='/login'>Go to login page</a></p>";
    exit;
}

// Display session data
echo "<h2>Session Data:</h2>";
echo "<pre>";
echo "user_id: " . $_SESSION['user_id'] . "\n";
echo "username: " . ($_SESSION['username'] ?? 'Not set') . "\n";
echo "role: " . ($_SESSION['role'] ?? 'Not set') . "\n";
echo "</pre>";

// Get user data from database
try {
    $db = Database::getInstance();
    $userId = $_SESSION['user_id'];
    
    $user = $db->get('users', '*', ['user_id' => $userId]);
    
    if ($user) {
        echo "<h2>Database User Data:</h2>";
        echo "<pre>";
        echo "user_id: " . $user['user_id'] . "\n";
        echo "username: " . $user['username'] . "\n";
        echo "email: " . $user['email'] . "\n";
        echo "role: " . $user['role'] . "\n";
        echo "is_active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
        echo "</pre>";
        
        // Check if roles match
        if ($user['role'] !== ($_SESSION['role'] ?? '')) {
            echo "<div style='color: red; font-weight: bold;'>";
            echo "WARNING: Role in session (" . ($_SESSION['role'] ?? 'Not set') . ") doesn't match role in database (" . $user['role'] . ")";
            echo "</div>";
            
            echo "<h3>Update Session:</h3>";
            echo "<p>Click below to update your session with the correct role:</p>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='action' value='update_role'>";
            echo "<button type='submit'>Update Session Role</button>";
            echo "</form>";
        }
    } else {
        echo "<p>User not found in database.</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Handle form submission to update role
if (isset($_POST['action']) && $_POST['action'] === 'update_role') {
    try {
        $db = Database::getInstance();
        $userId = $_SESSION['user_id'];
        
        $user = $db->get('users', '*', ['user_id' => $userId]);
        
        if ($user) {
            $_SESSION['role'] = $user['role'];
            echo "<p>Session updated successfully. Refreshing page...</p>";
            echo "<script>setTimeout(function() { window.location.reload(); }, 1000);</script>";
        }
    } catch (Exception $e) {
        echo "<p>Error updating session: " . $e->getMessage() . "</p>";
    }
}

// Add admin area link if role is admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    echo "<h2>Admin Links:</h2>";
    echo "<p><a href='/admin'>Go to Admin Dashboard</a></p>";
}

// Add logout link
echo "<p><a href='/logout'>Logout</a></p>"; 