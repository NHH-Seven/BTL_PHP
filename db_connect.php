<?php
// Thông tin kết nối đến cơ sở dữ liệu
$host = "localhost";     // Tên máy chủ MySQL
$dbname = "fruit_shop";    // Tên cơ sở dữ liệu
$username = "root";      // Tên người dùng MySQL
$password = "";          // Mật khẩu MySQL (mặc định rỗng)

// Thiết lập kết nối
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Đặt chế độ lỗi PDO để hiển thị exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Đặt mặc định fetch mode là associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Biến này sẽ được sử dụng để kiểm tra trạng thái kết nối
    $db_connected = true;
} catch(PDOException $e) {
    // Nếu có lỗi kết nối, ghi lại lỗi và đặt biến kết nối thành false
    error_log("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
    $db_connected = false;
    die("Không thể kết nối đến cơ sở dữ liệu. Vui lòng kiểm tra lại thông tin kết nối.");
}
?>