<?php
// Include the statistics logic file
require_once 'admin_static.php';

// Get all dashboard data
$dashboardData = getDashboardData($conn);
$statistics = $dashboardData['statistics'];
$revenue = $dashboardData['revenue'];
$topCustomers = $dashboardData['top_customers'];
$popularNews = $dashboardData['popular_news'];

// Helper function to format currency
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . ' đ';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Thống Kê</title>
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
        .dashboard-card {
            border-radius: 8px; /* Bo góc nhiều hơn */
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15); /* Đổ bóng rõ hơn */
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px; /* Tăng khoảng cách giữa các hàng */
            height: 200px; /* Chiều cao cố định */
        }
        
        .dashboard-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }
        
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .card-stat {
            font-size: 2.2rem;
            font-weight: bold;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #2dceb1, #3cba92);
            color: white;
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8, #148ea1);
            color: white;
        }
        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107, #f9a826);
            color: white;
        }
        .dashboard-title {
            margin-bottom: 25px;
            margin-top: 20px;
        }
        .stats-container {
            background-color: #f5f5f5;
            border-radius: 5px;
            padding: 30px;
            margin-bottom: 40px;
        }
        .table-container {
            margin-bottom: 30px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f8f8f8;
        }
        .revenue-progress {
            height: 25px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
        }
        .revenue-month {
            margin-bottom: 20px;
        }
        .month-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .month-value {
            text-align: right;
            font-weight: bold;
        }
        .progress-primary {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
        }
        .revenue-month:hover .progress-bar {
            opacity: 0.8;
        }
        .month-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
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
                                <li><a href="admin_checkout_view.php">Đơn Hàng</a></li>
                                <li><a href="admin_faqq_view.php">Câu Hỏi</a></li>
                                <li><a href="admin_binhluan_view.php">Bình Luận</a></li>
                                <li><a href="admin_user_view.php">Tài Khoản</a></li>
                                <li class="current-list-item"><a href="admin_static_view.php">Thống Kê</a></li>
                                <li>
                                    <div class="header-icons">
                                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a>
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
                        <h1>Thống Kê</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- statistics section -->
    <div class="mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="dashboard-title">Tổng Quan Thống Kê</h2>
                    
                    <!-- Overview statistics -->
                    <!-- Overview statistics -->
