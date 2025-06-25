<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user = getCurrentUser();
$cart_items = getCartItems($_SESSION['user_id']);
$categories = getCategories();

if (empty($cart_items) && !isset($_GET['book_id'])) {
    header("Location: books.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    // In a real application, you would send the books to the email
    // For this demo, we'll just clear the cart and show a success message
    clearCart($_SESSION['user_id']);
    
    $_SESSION['success_message'] = "کتابیں آپ کے ای میل پر بھیج دی گئی ہیں۔ شکریہ!";
    header("Location: index.php");
    exit();
}

// If book_id is provided in URL, add it to cart
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    addToCart($_SESSION['user_id'], $book_id);
    header("Location: checkout.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چیک آؤٹ - اردو بچوں کی کتابیں</title>
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
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
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
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4>چیک آؤٹ</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">نام</label>
                                <input type="text" class="form-control" id="name" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">ای میل</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">کتابیں حاصل کریں</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>آپ کا آرڈر</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php 
                            $total = 0;
                            foreach ($cart_items as $item): 
                                $item_total = $item['price'] * $item['quantity'];
                                $total += $item_total;
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($item['image_url']) ?: 'https://via.placeholder.com/60x90?text=No+Image' ?>" class="cart-item-img me-3" alt="<?= htmlspecialchars($item['title']) ?>">
                                        <div>
                                            <h6><?= htmlspecialchars($item['title']) ?></h6>
                                            <small>Rs. <?= $item['price'] ?> x <?= $item['quantity'] ?></small>
                                        </div>
                                    </div>
                                    <span>Rs. <?= $item_total ?></span>
                                </li>
                            <?php endforeach; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>کل رقم</strong>
                                <strong>Rs. <?= $total ?></strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>