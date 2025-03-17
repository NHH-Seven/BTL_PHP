<?php
// Include database connection
require_once 'db_connect.php';

// Check if database is connected before proceeding
if (!isset($db_connected) || $db_connected !== true) {
    die("Database connection is not available.");
}

/**
 * Function to get count from a table
 */
function getTableCount($conn, $tableName) {
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM $tableName");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    } catch (PDOException $e) {
        error_log("Error counting $tableName: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get statistics data
 */
function getStatistics($conn) {
    $statistics = [];
    
    // Count users
    $statistics['users_count'] = getTableCount($conn, 'users');
    
    // Count customers
    $statistics['customers_count'] = getTableCount($conn, 'customers');
    
    // Count FAQs
    $statistics['faqs_count'] = getTableCount($conn, 'faqs');
    
    // Count contacts/feedback
    $statistics['contacts_count'] = getTableCount($conn, 'contacts');
    
    // Count news
    $statistics['news_count'] = getTableCount($conn, 'news');
    
    // Count orders
    $statistics['orders_count'] = getTableCount($conn, 'orders');
    
    // Count comments
    $statistics['comments_count'] = getTableCount($conn, 'comments');
    
    return $statistics;
}

/**
 * Get revenue statistics
 */
/**
 * Get revenue statistics
 */
function getRevenueStatistics($conn) {
    $revenue = [];
    
    try {
        // Total revenue
        $stmt = $conn->prepare("SELECT SUM(total_amount) AS total_revenue FROM orders");
        $stmt->execute();
        $result = $stmt->fetch();
        $revenue['total'] = $result['total_revenue'] ?: 0;
        
        // Revenue by status
        $stmt = $conn->prepare("SELECT status, SUM(total_amount) AS revenue FROM orders GROUP BY status");
        $stmt->execute();
        $revenue['by_status'] = $stmt->fetchAll();
        
        // Monthly revenue for current year
        $year = date('Y'); // Tự động lấy năm hiện tại
        $stmt = $conn->prepare("
            SELECT 
                MONTH(created_at) AS month, 
                COALESCE(SUM(total_amount), 0) AS revenue 
            FROM orders 
            WHERE YEAR(created_at) = :year 
            GROUP BY MONTH(created_at) 
            ORDER BY month
        ");
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        $revenue['monthly'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debug
        error_log("Monthly revenue data: " . print_r($revenue['monthly'], true));
        
        // Recent orders
        // Recent orders
        $stmt = $conn->prepare("
            SELECT o.order_id, o.total_amount, o.status, o.created_at, c.name as customer_name
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            ORDER BY o.created_at ASC
            LIMIT 10
        ");
        $stmt->execute();
        $revenue['recent_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);// Thêm PDO::FETCH_ASSOC
        
    } catch (PDOException $e) {
        error_log("Error getting revenue statistics: " . $e->getMessage());
    }
    
    return $revenue;
}

/**
 * Get detailed statistics for dashboard
 */
function getDashboardData($conn) {
    $data = [];
    
    // Basic statistics
    $data['statistics'] = getStatistics($conn);
    
    // Revenue statistics
    $data['revenue'] = getRevenueStatistics($conn);
    
    // Get top customers
// Get top customers
    try {
        $stmt = $conn->prepare("
            SELECT c.customer_id, c.name AS customer_name, c.email, COUNT(o.order_id) AS order_count, SUM(o.total_amount) AS total_spent
            FROM customers c
            JOIN orders o ON c.customer_id = o.customer_id
            GROUP BY c.customer_id
            ORDER BY total_spent ASC
            LIMIT 5
        ");
        $stmt->execute();
        $data['top_customers'] = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error getting top customers: " . $e->getMessage());
        $data['top_customers'] = [];
    }

    // Get popular news
    try {
        $stmt = $conn->prepare("
            SELECT n.id, n.title, COUNT(c.id) AS comment_count
            FROM news n
            LEFT JOIN comments c ON n.id = c.news_id
            GROUP BY n.id
            ORDER BY comment_count ASC
            LIMIT 5
        ");
        $stmt->execute();
        $data['popular_news'] = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error getting popular news: " . $e->getMessage());
        $data['popular_news'] = [];
    }
    
    return $data;
}

// If this file is called directly, return JSON data
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header('Content-Type: application/json');
    echo json_encode(getDashboardData($conn));
    exit;
}
?>