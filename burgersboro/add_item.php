<?php
require_once 'db_connection.php';
session_start();

if (!isset($_SESSION['is_staff']) || $_SESSION['is_staff'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO Shop_Items (name, description, price, category, image_url)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['category'],
            $_POST['image_url']
        ]);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add item']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}