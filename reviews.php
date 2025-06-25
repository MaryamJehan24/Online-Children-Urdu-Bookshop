<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$reviews = getAllReviews(10); // Get 10 latest reviews
$categories = getCategories();

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $book_id = $_POST['book_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    addReview($_SESSION['user_id'], $book_id, $rating, $comment);
    
    $_SESSION['success_message'] = "آپ کا ریویو جمع کرایا گیا ہے۔ شکریہ!";
    header("Location: reviews.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آراء - اردو بچوں کی کتابیں</title>
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
        
        .review-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .review-user {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .review-book {
            font-style: italic;
            color: #666;
        }
        
        .stars {
            color: var(--accent-color);
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
        <h1 class="text-center mb-4">گاہکوں کی آراء</h1>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success_message'] ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-8">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div>
                                <span class="review-user"><?= htmlspecialchars($review['username']) ?></span>
                                <span class="review-book">کتاب: <?= htmlspecialchars($review['title']) ?></span>
                            </div>
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $review['rating']): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p><?= htmlspecialchars($review['comment']) ?></p>
                        <small class="text-muted"><?= date('d M Y', strtotime($review['created_at'])) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="col-md-4">
                <?php if (isLoggedIn()): ?>
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4>اپنا ریویو شامل کریں</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="book_id" class="form-label">کتاب</label>
                                    <select class="form-select" id="book_id" name="book_id" required>
                                        <option value="">کتاب منتخب کریں</option>
                                        <?php foreach (getAllBooks() as $book): ?>
                                            <option value="<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">درجہ بندی</label>
                                    <div class="rating">
                                        <input type="radio" id="star5" name="rating" value="5" required>
                                        <label for="star5"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star4" name="rating" value="4">
                                        <label for="star4"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star3" name="rating" value="3">
                                        <label for="star3"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star2" name="rating" value="2">
                                        <label for="star2"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star1" name="rating" value="1">
                                        <label for="star1"><i class="fas fa-star"></i></label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label">تبصرہ</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">جمع کروائیں</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4>اپنا ریویو شامل کریں</h4>
                        </div>
                        <div class="card-body text-center">
                            <p>ریویو جمع کرانے کے لیے براہ کرم لاگ ان کریں۔</p>
                            <a href="login.php" class="btn btn-primary">لاگ ان</a>
                            <a href="register.php" class="btn btn-accent">رجسٹر</a>
                        </div>
                    </div>
                <?php endif; ?>
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