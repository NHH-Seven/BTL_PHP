<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra mật khẩu trùng khớp
    if ($password !== $confirm_password) {
        header("Location: signup.php?error=Mật khẩu không khớp");
        exit();
    }
    
    try {
        // Kiểm tra username hoặc email đã tồn tại
        $check_user = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $check_user->bindParam(':username', $username);
        $check_user->bindParam(':email', $email);
        $check_user->execute();
        
        if ($check_user->rowCount() > 0) {
            header("Location: signup.php?error=Tên đăng nhập hoặc email đã tồn tại");
            exit();
        }
        
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Kiểm tra số lượng người dùng trong bảng để xác định role
        $count_users = $conn->query("SELECT COUNT(*) as total FROM users");
        $row = $count_users->fetch();
        $role = ($row['total'] == 0) ? 'admin' : 'user';
        
        // Thêm user mới với role dựa trên việc có phải là user đầu tiên hay không
        $insert_user = $conn->prepare("INSERT INTO users (username, email, password, role, created_at) 
        VALUES (:username, :email, :password, :role, NOW())");
        $insert_user->bindParam(':username', $username);
        $insert_user->bindParam(':email', $email);
        $insert_user->bindParam(':password', $hashed_password);
        $insert_user->bindParam(':role', $role);
        
        if ($insert_user->execute()) {
            header("Location: login.php?success=Đăng ký thành công");
        } else {
            header("Location: signup.php?error=Đăng ký thất bại");
        }
    } catch(PDOException $e) {
        header("Location: signup.php?error=Lỗi hệ thống: " . urlencode($e->getMessage()));
        exit();
    }
}
?>