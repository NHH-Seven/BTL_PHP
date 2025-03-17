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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <style>
        .product-img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Manage Products</h1>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-header">
                <?php echo !empty($id) ? 'Edit Product' : 'Add New Product'; ?>
            </div>
            <div class="card-body">
                <form action="admin_products.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?php echo !empty($id) ? 'edit' : 'add'; ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" value="<?php echo $price; ?>" step="0.01" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="unit" class="form-label">Unit</label>
                                <input type="text" class="form-control" id="unit" name="unit" value="<?php echo $unit; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="category" name="category" value="<?php echo $category; ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo $description; ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                                <?php if (!empty($image)): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo $image; ?>" alt="Current Image" class="product-img">
                                        <p class="small text-muted">Current image. Upload a new one to replace it.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            <?php echo !empty($id) ? 'Update Product' : 'Add Product'; ?>
                        </button>
                        <?php if (!empty($id)): ?>
                            <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                Products List
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td>
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-img">
                                            <?php else: ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $product['name']; ?></td>
                                        <td><?php echo $product['price']; ?> $</td>
                                        <td><?php echo $product['category']; ?></td>
                                        <td>
                                            <span class="badge <?php echo $product['status'] === 'active' ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo $product['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="admin_products.php?edit=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="admin_products.php" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No products found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>