<?php
session_start();
// Kết nối đến cơ sở dữ liệu
require_once 'db_connect.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra kết nối
if (!isset($db_connected) || $db_connected === false) {
    die("Không thể kết nối đến cơ sở dữ liệu.");
}

// Truy vấn dữ liệu từ bảng faqs
try {
    $stmt = $conn->prepare("SELECT id, question, answer FROM faqs ORDER BY display_order ASC");
    $stmt->execute();
    $faqs = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Lỗi truy vấn dữ liệu: " . $e->getMessage());
    $faqs = []; // Khởi tạo mảng rỗng nếu có lỗi
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- title -->
	<title>FAQ</title>
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

</head>
<style>
    /* FAQ Section Styling */
		.faq-section {
			padding: 50px 0;
		}
		.faq-title h2 {
			font-size: 32px;
			font-weight: 700;
			margin-bottom: 20px;
			color: #2d6a4f;
			text-align: center;
		}
		.faq-title p {
			font-size: 16px;
			color: #555;
			text-align: center;
			margin-bottom: 40px;
		}
		.faq-content .faq-item {
			margin-bottom: 20px;
			border: 1px solid #e0e0e0;
			border-radius: 5px;
			padding: 15px;
			background-color: #ffffff;
			box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
			cursor: pointer;
		}
		.faq-content .faq-item h3 {
			font-size: 20px;
			color: #2d6a4f;
			font-weight: 600;
		}
		.faq-content .faq-item h3:hover {
			color: #40916c;
		}
		.faq-content .faq-item p {
			display: none;
			font-size: 16px;
			color: #555;
			margin-top: 10px;
			line-height: 1.6;
		}
		.faq-item h3::after {
			content: "\f107"; /* FontAwesome down arrow */
			font-family: "Font Awesome 5 Free";
			font-weight: 900;
			float: right;
			color: #2d6a4f;
			transition: transform 0.3s ease;
		}
		.faq-item.open h3::after {
			transform: rotate(180deg); /* Rotates arrow when open */
		}
</style>
<body>
	
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
							<p>Fresh and Organic</p>
							<h1>F A Q</h1>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end breadcrumb section -->
		<!-- FAQ Section -->
	<div class="faq-section">
		<div class="container">
			<div class="faq-title">
				<h2>Frequently Asked Questions</h2>
				<p>Find answers to the most commonly asked questions about our products and services. If you need further assistance, please reach out to us directly.</p>
			</div>
			
			<!-- FAQ Questions and Answers -->
			<div class="faq-content">
				<?php if(empty($faqs)): ?>
					<div class="alert alert-info">Hiện tại chưa có câu hỏi thường gặp nào được thêm vào.</div>
				<?php else: ?>
					<?php foreach($faqs as $faq): ?>
						<div class="faq-item">
							<h3 onclick="toggleAnswer(this)"><?php echo htmlspecialchars($faq['question']); ?></h3>
							<p><?php echo htmlspecialchars($faq['answer']); ?></p>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- end FAQ section -->
		<!-- logo carousel -->

	<!-- end footer -->
	
	<!-- copyright -->
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
	<script>
		function toggleAnswer(element) {
			var faqItem = element.parentElement;
			var answer = element.nextElementSibling;
			
			if (faqItem.classList.contains('open')) {
				faqItem.classList.remove('open');
				answer.style.display = 'none';
			} else {
				faqItem.classList.add('open');
				answer.style.display = 'block';
			}
		}
    </script>
	</body>
</html>