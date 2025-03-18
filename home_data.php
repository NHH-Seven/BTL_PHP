<?php
// Include the database connection
require_once 'db_connect.php';

// Function to get featured products
function getFeaturedProducts($limit = 3) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT id, name, price, unit, category, description, image, status FROM products WHERE status = 'active' ORDER BY id LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error fetching products: " . $e->getMessage());
        return [];
    }
}

// Function to get latest news
function getLatestNews($limit = 3) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT id, title, excerpt, content, image, author, published_date FROM news ORDER BY published_date DESC LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Error fetching news: " . $e->getMessage());
        return [];
    }
}
?>