<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ہمارے بارے میں - اردو بچوں کی کتابیں</title>
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
        
        .about-section {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .team-member {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 5px solid var(--accent-color);
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
        <div class="about-section">
            <h1 class="text-center mb-4">ہمارے بارے میں</h1>
            
            <div class="row">
                <div class="col-md-6">
                    <h3>ہمارا مشن</h3>
                    <p>
                        اردو بچوں کی کتابیں ایک آن لائن پلیٹ فارم ہے جس کا مقصد بچوں کو معیاری اردو ادب سے روشناس کرانا ہے۔ ہمارا مشن ہے کہ ہر بچے تک اچھی اور تعلیمی کتابیں پہنچائیں تاکہ ان کی ذہنی اور اخلاقی نشوونما ہو سکے۔
                    </p>
                    <p>
                        ہماری کوشش ہے کہ بچوں کو جدید اور پرانی دونوں طرح کی اچھی کتابیں فراہم کی جائیں جو نہ صرف ان کی تعلیمی ضروریات پوری کریں بلکہ ان کے تخیل کو بھی پروان چڑھائیں۔
                    </p>
                </div>
                <div class="col-md-6">
                    <img src="https://images.unsplash.com/photo-1589998059171-988d887df646" alt="کتابیں" class="img-fluid rounded">
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="text-center mb-4">ہماری ٹیم</h3>
                </div>
                
                <div class="col-md-3">
                    <div class="team-member">
                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="ٹیم ممبر">
                        <h4>علی احمد</h4>
                        <p>بانی اور CEO</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="team-member">
                        <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="ٹیم ممبر">
                        <h4>فاطمہ خان</h4>
                        <p>محتسب</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="team-member">
                        <img src="https://randomuser.me/api/portraits/men/2.jpg" alt="ٹیم ممبر">
                        <h4>محمد عمر</h4>
                        <p>ڈیویلپر</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="team-member">
                        <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="ٹیم ممبر">
                        <h4>عائشہ ملک</h4>
                        <p>مصنف</p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12">
                    <h3>ہم سے رابطہ کریں</h3>
                    <p>
                        اگر آپ کے کوئی سوالات ہیں یا آپ ہماری ٹیم کا حصہ بننا چاہتے ہیں، تو براہ کرم ہم سے رابطہ کریں۔
                    </p>
                    <p>
                        <i class="fas fa-envelope me-2"></i> info@urdubooks.com<br>
                        <i class="fas fa-phone me-2"></i> 0300-1234567<br>
                        <i class="fas fa-map-marker-alt me-2"></i> لاہور، پاکستان
                    </p>
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