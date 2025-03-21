<?php
session_start();
// Kết nối cơ sở dữ liệu từ file db_connect.php
require_once 'db_connect.php';

// Kiểm tra trạng thái kết nối
if (!isset($db_connected) || $db_connected === false) {
    die("Không thể kết nối đến cơ sở dữ liệu.");
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu khách hàng từ form
    $name = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $notes = $_POST['saysomething'];
    
    // Lấy dữ liệu giỏ hàng từ session
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Giỏ hàng của bạn trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.";
        exit;
    }
    
    $cartItems = $_SESSION['cart'];
    
    // Tính tổng số tiền
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    
    // Thêm phí vận chuyển
    $totalAmount += 15;
    
    try {
        // Bắt đầu giao dịch
        $conn->beginTransaction();
        
        // Chèn thông tin khách hàng
        $sql = "INSERT INTO customers (name, email, address, phone, notes) 
                VALUES (:name, :email, :address, :phone, :notes)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':notes', $notes);
        $stmt->execute();
        
        $customer_id = $conn->lastInsertId();
        
        // Chèn đơn hàng
        $sql = "INSERT INTO orders (customer_id, total_amount) VALUES (:customer_id, :total_amount)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->execute();
        
        $order_id = $conn->lastInsertId();
        
        // Chèn chi tiết đơn hàng
        $sql = "INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (:order_id, :product_name, :quantity, :price)";
        $stmt = $conn->prepare($sql);
        
        foreach ($cartItems as $item) {
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':product_name', $item['name']);
            $stmt->bindParam(':quantity', $item['quantity']);
            $stmt->bindParam(':price', $item['price']);
            $stmt->execute();
        }
        
        // Hoàn tất giao dịch
        $conn->commit();
        
        // Xóa giỏ hàng sau khi đặt hàng thành công
        $_SESSION['cart'] = array();
        
        // Đơn hàng thành công
        header("Location: order-confirmation.php?order_id=" . $order_id);
        exit;
    } catch (PDOException $e) {
        // Hoàn tác giao dịch nếu có lỗi
        $conn->rollBack();
        echo "Lỗi khi xử lý đơn hàng: " . $e->getMessage();
    }
} else {
    echo "Phương thức yêu cầu không hợp lệ";
}
?>