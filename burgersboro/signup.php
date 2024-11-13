<?php
session_start(); // Start the session

// Include database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'burgers_boro';  // Updated to your new database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password for security
    $phone = $_POST['phone']; // Get the phone number
    $address = $_POST['address']; // Get the address

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address)) {
        echo json_encode(['success' => false, 'message' => "All fields are required!"]);
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, 'customer')");
        $stmt->bind_param("sssss", $name, $email, $password, $phone, $address);

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $name; // Store the name in the session
            $_SESSION['user_id'] = $stmt->insert_id; // Store the user ID in the session
            echo json_encode(['success' => true, 'message' => "Sign-up successful! You are now logged in."]);
        } else {
            echo json_encode(['success' => false, 'message' => "Error: " . $stmt->error]);
        }

        $stmt->close();
    }
}

$conn->close();
?>
