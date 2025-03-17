<?php
// Bao gồm file xử lý logic
require_once 'admin_faqq.php';

// Lấy dữ liệu FAQ cho trang hiện tại
$faqs = getAllFaqs();

// Lấy thông tin FAQ cần cập nhật (nếu có)
$faq_edit = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $faq_edit = getFaqById($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Manage FAQs</title>
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
        .faq-list {
            margin-top: 40px;
        }
        .faq-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .faq-list table th, .faq-list table td {
            padding: 10px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        .faq-list table th {
            background-color: #f8f8f8;
        }
        .action-buttons a, .action-buttons button {
            margin-right: 5px;
        }
        .pagination-wrap {
            margin-top: 30px;
        }
        textarea {
            min-height: 120px;
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
                                <li class="current-list-item"><a href="admin_faqq.php">Câu Hỏi</a></li>
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
                        <h1>Manage FAQs</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- admin section -->
    <div class="admin-section mt-150 mb-150">
        <div class="container">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-lg-12">
                    <h2><?php echo $faq_edit ? 'Edit FAQ' : 'Add New FAQ'; ?></h2>
                    <div class="admin-form">
                        <form method="POST" action="admin_faqq_view.php">
                            <input type="hidden" name="action" value="<?php echo $faq_edit ? 'update' : 'add'; ?>">
                            <?php if ($faq_edit): ?>
                                <input type="hidden" name="id" value="<?php echo $faq_edit['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="question">Question:</label>
                                <input type="text" class="form-control" id="question" name="question" 
                                       value="<?php echo $faq_edit ? htmlspecialchars($faq_edit['question']) : ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="answer">Answer:</label>
                                <textarea class="form-control" id="answer" name="answer" rows="5" required><?php echo $faq_edit ? htmlspecialchars($faq_edit['answer']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="display_order">Display Order:</label>
                                <input type="number" class="form-control" id="display_order" name="display_order" min="1" 
                                       value="<?php echo $faq_edit ? $faq_edit['display_order'] : (count($faqs) + 1); ?>">
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <?php echo $faq_edit ? 'Update FAQ' : 'Add FAQ'; ?>
                                </button>
                                <?php if ($faq_edit): ?>
                                    <a href="admin_faqq_view.php" class="btn btn-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="faq-list">
                        <h3>Existing FAQs</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Display Order</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($faqs)): ?>
                                    <?php foreach ($faqs as $faq): ?>
                                        <tr>
                                            <td><?php echo $faq['id']; ?></td>
                                            <td><?php echo htmlspecialchars($faq['question']); ?></td>
                                            <td>
                                                <?php
                                                    // Hiển thị một phần của câu trả lời với giới hạn ký tự
                                                    $answer_preview = strlen($faq['answer']) > 100 
                                                        ? htmlspecialchars(substr($faq['answer'], 0, 100)) . '...' 
                                                        : htmlspecialchars($faq['answer']);
                                                    echo $answer_preview;
                                                ?>
                                            </td>
                                            <td><?php echo $faq['display_order']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($faq['created_at'])); ?></td>
                                            <td class="action-buttons">
                                                <a href="admin_faqq_view.php?edit=<?php echo $faq['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $faq['id']; ?>)" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No FAQs found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end admin section -->

    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this FAQ? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Confirm Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="footer-box about-widget">
                        <h2 class="widget-title">About us</h2>
                        <p>Your trusted source for fresh, organic produce and quality groceries. We're committed to supporting local farmers and providing sustainable food options to our community.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="footer-box get-in-touch">
                        <h2 class="widget-title">Get in Touch</h2>
                        <ul>
                            <li>123 Market Street, City Center</li>
                            <li>support@fruitkha.com</li>
                            <li>+00 111 222 3333</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end footer -->

    <!-- copyright -->
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
    <!-- end copyright -->

    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    <script>
        // Hàm xác nhận xóa FAQ
        function confirmDelete(id) {
            $('#confirmDeleteBtn').attr('href', 'admin_faqq_view.php?action=delete&id=' + id);
            $('#deleteConfirmModal').modal('show');
        }
        
        // Hiển thị thông báo
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);
        });
    </script>
</body>
</html>