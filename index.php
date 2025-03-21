<?php
session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include the home data file
require_once 'home_data.php';
// Nhận các sản phẩm nổi bật và tin tức mới nhất
$featuredProducts = getFeaturedProducts(3);
$latestNews = getLatestNews(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- title -->
<title>Fruitkha - Slider Version</title>
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
									<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a>
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
	<!-- end search area -->
	<!-- home page slider -->
	<div class="homepage-slider">
		<!-- single home slider -->
		<div class="single-homepage-slider homepage-bg-1">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-7 offset-lg-1 offset-xl-0">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Tươi & Hữu cơ</p>
								<h1>Trái cây theo mùa ngon</h1>
								<div class="hero-btns">
									<a href="shop.php" class="boxed-btn">Cửa Hàng </a>
									<a href="contact.php" class="bordered-btn">Phản Hồi</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- single home slider -->
		<div class="single-homepage-slider homepage-bg-2">
			<div class="container">
				<div class="row">
					<div class="col-lg-10 offset-lg-1 text-center">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Tươi mới mỗi ngày</p>
								<h1>Bộ sưu tập 100% hữu cơ</h1>
								<div class="hero-btns">
									<a href="shop.php" class="boxed-btn">Tới cửa hàng</a>
									<a href="contact.php" class="bordered-btn">liên hệ</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- single home slider -->
		<div class="single-homepage-slider homepage-bg-3">
			<div class="container">
				<div class="row">
					<div class="col-lg-10 offset-lg-1 text-right">
						<div class="hero-text">
							<div class="hero-text-tablecell">
								<p class="subtitle">Đang có chương trình khuyến mại lớn!</p>
								<h1>Nhận giảm giá tháng 12</h1>
								<div class="hero-btns">
									<a href="shop.php" class="boxed-btn">Tới Shop</a>
									<a href="contact.php" class="bordered-btn">Phản Hồi</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end home page slider -->
	<!-- features list section -->
	<div class="list-section pt-80 pb-80">
		<div class="container">

			<div class="row">
				<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon">
							<i class="fas fa-shipping-fast"></i>
						</div>
						<div class="content">
							<h3>Miễn Phí Vận Chuyển</h3>
							<p>Khi đặt hàng trên $75</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
					<div class="list-box d-flex align-items-center">
						<div class="list-icon">
							<i class="fas fa-phone-volume"></i>
						</div>
						<div class="content">
							<h3>Hỗ Trợ 24/7</h3>
							<p>Hỗ trợ tất cả các ngày trong tuần</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-6">
					<div class="list-box d-flex justify-content-start align-items-center">
						<div class="list-icon">
							<i class="fas fa-sync"></i>
						</div>
						<div class="content">
							<h3>Đền bù</h3>
							<p>Nhận hoàn tiền trong vòng 3 ngày!</p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<!-- end features list section -->
	<!-- product section -->
	<div class="product-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Sản Phẩm</span>Của Chúng Tôi </h3>
						<p>Các Sản Phẩm Nổi Bật Của Chúng Tôi</p>
					</div>
				</div>
			</div>
			<div class="row">
				<?php 
				if (!empty($featuredProducts)): 
					foreach ($featuredProducts as $product): 
				?>
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.php?id=<?php echo $product['id']; ?>">
								<img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
							</a>
						</div>
						<h3><?php echo htmlspecialchars($product['name']); ?></h3>
						<p class="product-price"><span>Per <?php echo htmlspecialchars($product['unit']); ?></span> <?php echo number_format($product['price'], 2); ?>$ </p>
						<a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
					</div>
				</div>
				<?php 
					endforeach; 
				else: 
				?>
				<div class="col-12 text-center">
					<p>No products available at the moment.</p>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- end product section -->
<!-- cart banner section -->
<section class="cart-banner pt-100 pb-100">
    	<div class="container">
        	<div class="row clearfix">
            	<!--Image Column-->
            	<div class="image-column col-lg-6">
                	<div class="image">
                    	<div class="price-box">
                        	<div class="inner-price">
                                <span class="price">
                                    <strong>30%</strong> <br> off per kg
                                </span>
                            </div>
                        </div>
                    	<img src="assets/img/a.jpg" alt="">
                    </div>
                </div>
	<!-- shop banner -->
	<section class="shop-banner">
    	<div class="container">
        	<h3>December sale is on! <br> with big <span class="orange-text">Discount...</span></h3>
            <div class="sale-percent"><span>Sale! <br> Upto</span>50%<span>off</span></div>
            <a href="shop.php" class="cart-btn btn-lg">Shop Now</a>
        </div>
    </section>
	<!-- end shop banner -->
<!-- latest news -->
<div class="latest-news pt-150 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">	
                    <h3><span class="orange-text">Bài Viết</span> Của Chúng Tôi </h3>
                    <p>Các Bài Viêt Nổi Bật Của Chúng Tôi</p>
                </div>
            </div>
        </div>

        <div class="row">
            <?php 
            if (!empty($latestNews)): 
                foreach ($latestNews as $news): 
                    // Xử lý tên lớp CSS từ giá trị hình ảnh
                    $image_class = !empty($news['image']) ? str_replace('.jpg', '', $news['image']) : 'hoa/news-bg-1';
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="single-latest-news">
                    <a href="single-news.php?id=<?php echo $news['id']; ?>">
                        <div class="latest-news-bg <?php echo $image_class; ?>"></div>
                    </a>
                    <div class="news-text-box">
                        <h3><a href="single-news.php?id=<?php echo $news['id']; ?>"><?php echo htmlspecialchars($news['title']); ?></a></h3>
                        <p class="blog-meta">
                            <span class="author"><i class="fas fa-user"></i> <?php echo htmlspecialchars($news['author']); ?></span>
                            <span class="date"><i class="fas fa-calendar"></i> <?php echo date('d F, Y', strtotime($news['published_date'])); ?></span>
                        </p>
                        <p class="excerpt"><?php echo htmlspecialchars($news['excerpt']); ?></p>
                        <a href="single-news.php?id=<?php echo $news['id']; ?>" class="read-more-btn">read more <i class="fas fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
            <?php 
                endforeach; 
            else: 
            ?>
            <div class="col-12 text-center">
                <p>No news available at the moment.</p>
            </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <a href="news.php" class="boxed-btn">More News</a>
            </div>
        </div>
    </div>
</div>

	<!-- jquery -->
	<script src="assets/js/jquery-1.11.3.min.js"></script><!--Thư viện jQuery hỗ trợ thao tác DOM, hiệu ứng, AJAX.-->
	<!-- bootstrap -->
	<script src="assets/bootstrap/js/bootstrap.min.js"></script><!--Các tính năng giao diện động của Bootstrap.-->
	<!-- count down -->
	<script src="assets/js/jquery.countdown.js"></script><!--Hiển thị bộ đếm ngược thời gian.-->
	<!-- isotope -->
	<script src="assets/js/jquery.isotope-3.0.6.min.js"></script><!--Lọc, sắp xếp nội dung theo dạng lưới động..-->
	<!-- waypoints -->
	<script src="assets/js/waypoints.js"></script>
<!--Kích hoạt sự kiện khi cuộn trang..-->
	<!-- owl carousel -->
	<script src="assets/js/owl.carousel.min.js"></script>
	<!--Tạo slider/băng chuyền ảnh..-->
	<!-- magnific popup 
	 ..-->
	<script src="assets/js/jquery.magnific-popup.min.js"></script>
	<!-- mean menu 
	 Hiển thị popup đẹp mắt..-->
	<script src="assets/js/jquery.meanmenu.min.js"></script>
	<!-- sticker js
	 Chuyển menu thành dạng mobile-friendly.. -->
	<script src="assets/js/sticker.js"></script>
	<!--
	 	Làm thanh menu cố định khi cuộn trang..
-->
	<script src="assets/js/main.js"></script>
	<!-- Cấu hình chung, hiệu ứng tùy chỉnh của trang web. -->
</body>
</html>