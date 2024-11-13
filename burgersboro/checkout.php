<?php
session_start();
require_once 'db_connection.php'; // Updated to use require_once for consistency
require 'vendor/autoload.php'; // Include the Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51Q21U8P51RCIRPdIpxCvAFWYMeQWkPdOCtn9WKlH2F2TBABYidBVSgBYPUxqEcWJMWjI94J7Olc3CGFI2qKWhMfJ00F3DTOXNL'); // Your Stripe Secret Key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Calculate the total amount
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    // Insert the order into the database
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    try {
        $sql = "INSERT INTO Orders (user_id, order_date, total_price, status) VALUES (?, NOW(), ?, 'pending')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $total_amount]);
        $order_id = $pdo->lastInsertId();

        // Insert order items
        $sql = "INSERT INTO Order_Items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        foreach ($_SESSION['cart'] as $item) {
            $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
        }

        // Clear the cart after order is created
        unset($_SESSION['cart']);

        // Create Stripe Checkout session
        $totalAmountInCents = $total_amount * 100; // Stripe accepts payments in cents

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Burgers Boro Order',
                        ],
                        'unit_amount' => $totalAmountInCents,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => 'http://localhost/burgers_boro/success.php',
            'cancel_url' => 'http://localhost/burgers_boro/cancel.php',
        ]);

        // Redirect to Stripe Checkout page
        header("Location: " . $session->url);
        exit();
    } catch (PDOException $e) {
        // Handle any database errors
        error_log("Database error: " . $e->getMessage());
        header('Location: index.php?page=shop&error=database');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Burgers Boro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; // Include the header file ?>

    <main>
        <h2>Checkout</h2>
        <form action="checkout.php" method="POST">
            <!-- Add form fields for shipping information if needed -->
            <button type="submit">Proceed to Payment</button>
        </form>
    </main>

    <footer>
        <div class="footer-top">
            <div>
                <p>General Inquiries: contact@burgersboro.com</p>
                <p>Restaurant Address: 123 Burger Lane, Murfreesboro, TN</p>
                <p>Business Hours: Mon to Fri: 10AM to 10PM, Sat: 11AM to 11PM</p>
            </div>
            <div class="newsletter">
                <p>Get your free weekly tips!</p>
                <input type="email" placeholder="Your email">
                <button>Subscribe</button>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Burgers Boro. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
