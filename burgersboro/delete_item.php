<?php
require_once 'db_connection.php';
session_start();

if (!isset($_SESSION['is_staff']) || $_SESSION['is_staff'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM Shop_Items WHERE item_id = ?");
        $result = $stmt->execute([$_POST['item_id']]);
        
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete item']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}