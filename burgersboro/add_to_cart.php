<?php
session_start();
require_once 'db_connection.php'; // Include the database connection

// Debug: Log that the script has been called
error_log("add_to_cart.php script called");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log the POST data
    error_log("POST data received: " . print_r($_POST, true));

    $item_id = $_POST['item_id'] ?? null;
    $quantity = $_POST['quantity'] ?? null;

    if ($item_id === null || $quantity === null) {
        error_log("Invalid POST data: item_id or quantity is missing");
        header('Location: index.php?page=shop&error=invalid_data');
        exit();
    }

    try {
        // Use the correct table and column names from the Shop_Items table
        $sql = "SELECT * FROM Shop_Items WHERE item_id = ? AND available = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$item_id]);
        $item = $stmt->fetch();

        if ($item) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Add the item to the cart session array
            $_SESSION['cart'][] = [
                'item_id' => $item['item_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $quantity
            ];

            // Debug: Log the updated cart
            error_log("Item added to cart. Updated cart: " . print_r($_SESSION['cart'], true));

            header('Location: index.php?page=shop&status=added');
            exit();
        } else {
            error_log("Item not found or unavailable in the database: " . $item_id);
            header('Location: index.php?page=shop&error=item_not_found');
            exit();
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header('Location: index.php?page=shop&error=database');
        exit();
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    header('Location: index.php?page=shop&error=invalid_request');
    exit();
}
?>
