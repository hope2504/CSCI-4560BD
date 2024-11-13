<?php
session_start(); // Start the session

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log errors
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . PHP_EOL, 3, 'error.log');
}

// Include database connection details
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'burgers_boro'; // Updated to reflect the new database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    logError("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // Changed from 'name' to 'username'
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT user_id, password FROM Users WHERE name = ?"); // Updated to use 'name' instead of 'username'
    if (!$stmt) {
        logError("Prepare failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'An error occurred']);
        exit();
    }

    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        logError("Execute failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'An error occurred']);
        exit();
    }

    $stmt->store_result();

    // Check if a user with that username exists
    if ($stmt->num_rows > 0) {
        // Bind result
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['loggedin'] = true; // Store login status
            $_SESSION['username'] = $username; // Store username in session
            $_SESSION['user_id'] = $id; // Store user ID in session
            $_SESSION['message'] = "Login successful!"; // Optional: set success message

            // Return JSON response for AJAX request
            echo json_encode(['success' => true, 'message' => 'Login successful']);
        } else {
            // Incorrect password
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
        }
    } else {
        // Username doesn't exist
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }

    $stmt->close();
}

$conn->close();
?>