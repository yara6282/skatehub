<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$products_query = "SELECT * FROM products ORDER BY id DESC";

$products_result = mysqli_query($conn, $products_query);

$products = [];

while ($row = mysqli_fetch_assoc($products_result)) {

    $products[] = [
        "id" => $row["id"],
        "name" => $row["name"],
        "category" => $row["category"],
        "price" => "$" . number_format($row["price"], 2),
        "img" => $row["image"],
        "sizes" => $row["sizes"]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkateHub | Pro Shop</title>
  
  <!-- الروابط والخطوط -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="./style/global.css">
  <link rel="stylesheet" href="./style/shop.css">
  <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@300;600;900&display=swap" rel="stylesheet">
</head>
<body>

<!-- الناف بار الخاص بك -->

<nav class="navbar">

    <a href="home.php" class="logo-link">
       <div class="logo">
                <img src="./image/9037278.png" alt="SkateHub Logo" onerror="this.style.display='none'">
            </div>
        <span class="site-title">SkateHub</span>
    </a>

    <div class="nav-links">
        <a href="home.php" class="nav-item active-link">Home</a>
        <a href="events.php" class="nav-item">Events</a>
        <a href="shop.php" class="nav-item">Shop</a>
        <a href="community.php" class="nav-item">Community</a>
        <a href="tutorials.php" class="nav-item">Tutorials</a>
    </div>

    <div class="nav-icons">
<!-- التحقق من رتبة الأدمن -->
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin-dashboard.php" class="admin-nav-icon" title="Admin Panel">
            <i class="fas fa-user-shield"></i>
        </a>
    <?php endif; ?>
        <?php if (isset($_SESSION["user_id"])): ?>

            <a href="profile.php">
                <i class="fas fa-user-circle"></i>
            </a>

        <?php else: ?>

            <a href="login.html">
                <i class="fas fa-user-circle"></i>
            </a>

        <?php endif; ?>

        <div class="cart-btn">
            <a href="cart.php">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <span id="cart-count">0</span>
        </div>

        <div class="notif-wrapper">

            <button class="notif-btn" id="notifBtn">
                <i class="fas fa-bell"></i>

                <span class="notif-dot"></span>
            </button>

            <div class="notif-panel" id="notifPanel">

                <div class="notif-header">
                    NOTIFICATIONS
                </div>

                <div class="notif-list" id="notifList">

                    <div class="notif-loading">
                        Loading...
                    </div>

                </div>

            </div>

        </div>

    </div>

</nav>

<!-- قسم الهيرو السينمائي مع 3 صور -->
<section class="hero-shop">
    <div class="vhs-overlay"></div>
    <div class="scanline"></div>
    
    <div class="image-wrapper" id="hero-slider">
        <!-- الصورة الأولى (تكون نشطة في البداية) -->
        <img src="./image/pexels-introspectivedsgn-5880093.jpg" class="slide active" alt="Store 1">
        <!-- الصورة الثانية (ضع مسار صورتك الثانية هنا) -->
        <img src="./image/pexels-aedrian-8117154.jpg" class="slide" alt="Store 2">
        <!-- الصورة الثالثة (ضع مسار صورتك الثالثة هنا) -->
        <img src="./image/pexels-badhon-35750806.jpg" class="slide" alt="Store 3">
    </div>

    <div class="hero-title-area">
        <h1 class="glitch" data-text="SKATE HUB">SKATE HUB</h1>
        <p class="neon-text">EST. 2024 // PREMIUM SKATE GOODS</p>
    </div>
</section>

<!-- أزرار التصنيفات على شكل ملصقات (Stickers) -->
<div class="sticker-nav">
    <button onclick="filterProducts('all')" class="sticker s1 active">#ALL_STUFF</button>
    <button onclick="filterProducts('skates')" class="sticker s2">SKATES</button>
    <button onclick="filterProducts('tshirts')" class="sticker s3">CLOTHING</button>
    <button onclick="filterProducts('shoes')" class="sticker s4">KICKS</button>
    <button onclick="filterProducts('accessories')" class="sticker s5">GEAR</button>
</div>

<!-- معرض المنتجات -->
<main class="shop-main">
    <div class="section-header">
        <h2 id="category-title">FEATURED_ITEMS</h2>
    </div>

    <div class="products-grid" id="products-grid">
        <!-- المنتجات تظهر هنا عبر JS -->
    </div>
</main>
<section class="custom-lab">
    <div class="section-header">
        <h2>CUSTOM_<span>LAB</span></h2>
        <p>STREET_CREATIVITY_ACTIVE</p>
    </div>

    <div class="lab-container">
        <!-- منطقة المعاينة (تتغير الـ Class الخاصة بها بين lab-deck و lab-shirt) -->
        <div class="preview-side" id="preview-side">
            <div class="preview-box lab-deck" id="design-area">
                <!-- قالب المنتج الفارغ -->
                <img src="./image/blank-deck.png" id="base-product-img" class="base-obj">
                
                <!-- طبقة تصميم المستخدم -->
                <div class="user-overlay">
                    <img id="user-uploaded-img" src="" class="custom-img-style">
                    <span id="user-custom-text">YOUR_TEXT</span>
                </div>
            </div>
        </div>

        <!-- أزرار التحكم -->
        <div class="controls-side">
            <div class="control-group">
                <label>1. SELECT_BASE</label>
                <div class="base-toggles">
                    <button class="base-btn active" onclick="setBase('deck', './image/blank-deck.jpg')">DECK</button>
                    <button class="base-btn" onclick="setBase('shirt', './image/blank-shirt.jpg')">SHIRT</button>
                </div>
            </div>

            <div class="control-group">
                <label>2. UPLOAD_ART</label>
                <input type="file" id="lab-image-input" accept="image/*" class="custom-file-input">
            </div>

            <div class="control-group">
                <label>3. CUSTOM_TEXT</label>
                <input type="text" id="lab-text-input" placeholder="Enter text...">
            </div>

            <div class="control-group">
                <label>4. CHOOSE_COLOR</label>
                <input type="color" id="lab-color-input" value="#ff0055">
            </div>

            <button class="publish-btn" onclick="addCustomToCart()">ADD_CUSTOM_TO_DECK</button>
        </div>
    </div>
</section>
<!-- Footer المطور والنحيف -->
<footer class="main-footer">
    <div class="footer-content">
        <div class="footer-column">
            <h4>SKATEHUB INFO</h4>
            <ul>
                <li><a href="#" class="footer-link" data-type="about">About Us</a></li>
                <li><a href="#" class="footer-link" data-type="team">Team Riders</a></li>
                <li><a href="#" class="footer-link" data-type="privacy">Privacy Policy</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h4>CUSTOMER SERVICE</h4>
            <ul>
                <li><a href="#" class="footer-link" data-type="faq">FAQ</a></li>
                <li><a href="#" class="footer-link" data-type="contact">Contact Us</a></li>
                <li><a href="#" class="footer-link" data-type="sizing">Sizing Chart</a></li>
            </ul>
        </div>

        <div class="footer-column socials">
            <h4>FOLLOW THE FLOW</h4>
            <div class="social-icons">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="payment-methods">
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-cc-paypal"></i>
                <i class="fab fa-apple-pay"></i>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 SKATEHUB. DESIGNED BY SKATERS FOR SKATERS</p>
    </div>
</footer>

<!-- المودال الخاص بالفوتر (نفس تصميم التيكت بس أكبر وبلون #222) -->
<div id="footer-modal" class="modal-overlay">
    <div class="modal-card footer-modal-card">
        <button class="close-footer-modal"><i class="fas fa-times"></i></button>
        
        <div class="modal-header">
            <i id="modal-icon" class="fas fa-info-circle pulse-icon"></i>
            <h2 id="modal-title" class="neon-text-blue">TITLE HERE</h2>
        </div>

        <div id="modal-body-content" class="modal-text-content">
            <!-- الحكي والشرح سيظهر هنا برمجياً -->
        </div>

        <div class="modal-footer">
            <button class="close-btn-bottom">GOT IT!</button>
        </div>
    </div>
</div>
<!-- مكان عرض المنتجات -->
<div id="products" class="products-section"></div>
<!-- 1. مكتبة الأنميشن -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <!-- Custom Cursor (Global) -->
    <div class="cursor"></div>
    <div class="cursor-follower"></div>
<!-- ملف الماوس المخصص الخاص بك -->
<script src="./java/cursor.js"></script>

<script>
const dbProducts = <?php echo json_encode($products); ?>;
</script>
<!-- مكتبة تصوير العناصر -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="./js/shop.js"></script>

<!-- أو -->
<script src="/java/cart.js"></script> <!-- في صفحة الكارت -->
<script>

const notifBtn =
document.getElementById("notifBtn");

const notifPanel =
document.getElementById("notifPanel");

const notifList =
document.getElementById("notifList");

notifBtn.addEventListener("click", async () => {

    notifPanel.classList.toggle("active");

    if(notifPanel.classList.contains("active")){

        try{

            const response =
            await fetch("fetch_notifications.php");

            const data =
            await response.json();

            if(data.length === 0){

                notifList.innerHTML = `
                    <div class="notif-empty">
                        NO NOTIFICATIONS YET
                    </div>
                `;

                return;
            }

            notifList.innerHTML = "";

            data.forEach(notif => {

                notifList.innerHTML += `

                <div class="notif-item">

                    <div class="notif-message">
                        ${notif.message}
                    </div>

                    <div class="notif-time">
                        ${notif.created_at}
                    </div>

                </div>

                `;
            });

        }catch(err){

            notifList.innerHTML = `
                <div class="notif-empty">
                    ERROR LOADING NOTIFICATIONS
                </div>
            `;
        }
    }
});

document.addEventListener("click", (e)=>{

    if(
        !notifBtn.contains(e.target) &&
        !notifPanel.contains(e.target)
    ){
        notifPanel.classList.remove("active");
    }
});

</script>
<!-- حاوية إشعارات الشوب -->
<div id="shop-toast-container" class="shop-toast-container"></div>
</body>
</html>