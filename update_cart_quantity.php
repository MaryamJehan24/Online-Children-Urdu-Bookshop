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
    $cart_id = $_POST['cart_id'] ?? 0;
    $change = $_POST['change'] ?? 0;
    
    if ($cart_id && $change) {
        // Get current quantity
        $cart_item = getCartItemById($cart_id);
        
        if ($cart_item && $cart_item['user_id'] == $_SESSION['user_id']) {
            $new_quantity = $cart_item['quantity'] + $change;
            
            if ($new_quantity < 1) {
                // Remove item if quantity becomes 0
                removeFromCart($cart_id);
                $message = 'کتاب کارٹ سے حذف کر دی گئی ہے';
            } else {
                // Update quantity
                updateCartItemQuantity($cart_id, $new_quantity);
                $message = 'کارٹ اپ ڈیٹ ہو گیا ہے';
            }
            
            $cart_items = getCartItems($_SESSION['user_id']);
            $cart_count = count($cart_items);
            
            echo json_encode([
                'success' => true,
                'message' => $message,
                'cart_count' => $cart_count
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'غلط کارٹ آئٹم']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'غلط درخواست']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'غلط درخواست']);
}

// Helper function to get cart item by ID
function getCartItemById($cart_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE id = ?");
    $stmt->execute([$cart_id]);
    return $stmt->fetch();
}
?>