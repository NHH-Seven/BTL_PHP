<?php
// Include database connection
require_once 'db_connect.php';

// Kiểm tra kết nối thành công trước khi thực hiện các thao tác
if (!isset($db_connected) || $db_connected !== true) {
    die("Không thể kết nối đến cơ sở dữ liệu.");
}

/**
 * Lấy tất cả dữ liệu contacts từ cơ sở dữ liệu
 * @param int $limit Số lượng bản ghi tối đa
 * @param int $offset Vị trí bắt đầu
 * @return array Mảng kết quả
 */
function getAllContacts($limit = 25, $offset = 0) {
    global $conn;
    
    try {
        $sql = "SELECT * FROM contacts ORDER BY contact_date DESC LIMIT :limit OFFSET :offset";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Lỗi truy vấn contacts: " . $e->getMessage());
        return [];
    }
}

/**
 * Đếm tổng số bản ghi contacts
 * @return int Tổng số bản ghi
 */
function countContacts() {
    global $conn;
    
    try {
        $sql = "SELECT COUNT(*) as total FROM contacts";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    } catch(PDOException $e) {
        error_log("Lỗi đếm contacts: " . $e->getMessage());
        return 0;
    }
}

/**
 * Lấy thông tin chi tiết của một contact theo ID
 * @param int $id ID của contact cần lấy
 * @return array|bool Thông tin contact hoặc false nếu không tìm thấy
 */
function getContactById($id) {
    global $conn;
    
    try {
        $sql = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result) {
            return $result;
        } else {
            return false;
        }
    } catch(PDOException $e) {
        error_log("Lỗi truy vấn contact theo ID: " . $e->getMessage());
        return false;
    }
}

// Xử lý tham số trang và số lượng hiển thị
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $records_per_page;

// Xử lý để xem chi tiết một contact nếu có tham số id
$contact_detail = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $contact_detail = getContactById($_GET['id']);
}

// Lấy danh sách contacts nếu không xem chi tiết
$contacts = [];
$total_contacts = 0;
if ($contact_detail === null) {
    $contacts = getAllContacts($records_per_page, $offset);
    $total_contacts = countContacts();
}

// Tính toán thông tin phân trang
$total_pages = ceil($total_contacts / $records_per_page);