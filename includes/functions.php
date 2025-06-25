<?php
require_once 'db.php';

// Get all categories
function getCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories");
    return $stmt->fetchAll();
}

// Get category by ID
function getCategoryById($category_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetch();
}

// Get books by category
function getBooksByCategory($category_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM books WHERE category_id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetchAll();
}

// Get book by ID
function getBookById($book_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    return $stmt->fetch();
}

// Get all books
function getAllBooks($limit = null) {
    global $pdo;
    $sql = "SELECT * FROM books";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Search books
function searchBooks($query) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR description LIKE ?");
    $stmt->execute(["%$query%", "%$query%", "%$query%"]);
    return $stmt->fetchAll();
}

// Get reviews for a book
function getBookReviews($book_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT reviews.*, users.username FROM reviews JOIN users ON reviews.user_id = users.id WHERE book_id = ?");
    $stmt->execute([$book_id]);
    return $stmt->fetchAll();
}

// Add to cart
function addToCart($user_id, $book_id, $quantity = 1) {
    global $pdo;
    
    // Check if item already in cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$user_id, $book_id]);
    $item = $stmt->fetch();
    
    if ($item) {
        // Update quantity
        $new_quantity = $item['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_quantity, $item['id']]);
    } else {
        // Add new item
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $book_id, $quantity]);
    }
}

// Get cart items
function getCartItems($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT cart.*, books.title, books.price, books.image_url FROM cart JOIN books ON cart.book_id = books.id WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Remove from cart
function removeFromCart($cart_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->execute([$cart_id]);
}

// Update cart item quantity
function updateCartItemQuantity($cart_id, $quantity) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $cart_id]);
}

// Clear cart
function clearCart($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
}

// Add review
function addReview($user_id, $book_id, $rating, $comment) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, book_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $book_id, $rating, $comment]);
}

// Get all reviews
function getAllReviews($limit = null) {
    global $pdo;
    $sql = "SELECT reviews.*, users.username, books.title FROM reviews JOIN users ON reviews.user_id = users.id JOIN books ON reviews.book_id = books.id";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Admin functions
function addBook($title, $author, $description, $price, $category_id, $image_url) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO books (title, author, description, price, category_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $author, $description, $price, $category_id, $image_url]);
    return $pdo->lastInsertId();
}

function updateBook($book_id, $title, $author, $description, $price, $category_id, $image_url) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, description = ?, price = ?, category_id = ?, image_url = ? WHERE id = ?");
    $stmt->execute([$title, $author, $description, $price, $category_id, $image_url, $book_id]);
}

function deleteBook($book_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
}

function addCategory($name, $description) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->execute([$name, $description]);
    return $pdo->lastInsertId();
}

function updateCategory($category_id, $name, $description) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $description, $category_id]);
}

function deleteCategory($category_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);
}

function deleteReview($review_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$review_id]);
}