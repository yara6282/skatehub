
<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

$orders_sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$orders_result = mysqli_query($conn, $orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateHub | Order History</title>

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

<main class="orders-page">

    <h1 class="glitch-text">ORDER_<span>LOG</span></h1>

    <div class="orders-list">

        <?php if (mysqli_num_rows($orders_result) == 0): ?>

            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>NO ORDERS FOUND IN SYSTEM</p>
                <a href="shop.php">START_SHOPPING</a>
            </div>

        <?php else: ?>

            <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>

                <?php
                $order_id = $order["id"];
                $items_sql = "SELECT * FROM order_items WHERE order_id = '$order_id' LIMIT 3";
                $items_result = mysqli_query($conn, $items_sql);
                ?>

                <div class="simple-order-card">
                    <div class="order-info-top">
                        <span class="order-id">ID: #SK-<?php echo $order_id; ?></span>

                        <span class="order-date">
                            DATE: <?php echo date("d/m/Y", strtotime($order["order_date"])); ?>
                        </span>

                        <span class="order-status processing">PROCESSING</span>
                    </div>

                    <div class="order-body">
                        <div class="order-items-preview">
                            <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                                <img src="<?php echo $item["product_img"]; ?>" alt="gear">
                            <?php endwhile; ?>
                        </div>

                        <div class="order-action-area">
                            <div class="price-tag">
                                TOTAL: $<?php echo number_format($order["total"], 2); ?>
                            </div>

                            <a href="invoice.php?id=<?php echo $order_id; ?>" class="view-details-btn">
    VIEW_DETAILS
</a>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>

        <?php endif; ?>

    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
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
<script>
    updateCartCount();
// وظيفة تحديث عداد السلة
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('skateHub_FinalCart')) || [];
    let count = cart.reduce((sum, item) => sum + item.qty, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.innerText = count;
    }
}
</script>
</body>
</html>