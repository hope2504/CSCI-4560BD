<div class="cart-summary">
    <h3>Your Burger Cart</h3>
    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <li>
                    <?php echo htmlspecialchars($item['name']); ?> - 
                    <?php echo $item['quantity']; ?> x $<?php echo number_format($item['price'], 2); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>Total: $<?php echo number_format(array_sum(array_column($_SESSION['cart'], 'price')), 2); ?></p>
        <a href="cart.php" class="button">View Cart</a>
    <?php else: ?>
        <p>Your cart is empty. Start adding some tasty burgers!</p>
    <?php endif; ?>
</div>
