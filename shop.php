<?php
session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include database connection
require_once 'db_connect.php';

// Kiểm tra xem kết nối cơ sở dữ liệu đã được thiết lập chưa
if (!isset($db_connected) || $db_connected !== true) {
    die("Database connection error");
}

// Tìm nạp sản phẩm từ cơ sở dữ liệu
try {
    $query = "SELECT * FROM products WHERE status = 'active'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Error fetching products: " . $e->getMessage());
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- title -->
	<title>Shop</title>
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
                            <li ><a href="index.php">Trang Chủ</a>
                            </li>
                            <li><a href="contact.php">Phản Hồi</a></li>
                            </li>
                            <li><a href="news.php">Tin Tức</a>
                            </li>
                            <li class="current-list-item"><a href="shop.php">Cửa Hàng</a>
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
						<p>Tươi và hữu cơ</p>
						<h1>Cửa Hàng</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- products -->
	<div class="product-section mt-150 mb-150">
		<div class="container">

			<div class="row">
                <div class="col-md-12">
                    <div class="product-filters">
                        <ul>
                            <li class="active" data-filter="*">tất cả</li>
                            <?php
                            // Nhận danh mục độc đáo
                            $categories = [];
                            foreach ($products as $product) {
                                if (!in_array($product['category'], $categories)) {
                                    $categories[] = $product['category'];
                                }
                            }
                            
                            // Bộ lọc danh mục hiển thị
                            foreach ($categories as $category) {
                                echo '<li data-filter=".' . $category . '">' . ucfirst($category) . '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

			<div class="row product-lists">
                <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 text-center <?php echo $product['category']; ?>">
                    <div class="single-product-item">
                        <div class="product-image">
                            <a href="single-product.html?id=<?php echo $product['id']; ?>">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            </a>
                        </div>
                        <h3><?php echo $product['name']; ?></h3>
                        <p class="product-price">
                            <span>Per <?php echo $product['unit']; ?></span> 
                            <?php echo $product['price']; ?>$ 
                        </p>
                        <span class="cart-btn" onclick="addToCart('<?php echo $product['name']; ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
			</div>

			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="pagination-wrap">
						<ul>
							<li><a href="#">trước</a></li>
							<li><a class="active" href="#">1</a></li>
							<li><a href="#">sau</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end products -->

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
 </body> 

<script>
// Chức năng thêm sản phẩm vào giỏ hàng
function addToCart(name, price, image) {
    // Lấy mã sản phẩm từ thuộc tính URL hoặc dữ liệu
    const productElements = document.querySelectorAll('.single-product-item');
    let productId;
    
    // Tìm mã sản phẩm dựa trên tên sản phẩm
    productElements.forEach(function(element) {
        if (element.querySelector('h3').textContent === name) {
            const linkElement = element.querySelector('a');
            if (linkElement && linkElement.href) {
                const url = new URL(linkElement.href);
                productId = url.searchParams.get('id');
            }
        }
    });
    
    if (!productId) {
        alert('Error: Could not find product ID');
        return;
    }
    
    // Chuyển hướng đến cart.php có ID sản phẩm
    window.location.href = `cart.php?action=add&id=${productId}`;
}

// Chức năng cập nhật số lượng giỏ hàng (gọi đây khi tải trang)
function updateCartCount() {
    // Sử dụng AJAX để lấy số lượng giỏ hàng hiện tại
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_cart_count.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('cart-count').textContent = xhr.responseText;
        }
    };
    xhr.send();
}

// Gọi updateCartCount khi trang tải
window.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
</script>
</body>
</html>