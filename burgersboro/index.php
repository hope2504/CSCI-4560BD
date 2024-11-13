<?php
session_start(); // Start the session
require_once 'db_connection.php'; // Include database connection

// Fetch burger items from the database
try {
    $sql = "SELECT * FROM shop_items"; // Changed from chess_items to shop_items
    $stmt = $pdo->query($sql); // Changed from $conn to $pdo
    $shop_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $shop_items = []; // Initialize as empty array if query fails
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burgers Boro - Murfreesboro's Best Burgers</title>
    
    <!-- External CSS Files First -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Your Custom CSS Last -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Header Section -->
    <?php include 'header.php'; // Include the header file ?>

    <!-- Main Section -->
    <main>
    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    ?>

    <div class="page-content">
        <!-- Main content for shop -->
        <div class="main-content">
            <?php
            if ($page === 'home' || $page === 'shop') {
                include 'shop_items.php';  // Include the shop items
            }
            ?>
        </div>

        <!-- Cart Sidebar -->
        <div class="cart-sidebar">
            <?php include 'cart_summary.php'; // Include the cart summary ?>
        </div>
    </div>

        <!-- Restaurant Information Section -->
        <section id="about" class="restaurant-info">
            <div class="info">
                <h2>Burgers Boro</h2>
                <p>1234 Burger Lane, Murfreesboro, TN 37130</p>
                <p>Hours of Operation: Mon-Sun: 11am - 9pm</p>
            </div>
        </section>

        <!-- Links Section -->
        <section class="burger-links">
            <div class="link-box">
                <h3>Check our Menu</h3>
                <a href="#">Menu</a>
            </div>
            <div class="link-box">
                <h3>Order Online!</h3>
                <a href="#">Order Now</a>
            </div>
            <div class="link-box">
                <h3>Join our Rewards Program</h3>
                <a href="#">Rewards</a>
            </div>
            <div class="link-box">
                <h3>Follow us on Social Media!</h3>
                <a href="#">Instagram</a>
            </div>
        </section>

        <!-- Membership Section -->
<section class="membership">
    <h2>Become a Burgers Boro VIP Member</h2>
    <ul>
        <li>Exclusive discounts on burgers</li>
        <li>Special invites to tasting events</li>
        <li>Free fries on your birthday</li>
    </ul>
</section>

<!-- Upcoming Events Section -->
<section class="events">
    <h2>Upcoming Events</h2>
    <div class="event">
        <h3>Burger Eating Contest</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <div class="event">
        <h3>Live Music Night</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <div class="event">
        <h3>Exclusive Burger Tasting</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery">
    <h2>Our Delicious Burgers</h2>
    <div class="gallery-grid">
        <img src="https://menuimages.chownowcdn.com/image-resizing?image=2513b34d-b753-4497-bd7a-178e3c196361.jpg&left=0&top=0&right=0&bottom=0&quality=85&fit=contain" alt="Burger Image 1">
        <img src="https://live.staticflickr.com/65535/50038157088_40b7bc1116_b.jpg" alt="Burger Image 2">
        <img src="https://static.spotapps.co/spots/b9/b1721283f243eaa8e2e6d4809e6d4e/full" alt="Burger Image 3">
        <img src="https://clubparadisefound.com/wp-content/uploads/2022/02/paradise-found-beer-burger-wed_4x6_web-1280x_v4a.jpg" alt="Burger Image 4">
    </div>
</section>

<!-- Connect Section -->
<section class="connect">
    <h2>Connect with Burgers Boro</h2>
    <p>Follow us for the latest updates and mouthwatering offers!</p>
    <div class="contact-info">
        <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <p>1234 Burger Lane, Murfreesboro, TN 37130</p>
        </div>
        <div class="contact-item">
            <i class="fas fa-phone-alt"></i>
            <p>(615) 555-1234</p>
        </div>
        <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <p>info@burgersboro.com</p>
        </div>
        <div class="contact-item">
            <i class="fas fa-clock"></i>
            <p>Mon to Sun: 11AM to 9PM</p>
        </div>
    </div>
</section>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="footer-top">
            <div>
                <p>General Inquiries: info@burgersboro.com</p>
                <p>Restaurant Address: 1234 Burger Lane, Murfreesboro, TN 37130</p>
                <p>Business Hours: Mon-Sun: 11AM to 9PM</p>
            </div>
            <div class="newsletter">
                <p>Sign up for exclusive deals!</p>
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
