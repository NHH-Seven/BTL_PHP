<?php
// Include database connection
require_once 'db_connect.php';

// Check if database is connected before proceeding
if (!isset($db_connected) || $db_connected !== true) {
    die("Database connection is not available.");
}

/**
 * Chức năng lấy số lượng từ một bảng
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
 * Nhận dữ liệu thống kê
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
    
    // Count all orders
    $statistics['orders_count'] = getTableCount($conn, 'orders');
    
    // Count completed orders
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM orders WHERE status = 'completed'");
        $stmt->execute();
        $result = $stmt->fetch();
        $statistics['completed_orders_count'] = $result['count'];
    } catch (PDOException $e) {
        error_log("Error counting completed orders: " . $e->getMessage());
        $statistics['completed_orders_count'] = 0;
    }
    
    // Count comments
    $statistics['comments_count'] = getTableCount($conn, 'comments');
    
    $statistics['products_count'] = getTableCount($conn, 'products');
    
    return $statistics;
}

/**
 * Nhận số liệu thống kê doanh thu
 */
function getRevenueStatistics($conn) {
    $revenue = [];
    
    try {
        // Total revenue - CHỈ từ đơn hàng hoàn thành
        $stmt = $conn->prepare("SELECT SUM(total_amount) AS total_revenue FROM orders WHERE status = 'completed'");
        $stmt->execute();
        $result = $stmt->fetch();
        $revenue['total'] = $result['total_revenue'] ?: 0;
        
        // Revenue by status
        $stmt = $conn->prepare("SELECT status, SUM(total_amount) AS revenue FROM orders GROUP BY status");
        $stmt->execute();
        $revenue['by_status'] = $stmt->fetchAll();
        
        // Monthly revenue for current year - CHỈ từ đơn hàng hoàn thành
        $currentYear = date('Y');
        $stmt = $conn->prepare("
            SELECT 
                MONTH(created_at) AS month, 
                SUM(total_amount) AS revenue 
            FROM orders 
            WHERE YEAR(created_at) = :year 
            AND status = 'completed'
            GROUP BY MONTH(created_at) 
            ORDER BY month
        ");
        $stmt->bindParam(':year', $currentYear, PDO::PARAM_INT);
        $stmt->execute();
        $monthlyResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Khởi tạo mảng 12 tháng với giá trị 0
        $revenue['monthly'] = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue['monthly'][$i] = [
                'month' => $i,
                'revenue' => 0,
                'month_name' => date('F', mktime(0, 0, 0, $i, 1))
            ];
        }
        
        // Điền dữ liệu thực tế
        foreach ($monthlyResults as $item) {
            if (isset($item['month']) && isset($item['revenue'])) {
                $monthIndex = intval($item['month']);
                if ($monthIndex >= 1 && $monthIndex <= 12) {
                    $revenue['monthly'][$monthIndex]['revenue'] = floatval($item['revenue']);
                }
            }
        }
        
        // Tính doanh thu tối đa để tính tỷ lệ phần trăm
        $maxRevenue = 0;
        foreach ($revenue['monthly'] as $month) {
            if ($month['revenue'] > $maxRevenue) {
                $maxRevenue = $month['revenue'];
            }
        }
        $revenue['max_monthly'] = $maxRevenue;
        
        // Recent orders - hiển thị tất cả đơn hàng
        $stmt = $conn->prepare("
            SELECT o.order_id, o.total_amount, o.status, o.created_at, c.name as customer_name
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            ORDER BY o.created_at ASC
            LIMIT 10
        ");
        $stmt->execute();
        $revenue['recent_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Error getting revenue statistics: " . $e->getMessage());
    }
    
    return $revenue;
}

/**
 * Nhận số liệu thống kê chi tiết cho bảng điều khiển
 */
function getDashboardData($conn) {
    $data = [];
    
    // Thống kê cơ bản
    $data['statistics'] = getStatistics($conn);
    
    // Thống kê doanh thu
    $data['revenue'] = getRevenueStatistics($conn);
    
    // Get top customers - CHỈ từ đơn hàng hoàn thành
    try {
        $stmt = $conn->prepare("
            SELECT c.customer_id, c.name AS customer_name, c.email, 
                   COUNT(o.order_id) AS order_count, 
                   SUM(o.total_amount) AS total_spent
            FROM customers c
            JOIN orders o ON c.customer_id = o.customer_id
            WHERE o.status = 'completed'
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

    // Nhận tin tức phổ biến
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

// Nếu tệp này được gọi trực tiếp, hãy trả về dữ liệu JSON
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header('Content-Type: application/json');
    echo json_encode(getDashboardData($conn));
    exit;
}
?>