<!-- Overview statistics -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row justify-content-between">
                                <div class="col-md-2 mx-auto" style="flex: 0 0 auto; width: 19%;">
                                    <div class="card dashboard-card bg-gradient-primary" style="height: 200px;">
                                        <div class="card-body text-center p-4">
                                            <div class="card-icon" style="font-size: 2.5rem; margin-bottom: 15px;">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <h5 class="card-title">Khách hàng</h5>
                                            <p class="card-stat" style="font-size: 2.2rem;"><?= $statistics['customers_count'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mx-auto" style="flex: 0 0 auto; width: 19%;">
                                    <div class="card dashboard-card bg-gradient-success" style="height: 200px;">
                                        <div class="card-body text-center p-4">
                                            <div class="card-icon" style="font-size: 2.5rem; margin-bottom: 15px;">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <h5 class="card-title">Đơn hàng</h5>
                                            <p class="card-stat" style="font-size: 2.2rem;"><?= $statistics['orders_count'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mx-auto" style="flex: 0 0 auto; width: 19%;">
                                    <div class="card dashboard-card bg-gradient-info" style="height: 200px;">
                                        <div class="card-body text-center p-4">
                                            <div class="card-icon" style="font-size: 2.5rem; margin-bottom: 15px;">
                                                <i class="fas fa-newspaper"></i>
                                            </div>
                                            <h5 class="card-title">Tin tức</h5>
                                            <p class="card-stat" style="font-size: 2.2rem;"><?= $statistics['news_count'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mx-auto" style="flex: 0 0 auto; width: 19%;">
                                    <div class="card dashboard-card bg-gradient-warning" style="height: 200px;">
                                        <div class="card-body text-center p-4">
                                            <div class="card-icon" style="font-size: 2.5rem; margin-bottom: 15px;">
                                                <i class="fas fa-comments"></i>
                                            </div>
                                            <h5 class="card-title">Phản hồi</h5>
                                            <p class="card-stat" style="font-size: 2.2rem;"><?= $statistics['contacts_count'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mx-auto" style="flex: 0 0 auto; width: 19%;">
                                    <div class="card dashboard-card" style="background: linear-gradient(135deg, #FF5722, #FF9800); color: white; height: 200px;">
                                        <div class="card-body text-center p-4">
                                            <div class="card-icon" style="font-size: 2.5rem; margin-bottom: 15px;">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <h5 class="card-title">Sản phẩm</h5>
                                            <p class="card-stat" style="font-size: 2.2rem;"><?= $statistics['products_count'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <!-- Revenue by month - now using progress bars -->
                        <div class="col-md-8">
                            <h3 class="dashboard-title">Doanh thu theo tháng</h3>
                            <div class="stats-container">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="20%">Tháng</th>
                                                <th width="15%">Doanh thu</th>
                                                <th width="65%">Biểu đồ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($revenue['monthly'] as $month): ?>
                                            <?php
                                                $percentage = ($revenue['max_monthly'] > 0) ? 
                                                    ($month['revenue'] / $revenue['max_monthly'] * 100) : 0;
                                            ?>
                                            <tr>
                                                <td>Tháng <?= $month['month'] ?></td>
                                                <td class="text-right"><?= formatCurrency($month['revenue']) ?></td>
                                                <td>
                                                    <div class="progress revenue-progress">
                                                        <div class="progress-bar progress-primary" role="progressbar" 
                                                            style="width: <?= $percentage ?>%" 
                                                            aria-valuenow="<?= $month['revenue'] ?>" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="<?= $revenue['max_monthly'] ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Summary statistics -->
                        <div class="col-md-4">
                            <h3 class="dashboard-title">Tổng quan</h3>
                            <div class="stats-container">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Tổng doanh thu:</span>
                                    <span class="fw-bold"><?= formatCurrency($revenue['total']) ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Người dùng:</span>
                                    <span class="fw-bold"><?= $statistics['users_count'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Sản phẩm:</span>
                                    <span class="fw-bold"><?= $statistics['products_count'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Bình luận:</span>
                                    <span class="fw-bold"><?= $statistics['comments_count'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Câu hỏi FAQs:</span>
                                    <span class="fw-bold"><?= $statistics['faqs_count'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <!-- Recent orders -->
                        <div class="col-md-8">
                            <h3 class="dashboard-title">Đơn hàng gần đây</h3>
                            <div class="stats-container">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày đặt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($revenue['recent_orders'])): ?>
                                                <?php foreach ($revenue['recent_orders'] as $order): ?>
                                                    <tr>
                                                        <td>#<?= $order['order_id'] ?></td>
                                                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                                        <td><?= formatCurrency($order['total_amount']) ?></td>
                                                        <td>
                                                            <?php 
                                                            $statusClass = '';
                                                            switch($order['status']) {
                                                                case 'completed':
                                                                    $statusClass = 'badge bg-success';
                                                                    $statusText = 'Hoàn thành';
                                                                    break;
                                                                case 'pending':
                                                                    $statusClass = 'badge bg-warning';
                                                                    $statusText = 'Đang xử lý';
                                                                    break;
                                                                case 'processing':
                                                                    $statusClass = 'badge bg-warning';
                                                                    $statusText = 'Đang giao hàng';
                                                                    break;
                                                                case 'cancelled':
                                                                    $statusClass = 'badge bg-danger';
                                                                    $statusText = 'Đã hủy';
                                                                    break;
                                                                default:
                                                                    $statusClass = 'badge bg-secondary';
                                                                    $statusText = $order['status'];
                                                            }
                                                            ?>
                                                            <span class="<?= $statusClass ?>"><?= $statusText ?></span>
                                                        </td>
                                                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Không có đơn hàng nào</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Top customers -->
                        <div class="col-md-4">
                            <h3 class="dashboard-title">Khách hàng hàng đầu</h3>
                            <div class="stats-container">
                                <ul class="list-group">
                                    <?php if (!empty($topCustomers)): ?>
                                        <?php foreach ($topCustomers as $customer): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold"><?= htmlspecialchars($customer['customer_name']) ?></span>
                                                    <br>
                                                    <small class="text-muted"><?= htmlspecialchars($customer['email']) ?></small>
                                                </div>
                                                <span class="badge bg-primary rounded-pill">
                                                    <?= formatCurrency($customer['total_spent']) ?>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-center">Không có dữ liệu khách hàng</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="copyright-text">
                        <p>&copy; 2025 - All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end footer -->
     
    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
</body>
</html>