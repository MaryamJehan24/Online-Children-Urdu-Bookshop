<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'لاگ ان کرنے کی ضرورت ہے']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'] ?? 0;
    
    if ($book_id) {
        addToCart($_SESSION['user_id'], $book_id);
        $cart_items = getCartItems($_SESSION['user_id']);
        $cart_count = count($cart_items);
        
        echo json_encode([
            'success' => true,
            'message' => 'کتاب کارٹ میں شامل کر دی گئی ہے',
            'cart_count' => $cart_count
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'غلط کتاب ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'غلط درخواست']);
}
?>