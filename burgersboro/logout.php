<?php
session_start(); // Start the session

// Unset all of the session variables
session_unset(); 

// Destroy the session
if (session_destroy()) {
    // Return JSON response
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
} else {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Logout failed']);
}

exit();
?>
