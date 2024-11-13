<?php
session_start(); // Start the session

if (isset($_POST['item_index'])) {
    $index = $_POST['item_index'];

    // Check if the cart exists and the index is valid
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]); // Remove the item from the cart session

        // Optionally, reindex the cart array to maintain sequential indexes
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    } else {
        // Optional: Set a session variable for an error message if the item wasn't found
        $_SESSION['error_message'] = "Item not found in the cart.";
    }

    // Redirect back to the cart page
    header('Location: cart.php');
    exit();
} else {
    // Optional: Redirect to cart if the item index is not set
    header('Location: cart.php');
    exit();
}
?>
