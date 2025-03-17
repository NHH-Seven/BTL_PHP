<?php
// Include the logic file
require_once 'admin_user.php';

// Get all users for display
$users = getAllUsers();

// Check if we're editing a user
$editUser = false;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editUser = getUserById($_GET['id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- title -->
    <title>Manage Users</title>
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
        .admin-form input, .admin-form select {
            margin-bottom: 15px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .users-list {
            margin-top: 40px;
        }
        .users-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .users-list table th, .users-list table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .users-list table th {
            background-color: #f8f8f8;
        }
        .action-buttons a, .action-buttons button {
            margin-right: 5px;
        }
        .pagination-wrap {
            margin-top: 30px;
        }
        .password-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
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
                                <li><a href="admin_checkout_view.php">Đơn Hàng</a></li>
                                <li><a href="admin_faqq_view.php">Câu Hỏi</a></li>
                                <li><a href="admin_binhluan_view.php">Bình Luận</a></li>
                                <li class="current-list-item"><a href="admin_user_view.php">Tài Khoản</a></li>
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
                        <p>Admin Panel</p>
                        <h1>Manage Users</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- admin section -->
    <div class="admin-section mt-150 mb-150">
        <div class="container">
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-lg-12">
                    <h2><?php echo $editUser ? 'Edit User' : 'Add New User'; ?></h2>
                    <div class="admin-form">
                        <form action="admin_user.php" method="post">
                            <input type="hidden" name="action" value="<?php echo $editUser ? 'update' : 'add'; ?>">
                            <?php if ($editUser): ?>
                                <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">Username:</label>
                                        <input type="text" id="username" name="username" class="form-control" 
                                               value="<?php echo $editUser ? htmlspecialchars($editUser['username']) : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" class="form-control" 
                                               value="<?php echo $editUser ? htmlspecialchars($editUser['email']) : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" id="password" name="password" class="form-control" 
                                               <?php echo $editUser ? '' : 'required'; ?>>
                                        <?php if ($editUser): ?>
                                            <div class="password-info">Leave empty if you don't want to change the password</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role:</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="admin" <?php echo ($editUser && $editUser['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                            <option value="user" <?php echo ($editUser && $editUser['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="<?php echo $editUser ? 'update_user' : 'add_user'; ?>" class="btn btn-primary">
                                    <?php echo $editUser ? 'Update User' : 'Add User'; ?>
                                </button>
                                <?php if ($editUser): ?>
                                    <a href="admin_user_view.php" class="btn btn-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="users-list">
                        <h3>Existing Users</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No users found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                                            <td><?php echo htmlspecialchars($user['created_at'] ?? 'N/A'); ?></td>
                                            <td class="action-buttons">
                                                <a href="admin_user_view.php?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button onclick="confirmDelete(<?php echo $user['id']; ?>)" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'admin_user.php?action=delete&id=' + userId;
            }
        }
    </script>
</body>
</html>