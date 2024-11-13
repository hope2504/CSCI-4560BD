document.addEventListener('DOMContentLoaded', function() {
    // Original Element References
    const authButtons = document.querySelector('.auth-buttons');
    const messageContainer = document.getElementById('message-container');
    const signupSection = document.getElementById('signupSection');
    const loginSection = document.getElementById('loginSection');
    const signupForm = document.getElementById('signupForm');
    const loginForm = document.getElementById('loginForm');
    const accountSection = document.getElementById('accountSection');
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');

    // Admin Element References
    const adminLoginBtn = document.getElementById('adminLoginBtn');
    const adminLogoutBtn = document.getElementById('adminLogoutBtn');
    const adminLoginModal = document.getElementById('adminLoginModal');
    const addItemModal = document.getElementById('addItemModal');
    const closeButtons = document.querySelectorAll('.close');

    // Original Toggle functions
    function toggleSignup() {
        signupSection.style.display = 'block';
        loginSection.style.display = 'none';
        accountSection.style.display = 'none';
        if (adminLoginModal) adminLoginModal.style.display = 'none';
        if (addItemModal) addItemModal.style.display = 'none';
    }

    function toggleLogin() {
        loginSection.style.display = 'block';
        signupSection.style.display = 'none';
        accountSection.style.display = 'none';
        if (adminLoginModal) adminLoginModal.style.display = 'none';
        if (addItemModal) addItemModal.style.display = 'none';
    }

    function toggleAccountSection() {
        if (accountSection.style.display === 'none' || accountSection.style.display === '') {
            accountSection.style.display = 'block';
            fetchAccountInfo();
        } else {
            accountSection.style.display = 'none';
        }
        if (adminLoginModal) adminLoginModal.style.display = 'none';
        if (addItemModal) addItemModal.style.display = 'none';
    }

    // Original Authentication functions
    function logout() {
        fetch('logout.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAuthButtons(false);
                    showMessage(data.message);
                    accountSection.style.display = 'none';
                }
            })
            .catch(error => console.error('Logout Error:', error));
    }

    function login(event) {
        event.preventDefault();
        const formData = new FormData(event.target);

        fetch('login.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAuthButtons(true);
                    showMessage(data.message);
                    loginSection.style.display = 'none';
                } else {
                    showMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Login Error:', error);
                showMessage('An error occurred. Please try again.');
            });
    }

    function signup(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        formData.append('role', 'customer');

        fetch('signup.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAuthButtons(true);
                    showMessage(data.message);
                    signupSection.style.display = 'none';
                } else {
                    showMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Signup Error:', error);
                showMessage('An error occurred. Please try again.');
            });
    }

    function updateAuthButtons(loggedIn) {
        if (loggedIn) {
            authButtons.innerHTML = `
                <button class="account-btn">Account</button>
                <button class="logout-btn">Log Out</button>
            `;
        } else {
            authButtons.innerHTML = `
                <button class="signup-btn">Sign Up</button>
                <button class="login-btn">Login</button>
            `;
        }
    }

    function showMessage(message) {
        messageContainer.textContent = message;
        setTimeout(() => messageContainer.textContent = '', 3000);
    }

    function fetchAccountInfo() {
        fetch('account_info.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }
                document.getElementById('userLogin').textContent = data.name;
                document.getElementById('userEmail').textContent = data.email;
                document.getElementById('userPhone').textContent = data.phone || 'N/A';
                document.getElementById('userAddress').textContent = data.address || 'N/A';
                document.getElementById('userRegister').textContent = data.user_registered || 'N/A';
            })
            .catch(error => console.error('Error fetching account info:', error));
    }

    function initializeAdminFeatures() {
        // Admin Login Button
        if (adminLoginBtn) {
            adminLoginBtn.addEventListener('click', () => {
                adminLoginModal.style.display = 'block';
                signupSection.style.display = 'none';
                loginSection.style.display = 'none';
                accountSection.style.display = 'none';
            });
        }

        // Admin Logout functionality
        if (adminLogoutBtn) {
            adminLogoutBtn.addEventListener('click', async () => {
                if (confirm('Are you sure you want to logout from admin mode?')) {
                    try {
                        const response = await fetch('admin_logout.php', {
                            method: 'POST'
                        });
                        const data = await response.json();
                        
                        if (data.success) {
                            showMessage('Admin logged out successfully');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showMessage(data.message || 'Failed to logout');
                        }
                    } catch (error) {
                        console.error('Admin Logout Error:', error);
                        showMessage('An error occurred during admin logout');
                    }
                }
            });
        }

        // Close Modals
        if (closeButtons) {
            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    if (adminLoginModal) adminLoginModal.style.display = 'none';
                    if (addItemModal) addItemModal.style.display = 'none';
                });
            });
        }

        // Admin Login Form
        const adminLoginForm = document.getElementById('adminLoginForm');
        if (adminLoginForm) {
            adminLoginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(adminLoginForm);
                
                try {
                    const response = await fetch('admin_auth.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        location.reload();
                    } else {
                        showMessage('Invalid admin password');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showMessage('An error occurred during admin login');
                }
            });
        }

        // Delete Item Buttons
        const deleteButtons = document.querySelectorAll('.delete-item-btn');
        if (deleteButtons) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    if (confirm('Are you sure you want to delete this item?')) {
                        const itemId = button.dataset.itemId;
                        const formData = new FormData();
                        formData.append('item_id', itemId);
                        
                        try {
                            const response = await fetch('delete_item.php', {
                                method: 'POST',
                                body: formData
                            });
                            const data = await response.json();
                            
                            if (data.success) {
                                button.closest('.item-card').remove();
                                showMessage('Item deleted successfully');
                            } else {
                                showMessage(data.message || 'Failed to delete item');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            showMessage('An error occurred while deleting item');
                        }
                    }
                });
            });
        }

        // Add New Item Card
        const addNewItemCards = document.querySelectorAll('.add-new-item');
        if (addNewItemCards) {
            addNewItemCards.forEach(card => {
                card.addEventListener('click', () => {
                    if (addItemModal) {
                        // Set the category based on current slide
                        const categorySelect = addItemModal.querySelector('select[name="category"]');
                        if (categorySelect) {
                            categorySelect.value = document.querySelector('#drinkSlide.active') ? 'beverage' : 'food';
                        }
                        addItemModal.style.display = 'block';
                        signupSection.style.display = 'none';
                        loginSection.style.display = 'none';
                        accountSection.style.display = 'none';
                    }
                });
            });
        }

        // Add Item Form
        const addItemForm = document.getElementById('addItemForm');
        if (addItemForm) {
            addItemForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(addItemForm);
                
                try {
                    const response = await fetch('add_item.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        // Get current slide based on which slide is active
                        const currentSlide = document.querySelector('#drinkSlide.active') ? 'drink' : 'food';
                        window.location.href = window.location.pathname + '?slide=' + currentSlide;
                    } else {
                        showMessage(data.message || 'Failed to add item');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showMessage('An error occurred while adding item');
                }
            });
        }
    }

    // Event Listeners
    document.body.addEventListener('click', function(event) {
        if (event.target.classList.contains('account-btn')) {
            toggleAccountSection();
        }
    });

    resetPasswordBtn.addEventListener('click', function() {
        document.getElementById('accountInfo').style.display = 'none';
        document.getElementById('resetPasswordSection').style.display = 'block';
    });

    const resetPasswordForm = document.getElementById('resetPasswordForm');
    resetPasswordForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);

        fetch('reset_password.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message);
                    resetPasswordForm.reset();
                    document.getElementById('resetPasswordSection').style.display = 'none';
                    document.getElementById('accountInfo').style.display = 'block';
                } else {
                    showMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Reset Password Error:', error);
                showMessage('An error occurred. Please try again.');
            });
    });

    authButtons.addEventListener('click', function(event) {
        if (event.target.classList.contains('signup-btn')) {
            toggleSignup();
        } else if (event.target.classList.contains('login-btn')) {
            toggleLogin();
        } else if (event.target.classList.contains('logout-btn')) {
            logout();
        }
    });

    loginForm.addEventListener('submit', login);
    signupForm.addEventListener('submit', signup);

    // Check initial login status
    fetch('check_login.php')
        .then(response => response.json())
        .then(data => {
            updateAuthButtons(data.loggedin);
        })
        .catch(error => {
            console.error('Error checking login status:', error);
        });

    // Initialize admin features
    initializeAdminFeatures();
});