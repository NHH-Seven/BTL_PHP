<?php
// Include database connection
require_once 'db_connect.php';

// Kiểm tra xem kết nối cơ sở dữ liệu đã được thiết lập chưa
if (!isset($db_connected) || $db_connected !== true) {
    die("Database connection error");
}

// Khởi tạo biến
$id = '';
$name = '';
$price = '';
$unit = '';
$category = '';
$description = '';
$image = '';
$status = 'active';
$message = '';
$error = '';
$products = [];

// Hiển thị thông báo thành công từ URL nếu nó tồn tại
if (isset($_GET['success'])) {
    $message = urldecode($_GET['success']);
}

// Xử lý việc gửi biểu mẫu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận dữ liệu biểu mẫu
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'add' || $action === 'edit') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        $unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 'active';
        
        // Xác thực đầu vào
        if (empty($name)) {
            $error = "Product name is required";
        } elseif ($price <= 0) {
            $error = "Price must be greater than zero";
        } elseif (empty($unit)) {
            $error = "Unit is required";
        } elseif (empty($category)) {
            $error = "Category is required";
        } else {
            // Process image upload if a file was selected
            $imageFileName = '';
            $update_image = false;
            
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "assets/img/products/";
                $image = 'assets/img/products/default-product.jpg'; // Default image
                
                // Check if directory exists, create if needed
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $imageFileName = time() . '_' . basename($_FILES['image']['name']); // Add timestamp to avoid duplicate names
                $targetFilePath = $targetDir . $imageFileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                
                // Allow certain file formats
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array(strtolower($fileType), $allowTypes)) {
                    // Upload file to server
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $image = $targetFilePath; // Set $image to the full path
                        $update_image = true;
                    } else {
                        $error = "Sorry, there was an error uploading your file. Check directory permissions.";
                    }
                } else {
                    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            } elseif ($action === 'edit' && empty($image)) {
                // Keep existing image when editing if no new image was provided
                $image_query = "SELECT image FROM products WHERE id = :id";
                $image_stmt = $conn->prepare($image_query);
                $image_stmt->bindParam(':id', $id);
                $image_stmt->execute();
                $image_row = $image_stmt->fetch();
                $image = $image_row['image'];
            }
            
            if (empty($error)) {
                try {
                    if ($action === 'add') {
                        // Insert new product
                        $query = "INSERT INTO products (name, price, unit, category, description, image, status, created_at, updated_at) 
                                VALUES (:name, :price, :unit, :category, :description, :image, :status, NOW(), NOW())";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':unit', $unit);
                        $stmt->bindParam(':category', $category);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':image', $image);
                        $stmt->bindParam(':status', $status);
                        
                        if ($stmt->execute()) {
                            $message = "Product added successfully";
                            // Clear form fields
                            $id = '';
                            $name = '';
                            $price = '';
                            $unit = '';
                            $category = '';
                            $description = '';
                            $image = '';
                            $status = 'active';
                            
                            // Redirect to refresh the page
                            header("Location: admin_products.php?success=" . urlencode($message));
                            exit;
                        } else {
                            $error = "Error adding product";
                        }
                    } elseif ($action === 'edit') {
                        // Update existing product
                        if ($update_image) {
                            $query = "UPDATE products SET name = :name, price = :price, unit = :unit, 
                                    category = :category, description = :description, image = :image, 
                                    status = :status, updated_at = NOW() WHERE id = :id";
                            $stmt = $conn->prepare($query);
                            $stmt->bindParam(':image', $image);
                        } else {
                            $query = "UPDATE products SET name = :name, price = :price, unit = :unit, 
                                    category = :category, description = :description, 
                                    status = :status, updated_at = NOW() WHERE id = :id";
                            $stmt = $conn->prepare($query);
                        }
                        
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':unit', $unit);
                        $stmt->bindParam(':category', $category);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':status', $status);
                        $stmt->bindParam(':id', $id);
                        
                        if ($stmt->execute()) {
                            $message = "Product updated successfully";
                            $id = '';
                            $name = '';
                            $price = '';
                            $unit = '';
                            $category = '';
                            $description = '';
                            $image = '';
                            $status = 'active';
                            
                            // Redirect to main page with success message
                            header("Location: admin_products.php?success=" . urlencode($message));
                            exit;
                        } else {
                            $error = "Error updating product";
                        }
                    }
                } catch(PDOException $e) {
                    $error = "Database error: " . $e->getMessage();
                }
            }
        }
    } elseif ($action === 'delete') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        
        if (!empty($id)) {
            try {
                // Delete product
                $query = "DELETE FROM products WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $id);
                
                if ($stmt->execute()) {
                    $message = "Product deleted successfully";
                    
                    // Reset AUTO_INCREMENT to avoid duplicate IDs
                    $max_id_query = "SELECT MAX(id) as max_id FROM products";
                    $max_id_result = $conn->query($max_id_query);
                    $max_id_row = $max_id_result->fetch();
                    $next_id = ($max_id_row['max_id'] ?? 0) + 1;
                    
                    // Set AUTO_INCREMENT for products table
                    $reset_query = "ALTER TABLE products AUTO_INCREMENT = $next_id";
                    $conn->query($reset_query);
                    
                    // Redirect to main page with success message
                    header("Location: admin_products.php?success=" . urlencode($message));
                    exit;
                } else {
                    $error = "Error deleting product";
                }
            } catch(PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Get product for editing
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    
    try {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $edit_id);
        $stmt->execute();
        
        if ($product = $stmt->fetch()) {
            $id = $product['id'];
            $name = $product['name'];
            $price = $product['price'];
            $unit = $product['unit'];
            $category = $product['category'];
            $description = $product['description'];
            $image = $product['image'];
            $status = $product['status'];
        }
    } catch(PDOException $e) {
        $error = "Error fetching product: " . $e->getMessage();
    }
}

// Phân trang
$limit = 10; // Số sản phẩm trên một trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Đảm bảo page không nhỏ hơn 1
$offset = ($page - 1) * $limit;

// Đếm tổng số sản phẩm
try {
    $count_query = "SELECT COUNT(*) as total FROM products";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->execute();
    $total_products = $count_stmt->fetch()['total'];
    $total_pages = ceil($total_products / $limit);
} catch(PDOException $e) {
    $error = "Error counting products: " . $e->getMessage();
    $total_pages = 1;
}

// Fetch products with pagination
try {
    $query = "SELECT * FROM products ORDER BY id ASC LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching products: " . $e->getMessage();
    $products = [];
}

// Include the view file
include 'admin_products_view.php';
?>