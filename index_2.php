<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Admin Dashboard</title>
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
        .admin-dashboard {
            padding: 50px 0;
        }
        .admin-card {
            background-color: #f5f5f5;
            padding: 30px;
            border-radius: 5px;
            margin-bottom: 30px;
            transition: all 0.3s;
            text-align: center;
            height: 100%;
        }
        .admin-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }
        .admin-card i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #F28123;
        }
        .admin-card h3 {
            margin-bottom: 15px;
        }
        .admin-card p {
            margin-bottom: 20px;
            color: #555;
        }
        .admin-card .btn {
            background-color: #F28123;
            color: white;
            border: none;
        }
        .admin-card .btn:hover {
            background-color: #e07315;
        }
        .dashboard-welcome {
            margin-bottom: 50px;
        }
        .dashboard-welcome h2 {
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: #fff;
            border-left: 5px solid #F28123;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .stat-card h4 {
            color: #555;
            margin-bottom: 10px;
        }
        .stat-card .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .stat-card i {
            font-size: 36px;
            color: #F28123;
            float: right;
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
                                <li class="current-list-item"><a href="index_2.php">Trang Quản Lý</a></li>
                                <li><a href="admin_news.php">Tin Tức</a></li>
                                <li><a href="admin_products.php">Sản Phẩm</a></li>
                                <li><a href="admin_customer_view.php">Khách Hàng</a></li>
                                <li><a href="admin_checkout_view.php">Đơn Hàng</a></li>
                                <li><a href="admin_faqq_view.php">Câu Hỏi</a></li>
                                <li><a href="admin_binhluan_view.php">Bình Luận</a></li>
                                <li><a href="admin_user_view.php">Tài Khoản</a></li>
                                <li><a href="admin_static_view.php">Thống Kê</a></li>
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
                        <h1>Trang Chủ</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- dashboard section -->
    <div class="admin-dashboard mt-150 mb-150">
        <div class="container">
            <div class="dashboard-welcome">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>Chào mừng bạn đến với Bảng điều khiển quản trị viên</h2>
                        <p>Tại đây bạn có thể quản lý tất cả các khía cạnh của trang web của mình. Chọn từ các tùy chọn bên dưới hoặc sử dụng menu điều hướng ở trên.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- News Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="admin-card">
                        <i class="fas fa-newspaper"></i>
                        <h3>Tin Tức</h3>
                        <p>Quản lý bài viết tin tức, thêm, sửa, xóa và cập nhật nội dung tin tức trên website.</p>
                        <a href="admin_news.php" class="btn">Quản Lý Tin Tức</a>
                    </div>
                </div>

                <!-- Products Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="admin-card">
                        <i class="fas fa-box"></i>
                        <h3>Sản Phẩm</h3>
                        <p>Quản lý danh sách sản phẩm, thêm sản phẩm mới, cập nhật thông tin và giá cả.</p>
                        <a href="admin_products.php" class="btn">Quản Lý Sản Phẩm</a>
                    </div>
                </div>

                <!-- Customers Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="admin-card">
                        <i class="fas fa-users"></i>
                        <h3>Khách Hàng</h3>
                        <p>Xem và quản lý thông tin khách hàng, lịch sử mua hàng và tương tác.</p>
                        <a href="admin_customer_view.php" class="btn">Quản Lý Khách Hàng</a>
                    </div>
                </div>

                <!-- Orders Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="admin-card">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Đơn Hàng</h3>
                        <p>Xem và quản lý đơn hàng, cập nhật trạng thái và theo dõi quá trình giao hàng.</p>
                        <a href="admin_checkout.php" class="btn">Quản Lý Đơn Hàng</a>
                    </div>
                </div>

                <!-- FAQ Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="admin-card">
                        <i class="fas fa-question-circle"></i>
                        <h3>Câu Hỏi</h3>
                        <p>Quản lý các câu hỏi thường gặp và cung cấp câu trả lời cho khách hàng.</p>
                        <a href="admin_faqq.php" class="btn">Quản Lý Câu Hỏi</a>
                    </div>
                </div>

                <!-- Comments Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="admin-card">
                        <i class="fas fa-comments"></i>
                        <h3>Bình Luận</h3>
                        <p>Xem và quản lý bình luận của khách hàng về sản phẩm và bài viết.</p>
                        <a href="admin_binhluan_view.php" class="btn">Quản Lý Bình Luận</a>
                    </div>
                </div>

                <!-- User Accounts Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="admin-card">
                        <i class="fas fa-user-lock"></i>
                        <h3>Tài Khoản</h3>
                        <p>Quản lý tài khoản người dùng, phân quyền và bảo mật thông tin.</p>
                        <a href="admin_users.php" class="btn">Quản Lý Tài Khoản</a>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="col-lg-4 col-md-6">
                    <div class="admin-card">
                        <i class="fas fa-chart-bar"></i>
                        <h3>Thống Kê</h3>
                        <p>Xem báo cáo thống kê về doanh thu, sản phẩm bán chạy và hoạt động của website.</p>
                        <a href="admin_thongke.php" class="btn">Xem Thống Kê</a>
                    </div>
                </div>

                <!-- Settings -->
                
            </div>
        </div>
    </div>
    <!-- end dashboard section -->
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