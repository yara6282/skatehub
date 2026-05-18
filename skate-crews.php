<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$user_id = $_SESSION["user_id"] ?? null;

$check = mysqli_query($conn, "SELECT COUNT(*) AS c FROM skate_crews");
$count = mysqli_fetch_assoc($check)["c"];

if ($count == 0) {
    mysqli_query($conn, "
    INSERT INTO skate_crews (name, description, location, image) VALUES
    ('Nablus Street Riders', 'A crew for night rides, street tricks, and weekly concrete sessions.', 'Nablus', 'https://images.unsplash.com/photo-1547447134-cd3f5c716030?auto=format&fit=crop&w=1000'),
    ('Ramallah Roll Squad', 'Roller and inline skaters building flow, balance, and urban routes.', 'Ramallah', 'https://images.unsplash.com/photo-1536318431364-5cc762cfc8ec?auto=format&fit=crop&w=1000'),
    ('Jerusalem Concrete Crew', 'Skateboarders chasing ledges, stairs, and creative street lines.', 'Jerusalem', 'https://images.unsplash.com/photo-1536318431364-5cc762cfc8ec?auto=format&fit=crop&w=1000')
    ");
}

$crews_result = mysqli_query($conn, "
SELECT 
    sc.*,
    (SELECT COUNT(*) FROM crew_members WHERE crew_id=sc.id) AS members_count
FROM skate_crews sc
ORDER BY sc.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkateHub | Skate Crews</title>

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/community.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@300;600;900&display=swap" rel="stylesheet">

    <style>
        .crews-page {
            padding: 130px 6% 80px;
        }

        .crews-hero {
            min-height: 280px;
            border-radius: 35px;
            background:
                radial-gradient(circle at 20% 10%, rgba(255,45,85,0.35), transparent 35%),
                radial-gradient(circle at 80% 40%, rgba(0,242,255,0.25), transparent 35%),
                rgba(255,255,255,0.035);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            padding: 45px;
            margin-bottom: 45px;
            overflow: hidden;
            position: relative;
        }

        .crews-hero h1 {
            font-size: 4rem;
            line-height: 1;
        }

        .crews-hero span {
            color: var(--cyan);
        }

        .crews-hero p {
            margin-top: 18px;
            max-width: 620px;
            color: rgba(255,255,255,0.72);
            line-height: 1.7;
        }

        .crews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
            gap: 28px;
        }

        .crew-card {
            background: rgba(255,255,255,0.035);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 30px;
            overflow: hidden;
            transition: 0.35s;
            position: relative;
        }

        .crew-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 0 35px rgba(0,242,255,0.16);
        }

        .crew-card img {
            width: 100%;
            height: 230px;
            object-fit: cover;
        }

        .crew-info {
            padding: 24px;
        }

        .crew-info h3 {
            font-size: 1.45rem;
            margin-bottom: 10px;
        }

        .crew-info p {
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
        }

        .crew-meta {
            display: flex;
            justify-content: space-between;
            margin: 18px 0;
            color: var(--cyan);
            font-weight: 900;
        }

        .join-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--accent-red), #ff003c);
            color: white;
            font-weight: 900;
            transition: 0.3s;
        }

        .join-btn:hover {
            transform: scale(1.03);
            box-shadow: 0 0 25px rgba(255,45,85,0.45);
        }

        .back-community {
            display: inline-flex;
            gap: 10px;
            align-items: center;
            color: var(--cyan);
            text-decoration: none;
            font-weight: 900;
            margin-bottom: 25px;
        }
    </style>
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

<main class="crews-page">

    <a href="community.php" class="back-community">
        <i class="fas fa-arrow-left"></i> Back to Community
    </a>

    <section class="crews-hero">
        <div>
            <h1>SKATE <span>CREWS</span></h1>
            <p>
                Find your concrete family. Join local crews, build sessions,
                discover riders near you, and become part of the SkateHub underground movement.
            </p>
        </div>
    </section>

    <section class="crews-grid">

        <?php while ($crew = mysqli_fetch_assoc($crews_result)): ?>

            <?php
            $joined = false;

            if ($user_id) {
                $crew_id = $crew["id"];
                $join_check = mysqli_query($conn, "SELECT * FROM crew_members WHERE user_id='$user_id' AND crew_id='$crew_id'");
                $joined = mysqli_num_rows($join_check) > 0;
            }
            ?>

            <div class="crew-card">
                <img src="<?php echo $crew["image"]; ?>" alt="crew">

                <div class="crew-info">
                    <h3><?php echo htmlspecialchars($crew["name"]); ?></h3>

                    <p><?php echo htmlspecialchars($crew["description"]); ?></p>

                    <div class="crew-meta">
                        <span><i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($crew["location"]); ?></span>
                        <span><i class="fas fa-users"></i> <?php echo $crew["members_count"]; ?></span>
                    </div>

                    <form action="join_crew.php" method="POST">
                        <input type="hidden" name="crew_id" value="<?php echo $crew["id"]; ?>">

                        <button class="join-btn" type="submit">
                            <?php echo $joined ? "LEAVE_CREW" : "JOIN_CREW"; ?>
                        </button>
                    </form>
                </div>
            </div>

        <?php endwhile; ?>

    </section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="./java/cursor.js"></script>
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