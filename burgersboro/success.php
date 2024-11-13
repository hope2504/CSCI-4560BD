<?php
session_start();
echo "<h2>Payment successful! Thank you for your purchase.</h2>";

// Clear the cart after successful payment
unset($_SESSION['cart']);