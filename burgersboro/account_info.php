<?php
session_start();
require 'db_connection.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Prepare the SQL statement with the correct column names from the Users table
    $stmt = $pdo->prepare('SELECT name, email, phone, address, user_registered FROM Users WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $userId]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userInfo) {
        // Format the timestamp for user_registered
        $userInfo['user_registered'] = date('F j, Y, g:i a', strtotime($userInfo['user_registered']));
        
        // Optionally, format other fields if necessary (e.g., trimming address)
        $userInfo['address'] = trim($userInfo['address']);
        
        echo json_encode($userInfo);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} else {
    echo json_encode(['error' => 'Not logged in']);
}
?>
