<?php
// Error handling
function exception_error_handler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

// Start output buffering
ob_start();

try {
    session_start();

    error_log("Before including db_connection.php");

    // Include database connection
    require 'db_connection.php';

    error_log("After including db_connection.php");

    // Check if $pdo is defined and is a valid PDO object
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        throw new Exception("Valid database connection not found");
    }

    error_log("DB Connection included. pdo status: " . (isset($pdo) ? "set" : "not set"));

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $response = ['success' => false, 'message' => ''];

        if (!isset($_POST['newPassword']) || empty($_POST['newPassword'])) {
            throw new Exception('New password is required.');
        }

        if (!isset($_SESSION['user_id'])) {
            throw new Exception('User session not found.');
        }

        $newPassword = $_POST['newPassword'];
        $userId = $_SESSION['user_id'];

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        error_log("Preparing statement. pdo status: " . (isset($pdo) ? "set" : "not set"));

        // Update password in the database
        $stmt = $pdo->prepare("UPDATE Users SET password = :password WHERE user_id = :id");
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . implode(", ", $pdo->errorInfo()));
        }

        $result = $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $userId
        ]);

        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Password reset successfully.';
        } else {
            throw new Exception('Failed to reset password: ' . implode(", ", $stmt->errorInfo()));
        }

        echo json_encode($response);
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    // Clear the output buffer
    ob_clean();

    // Send JSON error response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} finally {
    // End output buffering and flush output
    ob_end_flush();
}
?>
