<?php
require_once 'db_connect.php';
session_start();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if news ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: news.php");
    exit;
}

$news_id = (int)$_GET['id'];

// Get news article data
$sql = "SELECT * FROM news WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bindParam(1, $news_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    header("Location: news.php");
    exit;
}

$news = $stmt->fetch();

// Get comments
$comments_sql = "SELECT c.*, parent.author_name as parent_author 
                FROM comments c 
                LEFT JOIN comments parent ON c.parent_id = parent.id 
                WHERE c.news_id = ? 
                ORDER BY c.parent_id ASC, c.comment_date ASC";
$comments_stmt = $conn->prepare($comments_sql);
$comments_stmt->bindParam(1, $news_id, PDO::PARAM_INT);
$comments_stmt->execute();
$comments_result = $comments_stmt->fetchAll();

// Get tags for this article
$tags_sql = "SELECT t.name FROM tags t 
            JOIN news_tags nt ON t.id = nt.tag_id 
            WHERE nt.news_id = ?";
$tags_stmt = $conn->prepare($tags_sql);
$tags_stmt->bindParam(1, $news_id, PDO::PARAM_INT);
$tags_stmt->execute();
$tags_result = $tags_stmt->fetchAll();

// Get recent posts
$recent_posts_sql = "SELECT id, title FROM news ORDER BY published_date ASC LIMIT 5";
$recent_posts_stmt = $conn->query($recent_posts_sql);
$recent_posts_result = $recent_posts_stmt->fetchAll();

// Get archive posts
$archive_sql = "SELECT * FROM archive_posts ORDER BY id ASC";
$archive_stmt = $conn->query($archive_sql);
$archive_result = $archive_stmt->fetchAll();

// Handle comment submission
$comment_success = $comment_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $comment_error = "Please log in to submit a comment";
    } else {
        $content = trim($_POST['comment']);
        $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        
        if (empty($content)) {
            $comment_error = "Comment cannot be empty";
        } else {
            // Get user information from session
            $author_name = $_SESSION['username'];
            $author_image = !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : 'default-avatar.png';
            
            $insert_sql = "INSERT INTO comments (news_id, parent_id, author_name, author_image, content, comment_date) VALUES (?, ?, ?, ?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            
            if ($parent_id === 0) {
                $parent_id = null;
            }
            
            $insert_stmt->bindParam(1, $news_id, PDO::PARAM_INT);
            $insert_stmt->bindParam(2, $parent_id, PDO::PARAM_INT);
            $insert_stmt->bindParam(3, $author_name, PDO::PARAM_STR);
            $insert_stmt->bindParam(4, $author_image, PDO::PARAM_STR);
            $insert_stmt->bindParam(5, $content, PDO::PARAM_STR);
            
            if ($insert_stmt->execute()) {
                $comment_success = "Comment posted successfully!";
                // Refresh page to show new comment
                header("Location: single-news.php?id=$news_id&comment_added=1");
                exit;
            } else {
                $comment_error = "Error posting comment";
            }
        }
    }
}

