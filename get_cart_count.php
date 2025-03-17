<?php
// Start session
session_start();

// Calculate total items in cart
$total_items = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['quantity'];
    }
}

// Output only the number (no HTML)
echo $total_items;
?>