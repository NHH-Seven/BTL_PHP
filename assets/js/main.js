(function ($) {
  "use strict";

  $(document).ready(function($){
      
      // testimonial sliders
      $(".testimonial-sliders").owlCarousel({
          items: 1,
          loop: true,
          autoplay: true,
          responsive:{
              0:{
                  items:1,
                  nav:false
              },
              600:{
                  items:1,
                  nav:false
              },
              1000:{
                  items:1,
                  nav:false,
                  loop:true
              }
          }
      });

      // homepage slider
      $(".homepage-slider").owlCarousel({
          items: 1,
          loop: true,
          autoplay: true,
          nav: true,
          dots: false,
          navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
          responsive:{
              0:{
                  items:1,
                  nav:false,
                  loop:true
              },
              600:{
                  items:1,
                  nav:true,
                  loop:true
              },
              1000:{
                  items:1,
                  nav:true,
                  loop:true
              }
          }
      });

      // logo carousel
      $(".logo-carousel-inner").owlCarousel({
          items: 4,
          loop: true,
          autoplay: true,
          margin: 30,
          responsive:{
              0:{
                  items:1,
                  nav:false
              },
              600:{
                  items:3,
                  nav:false
              },
              1000:{
                  items:4,
                  nav:false,
                  loop:true
              }
          }
      });

      // count down
      if($('.time-countdown').length){  
          $('.time-countdown').each(function() {
          var $this = $(this), finalDate = $(this).data('countdown');
          $this.countdown(finalDate, function(event) {
              var $this = $(this).html(event.strftime('' + '<div class="counter-column"><div class="inner"><span class="count">%D</span>Days</div></div> ' + '<div class="counter-column"><div class="inner"><span class="count">%H</span>Hours</div></div>  ' + '<div class="counter-column"><div class="inner"><span class="count">%M</span>Mins</div></div>  ' + '<div class="counter-column"><div class="inner"><span class="count">%S</span>Secs</div></div>'));
          });
       });
      }

      // projects filters isotop
      $(".product-filters li").on('click', function () {
          
          $(".product-filters li").removeClass("active");
          $(this).addClass("active");

          var selector = $(this).attr('data-filter');

          $(".product-lists").isotope({
              filter: selector,
          });
          
      });
      
      // isotop inner
      $(".product-lists").isotope();

      // magnific popup
      $('.popup-youtube').magnificPopup({
          disableOn: 700,
          type: 'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
          preloader: false,
          fixedContentPos: false
      });

      // light box
      $('.image-popup-vertical-fit').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          mainClass: 'mfp-img-mobile',
          image: {
              verticalFit: true
          }
      });

      // homepage slides animations
      $(".homepage-slider").on("translate.owl.carousel", function(){
          $(".hero-text-tablecell .subtitle").removeClass("animated fadeInUp").css({'opacity': '0'});
          $(".hero-text-tablecell h1").removeClass("animated fadeInUp").css({'opacity': '0', 'animation-delay' : '0.3s'});
          $(".hero-btns").removeClass("animated fadeInUp").css({'opacity': '0', 'animation-delay' : '0.5s'});
      });

      $(".homepage-slider").on("translated.owl.carousel", function(){
          $(".hero-text-tablecell .subtitle").addClass("animated fadeInUp").css({'opacity': '0'});
          $(".hero-text-tablecell h1").addClass("animated fadeInUp").css({'opacity': '0', 'animation-delay' : '0.3s'});
          $(".hero-btns").addClass("animated fadeInUp").css({'opacity': '0', 'animation-delay' : '0.5s'});
      });

     

      // stikcy js
      $("#sticker").sticky({
          topSpacing: 0
      });

      //mean menu
      $('.main-menu').meanmenu({
          meanMenuContainer: '.mobile-menu',
          meanScreenWidth: "992"
      });
      
       // search form
      $(".search-bar-icon").on("click", function(){
          $(".search-area").addClass("search-active");
      });

      $(".close-btn").on("click", function() {
          $(".search-area").removeClass("search-active");
      });
  
  });


  jQuery(window).on("load",function(){
      jQuery(".loader").fadeOut(1000);
  });


}(jQuery));

// Biến lưu trữ giỏ hàng
let cart = [];

