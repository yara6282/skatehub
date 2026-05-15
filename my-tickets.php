<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

$from = $_GET["from"] ?? "events";

$back_link = "events.php";
$back_text = "BACK TO EVENTS";

if ($from === "profile") {
    $back_link = "profile.php";
    $back_text = "BACK TO PROFILE";
}

$sql = "SELECT 
            et.id AS ticket_id,
            et.payment_method,
            et.purchase_date,
            et.quantity,
            e.title,
            e.category,
            e.event_date,
            e.location,
            e.price,
            e.image
        FROM event_tickets et
        JOIN events e ON et.event_id = e.id
        WHERE et.user_id = '$user_id'
        ORDER BY et.purchase_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SkateHub | My Tickets</title>

    <link rel="stylesheet" href="./style/global.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;900&display=swap"
        rel="stylesheet">
<link rel="stylesheet" href="./style/login.css">
    <style>
        :root {
            --navy: #020c1b;
            --card: #07182d;
            --blue: #00e5ff;
            --pink: #ff0055;
            --green: #00ff99;
            --white: #fff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: white;
            font-family: 'Poppins', sans-serif;
            background:
                radial-gradient(circle at top left,
                    rgba(0, 229, 255, 0.18),
                    transparent 25%),
                linear-gradient(180deg,
                    #031225 0%,
                    #000 100%);
        }

        .tickets-page {
            padding: 120px 7% 80px;
        }

        /* HERO */

        .tickets-hero {
            position: relative;
            min-height: 420px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 70px;
        }

        .tickets-hero::before {
            content: "SKATE";
            position: absolute;
            top: -20px;
            left: 0;
            font-size: 14rem;
            font-weight: 900;
            font-style: italic;
            color: rgba(255, 255, 255, 0.03);
            z-index: 0;
            letter-spacing: 5px;
        }

        .hero-circle {
            position: absolute;
            right: 8%;
            top: 40px;
            width: 260px;
            height: 260px;
            border: 3px solid var(--blue);
            border-radius: 50%;
            animation: spinCircle 10s linear infinite;
            opacity: 0.8;
            box-shadow: 0 0 35px rgba(0, 229, 255, 0.35);
        }

        .hero-circle::before {
            content: "";
            position: absolute;
            inset: 35px;
            border: 2px dashed var(--pink);
            border-radius: 50%;
        }

        .hero-glow {
            position: absolute;
            right: 13%;
            top: 100px;
            width: 120px;
            height: 120px;
            background: var(--blue);
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.3;
        }

        @keyframes spinCircle {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .tickets-title {
            position: relative;
            z-index: 2;
            font-size: 6rem;
            font-weight: 900;
            font-style: italic;
            line-height: 0.9;
            margin: 0;
            color: white;
            text-transform: uppercase;
            letter-spacing: -2px;
        }

        .tickets-title span {
            display: block;
            color: transparent;
            -webkit-text-stroke: 3px var(--blue);
            text-shadow:
                0 0 12px rgba(0, 229, 255, 0.5),
                0 0 25px rgba(0, 229, 255, 0.25);
        }

        .tickets-subtitle {
            position: relative;
            z-index: 2;
            margin-top: 20px;
            color: #8da2c0;
            font-size: 1.05rem;
            font-weight: 900;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .top-actions {
            position: relative;
            z-index: 2;
            margin-top: 35px;
        }

        .back-events-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: var(--blue);
            color: #00111f;
            text-decoration: none;
            padding: 18px 34px;
            font-weight: 900;
            font-style: italic;
            font-size: 1.1rem;
            text-transform: uppercase;
            transform: skewX(-12deg);
            box-shadow: 10px 10px 0 var(--pink);
            transition: 0.3s;
        }

        .back-events-btn:hover {
            transform: skewX(-12deg) translateY(-5px);
            box-shadow: 14px 14px 0 var(--pink);
        }

        /* TICKETS GRID */

        .tickets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 35px;
        }

        .ticket-card {
            position: relative;
            overflow: hidden;
            background: rgba(7, 24, 45, 0.95);
            border: 2px solid rgba(0, 229, 255, 0.2);
            box-shadow: 12px 12px 0 rgba(255, 0, 85, 0.8);
            transition: 0.35s;
        }

        .ticket-card:hover {
            transform: translateY(-8px);
            border-color: var(--blue);
            box-shadow: 14px 14px 0 rgba(0, 229, 255, 0.7);
        }

        .ticket-top-strip {
            height: 14px;
            background: linear-gradient(90deg,
                    var(--pink),
                    var(--blue));
        }

        .ticket-img-wrap {
            height: 230px;
            background: #000;
            overflow: hidden;
        }

        .ticket-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.4s;
        }

        .ticket-card:hover img {
            transform: scale(1.05);
        }

        .ticket-content {
            padding: 25px;
        }

        .ticket-category {
            display: inline-block;
            color: var(--green);
            font-weight: 900;
            font-size: 0.8rem;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .ticket-card h2 {
            margin: 0 0 20px;
            font-size: 3rem;
            line-height: 0.9;
            font-weight: 900;
            font-style: italic;
            text-transform: uppercase;
            color: white;
        }

        .ticket-info {
            display: grid;
            gap: 10px;
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding-bottom: 8px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.14);
        }

        .ticket-row span:first-child {
            color: #8da2c0;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .ticket-row span:last-child {
            color: white;
            font-weight: 700;
            text-align: right;
            font-size: 0.9rem;
        }

        .ticket-price {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 229, 255, 0.08);
            border: 1px solid rgba(0, 229, 255, 0.25);
            padding: 15px;
        }

        .ticket-price strong {
            font-size: 2.4rem;
            font-weight: 900;
            font-style: italic;
            color: var(--blue);
        }

        .qr-box {
            width: 55px;
            height: 55px;
            display: grid;
            place-items: center;
            border: 2px solid var(--pink);
            color: var(--pink);
            font-size: 1.5rem;
        }

        .empty-ticket {
            padding: 40px;
            background: rgba(7, 24, 45, 0.95);
            border: 2px solid var(--pink);
            font-size: 2rem;
            font-weight: 900;
            font-style: italic;
            color: var(--pink);
            box-shadow: 10px 10px 0 #000;
        }

        @media (max-width: 900px) {

            .hero-circle,
            .hero-glow {
                display: none;
            }

            .tickets-title {
                font-size: 4rem;
            }

            .tickets-hero::before {
                font-size: 7rem;
            }
        }
        html,
