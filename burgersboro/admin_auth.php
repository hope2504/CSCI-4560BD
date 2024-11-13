<?php
session_start();
require_once 'db_connection.php';

function isStaffLoggedIn() {
    return isset($_SESSION['is_staff']) && $_SESSION['is_staff'] === true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['admin_password'] ?? '';
    if ($password === 'admin123') {
        $_SESSION['is_staff'] = true;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
    }
    exit;
}