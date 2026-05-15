<?php
session_start();

require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$user_img = "image/user-avatar.jpg";

if (isset($user["profile_img"]) && !empty($user["profile_img"])) {
    $user_img = "image/" . $user["profile_img"];
}

$tickets_count_sql = "SELECT COALESCE(SUM(quantity), 0) AS total_tickets FROM event_tickets WHERE user_id = '$user_id'";
$tickets_count_result = mysqli_query($conn, $tickets_count_sql);
$tickets_count_row = mysqli_fetch_assoc($tickets_count_result);
$total_tickets = $tickets_count_row["total_tickets"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateHub | Pro Profile</title>

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@300;600;900&display=swap" rel="stylesheet">
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
            <a href="events.php">Events</a>
            <a href="community.html">Community</a>
            <a href="shop.html">Shop</a>
            <a href="tutorials.html">Tutorials</a>
        </div>

        <div class="nav-icons">
            <a href="profile.php"><i class="fas fa-user"></i></a>
            <a href="cart.html"><i class="fas fa-shopping-cart"></i></a>
            <a href="notifications.html"><i class="fas fa-bell"></i></a>
        </div>
    </div>
</nav>

<main class="profile-page">

    <section class="profile-hero">
        <div class="profile-banner">
            <img src="./image/pexels-introspectivedsgn-13583313.jpg" alt="Banner">
            <div class="banner-blend-overlay"></div>
        </div>

        <div class="user-profile-info">
            <div class="avatar-container">
                <img src="<?php echo $user_img; ?>" alt="Skater">
                <div class="avatar-glow"></div>
            </div>

            <div class="user-text">
                <h1 class="glitch-name">
                    <?php echo htmlspecialchars($user["fullname"]); ?>
                </h1>

                <p class="user-tag">
                    <?php echo htmlspecialchars($user["email"]); ?>
                </p>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">

        <div class="db-card wishlist-card">
            <h3><i class="fas fa-heart"></i> SKATE_WISHLIST</h3>

            <div class="wishlist-container-large">
                <?php
                $wish_sql = "SELECT * FROM wishlist WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT 4";
                $wish_result = mysqli_query($conn, $wish_sql);

                if (mysqli_num_rows($wish_result) > 0) {
                    while ($wish = mysqli_fetch_assoc($wish_result)) {
                        echo "
                        <div class='wish-item-lg'>
                            <img src='" . $wish["product_img"] . "' alt='Gear'>

                            <div class='wish-details'>
                                <span>" . $wish["product_name"] . "</span>
                                <p>$" . number_format($wish["product_price"], 2) . "</p>
                            </div>

                            <a href='shop.html' class='shop-link-lg'>
                                <i class='fas fa-cart-plus'></i>
                            </a>
                        </div>
                        ";
                    }
                } else {
                    echo "<p class='no-gear'>No wishlist items yet.</p>";
                }
                ?>
            </div>
        </div>

        <div class="db-card account-actions">
            <h3><i class="fas fa-user-shield"></i> ACCOUNT_SYSTEM</h3>

            <div class="action-list-lg">

                <a href="edit-profile.php" class="action-btn-lg">
                    <div class="btn-info">
                        <i class="fas fa-user-edit"></i>
                        <span>EDIT_PROFILE_INFO</span>
                    </div>

                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="orders.php" class="action-btn-lg">
                    <div class="btn-info">
                        <i class="fas fa-history"></i>
                        <span>ORDER_HISTORY</span>
                    </div>

                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="my-tickets.php?from=profile" class="action-btn-lg">
                    <div class="btn-info">
                        <i class="fas fa-ticket-alt"></i>
                        <span>MY_TICKETS (<?php echo $total_tickets; ?>)</span>
                    </div>

                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="logout.php" class="action-btn-lg logout-btn-lg">
                    <div class="btn-info">
                        <i class="fas fa-power-off"></i>
                        <span>LOGOUT</span>
                    </div>
                </a>

            </div>
        </div>

    </section>

</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="./java/cursor.js"></script>

</body>
</html>