body {
    min-height: 100%;
    height: auto !important;
    overflow-y: auto !important;
    overflow-x: hidden;
}
    </style>
</head>

<body>
    <div class="cursor"></div>

<div class="cursor-follower"></div>
<div class="navbar login-nav">
    <div class="logo">
        <a href="home.html" class="logo-link">
            <img src="./image/9037278.png" alt="SkateHub Logo">
            <span class="site-title">SkateHub</span>
        </a>
    </div>
    
    <div class="nav-icons">
        <a href="login.html"><i class="fas fa-user"></i></a>
        <a href="signup.html"><i class="fas fa-user-plus"></i></a>
        <a href="notifications.html"><i class="fas fa-bell"></i></a>
    </div>
</div>
    <main class="tickets-page">

        <section class="tickets-hero">

            <div class="hero-circle"></div>
            <div class="hero-glow"></div>

            <h1 class="tickets-title">
                MY
                <span>SKATE TICKETS</span>
            </h1>

            <p class="tickets-subtitle">
                YOUR CONCRETE BATTLES ARE LOCKED IN
            </p>

            <div class="top-actions">

                <a href="<?php echo $back_link; ?>"
                    class="back-events-btn">

                    <i class="fas fa-arrow-left"></i>

                    <?php echo $back_text; ?>

                </a>

            </div>

        </section>

        <div class="tickets-grid">

            <?php if (mysqli_num_rows($result) == 0): ?>

                <div class="empty-ticket">
                    NO TICKETS FOUND 🛹
                </div>

            <?php else: ?>

                <?php while ($ticket = mysqli_fetch_assoc($result)): ?>

                    <div class="ticket-card">

                        <div class="ticket-top-strip"></div>

                        <div class="ticket-img-wrap">

                            <img src="<?php echo $ticket['image']; ?>"
                                alt="Event">

                        </div>

                        <div class="ticket-content">

                            <span class="ticket-category">
                                <?php echo htmlspecialchars($ticket['category']); ?>
                            </span>

                            <h2>
                                <?php echo htmlspecialchars($ticket['title']); ?>
                            </h2>

                            <div class="ticket-info">

                                <div class="ticket-row">
                                    <span>Ticket ID</span>
                                    <span>
                                        #TK-<?php echo $ticket['ticket_id']; ?>
                                    </span>
                                </div>

                                <div class="ticket-row">
                                    <span>Date</span>

                                    <span>
                                        <?php echo date("M d, Y", strtotime($ticket['event_date'])); ?>
                                    </span>
                                </div>

                                <div class="ticket-row">
                                    <span>Location</span>

                                    <span>
                                        <?php echo htmlspecialchars($ticket['location']); ?>
                                    </span>
                                </div>

                                <div class="ticket-row">
                                    <span>Payment</span>

                                    <span>
                                        <?php echo htmlspecialchars($ticket['payment_method']); ?>
                                    </span>
                                </div>

                                <div class="ticket-row">
                                    <span>Purchased</span>

                                    <span>
                                        <?php echo date("M d, Y", strtotime($ticket['purchase_date'])); ?>
                                    </span>
                                </div>

                            </div>

                            <div class="ticket-price">
<div class="ticket-row">
    <span>Quantity</span>
    <span><?php echo $ticket['quantity']; ?></span>
</div>
                                <strong>
    $<?php echo number_format($ticket['price'] * $ticket['quantity'], 2); ?>
</strong>

                                <div class="qr-box">
                                    <i class="fas fa-qrcode"></i>
                                </div>

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