<?php
// Kết nối file xử lý logic
require_once 'admin_customer.php';

// Lấy danh sách khách hàng
$customers = getAllCustomers();

// Lấy thông tin khách hàng cần sửa nếu có
$editCustomer = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editCustomer = getCustomerById($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Quản Lý Khách Hàng</title>
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
        .admin-form input, .admin-form textarea {
            margin-bottom: 15px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .customer-list {
            margin-top: 40px;
        }
        .customer-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .customer-list table th, .customer-list table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .customer-list table th {
            background-color: #f8f8f8;
        }
        .action-buttons a, .action-buttons button {
            margin-right: 5px;
        }
        .pagination-wrap {
            margin-top: 30px;
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
                                <li><a href="index_2.html">Trang Quản Lý</a></li>
                                <li><a href="admin_news.php">Tin Tức</a></li>
                                <li><a href="admin_products.php">Sản Phẩm</a></li>
                                <li class="current-list-item"><a href="admin_customer_view.php">Khách Hàng</a></li>
                                <li><a href="admin_checkout.php">Đơn Hàng</a></li>
                                <li><a href="admin_faqq.php">Câu Hỏi</a></li>
                                <li><a href="admin_binhluan_view.php">Bình Luận</a></li>
                                <li><a href="admin_users.php">Tài Khoản</a></li>
                                <li><a href="admin_thongke.php">Thống Kê</a></li>
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
                        <p>Admin Panel</p>
                        <h1>Quản Lý Khách Hàng</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- admin section -->
    <div class="admin-section mt-150 mb-150">
        <div class="container">
            <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
                <div class="alert alert-<?php echo $_GET['status'] === 'success' ? 'success' : 'danger'; ?>">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-lg-12">
                    <h2><?php echo $editCustomer ? 'Sửa Thông Tin Khách Hàng' : 'Thêm Khách Hàng Mới'; ?></h2>
                    <div class="admin-form">
                        <form method="POST" action="admin_customer.php">
                            <input type="hidden" name="action" value="<?php echo $editCustomer ? 'update' : 'add'; ?>">
                            <?php if ($editCustomer): ?>
                                <input type="hidden" name="customer_id" value="<?php echo $editCustomer['customer_id']; ?>">
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Tên khách hàng:</label>
                                        <input type="text" class="form-control" id="name" name="name" required 
                                            value="<?php echo $editCustomer ? htmlspecialchars($editCustomer['name']) : ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                            value="<?php echo $editCustomer ? htmlspecialchars($editCustomer['email']) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Địa chỉ:</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="<?php echo $editCustomer ? htmlspecialchars($editCustomer['address']) : ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="<?php echo $editCustomer ? htmlspecialchars($editCustomer['phone']) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Ghi chú:</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $editCustomer ? htmlspecialchars($editCustomer['notes']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <?php echo $editCustomer ? 'Cập Nhật' : 'Thêm Mới'; ?>
                                </button>
                                <?php if ($editCustomer): ?>
                                    <a href="admin_customer_view.php" class="btn btn-secondary">Hủy</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="customer-list">
                        <h3>Danh Sách Khách Hàng Hiện Có</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Địa chỉ</th>
                                    <th>Số điện thoại</th>
                                    <th>Ghi chú</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($customers)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu khách hàng</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($customers as $customer): ?>
                                        <tr>
                                            <td><?php echo $customer['customer_id']; ?></td>
                                            <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['address']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['notes']); ?></td>
                                            <td><?php echo $customer['created_at']; ?></td>
                                            <td class="action-buttons">
                                                <a href="admin_customer_view.php?action=edit&id=<?php echo $customer['customer_id']; ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <a href="admin_customer.php?action=delete&id=<?php echo $customer['customer_id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này không?')">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination (Có thể thêm logic phân trang sau này) -->
                        <?php if (isset($total_pages) && $total_pages > 1): ?>
                            <div class="pagination-wrap">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="admin_customer_view.php?page=<?php echo ($page - 1); ?>">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="admin_customer_view.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="admin_customer_view.php?page=<?php echo ($page + 1); ?>">
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

    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    <script>
        // Tự động ẩn thông báo sau 3 giây
        document.addEventListener('DOMContentLoaded', function() {
            const statusMessage = document.querySelector('.alert');
            if (statusMessage) {
                setTimeout(function() {
                    statusMessage.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>