<?php
session_start();

require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    try {
        // Kiểm tra username
        $check_user = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $check_user->bindParam(':username', $username);
        $check_user->execute();
        
        if ($check_user->rowCount() == 1) {
            $user = $check_user->fetch();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                
                // Chuyển hướng đến trang khác nhau dựa vào role
                if ($user['role'] === 'admin') {
                    // Chuyển hướng đến trang index_2.php cho admin
                    header("Location: index_2.php");
                    exit();
                } else {
                    // Chuyển hướng đến trang index.php cho người dùng thông thường
                    header("Location: index.php");
                    exit();
                }
            } else {
                header("Location: login.php?error=Sai mật khẩu");
                exit();
            }
        } else {
            header("Location: login.php?error=Tên đăng nhập không tồn tại");
            exit();
        }
    } catch(PDOException $e) {
        header("Location: login.php?error=Lỗi hệ thống: " . urlencode($e->getMessage()));
        exit();
    }
}
?>