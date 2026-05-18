<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$events_query = "SELECT * FROM events ORDER BY event_date ASC";
$events_result = mysqli_query($conn, $events_query);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skate Events | Pro Ride</title>

  <!-- الروابط والخطوط -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="./style/global.css">
  <link rel="stylesheet" href="./style/events.css">
  <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@300;600;900&display=swap" rel="stylesheet">
</head>

<body>

<div class="cursor"></div>
<div class="cursor-follower"></div>

<header class="hero">
    <div class="massive-bg-text">SKATE</div>

    <div class="skate-tape">
        <div class="tape-track">
            <div class="tape-content">
                <i class="fas fa-bolt"></i> <span>KICKFLIP YOUR REALITY</span>
                <i class="fas fa-skull"></i> <span>GRIND THE STREETS</span>
                <i class="fas fa-fire"></i> <span>GRAVITY IS A SUGGESTION</span>
                <i class="fas fa-wind"></i> <span>CONCRETE SURFERS</span>
                <i class="fas fa-radiation"></i> <span>THRASH & BURN</span>
            </div>
            <div class="tape-content">
                <i class="fas fa-bolt"></i> <span>KICKFLIP YOUR REALITY</span>
                <i class="fas fa-skull"></i> <span>GRIND THE STREETS</span>
                <i class="fas fa-fire"></i> <span>GRAVITY IS A SUGGESTION</span>
                <i class="fas fa-wind"></i> <span>CONCRETE SURFERS</span>
                <i class="fas fa-radiation"></i> <span>THRASH & BURN</span>
            </div>
        </div>
    </div>

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


    <div class="floating-container">
        <img src="https://images.unsplash.com/photo-1547447134-cd3f5c716030?q=80&w=300" class="float-item img-main-1" data-speed="4">
        <img src="https://images.unsplash.com/photo-1520156582985-31368bd59c3b?q=80&w=300" class="float-item img-main-2" data-speed="-5">
        <i class="fas fa-compact-disc float-item icon-1" data-speed="8"></i>
        <i class="fas fa-skull float-item icon-2" data-speed="-10"></i>
    </div>

    <div class="hero-content">
        <p class="subtitle gsap-reveal">Find Your Next Experience</p>
        <h1 class="main-title gsap-reveal">
            DISCOVER & JOIN <br>
            <span>SKATE EVENTS</span>
        </h1>

        <div class="search-box gsap-reveal">
            <div class="search-field">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Event (e.g. Skateboard...)">
            </div>

            <div class="search-field">
                <i class="fas fa-map-marker-alt"></i>
                <select>
                    <option>Location</option>
                    <option>Nablus</option>
                    <option>Tulkarm</option>
                    <option>Ramallah</option>
                </select>
            </div>

            <button class="search-btn">SEARCH <i class="fas fa-arrow-right"></i></button>
        </div>
    </div>
</header>

<section class="events-section">
    <div class="section-header">
        <h2 class="gsap-scroll">Featured Battles</h2>
        <p class="gsap-scroll">Explore the best skating workshops and competitions</p>
    </div>

    <div class="expanding-slider gsap-scroll">

        <?php
        $first = true;

        while ($event = mysqli_fetch_assoc($events_result)) {
            $activeClass = $first ? "active" : "";
            $first = false;

            $event_id = $event["id"];
            $title = htmlspecialchars($event["title"]);
            $category = htmlspecialchars($event["category"]);
            $date = date("M d, Y", strtotime($event["event_date"]));
            $price = number_format($event["price"], 2);
            $image = htmlspecialchars($event["image"]);

            echo "
            <div class='skate-panel $activeClass'>
                <img src='$image' alt='$title' class='panel-bg-img'>
                <div class='panel-shadow'></div>

                <div class='panel-content'>
                    <div class='icon-box'>
                        <i class='fas fa-skating'></i>
                    </div>

                    <div class='info'>
                        <span class='category'>$category</span>
                        <h3>$title</h3>
                        <p><i class='far fa-calendar-alt'></i> $date</p>

                        <button
                            class='btn-card'
                            onclick='openTicketModal($event_id, \"$title\", $price)'
                        >
                            GET TICKET
                        </button>
                    </div>
                </div>
            </div>
            ";
        }
        ?>

    </div>

    <div class="see-more gsap-scroll">
        <button class="see-more-btn">LOAD MORE <i class="fas fa-bolt"></i></button>
    </div>
</section>

