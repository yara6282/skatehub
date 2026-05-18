<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateHub | Home</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/home.css">

    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@300;600;900&display=swap" rel="stylesheet">
</head>

<body>

<div class="cursor"></div>
<div class="cursor-follower"></div>

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

<section class="hero-slider">

    <div class="vhs-overlay"></div>
    <div class="scanlines"></div>

    <div class="slide active">
        <img src="./image/welc.jpg" alt="Welcome">

        <div class="slide-content">
            <h1 class="glitch" data-text="SKATE HUB">
                SKATE <span>HUB</span>
            </h1>

            <p>UNDERGROUND CULTURE EST. 2024</p>
        </div>
    </div>

    <div class="slide">
        <img src="./image/event.jpg" alt="Events">

        <div class="slide-content">
            <h2>THE_BATTLE</h2>
            <p>EPIC COMPETITIONS AWAIT</p>
        </div>
    </div>

    <div class="slide">
        <img src="./image/vans.jpg" alt="Gear">

        <div class="slide-content">
            <h2>PREMIUM_GEAR</h2>
            <p>UPGRADE YOUR RIDE</p>
        </div>
    </div>

    <div class="slide">

        <video autoplay muted loop playsinline class="slide-video">
            <source src="./videos/vid1.mp4" type="video/mp4">
        </video>

        <div class="slide-content">
            <h2>LEARN_PRO</h2>
            <p>MASTER THE STREETS</p>
        </div>
    </div>

    <div class="slide">

        <video autoplay muted loop playsinline class="slide-video">
            <source src="./videos/trick.mp4" type="video/mp4">
        </video>

        <div class="slide-content">
            <h2>JOIN_CREW</h2>
            <p>SHARE YOUR BEST MOVES</p>
        </div>
    </div>

</section>

<section class="featured-event-highlight">

    <div class="section-header">
        <h2>FEATURED_<span>EVENT</span></h2>
    </div>

    <div class="event-cinema-card">

        <div class="event-cinema-img">
            <img src="./image/3333.png" alt="Street King">

            <div class="cinema-overlay"></div>
        </div>

        <div class="event-cinema-content">

            <span class="cat-tag">
                SKATEBOARD
            </span>

            <h3>STREET KING</h3>

            <p class="event-date">
                <i class="far fa-calendar-alt"></i>
                SEPT 15, 2026
            </p>

            <p class="event-desc">
                The biggest underground competition is back in town.
                Prepare for the concrete battle.
            </p>

            <a href="events.php" class="neon-btn-pink">
                GET_TICKET
            </a>

        </div>

    </div>

</section>

<section class="shop-preview-compact">

    <div class="section-header">

        <h2>
            GEAR_<span>LOG</span>
        </h2>

        <a href="shop.php" class="view-all-link">
            EXPLORE_ALL
        </a>

    </div>

    <div class="compact-grid">

        <div class="p-card-small">

            <div class="p-img-box">
                <img src="./image/skate1.png" alt="Skate">
            </div>

            <div class="p-details">
                <h4>PRO SKATEBOARD</h4>
                <span class="price">$120</span>
            </div>

        </div>

        <div class="p-card-small">

            <div class="p-img-box">
                <img src="./image/t-shirt1.webp" alt="Tee">
            </div>

            <div class="p-details">
                <h4>URBAN T-SHIRT</h4>
                <span class="price">$25</span>
            </div>

        </div>

    </div>

</section>

<section class="tutorials-home-section">

    <div class="section-header">
        <h2>LEARN_<span>SKATE</span></h2>
    </div>

    <div class="tutorial-featured-box">

        <div class="vid-side">
            <iframe src="https://www.youtube.com/embed/JNmUK9fvrAs"
                    frameborder="0"
                    allowfullscreen>
            </iframe>
        </div>

        <div class="text-side">

            <h3>OLLIE_MASTERY</h3>

            <p>
                Learn the foundation of all technical street skating tricks.
            </p>

            <a href="tutorials.php" class="blue-link">
                GO_TO_TUTORIALS
            </a>

        </div>

    </div>

</section>

<footer class="main-footer">

    <div class="footer-content">

        <div class="footer-column">

            <h4>SKATEHUB INFO</h4>

            <ul>
                <li><a href="#" class="footer-link">About Us</a></li>
                <li><a href="#" class="footer-link">Team Riders</a></li>
                <li><a href="#" class="footer-link">Privacy Policy</a></li>
            </ul>

        </div>

        <div class="footer-column">

            <h4>CUSTOMER SERVICE</h4>

            <ul>
                <li><a href="#" class="footer-link">FAQ</a></li>
                <li><a href="#" class="footer-link">Contact Us</a></li>
                <li><a href="#" class="footer-link">Sizing Chart</a></li>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<script src="./java/slider.js"></script>
<script src="./java/home.js"></script>
<script src="./java/cursor.js"></script>

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

</body>
</html>