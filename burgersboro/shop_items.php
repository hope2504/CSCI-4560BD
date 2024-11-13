<?php
$is_staff = isset($_SESSION['is_staff']) && $_SESSION['is_staff'] === true;

// Get initial slide state from URL parameter
$initial_slide = isset($_GET['slide']) ? $_GET['slide'] : 'food';

// Organize items by category
$food_items = array_filter($shop_items, function($item) {
    return $item['category'] === 'food';
});

$drink_items = array_filter($shop_items, function($item) {
    return $item['category'] === 'beverage';
});
?>

<style>
.shop-container {
    position: relative;
    overflow: hidden;
}

.slide {
    width: 100%;
    display: none;
    opacity: 0;
    transition: opacity 0.5s ease;
}

.slide.active {
    display: block;
    opacity: 1;
}

.slide-button {
    background-color: var(--primary-color);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 20px auto;
    display: block;
    font-size: 1.1em;
    transition: background-color 0.3s ease;
}

.slide-button:hover {
    background-color: var(--secondary-color);
}

.warning {
    display: none;
}

.add-new-item {
    cursor: pointer;
}
</style>

<div class="shop-container">
    <!-- Food Items Slide -->
    <div class="slide <?php echo $initial_slide === 'food' ? 'active' : ''; ?>" id="foodSlide">
        <div class="shop-items">
            <h2>Menu</h2>
            <div class="items-grid">
                <?php foreach ($food_items as $item): ?>
                    <div class="item-card" data-item-id="<?php echo $item['item_id']; ?>">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                            <input type="number" name="quantity" value="1" min="1">
                            <button type="submit">Add to Cart</button>
                        </form>
                        <?php if ($is_staff): ?>
                            <button class="delete-item-btn" data-item-id="<?php echo $item['item_id']; ?>">
                                Delete Item
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if ($is_staff): ?>
                    <div class="item-card add-new-item">
                        <div class="add-item-button">
                            <span>+</span>
                            <p>Add New Item</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Drinks Items Slide -->
    <div class="slide <?php echo $initial_slide === 'drink' ? 'active' : ''; ?>" id="drinkSlide">
        <div class="shop-items">
            <h2>Drinks</h2>
            <div class="items-grid">
                <?php foreach ($drink_items as $item): ?>
                    <div class="item-card" data-item-id="<?php echo $item['item_id']; ?>">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                            <input type="number" name="quantity" value="1" min="1">
                            <button type="submit">Add to Cart</button>
                        </form>
                        <?php if ($is_staff): ?>
                            <button class="delete-item-btn" data-item-id="<?php echo $item['item_id']; ?>">
                                Delete Item
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if ($is_staff): ?>
                    <div class="item-card add-new-item">
                        <div class="add-item-button">
                            <span>+</span>
                            <p>Add New Item</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <button class="slide-button" id="slideButton">Next</button>
</div>

<!-- Single Add Item Modal -->
<?php if ($is_staff): ?>
<div id="addItemModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Item</h2>
        <form id="addItemForm">
            <input type="text" name="name" placeholder="Item Name" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <select name="category" required>
                <option value="food">Food</option>
                <option value="beverage">Beverage</option>
                <option value="dessert">Dessert</option>
            </select>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <button type="submit">Add Item</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const foodSlide = document.getElementById('foodSlide');
    const drinkSlide = document.getElementById('drinkSlide');
    const slideButton = document.getElementById('slideButton');
    
    // Initialize slide state
    let showingFood = <?php echo $initial_slide === 'food' ? 'true' : 'false'; ?>;

    // Slide button functionality
    slideButton.addEventListener('click', function() {
        if (showingFood) {
            foodSlide.classList.remove('active');
            drinkSlide.classList.add('active');
        } else {
            drinkSlide.classList.remove('active');
            foodSlide.classList.add('active');
        }
        showingFood = !showingFood;
    });

    // Add new item functionality
    const addNewItemCards = document.querySelectorAll('.add-new-item');
    addNewItemCards.forEach(card => {
        card.addEventListener('click', () => {
            const modal = document.getElementById('addItemModal');
            if (modal) {
                const categorySelect = modal.querySelector('select[name="category"]');
                if (categorySelect) {
                    categorySelect.value = document.querySelector('#drinkSlide.active') ? 'beverage' : 'food';
                }
                modal.style.display = 'block';
            }
        });
    });

    // Close modal when clicking the X
    const closeButtons = document.querySelectorAll('.close');
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('addItemModal').style.display = 'none';
        });
    });

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('addItemModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
</script>