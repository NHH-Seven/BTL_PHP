<?php
// Kết nối đến cơ sở dữ liệu
require_once 'db_connect.php';

// Kiểm tra kết nối
if (!isset($db_connected) || $db_connected === false) {
    die("Không thể kết nối đến cơ sở dữ liệu.");
}

/**
 * Lấy tất cả các câu hỏi FAQ từ CSDL
 * @return array Mảng các câu hỏi FAQ
 */
function getAllFaqs() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT * FROM faqs ORDER BY display_order ASC");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Lỗi khi lấy danh sách FAQ: " . $e->getMessage());
        return [];
    }
}

/**
 * Lấy thông tin một câu hỏi FAQ theo ID
 * @param int $id ID của câu hỏi cần lấy
 * @return array|false Thông tin câu hỏi hoặc false nếu không tìm thấy
 */
function getFaqById($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM faqs WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Lỗi khi lấy thông tin FAQ ID $id: " . $e->getMessage());
        return false;
    }
}

/**
 * Thêm một câu hỏi FAQ mới
 * @param string $question Nội dung câu hỏi
 * @param string $answer Nội dung câu trả lời
 * @param int $display_order Thứ tự hiển thị
 * @return bool Kết quả thực hiện
 */
function addFaq($question, $answer, $display_order) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO faqs (question, answer, display_order, created_at, updated_at) 
                               VALUES (:question, :answer, :display_order, NOW(), NOW())");
        
        $stmt->bindParam(':question', $question, PDO::PARAM_STR);
        $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
        $stmt->bindParam(':display_order', $display_order, PDO::PARAM_INT);
        
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Lỗi khi thêm FAQ mới: " . $e->getMessage());
        return false;
    }
}

/**
 * Cập nhật thông tin câu hỏi FAQ
 * @param int $id ID của câu hỏi cần cập nhật
 * @param string $question Nội dung câu hỏi
 * @param string $answer Nội dung câu trả lời
 * @param int $display_order Thứ tự hiển thị
 * @return bool Kết quả thực hiện
 */
function updateFaq($id, $question, $answer, $display_order) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE faqs 
                              SET question = :question, 
                                  answer = :answer, 
                                  display_order = :display_order, 
                                  updated_at = NOW() 
                              WHERE id = :id");
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':question', $question, PDO::PARAM_STR);
        $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
        $stmt->bindParam(':display_order', $display_order, PDO::PARAM_INT);
        
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Lỗi khi cập nhật FAQ ID $id: " . $e->getMessage());
        return false;
    }
}

/**
 * Xóa một câu hỏi FAQ
 * @param int $id ID của câu hỏi cần xóa
 * @return bool Kết quả thực hiện
 */
function deleteFaq($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM faqs WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Lỗi khi xóa FAQ ID $id: " . $e->getMessage());
        return false;
    }
}

// Xử lý các hành động từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Xử lý thêm FAQ mới
    if ($action == 'add') {
        $question = isset($_POST['question']) ? trim($_POST['question']) : '';
        $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        $display_order = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0;
        
        if (empty($question) || empty($answer)) {
            $error_message = "Vui lòng nhập đầy đủ câu hỏi và câu trả lời.";
        } else {
            if (addFaq($question, $answer, $display_order)) {
                $success_message = "Thêm câu hỏi mới thành công!";
                // Chuyển hướng để tránh gửi lại form khi refresh trang
                header("Location: admin_faqq_view.php?success=add");
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi thêm câu hỏi mới.";
            }
        }
    }
    
    // Xử lý cập nhật FAQ
    elseif ($action == 'update') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $question = isset($_POST['question']) ? trim($_POST['question']) : '';
        $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        $display_order = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0;
        
        if (empty($question) || empty($answer) || $id <= 0) {
            $error_message = "Dữ liệu không hợp lệ.";
        } else {
            if (updateFaq($id, $question, $answer, $display_order)) {
                $success_message = "Cập nhật câu hỏi thành công!";
                header("Location: admin_faqq_view.php?success=update");
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi cập nhật câu hỏi.";
            }
        }
    }
    
    // Xử lý xóa FAQ
    elseif ($action == 'delete') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id <= 0) {
            $error_message = "ID không hợp lệ.";
        } else {
            if (deleteFaq($id)) {
                $success_message = "Xóa câu hỏi thành công!";
                header("Location: admin_faqq_view.php?success=delete");
                exit;
            } else {
                $error_message = "Có lỗi xảy ra khi xóa câu hỏi.";
            }
        }
    }
}

// Xử lý các thông báo từ redirect
if (isset($_GET['success'])) {
    $action = $_GET['success'];
    switch ($action) {
        case 'add':
            $success_message = "Thêm câu hỏi mới thành công!";
            break;
        case 'update':
            $success_message = "Cập nhật câu hỏi thành công!";
            break;
        case 'delete':
            $success_message = "Xóa câu hỏi thành công!";
            break;
    }
}
?>