<?php
// Include database connection
require_once 'db_connect.php';

// Check if database connection is established
if (!isset($db_connected) || $db_connected !== true) {
    die("Database connection error");
}

// Initialize or access the shopping cart session
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart action from shop.php
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    // Fetch product details
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id AND status = 'active'");
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            // Check if product already in cart
            $found = false;
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    $_SESSION['cart'][$key]['quantity']++;
                    $found = true;
                    break;
                }
            }
            
            // If product not in cart, add it
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => 1
                ];
            }
            
            // Redirect to prevent form resubmission
            header("Location: cart.php?status=added");
            exit();
        }
    } catch(PDOException $e) {
        error_log("Error adding product to cart: " . $e->getMessage());
    }
}

// Handle remove from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    
    // Reset array keys
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    
    // Redirect to prevent form resubmission
    header("Location: cart.php?status=removed");
    exit();
}

// Handle quantity update
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        $product_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_var($qty, FILTER_SANITIZE_NUMBER_INT);
        
        if ($quantity > 0) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    $_SESSION['cart'][$key]['quantity'] = $quantity;
                    break;
                }
            }
        }
    }
    
    // Redirect to prevent form resubmission
    header("Location: cart.php?status=updated");
    exit();
}

// Calculate cart totals
$total_items = 0;
$total_price = 0;

foreach ($_SESSION['cart'] as $item) {
    $total_items += $item['quantity'];
    $total_price += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- title -->
	<title>Cart</title>
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
	/* General Styles */
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f4f4f4;
}

.container {
  width: 80%;
  margin: 0 auto;
  padding: 20px;
}

/* Cart Section Styles */
.cart-section {
  background-color: white;
  padding: 50px 0;
}

h2 {
  text-align: center;
}

.cart-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.cart-table th, .cart-table td {
  padding: 10px;
  text-align: center;
}

.cart-table th {
  background-color: #333;
  color: white;
}

.cart-table tr:nth-child(even) {
  background-color: #f2f2f2;
}

.cart-summary {
  text-align: right;
  padding: 20px;
  background-color: #f2f2f2;
}

.cart-summary p {
  margin: 5px 0;
}

.checkout-btn {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
  font-size: 16px;
}

.checkout-btn:hover {
  background-color: #45a049;
}

/* Shop Section Styles */
.shop-section {
  padding: 50px 0;
  background-color: white;
}

.product-list {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: space-between;
}

.product-item {
  background-color: #fff;
  border: 1px solid #ddd;
  padding: 20px;
  text-align: center;
  width: 30%;
}

.product-item img {
  width: 100%;
  height: auto;
}

.product-item button {
  padding: 10px;
  background-color: #333;
  color: white;
  border: none;
  cursor: pointer;
  font-size: 16px;
}

.product-item button:hover {
  background-color: #555;
}

.quantity-input {
    width: 60px;
    text-align: center;
    padding: 5px;
}

.remove-button {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
}

.empty-cart-message {
    text-align: center;
    padding: 20px;
    font-size: 18px;
    color: #666;
}

.status-message {
    margin: 10px 0;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
}

.status-added {
    background-color: #d4edda;
    color: #155724;
}

.status-removed {
    background-color: #f8d7da;
    color: #721c24;
}

.status-updated {
    background-color: #d1ecf1;
    color: #0c5460;
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
	
	<!-- header --> <div class="top-header-area" id="sticker">
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
										<li><a href="faqq.php">Câu Hỏi</a></li>
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
						<h1>Cart</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section --> 
    
    <!-- cart -->
	<div class="cart-section mt-150 mb-150">
		<div class="container">
            <?php
            // Display status messages
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                if ($status == 'added') {
                    echo '<div class="status-message status-added">Product added to cart successfully!</div>';
                } elseif ($status == 'removed') {
                    echo '<div class="status-message status-removed">Product removed from cart!</div>';
                } elseif ($status == 'updated') {
                    echo '<div class="status-message status-updated">Cart updated successfully!</div>';
                }
            }
            ?>
			<div class="row">
				<div class="col-lg-8 col-md-12">
					<div class="cart-table-wrap">
                        <?php if (empty($_SESSION['cart'])): ?>
                            <div class="empty-cart-message">
                                <p>Your cart is empty. <a href="shop.php">Continue shopping</a></p>
                            </div>
                        <?php else: ?>
                            <form action="cart.php" method="post">
                                <table class="cart-table">
                                    <thead>
                                        <tr>
                                            <th>Remove</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($_SESSION['cart'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="remove-button">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="width: 60px; height: auto;">
                                                </td>
                                                <td><?php echo $item['name']; ?></td>
<td>$<?php echo number_format($item['price'], 2); ?></td>
<td>
    <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
</td>
<td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<div class="cart-buttons">
    <button type="submit" name="update_cart" class="boxed-btn">Update Cart</button>
    <a href="shop.php" class="boxed-btn">Continue Shopping</a>
</div>
</form>
<?php endif; ?>
</div>
</div>

<div class="col-lg-4">
    <div class="total-section">
        <table class="total-table">
            <thead class="total-table-head">
                <tr class="table-total-row">
                    <th>Total</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr class="total-data">
                    <td><strong>Subtotal:</strong></td>
                    <td>$<?php echo number_format($total_price, 2); ?></td>
                </tr>
                <tr class="total-data">
                    <td><strong>Shipping:</strong></td>
                    <td>$<?php echo ($total_price > 0) ? number_format(15, 2) : '0.00'; ?></td>
                </tr>
                <tr class="total-data">
                    <td><strong>Total:</strong></td>
                    <td>$<?php echo ($total_price > 0) ? number_format($total_price + 15, 2) : '0.00'; ?></td>
                </tr>
            </tbody>
        </table>
        <div class="cart-buttons">
            <a href="checkout.php" class="boxed-btn black">Proceed to Checkout</a>
        </div>
    </div>

    <div class="coupon-section">
        <h3>Apply Coupon</h3>
        <div class="coupon-form-wrap">
            <form action="">
                <p><input type="text" placeholder="Coupon Code"></p>
                <p><input type="submit" value="Apply"></p>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- end cart -->

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
                    <li><a href="news.php">Tin Tức</a></li>
                    <li><a href="contact.php">Phản Hồi</a></li>
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
            <p>Copyrights &copy; 2025 - <a href="https://fruitkha.com/">Fruitkha</a>, All Rights Reserved.</p>
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