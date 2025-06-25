<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    echo '<div class="alert alert-info">لاگ ان کرنے کی ضرورت ہے</div>';
    exit();
}

$cart_items = getCartItems($_SESSION['user_id']);

if (empty($cart_items)): ?>
    <div class="alert alert-info">آپ کے کارٹ میں کوئی کتاب موجود نہیں ہے۔</div>
<?php else: 
    $total = 0;
    foreach ($cart_items as $item):
        $item_total = $item['price'] * $item['quantity'];
        $total += $item_total;
    ?>
        <div class="cart-item mb-3 pb-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="<?= htmlspecialchars($item['image_url']) ?: 'https://via.placeholder.com/60x90?text=No+Image' ?>" class="cart-item-img me-3" alt="<?= htmlspecialchars($item['title']) ?>">
                    <div>
                        <h6><?= htmlspecialchars($item['title']) ?></h6>
                        <p class="mb-0">Rs. <?= $item['price'] ?> x <?= $item['quantity'] ?></p>
                    </div>
                </div>
                <div>
                    <p class="mb-0">Rs. <?= $item_total ?></p>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn btn-sm btn-outline-secondary quantity-btn decrease-quantity" data-cart-id="<?= $item['id'] ?>">-</button>
                <input type="text" class="form-control quantity-input mx-2" value="<?= $item['quantity'] ?>" readonly style="width: 50px;">
                <button class="btn btn-sm btn-outline-secondary quantity-btn increase-quantity" data-cart-id="<?= $item['id'] ?>">+</button>
                <button class="btn btn-sm btn-danger ms-2 remove-from-cart" data-cart-id="<?= $item['id'] ?>">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
    
    <div class="d-flex justify-content-between align-items-center mt-3">
        <h6>کل رقم:</h6>
        <h5>Rs. <?= $total ?></h5>
    </div>
<?php endif; ?>