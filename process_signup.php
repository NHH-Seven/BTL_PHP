<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Kiểm tra mật khẩu trùng khớp
    if ($password !== $confirm_password) {
        header("Location: signup.php?error=Mật khẩu không khớp");
        exit();
    }
    
    // Kiểm tra username đã tồn tại
    $check_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($check_user) > 0) {
        header("Location: signup.php?error=Tên đăng nhập hoặc email đã tồn tại");
        exit();
    }
    
    
    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Thêm user mới với các trường bổ sung từ database
    $sql = "INSERT INTO users (username, email, password_hash, first_name, last_name, phone) 
            VALUES ('$username', '$email', '$hashed_password', '$first_name', '$last_name', '$phone')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?success=Đăng ký thành công");
    } else {
        header("Location: signup.php?error=Đăng ký thất bại: " . mysqli_error($conn));
    }
}
?>