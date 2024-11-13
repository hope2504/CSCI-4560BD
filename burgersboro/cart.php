<?php
session_start();
require_once 'db_connection.php'; // Use require_once for consistency
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Burgers Boro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <?php include 'header.php'; // Include the header file ?>
    </header>

    <main>
        <h2>Your Shopping Cart</h2>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <table>
                <thead>
                    <tr>
                        <th>Burger</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $index => $item):
                        $item_total = $item['price'] * $item['quantity'];
                        $total += $item_total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item_total, 2); ?></td>
                            <td>
                                <form action="remove_from_cart.php" method="POST">
                                    <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                                    <button type="submit" class="button">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td>$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
            <a href="checkout.php" class="button">Proceed to Checkout</a>
        <?php else: ?>
            <p>Your cart is empty. Start adding some delicious burgers!</p>
        <?php endif; ?>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="footer-top">
            <div>
                <p>General Inquiries: info@burgersboro.com</p>
                <p>Restaurant Address: 123 Burger Lane, Murfreesboro, TN 37130, USA</p>
                <p>Business Hours: Mon to Fri: 10AM to 10PM, Sat to Sun: 11AM to 11PM</p>
            </div>
            <div class="newsletter">
                <p>Join our newsletter for exclusive deals!</p>
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
