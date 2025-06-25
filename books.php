<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$books = getAllBooks();
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمام کتابیں - اردو بچوں کی کتابیں</title>
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
        
        .book-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: white;
        }
        
        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .book-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .book-title {
            font-weight: bold;
            margin-top: 10px;
            color: var(--dark-color);
        }
        
        .book-author {
            color: #666;
            font-size: 0.9rem;
        }
        
        .book-price {
            color: var(--primary-color);
            font-weight: bold;
            margin: 10px 0;
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
                                    <?php 
                                    $cart_count = 0;
                                    if (isLoggedIn()) {
                                        $cart_items = getCartItems($_SESSION['user_id']);
                                        $cart_count = count($cart_items);
                                    }
                                    echo $cart_count;
                                    ?>
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
        <h1 class="text-center mb-4">تمام کتابیں</h1>
        
        <div class="row">
            <?php foreach ($books as $book): ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="book-card h-100">
                        <img src="<?= htmlspecialchars($book['image_url']) ?: 'https://via.placeholder.com/200x300?text=No+Image' ?>" class="book-img" alt="<?= htmlspecialchars($book['title']) ?>">
                        <div class="p-3">
                            <h5 class="book-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="book-author">مصنف: <?= htmlspecialchars($book['author']) ?></p>
                            <p class="book-price">قیمت: Rs. <?= htmlspecialchars($book['price']) ?></p>
                            <p><?= mb_substr(htmlspecialchars($book['description']), 0, 50) ?>...</p>
                            
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-sm btn-primary add-to-cart" data-book-id="<?= $book['id'] ?>">
                                    <i class="fas fa-cart-plus"></i> کارٹ میں شامل کریں
                                </button>
                                <a href="checkout.php?book_id=<?= $book['id'] ?>" class="btn btn-sm btn-accent">
                                    <i class="fas fa-bolt"></i> ابھی خریدیں
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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

    <!-- Cart Offcanvas (same as index.php) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">آپ کا کارٹ</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="cartContent">
            <!-- Cart items will be loaded here via AJAX -->
        </div>
        <div class="offcanvas-footer p-3">
            <a href="checkout.php" class="btn btn-primary w-100">خریداری جاری رکھیں</a>
        </div>
    </div>

    <!-- Success Toast (same as index.php) -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">کامیابی</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                آپ کی مصنوعات کارٹ میں شامل کر دی گئی ہے۔
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
            // Add to cart button click
            $('.add-to-cart').click(function() {
                const bookId = $(this).data('book-id');
                
                <?php if (isLoggedIn()): ?>
                    $.post('add_to_cart.php', { book_id: bookId }, function(response) {
                        if (response.success) {
                            // Update cart count
                            $('#cartCount').text(response.cart_count);
                            
                            // Show success message
                            $('#toastMessage').text(response.message);
                            $('#successToast').toast('show');
                            
                            // Open cart offcanvas
                            const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
                            cartOffcanvas.show();
                            
                            // Load cart content
                            loadCartContent();
                        }
                    }, 'json');
                <?php else: ?>
                    window.location.href = 'login.php';
                <?php endif; ?>
            });
            
            // Load cart content when offcanvas is shown
            $('#cartOffcanvas').on('show.bs.offcanvas', function() {
                loadCartContent();
            });
            
            // Function to load cart content via AJAX
            function loadCartContent() {
                $.get('cart_content.php', function(data) {
                    $('#cartContent').html(data);
                });
            }
        });
    </script>
</body>
</html>