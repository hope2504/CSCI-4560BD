<header>
<div class="admin-section">
    <?php if (!isset($_SESSION['is_staff']) || $_SESSION['is_staff'] !== true): ?>
        <button id="adminLoginBtn" class="admin-login-btn">Admin</button>
    <?php else: ?>
        <button id="adminLogoutBtn" class="admin-logout-btn">Admin Logout</button>
    <?php endif; ?>
</div>

<!-- Add these modals just before the closing header tag -->
<div id="adminLoginModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Admin Login</h2>
        <form id="adminLoginForm">
            <input type="password" id="adminPassword" name="admin_password" placeholder="Enter admin password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
    
<div class="header-bg-image"></div>
    <div class="logo">
        <h1>Burgers Boro</h1>
        <p>Murfreesboro's Best Burgers</p>
    </div>

    <nav>
        <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#events">Events</a></li>
        <li><a href="index.php?page=shop">Menu</a></li>
        <li><a href="#contact">Contact Us</a></li>
        </ul>
    </nav>

    <!-- Authentication Buttons -->
    <div class="auth-buttons">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <button class="account-btn">Account</button>
            <button class="logout-btn">Log Out</button>
        <?php else: ?>
            <button class="signup-btn">Sign Up</button>
            <button class="login-btn">Login</button>
        <?php endif; ?>
    </div>

    <!-- Display Success or Error Message -->
    <div id="message-container"></div>

    <!-- Sign-Up Section -->
    <section class="signup-section" id="signupSection" style="display: none;">
        <h2>Sign Up</h2>
        <form id="signupForm" method="POST" action="signup.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="name" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required><br>

            <button type="submit">Sign Up</button>
        </form>
    </section>

    <!-- Login Section -->
    <section class="login-section" id="loginSection" style="display: none;">
        <h2>Login</h2>
        <form id="loginForm">
            <label for="login-username">Username:</label>
            <input type="text" id="login-username" name="username" required><br>
            <label for="login-password">Password:</label>
            <input type="password" id="login-password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
    </section>

    <!-- Account Section -->
    <section class="account-section" id="accountSection" style="display: none;">
        <h2>Account Information</h2>
        <div id="accountInfo">
            <p>Username: <span id="userLogin"></span></p>
            <p>Email: <span id="userEmail"></span></p>
            <p>Signed up on: <span id="userRegister"></span></p>
            <p>Phone: <span id="userPhone"></span></p>
            <p>Address: <span id="userAddress"></span></p>
            <button id="resetPasswordBtn">Reset Password</button>
        </div>

        <!-- Reset Password Section -->
        <div id="resetPasswordSection" style="display: none;">
            <h3>Reset Password</h3>
            <form id="resetPasswordForm">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required><br>
                <button type="submit">Submit</button>
            </form>
        </div>
    </section>

    <script src="header.js"></script>
</header>