// Show success message if comment was added
if (isset($_GET['comment_added'])) {
    $comment_success = "Comment posted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- title -->
	<title>Single News</title>
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
	<!-- owl carousel -->
	<link rel="stylesheet" href="assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        .comment-form {
            margin-top: 40px;
            padding: 30px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-bottom: 15px;
        }
        .comment-form button {
            background-color: #F28123;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }
        .reply-form {
            display: none;
            margin-top: 15px;
            margin-left: 50px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 3px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- [Your existing header HTML] -->
    <!--PreLoader-->
    <div class="loader">
        <div class="loader-inner">
            <div class="circle"></div>
        </div>
    </div>
    <!--PreLoader Ends-->
	
	<!-- header -->
    <div class="top-header-area" id="sticker">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12 text-center">
                <div class="main-menu-wrap">
                    <!-- logo -->
                    <div class="site-logo">
                        <a href="index.php">
                            <img src="assets/img/logo.png" alt="">
                        </a>
                    </div>
                    <!-- logo -->

                    <!-- menu start -->
                    <nav class="main-menu">
                        <ul>
                            <li class="current-list-item"><a href="index.php">Trang Chủ</a>
                            </li>
                            <li><a href="contact.php">Phản Hồi</a></li>
                            </li>
                            <li><a href="news.php">Tin Tức</a>
                            </li>
                            <li><a href="shop.php">Cửa Hàng</a>
                                <ul class="sub-menu">
                                    <li><a href="shop.php">Cửa Hàng</a></li>
                                    <li><a href="checkout.php">Thanh Toán</a></li>
                                    <li><a href="cart.php">Giỏ Hàng</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Trang</a>
                                <ul class="sub-menu">
                                    <li><a href="cart.php">Giỏ Hàng</a></li>
                                    <li><a href="checkout.php">Thanh Toán</a></li>
                                    <li><a href="contact.php">Phản Hồi</a></li>
                                    <li><a href="news.php">Tin Tức</a></li>
                                    <li><a href="shop.php">Cửa Hàng</a></li>
                                    <li><a href="faqq.php">Câu Hỏi</a></li>
                                </ul>
                            </li>
                            <li>
                                <div class="header-icons">
                                    <a class="shopping-cart" href="cart.php"><i class="fas fa-shopping-cart"></i></a>

                                    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                                        <a class="shopping-login" href="user_profile.php" title="<?php echo htmlspecialchars($_SESSION['username']); ?>">
                                            <i class="fa-solid fa-user-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class="shopping-login" href="login.php"><i class="fa-solid fa-user"></i></a>
                                    <?php endif; ?>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
                    <div class="mobile-menu"></div>
                    <!-- menu end -->
                </div>
            </div>
        </div>
    </div>
</div>
	<!-- end header -->

	<!-- search area -->
	<!-- end search arewa -->
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Read the Details</p>
                        <h1><?php echo htmlspecialchars($news['title']); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->
    
    <!-- single article section -->
    <div class="mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="single-article-section">
                        <div class="single-article-text">
                            <div class="single-artcile-bg" style="background-image: url('assets/img/latest-news/<?php echo htmlspecialchars($news['image']); ?>');"></div>
                            <p class="blog-meta">
                                <span class="author"><i class="fas fa-user"></i> <?php echo htmlspecialchars($news['author']); ?></span>
                                <span class="date"><i class="fas fa-calendar"></i> <?php echo date('d F, Y', strtotime($news['published_date'])); ?></span>
                            </p>
                            <h2><?php echo htmlspecialchars($news['title']); ?></h2>
                            <?php
                            // Split content into paragraphs and render
                            $paragraphs = explode("\n", $news['content']);
                            foreach ($paragraphs as $paragraph) {
                                if (!empty(trim($paragraph))) {
                                    echo '<p>' . htmlspecialchars($paragraph) . '</p>';
                                }
                            }
                            ?>
                        </div>

                        <div class="comments-list-wrap">
                            <h3 class="comment-count-title"><?php echo count($comments_result); ?> Comments</h3>
                            
                            <?php if (!empty($comment_success)): ?>
                            <div class="alert alert-success"><?php echo $comment_success; ?></div>
                            <?php endif; ?>
                            
                            <?php if (!empty($comment_error)): ?>
                            <div class="alert alert-danger"><?php echo $comment_error; ?></div>
                            <?php endif; ?>
                            
                            <div class="comment-list">
                                <?php
                                $comments = [];
                                $comment_replies = [];
                                
                                // Sort comments into parent comments and replies
                                foreach ($comments_result as $comment) {
                                    if ($comment['parent_id'] === null) {
                                        $comments[] = $comment;
                                    } else {
                                        $comment_replies[$comment['parent_id']][] = $comment;
                                    }
                                }
                                
                                // Display comments and their replies
                                foreach ($comments as $comment):
                                ?>
                                <div class="single-comment-body">
                                    <div class="comment-user-avater">
                                        <img src="assets/img/avaters/<?php echo htmlspecialchars($comment['author_image']); ?>" alt="">
                                    </div>
                                    <div class="comment-text-body">
                                        <h4><?php echo htmlspecialchars($comment['author_name']); ?> 
                                            <span class="comment-date"><?php echo date('M d, Y', strtotime($comment['comment_date'])); ?></span> 
                                            <?php if (isset($_SESSION['user_id'])): ?>
                                            <a href="javascript:void(0)" class="reply-link" data-comment-id="<?php echo $comment['id']; ?>">reply</a>
                                            <?php endif; ?>
                                        </h4>
                                        <p><?php echo htmlspecialchars($comment['content']); ?></p>
                                        
                                        <!-- Reply form (hidden by default) -->
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                        <div id="reply-form-<?php echo $comment['id']; ?>" class="reply-form">
                                            <form method="post">
                                                <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                                                <textarea name="comment" rows="3" placeholder="Your reply here..."></textarea>
                                                <button type="submit" name="submit_comment">Submit Reply</button>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Display replies to this comment -->
                                <?php if (isset($comment_replies[$comment['id']])): ?>
                                    <?php foreach ($comment_replies[$comment['id']] as $reply): ?>
                                    <div class="single-comment-body child">
                                        <div class="comment-user-avater">
                                            <img src="assets/img/avaters/<?php echo htmlspecialchars($reply['author_image']); ?>" alt="">
                                        </div>
                                        <div class="comment-text-body">
                                            <h4><?php echo htmlspecialchars($reply['author_name']); ?> 
                                                <span class="comment-date"><?php echo date('M d, Y', strtotime($reply['comment_date'])); ?></span>
                                                <span class="replying-to">replying to <?php echo htmlspecialchars($reply['parent_author']); ?></span>
                                            </h4>
                                            <p><?php echo htmlspecialchars($reply['content']); ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Comment form -->
                        <div class="comment-form">
                            <h3>Leave a comment</h3>
                            <?php if (!isset($_SESSION['user_id'])): ?>
                            <p>Please <a href="login.php">login</a> to submit a comment.</p>
                            <?php else: ?>
                            <form method="post">
                                <textarea name="comment" rows="5" placeholder="Your comment here..." required></textarea>
                                <button type="submit" name="submit_comment">Submit Comment</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar-section">
                        <div class="recent-posts">
                            <h4>Recent Posts</h4>
                            <ul>
                                <?php
                                foreach ($recent_posts_result as $recent_post) {
                                    echo '<li><a href="single-news.php?id=' . $recent_post['id'] . '">' . htmlspecialchars($recent_post['title']) . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="archive-posts">
                            <h4>Archive Posts</h4>
                            <ul>
                                <?php
                                foreach ($archive_result as $archive) {
                                    echo '<li>' . htmlspecialchars($archive['month']) . ' (' . $archive['count'] . ')</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="tag-section">
                            <h4>Tags</h4>
                            <ul>
                                <?php
                                $tags = array();
                                foreach ($tags_result as $tag) {
                                    $tags[] = $tag['name'];
                                }
                                
                                foreach ($tags as $tag) {
                                    echo '<li><a href="news.php?tag=' . urlencode($tag) . '">' . htmlspecialchars($tag) . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end single article section -->

    <!-- logo carousel -->
    <div class="logo-carousel-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-carousel-inner">
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/1.png" alt="">
                        </div>
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/2.png" alt="">
                        </div>
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/3.png" alt="">
                        </div>
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/4.png" alt="">
                        </div>
                        <div class="single-logo-item">
                            <img src="assets/img/company-logos/5.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end logo carousel -->

    <!-- footer -->
    <?php include 'footer.php'; ?>
    <!-- end copyright -->
    
    <!-- jquery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- count down -->
    <script src="assets/js/jquery.countdown.js"></script>
    <!-- isotope -->
    <script src="assets/js/jquery.isotope-3.0.6.min.js"></script>
    <!-- waypoints -->
    <script src="assets/js/waypoints.js"></script>
    <!-- owl carousel -->
    <script src="assets/js/owl.carousel.min.js"></script>
    <!-- magnific popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- mean menu -->
    <script src="assets/js/jquery.meanmenu.min.js"></script>
    <!-- sticker js -->
    <script src="assets/js/sticker.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>
    
    <!-- Add JavaScript for comment functionality -->
    <script>
        $(document).ready(function() {
            // Show/hide reply form when reply link is clicked
            $('.reply-link').click(function() {
                var commentId = $(this).data('comment-id');
                $('#reply-form-' + commentId).toggle();
            });
        });
    </script>
</body>
</html>