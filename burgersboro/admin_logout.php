<?php
session_start();

// Check if admin is logged in
if (isset($_SESSION['is_staff']) && $_SESSION['is_staff'] === true) {
    // Remove admin status
    unset($_SESSION['is_staff']);
    
    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Admin logged out successfully'
    ]);
} else {
    // Send error response if not logged in as admin
    echo json_encode([
        'success' => false,
        'message' => 'No admin session found'
    ]);
}
exit;