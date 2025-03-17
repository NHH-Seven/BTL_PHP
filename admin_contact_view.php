<?php
// Include file logic
require_once 'admin_contact.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Manage Contacts</title>
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
        .alert {
            margin-bottom: 20px;
        }
        .contact-list {
            margin-top: 40px;
        }
        .contact-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .contact-list table th, .contact-list table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .contact-list table th {
            background-color: #f8f8f8;
        }
        .action-buttons a {
            margin-right: 5px;
        }
        .pagination-wrap {
            margin-top: 30px;
        }
        .message-preview {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .detail-card {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .detail-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
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
                                <li class="current-list-item"><a href="admin_contact_view.php">Liên Hệ</a></li>
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
                        <h1>Quản Lý Liên Hệ</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- admin section -->
    <div class="admin-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($contact_detail): ?>
                        <!-- Hiển thị chi tiết một contact -->
                        <div class="detail-card">
                            <h2>Chi tiết liên hệ #<?php echo htmlspecialchars($contact_detail['id']); ?></h2>
                            
                            <div class="detail-row">
                                <span class="detail-label">ID:</span>
                                <span><?php echo htmlspecialchars($contact_detail['id']); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Tên người dùng:</span>
                                <span><?php echo htmlspecialchars($contact_detail['username']); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Email:</span>
                                <span><?php echo htmlspecialchars($contact_detail['email']); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Số điện thoại:</span>
                                <span><?php echo htmlspecialchars($contact_detail['phone']); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Chủ đề:</span>
                                <span><?php echo htmlspecialchars($contact_detail['subject']); ?></span>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Nội dung:</span>
                                <div style="margin-top: 10px;">
                                    <?php echo nl2br(htmlspecialchars($contact_detail['message'])); ?>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <span class="detail-label">Ngày liên hệ:</span>
                                <span><?php echo htmlspecialchars($contact_detail['contact_date']); ?></span>
                            </div>
                            
                            <div class="form-group">
                                <a href="admin_contact_view.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Hiển thị danh sách các contact -->
                        <div class="contact-list">
                            <h3>Danh Sách Liên Hệ</h3>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên người dùng</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Chủ đề</th>
                                        <th>Nội dung</th>
                                        <th>Ngày liên hệ</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($contacts)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($contacts as $contact): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($contact['id']); ?></td>
                                            <td><?php echo htmlspecialchars($contact['username']); ?></td>
                                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                            <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                            <td>
                                                <div class="message-preview">
                                                    <?php echo htmlspecialchars($contact['message']); ?>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($contact['contact_date']); ?></td>
                                            <td class="action-buttons">
                                                <a href="admin_contact_view.php?id=<?php echo $contact['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <?php if ($total_pages > 1): ?>
                                <div class="pagination-wrap">
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="admin_contact_view.php?page=<?php echo ($page - 1); ?>&limit=<?php echo $records_per_page; ?>">
                                                    <i class="fas fa-angle-left"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <?php
                                        $start_page = max(1, $page - 2);
                                        $end_page = min($total_pages, $page + 2);
                                        
                                        for ($i = $start_page; $i <= $end_page; $i++):
                                        ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="admin_contact_view.php?page=<?php echo $i; ?>&limit=<?php echo $records_per_page; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if ($page < $total_pages): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="admin_contact_view.php?page=<?php echo ($page + 1); ?>&limit=<?php echo $records_per_page; ?>">
                                                    <i class="fas fa-angle-right"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <p>Hiển thị <?php echo count($contacts); ?> trong tổng số <?php echo $total_contacts; ?> liên hệ</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
</body>
</html>