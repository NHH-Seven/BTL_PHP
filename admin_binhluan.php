<?php
// Kết nối đến cơ sở dữ liệu
require_once 'db_connect.php';

// Kiểm tra xem đã kết nối thành công chưa
if (!isset($db_connected) || $db_connected === false) {
    die("Không thể kết nối đến cơ sở dữ liệu");
}

// Hàm xử lý xóa bình luận
function deleteComment($id) {
    global $conn;
    
    try {
        // Chuẩn bị câu truy vấn DELETE
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = :id");
        
        // Bind tham số
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Thực hiện truy vấn
        $result = $stmt->execute();
        
        if ($result) {
            return ["success" => true, "message" => "Đã xóa bình luận thành công"];
        } else {
            return ["success" => false, "message" => "Không thể xóa bình luận"];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Lỗi: " . $e->getMessage()];
    }
}

// Hàm xử lý cập nhật bình luận
function updateComment($id, $content) {
    global $conn;
    
    try {
        // Chuẩn bị câu truy vấn UPDATE
        $stmt = $conn->prepare("UPDATE comments SET content = :content, comment_date = NOW() WHERE id = :id");
        
        // Bind các tham số
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        
        // Thực hiện truy vấn
        $result = $stmt->execute();
        
        if ($result) {
            return ["success" => true, "message" => "Đã cập nhật bình luận thành công"];
        } else {
            return ["success" => false, "message" => "Không thể cập nhật bình luận"];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Lỗi: " . $e->getMessage()];
    }
}

// Hàm lấy thông tin một bình luận theo ID
function getCommentById($id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM comments WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

// Hàm lấy danh sách tất cả bình luận
function getAllComments($offset = 0, $records_per_page = 10) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("
            SELECT c.*, 
                   n.title as news_title
            FROM comments c
            LEFT JOIN news n ON c.news_id = n.id
            ORDER BY c.comment_date DESC
            LIMIT :offset, :records_per_page
        ");
        
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Lỗi truy vấn: " . $e->getMessage());
        return [];
    }
}
function getTotalComments() {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM comments");
        $stmt->execute();
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Lỗi truy vấn: " . $e->getMessage());
        return 0;
    }
}
// Xử lý các hành động từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý yêu cầu xóa bình luận
    if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
        $deleteResult = deleteComment($_POST['id']);
        
        // Lưu kết quả vào session để hiển thị thông báo
        session_start();
        $_SESSION['message'] = $deleteResult['message'];
        $_SESSION['message_type'] = $deleteResult['success'] ? 'success' : 'error';
        
        // Chuyển hướng về trang danh sách
        header("Location: admin_binhluan_view.php");
        exit;
    }
    
    // Xử lý yêu cầu cập nhật bình luận
    if (isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['id']) && isset($_POST['content'])) {
        $updateResult = updateComment($_POST['id'], $_POST['content']);
        
        // Lưu kết quả vào session để hiển thị thông báo
        session_start();
        $_SESSION['message'] = $updateResult['message'];
        $_SESSION['message_type'] = $updateResult['success'] ? 'success' : 'error';
        
        // Chuyển hướng về trang danh sách
        header("Location: admin_binhluan_view.php");
        exit;
    }
}

// Xử lý các yêu cầu GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Xử lý yêu cầu xóa (nếu được gửi qua URL)
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $deleteResult = deleteComment($_GET['id']);
        
        // Lưu kết quả vào session để hiển thị thông báo
        session_start();
        $_SESSION['message'] = $deleteResult['message'];
        $_SESSION['message_type'] = $deleteResult['success'] ? 'success' : 'error';
        
        // Chuyển hướng về trang danh sách
        header("Location: admin_binhluan_view.php");
        exit;
    }
}
?>