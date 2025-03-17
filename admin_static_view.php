
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
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.7.1/chart.min.js"></script>
    <style>
        .dashboard-card {
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .card-stat {
            font-size: 1.8rem;
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
                                <li><a href="admin_customer_view.php">Khách Hàng</a></li>
                                <li><a href="admin_checkout.php">Đơn Hàng</a></li>
                                <li><a href="admin_faqq.php">Câu Hỏi</a></li>
                                <li><a href="admin_binhluan_view.php">Bình Luận</a></li>
                                <li><a href="admin_users.php">Tài Khoản</a></li>
                                <li class="current-list-item"><a href="admin_static_view.php">Thống Kê</a></li>
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
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card dashboard-card bg-gradient-primary">
                                <div class="card-body text-center p-4">
                                    <div class="card-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h5 class="card-title">Khách hàng</h5>
                                    <p class="card-stat"><?= $statistics['customers_count'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card dashboard-card bg-gradient-success">
                                <div class="card-body text-center p-4">
                                    <div class="card-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <h5 class="card-title">Đơn hàng</h5>
                                    <p class="card-stat"><?= $statistics['orders_count'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card dashboard-card bg-gradient-info">
                                <div class="card-body text-center p-4">
                                    <div class="card-icon">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                    <h5 class="card-title">Tin tức</h5>
                                    <p class="card-stat"><?= $statistics['news_count'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card dashboard-card bg-gradient-warning">
                                <div class="card-body text-center p-4">
                                    <div class="card-icon">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h5 class="card-title">Phản hồi</h5>
                                    <p class="card-stat"><?= $statistics['contacts_count'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <!-- Revenue chart -->
                        <div class="col-md-8">
                            <h3 class="dashboard-title">Doanh thu theo tháng</h3>
                            <div class="stats-container">
                                <canvas id="revenueChart" height="300"></canvas>
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
                    
                    <!-- Popular news section could be added here if needed -->
                </div>
            </div>
            </div>
    </div>
    
    <!-- Popular news section -->
    
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
    <!-- Debug -->
<!-- Debug -->
<div class="card mt-3">
    <div class="card-header">Debug Data</div>
    <div class="card-body">
        <pre>
            <?php 
                echo "Current Year: " . date('Y') . "\n";
                echo "Monthly Data: "; 
                print_r($revenue['monthly']); 
                
                echo "\nMonthly Data Array for Chart: ";
                $monthlyDataDebug = array_fill(0, 12, 0);
                if (!empty($revenue['monthly'])) {
                    foreach ($revenue['monthly'] as $item) {
                        if (isset($item['month']) && isset($item['revenue'])) {
                            $monthIndex = intval($item['month']) - 1;
                            if ($monthIndex >= 0 && $monthIndex < 12) {
                                $monthlyDataDebug[$monthIndex] = floatval($item['revenue']);
                            }
                        }
                    }
                }
                print_r($monthlyDataDebug);
            ?>
        </pre>
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
    <!-- Chart.js integration -->
    <script>
    console.log("Monthly revenue data:", <?php echo json_encode($revenue['monthly']); ?>);
    console.log("Monthly data for chart:", <?php echo json_encode($monthlyData); ?>);
    </script>
    <script>
        // Revenue chart
    const revenueChart = new Chart(
    document.getElementById('revenueChart'),
    {
        type: 'bar',
        data: {
            labels: [
                <?php
                // Tạo mảng tên tháng
                $monthLabels = [];
                for ($i = 1; $i <= 12; $i++) {
                    $monthLabels[] = '"Tháng ' . $i . '"';
                }
                echo implode(',', $monthLabels);
                ?>
            ],
            datasets: [{
                label: 'Doanh thu theo tháng',
                data: [
                    <?php
                    // Khởi tạo mảng 12 tháng với giá trị 0
                    $monthlyData = array_fill(0, 12, 0);
                    
                    // Điền dữ liệu thực tế
                    if (!empty($revenue['monthly'])) {
                        foreach ($revenue['monthly'] as $item) {
                            if (isset($item['month']) && isset($item['revenue'])) {
                                $monthIndex = intval($item['month']) - 1; // Chuyển từ 1-12 sang 0-11
                                if ($monthIndex >= 0 && $monthIndex < 12) {
                                    $monthlyData[$monthIndex] = floatval($item['revenue']);
                                }
                            }
                        }
                    }
                    
                    // Xuất chuỗi dữ liệu
                    echo implode(',', $monthlyData);
                    ?>
                ],
                backgroundColor: 'rgba(110, 142, 251, 0.7)',
                borderColor: 'rgb(110, 142, 251)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN', { 
                                style: 'currency', 
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                notation: 'compact',
                                compactDisplay: 'short',
                                maximumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            }
        }
    }
);
    </script>
</body>
</html>