<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Manage News</title>
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
        .news-list {
            margin-top: 40px;
        }
        .news-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .news-list table th, .news-list table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .news-list table th {
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
                                <li><a href="index_2.php">Trang Quản Lý</a></li>
                                <li class="current-list-item"><a href="admin_news.php">Tin Tức</a></li>
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
                        <h1>Quản Lý Bài Viết</h1>
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
                    <h2><?php echo $news_id > 0 ? 'Chỉnh sửa tin tức' : 'Thêm bài viết tin tức mới'; ?></h2>
                    <div class="admin-form">
                        <form method="post" enctype="multipart/form-data">
                            <?php if ($news_id > 0): ?>
                                <input type="hidden" name="news_id" value="<?php echo $news_id; ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="title">Tiêu Đề:</label>
                                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="excerpt">Trích:</label>
                                <textarea id="excerpt" name="excerpt" class="form-control" rows="2" required><?php echo htmlspecialchars($excerpt); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="content">nội dung:</label>
                                <textarea id="content" name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="author">Tác giả:</label>
                                <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($author); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="image">Ảnh:</label>
                                <?php if ($image != 'news-bg-1.jpg'): ?>
                                    <div class="mb-2">
                                        <img src="assets/img/latest-news/<?php echo htmlspecialchars($image); ?>" alt="Current image" style="max-width: 200px;">
                                        <p class="mt-1">Hình ảnh hiện tại: <?php echo htmlspecialchars($image); ?></p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" id="image" name="image" class="form-control-file">
                                <small class="form-text text-muted">Để trống để giữ hình ảnh hiện tại. Chỉ cho phép các tệp JPG, JPEG, PNG & GIF.</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="<?php echo $news_id > 0 ? 'update_news' : 'add_news'; ?>" class="btn btn-primary">
                                    <?php echo $news_id > 0 ? 'Update Article' : 'Add Article'; ?>
                                </button>
                                <?php if ($news_id > 0): ?>
                                    <a href="admin_news.php" class="btn btn-secondary">Hủy</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="news-list">
                        <h3>Các bài Viết hiện có</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tiêu đề</th>
                                    <th>Tác giả</th>
                                    <th>Ngày xuất bản</th>
                                    <th>Thực HIện</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->rowCount() > 0): ?>
                                    <?php while ($row = $result->fetch()): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($row['published_date'])); ?></td>
                                            <td class="action-buttons">
                                                <a href="admin_news.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <form method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                                    <input type="hidden" name="news_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="delete_news" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                                <a href="single-news.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Không tìm thấy bài viết</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="pagination-wrap">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="admin_news.php?page=<?php echo ($page - 1); ?>">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="admin_news.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="admin_news.php?page=<?php echo ($page + 1); ?>">
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
    <!-- end copyright -->

    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
</body>
</html>