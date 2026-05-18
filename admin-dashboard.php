<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . "/includes/db.php";

$products_result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
$events_result =
mysqli_query($conn,
"SELECT * FROM events ORDER BY id DESC");

$tutorials_result =
mysqli_query($conn,
"SELECT * FROM tutorials ORDER BY id DESC");
$orders_sql = "
SELECT o.*, u.fullname, u.email
FROM orders o
JOIN users u ON o.user_id = u.id
ORDER BY o.order_date DESC
";
$orders_result = mysqli_query($conn, $orders_sql);

$count_result = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM orders");
$total_orders = mysqli_fetch_assoc($count_result)["total_orders"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateHub | Admin Panel</title>

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@300;400;600;900&display=swap" rel="stylesheet">
</head>

<body>

<div class="cursor"></div>
<div class="cursor-follower"></div>

<div class="admin-wrapper">

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2 class="logo">SKATE<span>ADMIN</span></h2>
        </div>

        <nav class="sidebar-nav">
            <button class="nav-btn active" onclick="showSection('shop-mgmt')">
                <i class="fas fa-shopping-cart"></i> SHOP_GEAR
            </button>

            <button class="nav-btn" onclick="showSection('events-mgmt')">
                <i class="fas fa-calendar-alt"></i> EVENTS_MGMT
            </button>

            <button class="nav-btn" onclick="showSection('tutorials-mgmt')">
                <i class="fas fa-play-circle"></i> TUTORIALS_CLIPS
            </button>

            <button class="nav-btn" onclick="showSection('orders-mgmt')">
                <i class="fas fa-box"></i> ORDERS_QUEUE
                <span class="order-badge"><?php echo $total_orders; ?></span>
            </button>

            <hr class="nav-divider">

            <a href="home.php" class="nav-btn logout">
                <i class="fas fa-external-link-alt"></i> VIEW_SITE
            </a>

            <a href="logout.php" class="nav-btn logout">
                <i class="fas fa-power-off"></i> LOGOUT
            </a>
        </nav>
    </aside>

    <main class="main-content">

        <header class="content-header">
            <h1 id="section-title">SHOP_GEAR_MANAGEMENT</h1>

            <div class="admin-profile">
                <span>WELCOME, <?php echo strtoupper($_SESSION["fullname"]); ?></span>
                <img src="./image/user-avatar.jpg" alt="Admin">
            </div>
        </header>

        <section id="shop-mgmt" class="mgmt-section active">
            <div class="action-bar">
                <h2 class="sub-title">
                    <i class="fas fa-cart-plus"></i> INVENTORY_CONTROL
                </h2>
            </div>

            <div class="admin-form-card">
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <div class="form-grid">

                        <div class="input-group">
                            <label>PRODUCT_NAME</label>
                            <input type="text" name="p_name" placeholder="e.g. Baker Skull Skateboard" required>
                        </div>

                        <div class="input-group">
                            <label>PRICE ($)</label>
                            <input type="number" name="p_price" step="0.01" placeholder="59.99" required>
                        </div>

                        <div class="input-group">
                            <label>CATEGORY</label>
                            <select name="p_category" required>
                                <option value="skates">SKATES</option>
                                <option value="tshirts">TSHIRTS</option>
                                <option value="shoes">SHOES</option>
                                <option value="accessories">ACCESSORIES</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label>PRODUCT_IMAGE</label>
                            <input type="file" name="p_img" accept="image/*" required>
                        </div>

                        <div class="input-group full-width">
                            <label>AVAILABLE_SIZES</label>

                            <div class="sizes-checkboxes">
                                <label class="check-item"><input type="checkbox" name="sizes[]" value="S"> S</label>
                                <label class="check-item"><input type="checkbox" name="sizes[]" value="M"> M</label>
                                <label class="check-item"><input type="checkbox" name="sizes[]" value="L"> L</label>
                                <label class="check-item"><input type="checkbox" name="sizes[]" value="XL"> XL</label>
                                <label class="check-item"><input type="checkbox" name="sizes[]" value="OS"> ONE_SIZE</label>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="publish-btn shop-btn">
                        ADD_TO_INVENTORY
                    </button>
                </form>
            </div>

            <div class="products-admin-grid">

                <?php if (mysqli_num_rows($products_result) == 0): ?>

                    <p style="color:#8892b0;">No products added yet.</p>

                <?php else: ?>

                    <?php while ($product = mysqli_fetch_assoc($products_result)): ?>

                        <div class="admin-product-card">

                            <img src="<?php echo $product['image']; ?>" alt="product">

                            <div class="admin-product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>

                                <p class="admin-price">
                                    $<?php echo number_format($product['price'], 2); ?>
                                </p>

                                <span class="admin-category">
                                    <?php echo strtoupper(htmlspecialchars($product['category'])); ?>
                                </span>
                            </div>

                            <div class="admin-product-actions">
                                <a
                                    href="delete_product.php?id=<?php echo $product['id']; ?>"
                                    class="delete-product-btn"
                                    onclick="return confirm('Delete this product?');"
                                >
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php endif; ?>

            </div>
        </section>

        <section id="events-mgmt" class="mgmt-section">
            <div class="action-bar">
                <h2 class="sub-title">
                    <i class="fas fa-calendar-alt"></i> POST_NEW_EVENT
                </h2>
            </div>

            <div class="admin-form-card">
                <form action="add_event.php" method="POST" enctype="multipart/form-data">
                    <div class="form-grid">

                        <div class="input-group">
                            <label>EVENT_TITLE</label>
                            <input type="text" name="event_title" placeholder="e.g. STREET KING" required>
                        </div>

                        <div class="input-group">
                            <label>EVENT_CATEGORY</label>
                            <input type="text" name="event_category" placeholder="e.g. SKATEBOARD / NIGHT RIDE" required>
                        </div>

                        <div class="input-group">
                            <label>EVENT_LOCATION</label>
                            <input type="text" name="event_location" placeholder="e.g. Nablus" required>
                        </div>

                        <div class="input-group">
                            <label>EVENT_DATE</label>
                            <input type="date" name="event_date" required>
                        </div>

                        <div class="input-group">
                            <label>TICKET_PRICE ($)</label>
                            <input type="number" name="event_price" step="0.01" required>
                        </div>

                        <div class="input-group">
                            <label>HERO_BACKGROUND_IMAGE</label>
                            <input type="file" name="event_bg" accept="image/*" required>
                        </div>

                        <div class="input-group full-width">
                            <label>SHORT_DESCRIPTION</label>
                            <textarea name="event_desc" rows="3"></textarea>
                        </div>

                    </div>

                    <button type="submit" class="publish-btn">
                        PUBLISH_TO_LOBBY
                    </button>
                </form>
            </div>
            <div class="admin-content-grid">

<?php while($event = mysqli_fetch_assoc($events_result)): ?>

<div class="content-card">

    <img
    src="<?php echo $event['image']; ?>"
    alt="event">

    <div class="content-info">

        <h3>
            <?php echo $event['title']; ?>
        </h3>

        <p class="content-date">
            <?php echo $event['date']; ?>
        </p>

        <p class="content-price">
            $<?php echo number_format($event['price'],2); ?>
        </p>

    </div>

    <a
    href="delete_event.php?id=<?php echo $event['id']; ?>"
    class="content-delete"
    >
        <i class="fas fa-trash"></i>
    </a>

</div>

<?php endwhile; ?>

</div>
        </section>

        <section id="tutorials-mgmt" class="mgmt-section">
            <div class="action-bar">
                <h2 class="sub-title">
                    <i class="fas fa-play-circle"></i> UPLOAD_TUTORIAL
                </h2>
            </div>

            <div class="admin-form-card">
                <form action="add_tutorial.php" method="POST">
                    <div class="form-grid">

                        <div class="input-group">
                            <label>VIDEO_TITLE</label>
                            <input type="text" name="video_title" placeholder="e.g. Basic Ollie" required>
                        </div>

                        <div class="input-group">
                            <label>STYLE_CATEGORY</label>

                            <select name="video_style" required>
                                <option value="Skateboard">SKATEBOARD</option>
                                <option value="Roller Skate">ROLLER SKATE</option>
                                <option value="Inline">INLINE</option>
                            </select>
                        </div>

                        <div class="input-group full-width">
                            <label>YOUTUBE_VIDEO_ID</label>
                            <input type="text" name="video_url" placeholder="e.g. dF7T_f88vI4" required>
                        </div>

                        <div class="input-group full-width">
                            <label>SHORT_DESCRIPTION</label>
                            <textarea name="video_desc" rows="3" required></textarea>
                        </div>

                    </div>

                    <button type="submit" class="publish-btn tutorial-btn">
                        SYNC_TUTORIAL_CLIP
                    </button>
                </form>
            </div>
            <div class="admin-content-grid">

<?php while($tutorial = mysqli_fetch_assoc($tutorials_result)): ?>

<div class="content-card tutorial-card">

    <iframe
    src="https://www.youtube.com/embed/<?php echo $tutorial['video_id']; ?>"
    frameborder="0"
    allowfullscreen>
    </iframe>

    <div class="content-info">

        <h3>
            <?php echo $tutorial['title']; ?>
        </h3>

        <p class="tutorial-style">
            <?php echo $tutorial['style']; ?>
        </p>

    </div>

    <a
    href="delete_tutorial.php?id=<?php echo $tutorial['id']; ?>"
    class="content-delete"
    >
        <i class="fas fa-trash"></i>
    </a>

</div>

<?php endwhile; ?>

</div>
        </section>

        <section id="orders-mgmt" class="mgmt-section">
            <div class="action-bar">
                <h2 class="sub-title">
                    <i class="fas fa-stream"></i> INCOMING_ORDERS_LOG
                </h2>
            </div>

            <div class="orders-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ORDER_ID</th>
                            <th>CUSTOMER_INFO</th>
                            <th>ORDER_ITEMS (SIZE/QTY)</th>
                            <th>TOTAL_PRICE</th>
                            <th>STATUS_CONTROL</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (mysqli_num_rows($orders_result) == 0): ?>

                            <tr>
                                <td colspan="5" style="text-align:center; color:#8892b0;">
                                    NO ORDERS FOUND
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>

                                <tr>
                                    <td class="pink-text">
                                        #SK-<?php echo $order["id"]; ?>
                                    </td>

                                    <td>
                                        <div class="user-cell">
                                            <strong>
                                                <?php echo htmlspecialchars($order["fullname"]); ?>
                                            </strong>

                                            <span>
                                                <?php echo htmlspecialchars($order["email"]); ?>
                                            </span>

                                            <small>
                                                <?php echo htmlspecialchars($order["governorate"]); ?>,
                                                <?php echo htmlspecialchars($order["address"]); ?>
                                            </small>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="items-list-cell">
                                            <?php
                                            $order_id = $order["id"];

                                            $items_sql = "SELECT * FROM order_items WHERE order_id = '$order_id'";
                                            $items_result = mysqli_query($conn, $items_sql);

                                            while ($item = mysqli_fetch_assoc($items_result)) {
                                                echo "<div>• " .
                                                    htmlspecialchars($item["product_name"]) .
                                                    " (Size: " . htmlspecialchars($item["size"]) . ") x" .
                                                    $item["quantity"] .
                                                    "</div>";
                                            }
                                            ?>
                                        </div>
                                    </td>

                                    <td class="blue-text">
                                        $<?php echo number_format($order["total"], 2); ?>
                                    </td>

                                    <td>
                                        <form action="update_order_status.php" method="POST" class="status-form">

                                            <input
                                                type="hidden"
                                                name="order_id"
                                                value="<?php echo $order['id']; ?>"
                                            >

                                            <select name="status" class="status-select">
                                                <option value="PENDING_REVIEW" <?php if ($order["status"] == "PENDING_REVIEW") echo "selected"; ?>>
                                                    PENDING_REVIEW
                                                </option>

                                                <option value="PREPARING_GEAR" <?php if ($order["status"] == "PREPARING_GEAR") echo "selected"; ?>>
                                                    PREPARING_GEAR
                                                </option>

                                                <option value="SHIPPED" <?php if ($order["status"] == "SHIPPED") echo "selected"; ?>>
                                                    SHIPPED
                                                </option>

                                                <option value="DELIVERED" <?php if ($order["status"] == "DELIVERED") echo "selected"; ?>>
                                                    DELIVERED
                                                </option>

                                                <option value="CANCELLED" <?php if ($order["status"] == "CANCELLED") echo "selected"; ?>>
                                                    CANCELLED
                                                </option>
                                            </select>

                                            <button type="submit" class="update-status-btn">
                                                UPDATE
                                            </button>

                                        </form>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="./java/cursor.js"></script>

<script>
function showSection(sectionId) {
    document.querySelectorAll('.mgmt-section').forEach(sec => {
        sec.classList.remove('active');
    });

    document.getElementById(sectionId).classList.add('active');

    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    const activeBtn = Array.from(document.querySelectorAll('.nav-btn')).find(btn =>
        btn.getAttribute('onclick') && btn.getAttribute('onclick').includes(sectionId)
    );

    if (activeBtn) {
        activeBtn.classList.add('active');
    }

    document.getElementById('section-title').innerText =
        sectionId.replace('-', '_').toUpperCase();
}

const params = new URLSearchParams(window.location.search);

if (params.has('status')) {
    alert("SYSTEM_MESSAGE: Data Successfully Processed & Saved!");
    window.history.replaceState({}, document.title, "admin-dashboard.php");
}
</script>

</body>
</html>