// Hàm thêm sản phẩm vào giỏ hàng
function addToCart(productName, productPrice, productImage) {
// Tìm sản phẩm trong giỏ hàng
let existingProduct = cart.find(item => item.name === productName);

if (existingProduct) {
  // Nếu sản phẩm đã tồn tại, tăng số lượng
  existingProduct.quantity += 1;
} else {
  // Nếu sản phẩm chưa tồn tại, thêm mới
  cart.push({ name: productName, price: productPrice, quantity: 1, image: productImage });
}

// Cập nhật hiển thị bảng giỏ hàng và lưu giỏ hàng vào localStorage
updateCartTable();
saveCartToLocalStorage();
// Cập nhật số lượng trong biểu tượng giỏ hàng
updateCartCount();
updateCartSummary();
Toastify({
  text: `${productName} đã được thêm vào giỏ hàng!`,
  duration: 3000, // Thời gian thông báo hiển thị (3 giây)
  close: true, // Hiển thị nút đóng thông báo
  gravity: "top", // Vị trí thông báo: "top" hoặc "bottom"
  position: "right", // Vị trí thông báo: "left", "center", "right"
  backgroundColor: "#4CAF50", 
  stopOnFocus: true, 
}).showToast();
}

// Hàm cập nhật bảng giỏ hàng
function updateCartTable() {
  const cartTableBody = document.getElementById('cart-table-body');

  if (!cartTableBody) {
    console.error("Không tìm thấy phần tử có ID 'cart-table-body'.");
    return; 
  }

  cartTableBody.innerHTML = ''; 

  cart.forEach((item, index) => {
    const row = `
      <tr class="table-body-row">
        <td class="product-remove">
          <a href="javascript:void(0)" onclick="removeFromCart(${index})"><i class="far fa-window-close"></i></a>
        </td>
        <td class="product-image"><img src="${item.image}" alt=""></td>
        <td class="product-name">${item.name}</td>
        <td class="product-price">$${item.price}</td>
        <td class="product-quantity">
          <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)">
        </td>
        <td class="product-total">$${(item.price * item.quantity).toFixed(2)}</td>
      </tr>
    `;
    cartTableBody.innerHTML += row;
  });

  updateCartSummary();
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeFromCart(index) {
cart.splice(index, 1); // Xóa sản phẩm khỏi mảng
updateCartTable(); // Cập nhật hiển thị bảng
saveCartToLocalStorage(); // Lưu giỏ hàng vào localStorage
updateCartSummary();
}

// Hàm cập nhật số lượng sản phẩm
function updateQuantity(index, newQuantity) {
if (newQuantity > 0) {
  cart[index].quantity = parseInt(newQuantity);
  updateCartTable(); // Cập nhật hiển thị bảng
  saveCartToLocalStorage(); // Lưu giỏ hàng vào localStorage
  updateCartSummary();
}
}

// Lưu giỏ hàng vào localStorage
function saveCartToLocalStorage() {
localStorage.setItem('cart', JSON.stringify(cart));
}

// Tải giỏ hàng từ localStorage khi trang được tải
function loadCartFromLocalStorage() {
const savedCart = localStorage.getItem('cart');
cart = savedCart ? JSON.parse(savedCart) : [];
console.log("cart",cart)
updateCartTable(); // Hiển thị lại giỏ hàng từ localStorage
}

// Gọi loadCartFromLocalStorage khi trang được tải
document.addEventListener('DOMContentLoaded', loadCartFromLocalStorage);

// Hàm cập nhật số lượng giỏ hàng
function updateCartCount() {
  const cartCountElement = document.getElementById('cart-count');
  
  if (cartCountElement) {
    const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCountElement.textContent = totalQuantity;
  }
}

// Hàm cập nhật tổng số lượng và tổng giá trị giỏ hàng
function updateCartSummary() {
  const totalItems = document.getElementById('total-items');
  const totalPrice = document.getElementById('total-price');

  if (totalItems && totalPrice) {
    // Tính tổng số lượng và tổng giá trị
    const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0).toFixed(2);

    // Cập nhật hiển thị tổng số lượng và tổng giá trị
    totalItems.textContent = totalQuantity;
    totalPrice.textContent = totalAmount;
  }
}

