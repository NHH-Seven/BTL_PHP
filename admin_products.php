<?php
// Include database connection
require_once 'db_connect.php';

// Check if database connection is established
if (!isset($db_connected) || $db_connected !== true) {
    die("Database connection error");
}

// Initialize variables
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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'add' || $action === 'edit') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        $unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 'active';
        
        // Validate inputs
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
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "assets/img/products/";
                $imageFileName = basename($_FILES['image']['name']);
                $targetFilePath = $targetDir . $imageFileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                
                // Allow certain file formats
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array(strtolower($fileType), $allowTypes)) {
                    // Upload file to server
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                        $image = $targetFilePath;
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            }
            
            if (empty($error)) {
                try {
                    if ($action === 'add') {
                        // Insert new product
                        $query = "INSERT INTO products (name, price, unit, category, description, image, status) 
                                VALUES (:name, :price, :unit, :category, :description, :image, :status)";
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
                            $name = $price = $unit = $category = $description = $image = '';
                            $status = 'active';
                        } else {
                            $error = "Error adding product";
                        }
                    } elseif ($action === 'edit') {
                        // Update existing product
                        if (!empty($image)) {
                            $query = "UPDATE products SET name = :name, price = :price, unit = :unit, 
                                    category = :category, description = :description, image = :image, 
                                    status = :status, updated_at = NOW() WHERE id = :id";
                        } else {
                            $query = "UPDATE products SET name = :name, price = :price, unit = :unit, 
                                    category = :category, description = :description, 
                                    status = :status, updated_at = NOW() WHERE id = :id";
                        }
                        
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':unit', $unit);
                        $stmt->bindParam(':category', $category);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':status', $status);
                        $stmt->bindParam(':id', $id);
                        
                        if (!empty($image)) {
                            $stmt->bindParam(':image', $image);
                        }
                        
                        if ($stmt->execute()) {
                            $message = "Product updated successfully";
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
                $query = "DELETE FROM products WHERE id = :id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id', $id);
                
                if ($stmt->execute()) {
                    $message = "Product deleted successfully";
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

// Fetch all products
try {
    $query = "SELECT * FROM products ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching products: " . $e->getMessage();
    $products = [];
}

// Include the view file
include 'admin_products_view.php';
?>