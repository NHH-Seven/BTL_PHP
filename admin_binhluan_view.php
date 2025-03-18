<?php
// Khởi động session để lưu trữ thông báo
session_start();

// Kết nối đến file logic
require_once 'admin_binhluan.php';

// Khởi tạo biến cho form chỉnh sửa
$editMode = false;
$commentToEdit = [];

// Kiểm tra nếu có yêu cầu chỉnh sửa
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $editMode = true;
    $commentToEdit = getCommentById($_GET['id']);
    
    if (!$commentToEdit) {
        $_SESSION['message'] = "Không tìm thấy bình luận để chỉnh sửa";
        $_SESSION['message_type'] = "error";
        header("Location: admin_binhluan_view.php");
        exit;
    }
}

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Lấy tất cả bình luận để hiển thị với phân trang
$comments = getAllComments($offset, $records_per_page);
$total_comments = getTotalComments();
$total_pages = ceil($total_comments / $records_per_page);

// Chuẩn bị biến thông báo
$error = $_SESSION['message_type'] ?? '' === 'error' ? $_SESSION['message'] ?? '' : '';
$success = $_SESSION['message_type'] ?? '' === 'success' ? $_SESSION['message'] ?? '' : '';

// Xóa thông báo sau khi sử dụng
unset($_SESSION['message']);
unset($_SESSION['message_type']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Manage Comments</title>
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
        .comment-list {
            margin-top: 40px;
        }
        .comment-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .comment-list table th, .comment-list table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .comment-list table th {
            background-color: #f8f8f8;
        }
        .action-buttons a, .action-buttons button {
            margin-right: 5px;
        }
        .pagination-wrap {
            margin-top: 30px;
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .comment-content {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
                                <li class="current-list-item"><a href="admin_binhluan_view.php">Bình Luận</a></li>
                                <li><a href="admin_user_view.php">Tài Khoản</a></li>
                                <li><a href="admin_static_view.php">Thống Kê</a></li>
                                <li>
                                    <div class="header-icons">
                                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Đăng Xuất</a>
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
                        <h1>Quản Lý Bình Luận</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- admin section -->
    <div class="admin-section mt-150 mb-150">
        <div class="container">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($editMode): ?>
                        <h2>Chỉnh Sửa Bình Luận</h2>
                        <div class="admin-form">
                            <form method="post" action="admin_binhluan.php">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $commentToEdit['id']; ?>">
                                
                                <div class="form-group">
                                    <label for="content">Nội dung bình luận:</label>
                                    <textarea id="content" name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($commentToEdit['content']); ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Cập Nhật Bình Luận</button>
                                    <a href="admin_binhluan_view.php" class="btn btn-secondary">Hủy</a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <div class="comment-list">
                        <h3>Danh Sách Bình Luận</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tác giả</th>
                                    <th>Ảnh đại diện</th>
                                    <th>Bài viết</th>
                                    <th>Nội dung</th>
                                    <th>Ngày bình luận</th>
                                    <th>Tùy chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($comments)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Không có bình luận nào</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($comments as $comment): ?>
                                        <tr>
                                            <td><?php echo $comment['id']; ?></td>
                                            <td><?php echo htmlspecialchars($comment['author_name'] ?? 'Không xác định'); ?></td>
                                            <td>
                                                <?php if (!empty($comment['author_image'])): ?>
                                                    <img src="assets/img/avatars/<?php echo htmlspecialchars($comment['author_image']); ?>" alt="Avatar" class="avatar">
                                                <?php else: ?>
                                                    <img src="assets/img/avatars/default-avatar.png" alt="Default Avatar" class="avatar">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($comment['news_title'] ?? 'Không xác định'); ?></td>
                                            <td class="comment-content"><?php echo htmlspecialchars($comment['content']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($comment['comment_date'])); ?></td>
                                            <td class="action-buttons">
                                                <a href="admin_binhluan_view.php?action=edit&id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <button onclick="confirmDelete(<?php echo $comment['id']; ?>)" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                                <?php if (!empty($comment['news_id'])): ?>
                                                <a href="single-news.php?id=<?php echo $comment['news_id']; ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                                <?php endif; ?>
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
                                            <a class="page-link" href="admin_binhluan_view.php?page=<?php echo ($page - 1); ?>">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="admin_binhluan_view.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="admin_binhluan_view.php?page=<?php echo ($page + 1); ?>">
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
    <!-- end admin section -->

    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    
    <script>
        function confirmDelete(id) {
            if (confirm("Bạn có chắc chắn muốn xóa bình luận này không?")) {
                // Tạo form ẩn để submit
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "admin_binhluan.php";
                
                var actionInput = document.createElement("input");
                actionInput.type = "hidden";
                actionInput.name = "action";
                actionInput.value = "delete";
                
                var idInput = document.createElement("input");
                idInput.type = "hidden";
                idInput.name = "id";
                idInput.value = id;
                
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>