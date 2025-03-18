<?php
// Kết nối logic xử lý
require_once 'admin_checkout.php';

// Lấy danh sách tất cả đơn hàng
$orders = getAllOrders();

// Kiểm tra thông báo từ form xử lý
$message = isset($_GET['message']) ? $_GET['message'] : '';
$success = isset($_GET['success']) ? (bool)$_GET['success'] : false;

// Xử lý hiển thị chi tiết đơn hàng nếu có yêu cầu
$order_detail = null;
if (isset($_GET['view_detail']) && !empty($_GET['view_detail'])) {
    $order_id = (int)$_GET['view_detail'];
    $order_detail = getOrderDetails($order_id);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Quản lý đơn hàng - Fruit Shop</title>
    
    <!-- favicon -->
    <link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
    
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    
    <!-- fontawesome -->
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
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
        .order-item {
            margin-bottom: 5px;
            padding: 5px;
            border-bottom: 1px solid #eee;
        }
        .alert-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            animation: fadeOut 5s forwards;
        }
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; display: none; }
        }
        .order-detail-card {
            background-color: #f5f5f5;
            padding: 30px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .action-buttons a, .action-buttons button {
            margin-right: 5px;
        }
        .breadcrumb-text h1 {
            color: white;
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
                                <li><a href="admin_products.php">Sản Phẩm</a></li>
                                <li><a href="admin_customer_view.php">Khách Hàng</a></li>
                                <li class="current-list-item"><a href="admin_checkout_view.php">Đơn Hàng</a></li>
                                <li><a href="admin_faqq_view.php">Câu Hỏi</a></li>
                                <li><a href="admin_binhluan_view.php">Bình Luận</a></li>
                                <li><a href="admin_user_view.php">Tài Khoản</a></li>
                                <li><a href="admin_static_view.php">Thống Kê</a></li>
                                <li>
                                    <div class="header-icons">
                                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
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
                        <h1>Quản lý đơn hàng</h1>
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
                <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?> alert-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <!-- Danh sách đơn hàng -->
                <div class="<?php echo $order_detail ? 'col-lg-7' : 'col-lg-12'; ?>">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Danh sách đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($orders)): ?>
                                <p class="text-center">Không có đơn hàng nào</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày tạo</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                                    <td><?php echo number_format($order['total_amount'], 0); ?> VND</td>
                                                    <td>
                                                        <span class="badge bg-<?php 
                                                            echo $order['status'] == 'pending' ? 'warning' : 
                                                                ($order['status'] == 'completed' ? 'success' : 
                                                                ($order['status'] == 'processing' ? 'info' : 'secondary')); 
                                                        ?>">
                                                            <?php 
                                                                $status_text = '';
                                                                switch($order['status']) {
                                                                    case 'pending': $status_text = 'Đang xử lý'; break;
                                                                    case 'processing': $status_text = 'Đang giao hàng'; break;
                                                                    case 'completed': $status_text = 'Hoàn thành'; break;
                                                                    case 'cancelled': $status_text = 'Đã hủy'; break;
                                                                    default: $status_text = $order['status'];
                                                                }
                                                                echo htmlspecialchars($status_text); 
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                                    <td class="action-buttons">
                                                        <a href="?view_detail=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> Chi tiết
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $order['order_id']; ?>">
                                                            <i class="fas fa-trash"></i> Xóa
                                                        </button>
                                                        
                                                        <!-- Modal xác nhận xóa -->
                                                        <div class="modal fade" id="deleteModal<?php echo $order['order_id']; ?>" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Xác nhận xóa</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Bạn có chắc chắn muốn xóa đơn hàng #<?php echo $order['order_id']; ?>?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                        <form action="admin_checkout.php" method="POST">
                                                                            <input type="hidden" name="action" value="delete">
                                                                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                                            <button type="submit" class="btn btn-danger">Xóa</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Chi tiết đơn hàng -->
                <?php if ($order_detail): ?>
                    <div class="col-lg-5">
                        <div class="card order-detail-card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">Chi tiết đơn hàng #<?php echo $order_detail['order_id']; ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Khách hàng:</strong> <?php echo htmlspecialchars($order_detail['customer_name']); ?>
                                </div>
                                <div class="mb-3">
                                    <strong>Ngày đặt:</strong> <?php echo htmlspecialchars($order_detail['created_at']); ?>
                                </div>
                                <div class="mb-3">
                                    <strong>Tổng tiền:</strong> <?php echo number_format($order_detail['total_amount'], 0); ?> VND
                                </div>
                                
                                <form action="admin_checkout.php" method="POST" class="mb-4">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="order_id" value="<?php echo $order_detail['order_id']; ?>">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" for="status">Trạng thái:</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="pending" <?php echo $order_detail['status'] == 'pending' ? 'selected' : ''; ?>>Đang xử lý</option>
                                            <option value="processing" <?php echo $order_detail['status'] == 'processing' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                            <option value="completed" <?php echo $order_detail['status'] == 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                                            <option value="cancelled" <?php echo $order_detail['status'] == 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    </div>
                                </form>
                                
                                <h6 class="mb-3">Danh sách sản phẩm</h6>
                                <?php if (empty($order_detail['items'])): ?>
                                    <p class="text-center">Không có sản phẩm nào</p>
                                <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($order_detail['items'] as $item): ?>
                                            <div class="list-group-item order-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                                        <small>Đơn giá: <?php echo number_format($item['price'], 0); ?> VND</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge bg-secondary"><?php echo $item['quantity']; ?> x</span>
                                                        <div><?php echo number_format($item['price'] * $item['quantity'], 0); ?> VND</div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <div class="mt-3 text-end">
                                        <strong>Tổng số lượng:</strong> 
                                        <?php
                                            $total_quantity = 0;
                                            foreach ($order_detail['items'] as $item) {
                                                $total_quantity += $item['quantity'];
                                            }
                                            echo $total_quantity;
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-4">
                                    <a href="admin_checkout_view.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                    
                                    <?php if ($order_detail['status'] != 'completed' && $order_detail['status'] != 'cancelled'): ?>
                                        <!-- Thay thế nút kích hoạt modal bằng form trực tiếp -->
                                        <form action="admin_checkout.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="completed">
                                            <input type="hidden" name="order_id" value="<?php echo $order_detail['order_id']; ?>">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Hoàn thành đơn hàng
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    

    <!-- end logo carousel -->

    <!-- end copyright -->
    
    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    <script>
        // Ẩn thông báo sau 5 giây
        setTimeout(function() {
            var alert = document.querySelector('.alert-message');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);
    </script>
</body>

</html>