<div id="ticket-modal" class="modal-overlay">
    <div class="modal-card">
        <button class="close-modal"><i class="fas fa-times"></i></button>

        <div class="modal-header">
            <i class="fas fa-ticket-alt pulse-icon"></i>
            <h2>SECURE CHECKOUT</h2>
            <p>You're one step away from the concrete battle.</p>
        </div>

        <div class="order-info">
            <div class="info-row">
                <span>EVENT:</span>
                <span id="modal-event-name" class="neon-text">EVENT_NAME</span>
            </div>

            <div class="info-row">
                <span>PRICE:</span>
                <span id="modal-event-price">$0.00</span>
            </div>
            <div class="info-row">
    <span>QUANTITY:</span>

    <div class="qty-controls">

        <button
            type="button"
            class="qty-btn"
            onclick="changeTicketQty(-1)"
        >
            <i class="fas fa-minus"></i>
        </button>

        <span id="ticket-qty" class="qty-number">
            1
        </span>

        <button
            type="button"
            class="qty-btn"
            onclick="changeTicketQty(1)"
        >
            <i class="fas fa-plus"></i>
        </button>

    </div>

</div>
</div>
<div class="info-row">
    <span>TOTAL:</span>
    <span id="modal-ticket-total">$0.00</span>
</div>


        

        <div class="payment-section">
            <p class="label">SELECT PAYMENT METHOD</p>

            <div class="payment-grid">
                <label class="pay-option">
                    <input type="radio" name="payment" value="Credit Card" checked>
                    <div class="pay-box">
                        <i class="fab fa-cc-visa"></i>
                        <span>CREDIT CARD</span>
                    </div>
                </label>

                <label class="pay-option">
                    <input type="radio" name="payment" value="PayPal">
                    <div class="pay-box">
                        <i class="fab fa-paypal"></i>
                        <span>PAYPAL</span>
                    </div>
                </label>
            </div>
        </div>

        <input type="hidden" id="selected-event-id">

        <button class="pay-now-btn" onclick="purchaseTicket()">
            CONFIRM PURCHASE <i class="fas fa-bolt"></i>
        </button>

        <div class="modal-footer">
            <i class="fas fa-shield-alt"></i>
            Encrypted & Secured by SkateHub
        </div>
    </div>
</div>

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
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2024 SKATEHUB. DESIGNED BY SKATERS FOR SKATERS</p>
    </div>
</footer>

<div id="footer-modal" class="modal-overlay">
    <div class="modal-card footer-modal-card">
        <button class="close-footer-modal"><i class="fas fa-times"></i></button>

        <div class="modal-header">
            <i id="modal-icon" class="fas fa-info-circle pulse-icon"></i>
            <h2 id="modal-title" class="neon-text-blue">TITLE HERE</h2>
        </div>

        <div id="modal-body-content" class="modal-text-content"></div>

        <div class="modal-footer">
            <button class="close-btn-bottom">GOT IT!</button>
        </div>
    </div>
</div>


<script>

let currentTicketPrice = 0;
let currentQty = 1;

function openTicketModal(eventId, eventName, price) {

    currentTicketPrice = Number(price);
    currentQty = 1;

    document.getElementById("selected-event-id").value = eventId;

    document.getElementById("modal-event-name").innerText =
        eventName;

    document.getElementById("modal-event-price").innerText =
        "$" + currentTicketPrice.toFixed(2);

    document.getElementById("ticket-qty").innerText =
        currentQty;

    updateTicketTotal();

    document.getElementById("ticket-modal").style.display =
        "flex";
}

function changeTicketQty(change) {

    currentQty += change;

    if (currentQty < 1) {
        currentQty = 1;
    }

    document.getElementById("ticket-qty").innerText =
        currentQty;

    updateTicketTotal();
}

function updateTicketTotal() {

    const total =
        currentTicketPrice * currentQty;

    document.getElementById("modal-ticket-total").innerText =
        "$" + total.toFixed(2);
}

function purchaseTicket() {

    const eventId =
        document.getElementById("selected-event-id").value;

    const paymentMethod =
        document.querySelector(
            'input[name="payment"]:checked'
        ).value;

    fetch("purchase_ticket.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/json"
        },

        body: JSON.stringify({

            event_id: eventId,

            payment_method: paymentMethod,

            quantity: currentQty
        })
    })

    .then(response => response.json())

    .then(data => {

        if (data.success) {

            document.querySelector(".pay-now-btn").innerHTML =
                "✔ PURCHASE COMPLETE";

            document.querySelector(".pay-now-btn").style.background =
                "#00ff99";

            setTimeout(() => {

                window.location.href =
                    "my-tickets.php?from=events";

            }, 1200);

        } else {

            if (data.message === "login_required") {

                alert("Please login first.");

                window.location.href = "login.html";

            } else {

                alert(data.message);
            }
        }
    });
}

document.querySelector(".close-modal")
.addEventListener("click", function () {

    document.getElementById("ticket-modal").style.display =
        "none";
});

</script>
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="./java/cursor.js"></script>
<script src="./java/events.js"></script>
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
<?php include "ai-widget.php"; ?>
</body>
</html>