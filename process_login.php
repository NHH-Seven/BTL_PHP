<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Kiểm tra username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            // Chuyển hướng đến trang index.html cho tất cả người dùng
            header("Location: index.php");
        } else {
            header("Location: login.php?error=Sai mật khẩu");
        }
    } else {
        header("Location: login.php?error=Tên đăng nhập không tồn tại");
    }
}
?>