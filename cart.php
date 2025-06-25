<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$cart_items = getCartItems($_SESSION['user_id']);
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آپ کا کارٹ - اردو بچوں کی کتابیں</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Same styles as index.php */
        :root {
            --primary-color: #FF6B6B;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --dark-color: #292F36;
            --light-color: #F7FFF7;
        }
        
        body {
            font-family: 'Jameel Noori Nastaleeq', 'Noto Nastaliq Urdu', serif;
            background-color: var(--light-color);
            color: var(--dark-color);
        }
        
        .navbar {
            background-color: var(--primary-color) !important;
        }
        
        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        
        .quantity-input {
            width: 50px;
            text-align: center;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--dark-color);
        }
        
        .btn-accent:hover {
            background-color: #ffdc40;
            border-color: #ffdc40;
            color: var(--dark-color);
        }
        
        /* RTL adjustments */
        .dropdown-menu {
            text-align: right;
        }
        
        .me-2 {
            margin-left: 0.5rem !important;
            margin-right: inherit !important;
        }
        
        .ms-2 {
            margin-right: 0.5rem !important;
            margin-left: inherit !important;
        }
        
        .me-3 {
            margin-left: 1rem !important;
            margin-right: inherit !important;
        }
        
        .ms-3 {
            margin-right: 1rem !important;
            margin-left: inherit !important;
        }
        
        .pe-2 {
            padding-left: 0.5rem !important;
            padding-right: inherit !important;
        }
        
        .ps-2 {
            padding-right: 0.5rem !important;
            padding-left: inherit !important;
        }
        
        .text-end {
            text-align: left !important;
        }
        
        .text-start {
            text-align: right !important;
        }
    </style>
</head>
<body>
    <!-- Navigation (same as index.php) -->
    <nav class="navbar navbar-expand-lg navbar-dark">
	 
        <div class="container">
            <a class="navbar-brand" href="index.php">
               <h2>اردو بچوں کی کتابیں</h2>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown category-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown">
                            زمرہ جات
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($categories as $category): ?>
                                <li><a class="dropdown-item" href="categories.php?id=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">ہمارے بارے میں</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reviews.php">آراء</a>
                    </li>
                </ul>
                
                <form class="d-flex me-3" action="search.php" method="get">
                    <input class="form-control me-2" type="search" placeholder="کتاب تلاش کریں..." name="q" aria-label="Search">
                    <button class="btn btn-accent" type="submit">تلاش</button>
                </form>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge bg-light text-dark" id="cartCount">
                                    <?= count($cart_items) ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <?= htmlspecialchars($_SESSION['username']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="logout.php">لاگ آؤٹ</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">لاگ ان</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">رجسٹر</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <h1 class="text-center mb-4">آپ کا کارٹ</h1>
        
        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info text-center">
                آپ کے کارٹ میں کوئی کتاب موجود نہیں ہے۔
            </div>
            <div class="text-center">
                <a href="books.php" class="btn btn-primary">کتابیں دیکھیں</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>کتاب</th>
                            <th>قیمت</th>
                            <th>تعداد</th>
                            <th>کل</th>
                            <th>عمل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($cart_items as $item): 
                            $item_total = $item['price'] * $item['quantity'];
                            $total += $item_total;
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($item['image_url']) ?: 'https://via.placeholder.com/100x150?text=No+Image' ?>" class="cart-item-img me-3" alt="<?= htmlspecialchars($item['title']) ?>">
                                        <div>
                                            <h6><?= htmlspecialchars($item['title']) ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td>Rs. <?= htmlspecialchars($item['price']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-sm btn-outline-secondary quantity-btn decrease-quantity" data-cart-id="<?= $item['id'] ?>">-</button>
                                        <input type="text" class="form-control quantity-input mx-2" value="<?= $item['quantity'] ?>" readonly>
                                        <button class="btn btn-sm btn-outline-secondary quantity-btn increase-quantity" data-cart-id="<?= $item['id'] ?>">+</button>
                                    </div>
                                </td>
                                <td>Rs. <?= $item_total ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger remove-from-cart" data-cart-id="<?= $item['id'] ?>">
                                        <i class="fas fa-trash"></i> حذف کریں
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>کل رقم:</strong></td>
                            <td><strong>Rs. <?= $total ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="text-center mt-4">
                <a href="books.php" class="btn btn-secondary me-2">مزید کتابیں شامل کریں</a>
                <a href="checkout.php" class="btn btn-primary">خریداری جاری رکھیں</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer (same as index.php) -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>اردو بچوں کی کتابیں</h5>
                    <p>بچوں کی تعلیم و تربیت کے لیے بہترین اردو کتابیں</p>
                </div>
                <div class="col-md-4">
                    <h5>تیز لنکس</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">ہوم</a></li>
                        <li><a href="books.php">کتابیں</a></li>
                        <li><a href="about.php">ہمارے بارے میں</a></li>
                        <li><a href="reviews.php">آراء</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>رابطہ کریں</h5>
                    <p>ای میل: info@urdubooks.com</p>
                    <p>فون: 0300-1234567</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2025 اردو بچوں کی کتابیں۔ تمام حقوق محفوظ ہیں۔</p>
            </div>
        </div>
    </footer>

    <!-- Success Toast (same as index.php) -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">کامیابی</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                <!-- Message will be shown here -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
            // Increase quantity
            $('.increase-quantity').click(function() {
                const cartId = $(this).data('cart-id');
                updateCartItemQuantity(cartId, 1);
            });
            
            // Decrease quantity
            $('.decrease-quantity').click(function() {
                const cartId = $(this).data('cart-id');
                updateCartItemQuantity(cartId, -1);
            });
            
            // Remove from cart
            $('.remove-from-cart').click(function() {
                const cartId = $(this).data('cart-id');
                
                if (confirm('کیا آپ واقعی اس کتاب کو کارٹ سے حذف کرنا چاہتے ہیں؟')) {
                    $.post('remove_from_cart.php', { cart_id: cartId }, function(response) {
                        if (response.success) {
                            // Show success message
                            $('#toastMessage').text(response.message);
                            $('#successToast').toast('show');
                            
                            // Reload page after 1 second
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    }, 'json');
                }
            });
            
            // Function to update cart item quantity
            function updateCartItemQuantity(cartId, change) {
                $.post('update_cart_quantity.php', { 
                    cart_id: cartId, 
                    change: change 
                }, function(response) {
                    if (response.success) {
                        // Show success message
                        $('#toastMessage').text(response.message);
                        $('#successToast').toast('show');
                        
                        // Reload page after 1 second
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }, 'json');
            }
        });
    </script>
</body>
</html>