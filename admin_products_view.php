<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- favicon -->
    <link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <!-- fontawesome -->
    <link rel="stylesheet" href="assets/css/all.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <!-- main style -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- responsive -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        .admin-section {
            padding: 50px 0;
        }
        .admin-form {
            background-color: #f5f5f5;
            padding: 30px;
            border-radius: 5px;
            margin-bottom: 40px;
        }
        .admin-form label {
            font-weight: bold;
        }
        .admin-form input, .admin-form textarea, .admin-form select {
            margin-bottom: 15px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .products-list {
            margin-top: 40px;
        }
        .products-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .products-list table th, .products-list table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .products-list table th {
            background-color: #f8f8f8;
        }
        .action-buttons a, .action-buttons button {
            margin-right: 5px;
        }
        .pagination-wrap {
            margin-top: 30px;
        }
        .product-img {
            max-width: 100px;
            max-height: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- header -->
    <div class="top-header-area" id="sticker">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 text-center">
                    <div class="main-menu-wrap">
                        <!-- logo -->
                        <div class="site-logo">
                            <a href="index.html">
                                <img src="assets/img/logo.png" alt="">
                            </a>
                        </div>
                        <!-- logo -->

                        <!-- menu start -->
                        <nav class="main-menu">
                            <ul>
                                <li><a href="index_2.php">Trang Quản Lý</a></li>
                                <li><a href="admin_news.php">Tin Tức</a></li>
                                <li class="current-list-item"><a href="admin_products.php">Sản Phẩm</a></li>
                                <li><a href="admin_customer_view.php">Khách Hàng</a></li>
                                <li><a href="admin_checkout_view.php">Đơn Hàng</a></li>
                                <li><a href="admin_faqq_view.php">Câu Hỏi</a></li>
                                <li><a href="admin_binhluan_view.php">Bình Luận</a></li>
                                <li><a href="admin_user_view.php">Tài Khoản</a></li>
                                <li><a href="admin_static_view.php">Thống Kê</a></li>
                                <li>
                                    <div class="header-icons">
                                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                        <div class="mobile-menu"></div>
                        <!-- menu end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end header -->

    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Trang Quản Lý</p>
                        <h1>Quản Lý Sản Phẩm</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- admin section -->
    <div class="admin-section mt-150 mb-150">
        <div class="container">
            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-lg-12">
                    <h2><?php echo !empty($id) ? 'Sửa Sản Phẩm' : 'Thêm sản phẩm mới'; ?></h2>
                    <div class="admin-form">
                        <form action="admin_products.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="<?php echo !empty($id) ? 'edit' : 'add'; ?>">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Tên Sản Phẩm:</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="price">Giá:</label>
                                        <input type="number" class="form-control" id="price" name="price" value="<?php echo $price; ?>" step="0.01" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="unit">Đơn vị:</label>
                                        <input type="text" class="form-control" id="unit" name="unit" value="<?php echo $unit; ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="category">Loại:</label>
                                        <input type="text" class="form-control" id="category" name="category" value="<?php echo $category; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Sự miêu tả:</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"><?php echo $description; ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="image">Ảnh Sản Phẩm:</label>
                                        <?php if (!empty($image)): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo $image; ?>" alt="Current Image" class="product-img">
                                                <p class="mt-1">Hình ảnh hiện tại. Tải lên một cái mới để thay thế nó.</p>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control-file" id="image" name="image">
                                        <small class="form-text text-muted">Để trống để giữ hình ảnh hiện tại. Chỉ cho phép các tệp JPG, JPEG, PNG & GIF.</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                                            <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo !empty($id) ? 'Update Product' : 'Add Product'; ?>
                                </button>
                                <?php if (!empty($id)): ?>
                                    <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="products-list">
                        <h3>Các Sản Phẩm Hiện Có</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ảnh</th>
                                    <th>Tên</th>
                                    <th>Giá</th>
                                    <th>loại</th>
                                    <th>Tình trạng</th>
                                    <th>Thực Hiện</th>
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
                                                    <span class="text-muted">Không có hình ảnh</span>
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
                                            <td class="action-buttons">
                                                <a href="admin_products.php?edit=<?php echo $product['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Sửa 
                                                </a>
                                                <form action="admin_products.php" method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> xóa
                                                    </button>
                                                </form>
                                                <a href="shop.php?id=<?php echo $product['id']; ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-eye"></i> xem
                                                </a>
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
                        
                        <!-- Pagination -->
                        <?php if (isset($total_pages) && $total_pages > 1): ?>
                            <div class="pagination-wrap">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="admin_products.php?page=<?php echo ($page - 1); ?>">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="admin_products.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="admin_products.php?page=<?php echo ($page + 1); ?>">
                                                <i class="fas fa-angle-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end admin section -->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <p>Copyrights &copy; 2025 - <a href="https://fruitkha.com/">Fruitkha</a>, All Rights Reserved.</p>
                </div>
                <div class="col-lg-6 text-right col-md-12">
                    <div class="social-icons">
                        <ul>
                            <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
</body>
</html>