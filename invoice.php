<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

if (!isset($_GET["id"])) {
    die("INVALID_ORDER_ID");
}

$order_id = $_GET["id"];

$order_sql = "SELECT * FROM orders WHERE id='$order_id' AND user_id='$user_id'";
$order_result = mysqli_query($conn, $order_sql);

if (mysqli_num_rows($order_result) == 0) {
    die("ORDER_NOT_FOUND");
}

$order = mysqli_fetch_assoc($order_result);

$items_sql = "SELECT * FROM order_items WHERE order_id='$order_id'";
$items_result = mysqli_query($conn, $items_sql);

$user_name = $_SESSION["fullname"] ?? "SKATER_USER";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateHub | Digital Invoice</title>

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/invoice.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@300;400;600;900&display=swap" rel="stylesheet">
</head>

<body>

<div class="cursor"></div>
<div class="cursor-follower"></div>

<nav class="navbar">
    <div class="navbar login-nav">
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
    </div>
</nav>

<main class="invoice-page">

    <a href="orders.php" class="back-link">
        <i class="fas fa-arrow-left"></i> RETURN_TO_LOG
    </a>

    <div class="invoice-wrapper" id="printable-invoice">
        <div class="invoice-grain"></div>

        <header class="invoice-header">
            <div class="brand">
                <h1 class="logo">SKATE<span>HUB</span></h1>
                <p>OFFICIAL_STREET_GEAR</p>
            </div>

            <div class="invoice-meta">
                <span class="label">INVOICE_ID</span>
                <h2>#SK-<?php echo $order["id"]; ?></h2>
                <p>DATE: <?php echo date("M d, Y", strtotime($order["order_date"])); ?></p>
            </div>
        </header>

        <hr class="neon-hr">

        <section class="invoice-addresses">
            <div class="address-box">
                <h3>BILL_TO:</h3>
                <p class="skater-name"><?php echo $user_name; ?></p>
                <p><?php echo $order["address"]; ?></p>
                <p><?php echo $order["governorate"]; ?></p>
            </div>

            <div class="address-box">
                <h3>DELIVERY_STATUS:</h3>
                <span class="status-badge processing">PROCESSING</span>
                <p style="margin-top:10px;">
                    METHOD: <?php echo $order["shipping_method"]; ?>
                </p>
                <p>
                    PAYMENT: <?php echo $order["payment_method"]; ?>
                </p>
            </div>
        </section>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>PRODUCT_DESCRIPTION</th>
                    <th>SIZE</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>TOTAL</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                    <tr>
                        <td>
                            <div class="prod-cell">
                                <img src="<?php echo $item["product_img"]; ?>" alt="gear">
                                <span><?php echo $item["product_name"]; ?></span>
                            </div>
                        </td>

                        <td><?php echo $item["size"]; ?></td>
                        <td><?php echo $item["quantity"]; ?></td>
                        <td>$<?php echo number_format($item["price"], 2); ?></td>
                        <td>$<?php echo number_format($item["item_total"], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <hr class="neon-hr">

        <footer class="invoice-footer">
            <div class="note-box">
                <p>THANKS FOR SUPPORTING THE CREW.</p>
                <p class="slogan">SKATEBOARDING IS NOT A CRIME.</p>
            </div>

            <div class="summary-box">
                <div class="summary-row">
                    <span>SUBTOTAL:</span>
                    <span>$<?php echo number_format($order["subtotal"], 2); ?></span>
                </div>

                <div class="summary-row">
                    <span>SHIPPING:</span>
                    <span style="color:#00ff99;">
                        $<?php echo number_format($order["shipping_fee"], 2); ?>
                    </span>
                </div>

                <div class="summary-row total-row">
                    <span>GRAND_TOTAL:</span>
                    <span class="neon-price">
                        $<?php echo number_format($order["total"], 2); ?>
                    </span>
                </div>
            </div>
        </footer>

        <div class="invoice-controls">
            <button onclick="window.print()" class="control-btn">
                <i class="fas fa-print"></i> PRINT_REPORT
            </button>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="./java/cursor.js"></script>

</body>
</html>