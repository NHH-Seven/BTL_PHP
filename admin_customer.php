<?php
// Kết nối đến cơ sở dữ liệu
require_once 'db_connect.php';

/**
 * Lấy danh sách tất cả khách hàng từ cơ sở dữ liệu
 * @return array Danh sách khách hàng
 */
function getAllCustomers() {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM customers ORDER BY customer_id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Lỗi khi lấy danh sách khách hàng: " . $e->getMessage());
        return [];
    }
}

/**
 * Lấy thông tin của một khách hàng theo ID
 * @param int $customerId ID của khách hàng cần lấy thông tin
 * @return array|false Thông tin khách hàng hoặc false nếu không tìm thấy
 */
function getCustomerById($customerId) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = :id");
        $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Lỗi khi lấy thông tin khách hàng: " . $e->getMessage());
        return false;
    }
}

/**
 * Thêm một khách hàng mới vào cơ sở dữ liệu
 * @param array $customerData Dữ liệu của khách hàng cần thêm
 * @return bool True nếu thêm thành công, false nếu thất bại
 */
function addCustomer($customerData) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO customers (name, email, address, phone, notes) 
                               VALUES (:name, :email, :address, :phone, :notes)");
        
        $stmt->bindParam(':name', $customerData['name'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $customerData['email'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $customerData['address'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $customerData['phone'], PDO::PARAM_STR);
        $stmt->bindParam(':notes', $customerData['notes'], PDO::PARAM_STR);
        
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Lỗi khi thêm khách hàng: " . $e->getMessage());
        return false;
    }
}

/**
 * Cập nhật thông tin của một khách hàng
 * @param int $customerId ID của khách hàng cần cập nhật
 * @param array $customerData Dữ liệu mới của khách hàng
 * @return bool True nếu cập nhật thành công, false nếu thất bại
 */
function updateCustomer($customerId, $customerData) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE customers 
                               SET name = :name, 
                                   email = :email, 
                                   address = :address, 
                                   phone = :phone, 
                                   notes = :notes 
                               WHERE customer_id = :id");
        
        $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $customerData['name'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $customerData['email'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $customerData['address'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $customerData['phone'], PDO::PARAM_STR);
        $stmt->bindParam(':notes', $customerData['notes'], PDO::PARAM_STR);
        
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Lỗi khi cập nhật khách hàng: " . $e->getMessage());
        return false;
    }
}

/**
 * Xóa một khách hàng khỏi cơ sở dữ liệu
 * @param int $customerId ID của khách hàng cần xóa
 * @return bool True nếu xóa thành công, false nếu thất bại
 */
function deleteCustomer($customerId) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = :id");
        $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
        return $stmt->execute();
    } catch(PDOException $e) {
        error_log("Lỗi khi xóa khách hàng: " . $e->getMessage());
        return false;
    }
}

/**
 * Xử lý hành động từ form
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý thêm khách hàng mới
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $customerData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'notes' => $_POST['notes']
        ];
        
        if (addCustomer($customerData)) {
            header('Location: admin_customer_view.php?status=success&message=Đã thêm khách hàng thành công');
            exit;
        } else {
            header('Location: admin_customer_view.php?status=error&message=Không thể thêm khách hàng');
            exit;
        }
    }
    
    // Xử lý cập nhật khách hàng
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $customerId = $_POST['customer_id'];
        $customerData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'notes' => $_POST['notes']
        ];
        
        if (updateCustomer($customerId, $customerData)) {
            header('Location: admin_customer_view.php?status=success&message=Đã cập nhật khách hàng thành công');
            exit;
        } else {
            header('Location: admin_customer_view.php?status=error&message=Không thể cập nhật khách hàng');
            exit;
        }
    }
}

// Xử lý xóa khách hàng (qua GET để đơn giản)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $customerId = $_GET['id'];
    
    if (deleteCustomer($customerId)) {
        header('Location: admin_customer_view.php?status=success&message=Đã xóa khách hàng thành công');
        exit;
    } else {
        header('Location: admin_customer_view.php?status=error&message=Không thể xóa khách hàng');
        exit;
    }
}
?>