<?php
require_once 'config.php';

// Database connection
$host = 'localhost';
$dbname = 'urdu_children_bookshop';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("ڈیٹا بیس کنکشن ناکام: " . $e->getMessage());
}
?>