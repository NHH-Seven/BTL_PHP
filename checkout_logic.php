<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruit_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get customer data from the form
    $name = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $notes = $_POST['saysomething'];
    
    // Get cart data from session
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Giỏ hàng của bạn trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.";
        exit;
    }
    
    $cartItems = $_SESSION['cart'];
    
    // Calculate total amount
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    
    // Add shipping cost
    $totalAmount += 15;
    
    // Insert customer information
    $sql = "INSERT INTO customers (name, email, address, phone, notes) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $address, $phone, $notes);
    
    if ($stmt->execute()) {
        $customer_id = $conn->insert_id;
        
        // Insert order
        $sql = "INSERT INTO orders (customer_id, total_amount) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $customer_id, $totalAmount);
        
        if ($stmt->execute()) {
            $order_id = $conn->insert_id;
            
            // Insert order items
            $sql = "INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            foreach ($cartItems as $item) {
                $stmt->bind_param("isid", $order_id, $item['name'], $item['quantity'], $item['price']);
                $stmt->execute();
            }
            
            // Clear the cart after successful order
            $_SESSION['cart'] = array();
            
            // Order completed successfully
            header("Location: order-confirmation.php?order_id=" . $order_id);
            exit;
        } else {
            echo "Lỗi khi tạo đơn hàng: " . $conn->error;
        }
    } else {
        echo "Lỗi khi lưu thông tin khách hàng: " . $conn->error;
    }
    
    $stmt->close();
} else {
    echo "Phương thức yêu cầu không hợp lệ";
}

$conn->close();
?>