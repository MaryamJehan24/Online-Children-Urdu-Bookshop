<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}

$total_books = count(getAllBooks());
$total_categories = count(getCategories());
$total_reviews = count(getAllReviews());
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ایڈمن ڈیش بورڈ - اردو بچوں کی کتابیں</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #FF6B6B;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --dark-color: #292F36;
            --light-color: #F7FFF7;
        }
        
        body {
            font-family: 'Jameel Noori Nastaleeq', 'Noto Nastaliq Urdu', serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background-color: var(--dark-color);
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: var(--primary-color);
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-icon {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        
        .card-book {
            background: linear-gradient(135deg, #FF6B6B, #ff8e8e);
            color: white;
        }
        
        .card-category {
            background: linear-gradient(135deg, #4ECDC4, #7ae1da);
            color: white;
        }
        
        .card-review {
            background: linear-gradient(135deg, #FFE66D, #fff191);
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
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar p-3" style="width: 250px;">
            <div class="text-center mb-4">
                <h4>ایڈمن پینل</h4>
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> ڈیش بورڈ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="books.php">
                        <i class="fas fa-book me-2"></i> کتابیں
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviews.php">
                        <i class="fas fa-star me-2"></i> آراء
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link" href="../index.php">
                        <i class="fas fa-arrow-left me-2"></i> ویب سائٹ پر جائیں
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> لاگ آؤٹ
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <h1 class="mb-4">ڈیش بورڈ</h1>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card card-book text-white p-4">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h5>کل کتابیں</h5>
                            <h2><?= $total_books ?></h2>
                            <a href="books.php" class="text-white">مزید دیکھیں <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card card-category text-white p-4">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <h5>کل زمرہ جات</h5>
                            <h2><?= $total_categories ?></h2>
                            <a href="books.php" class="text-white">مزید دیکھیں <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card card-review p-4">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h5>کل آراء</h5>
                            <h2><?= $total_reviews ?></h2>
                            <a href="reviews.php" class="text-dark">مزید دیکھیں <i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>حالیہ سرگرمیاں</h5>
                </div>
                <div class="card-body">
                    <p>یہاں حالیہ سرگرمیوں کا چارٹ یا معلومات ظاہر کی جائیں گی۔</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>