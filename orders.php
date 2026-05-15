
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
    <div class="logo">
        <a href="home.php" class="logo-link">
            <img src="./image/9037278.png" alt="SkateHub Logo">
            <span class="site-title">SkateHub</span>
        </a>
    </div>

    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="events.html">Events</a>
        <a href="community.html">Community</a>
        <a href="shop.html">Shop</a>
        <a href="tutorials.html">Tutorials</a>
    </div>

    <div class="nav-icons">
        <a href="login.html"><i class="fas fa-user"></i></a>
        <a href="cart.html"><i class="fas fa-shopping-cart"></i></a>
        <a href="orders.php"><i class="fas fa-box"></i></a>
        <a href="notifications.html"><i class="fas fa-bell"></i></a>
    </div>
</nav>

<main class="orders-page">

    <h1 class="glitch-text">ORDER_<span>LOG</span></h1>

    <div class="orders-list">

        <?php if (mysqli_num_rows($orders_result) == 0): ?>

            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>NO ORDERS FOUND IN SYSTEM</p>
                <a href="shop.html">START_SHOPPING</a>
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

</body>
</html>