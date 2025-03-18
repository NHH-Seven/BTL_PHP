<?php
// Kết nối đến cơ sở dữ liệu
require_once 'db_connect.php';

// Kiểm tra xem kết nối database đã thành công chưa
if (!isset($db_connected) || $db_connected !== true) {
    die("Không thể kết nối đến cơ sở dữ liệu");
}

// Hàm lấy tất cả đơn hàng
function getAllOrders() {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT o.order_id, o.customer_id, c.name as customer_name, 
                   o.total_amount, o.status, o.created_at
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.customer_id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Lỗi truy vấn đơn hàng: " . $e->getMessage());
        return [];
    }
}

// Hàm lấy chi tiết một đơn hàng
function getOrderDetails($order_id) {
    global $conn;
    try {
        // Lấy thông tin đơn hàng
        $stmt = $conn->prepare("
            SELECT o.order_id, o.customer_id, c.name as customer_name, 
                   o.total_amount, o.status, o.created_at
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.customer_id
            WHERE o.order_id = :order_id
        ");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch();
        
        if (!$order) {
            return null;
        }
        
        // Lấy các sản phẩm trong đơn hàng
        $stmt = $conn->prepare("
            SELECT oi.item_id, oi.product_name, oi.quantity, oi.price
            FROM order_items oi
            WHERE oi.order_id = :order_id
        ");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $order['items'] = $stmt->fetchAll();
        
        return $order;
    } catch(PDOException $e) {
        error_log("Lỗi lấy chi tiết đơn hàng: " . $e->getMessage());
        return null;
    }
}

// Hàm cập nhật trạng thái đơn hàng
function updateOrderStatus($order_id, $status) {
    global $conn;
    try {
        $stmt = $conn->prepare("
            UPDATE orders
            SET status = :status
            WHERE order_id = :order_id
        ");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    } catch(PDOException $e) {
        error_log("Lỗi cập nhật trạng thái đơn hàng: " . $e->getMessage());
        return false;
    }
}

// Hàm xóa đơn hàng
function deleteOrder($order_id) {
    global $conn;
    try {
        // Bắt đầu transaction
        $conn->beginTransaction();
        
        // Xóa các mục trong đơn hàng
        $stmt = $conn->prepare("
            DELETE FROM order_items
            WHERE order_id = :order_id
        ");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Xóa đơn hàng
        $stmt = $conn->prepare("
            DELETE FROM orders
            WHERE order_id = :order_id
        ");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        return true;
    } catch(PDOException $e) {
        // Rollback transaction nếu có lỗi
        $conn->rollBack();
        error_log("Lỗi xóa đơn hàng: " . $e->getMessage());
        return false;
    }
}

// Xử lý các hành động từ form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    
    $result = false;
    $message = '';
    
    switch ($action) {
        case 'update_status':
            $status = isset($_POST['status']) ? $_POST['status'] : '';
            if (!empty($status)) {
                $result = updateOrderStatus($order_id, $status);
                $message = $result ? "Đã cập nhật trạng thái đơn hàng thành công" : "Không thể cập nhật trạng thái đơn hàng";
            } else {
                $message = "Vui lòng chọn trạng thái";
            }
            break;
            
        case 'delete':
            $result = deleteOrder($order_id);
            $message = $result ? "Đã xóa đơn hàng thành công" : "Không thể xóa đơn hàng";
            break;
            
        case 'completed':
            $result = updateOrderStatus($order_id, 'completed');
            $message = $result ? "Đã hoàn thành đơn hàng thành công" : "Không thể hoàn thành đơn hàng";
            break;
    }
    
    // Thêm debug log để xác định vấn đề
    error_log("Action: $action, Order ID: $order_id, Result: " . ($result ? 'true' : 'false') . ", Message: $message");
    
    // Trả về kết quả dưới dạng JSON nếu là yêu cầu AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => $result, 'message' => $message]);
        exit;
    }
    
    // Nếu không phải AJAX, chuyển hướng lại trang quản lý đơn hàng
    header("Location: admin_checkout_view.php?message=" . urlencode($message) . "&success=" . ($result ? '1' : '0') . 
           (isset($_GET['view_detail']) ? "&view_detail=$order_id" : ""));
    exit;
}
?>