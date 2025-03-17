<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fruit_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order ID from URL parameter
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id <= 0) {
    echo "Invalid order ID";
    exit;
}

// Get order details
$sql = "SELECT o.order_id, o.total_amount, o.created_at, o.status, 
               c.name, c.email, c.address, c.phone
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        WHERE o.order_id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Order not found";
    exit;
}

$order = $result->fetch_assoc();

// Get order items
$sql = "SELECT product_name, quantity, price
        FROM order_items
        WHERE order_id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

$items = [];
while ($row = $items_result->fetch_assoc()) {
    $items[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
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
                            <a href="index.php">
                                <img src="assets/img/logo.png" alt="">
                            </a>
                        </div>
                        <!-- logo -->

                        <!-- menu start -->
                        <nav class="main-menu">
                            <ul>
                                <li><a href="index.php">Trang Chủ</a></li>
                                <li class="current-list-item"><a href="shop.php">Cửa hàng</a></li>
                                <li><a href="news.php">Tin Tức</a></li>
                                <li><a href="faqq.html">Câu Hỏi</a></li>
                                <li><a href="contact.php">Phản Hồi</a></li>
                                <li>
                                    <div class="header-icons">
                                        <a class="shopping-cart" href="cart.php"><i class="fas fa-shopping-cart"></i></a>
                                        <a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>
                                        <a class="shopping-login" href="login.php"><i class="fa-solid fa-user"></i></a>
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
                        <p>Thank you for your order</p>
                        <h1>Order Confirmation</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- order confirmation section -->
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="order-success-wrapper">
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h3>Your order has been received</h3>
                            <p>Thank you for your purchase!</p>
                        </div>
                        
                        <div class="order-details mt-5">
                            <h4>Order Details</h4>
                            <table class="table">
                                <tr>
                                    <th>Order Number:</th>
                                    <td>#<?php echo $order['order_id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td><?php echo date('F j, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Total:</th>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>Credit Card</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="customer-details mt-5">
                            <h4>Customer Information</h4>
                            <table class="table">
                                <tr>
                                    <th>Name:</th>
                                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="order-items mt-5">
                            <h4>Order Items</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-5">
                            <a href="index.php" class="boxed-btn">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end order confirmation section -->

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
					<div class="footer-box pages"> <h2 class="widget-title">Pages</h2>
						<ul>
							<li><a href="index.php">Home</a></li>
							<li><a href="shop.php">Shop</a></li>
							<li><a href="news.html">News</a></li>
							<li><a href="contact.html">Contact</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer-box subscribe">
						<h2 class="widget-title">Subscribe</h2>
						<p>Subscribe to our mailing list to get the latest updates.</p>
						<form action="index.php">
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

</body>

</html>