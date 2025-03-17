<?php
// Kết nối đến cơ sở dữ liệu
require_once 'db_connect.php';

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
								<li ><a href="index.php">Trang Chủ</a>
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
										<li><a href="checkout.php">Thanh TOán</a></li>
										<li><a href="contact.php">Phản Hồi</a></li>
										<li><a href="news.php">Tin Tức</a></li>
										<li><a href="shop.php">Cửa Hàng</a></li>
										<li class="current-list-item"><a href="faqq.php">Câu Hỏi</a></li>
									</ul>
								</li>
								<li>
									<div class="header-icons">
										<a class="shopping-cart" href="cart.php"><i class="fas fa-shopping-cart"></i></a>
										<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
										<a class="shopping-login" href="login.php"><i class="fa-solid fa-user"></i></a>
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
		<div class="search-area">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<span class="close-btn"><i class="fas fa-window-close"></i></span>
						<div class="search-bar">
							<div class="search-bar-tablecell">
								<h3>Search For:</h3>
								<input type="text" placeholder="Keywords">
								<button type="submit">Search <i class="fas fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	<div class="footer-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer-box about-widget">
						<h2 class="widget-title">About us</h2>
						<p>Ut enim ad minim veniam perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae.</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box get-in-touch">
						<h2 class="widget-title">Get in Touch</h2>
						<ul>
							<li>34/8, East Hukupara, Gifirtok, Sadan.</li>
							<li>support@fruitkha.com</li>
							<li>+00 111 222 3333</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box pages">
						<h2 class="widget-title">Trang</h2>
						<ul>
							<li><a href="index.php">Trang Chủ</a></li>
							<li><a href="shop.php">Cửa Hàng</a></li>
							<li><a href="news.php">News</a></li>
							<li><a href="contact.php">Contact</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box subscribe">
						<h2 class="widget-title">Subscribe</h2>
						<p>Subscribe to our mailing list to get the latest updates.</p>
						<form action="index.html">
							<input type="email" placeholder="Email">
							<button type="submit"><i class="fas fa-paper-plane"></i></button>
						</form>
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
					
				</div>
				<div class="col-lg-6 text-right col-md-12">
					<div class="social-icons">
						<ul>
							<li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
							<li><a href="#" target="_blank"><i class="fab fa-dribbble"></i></a></li>
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