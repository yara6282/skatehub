<?php
session_start();
include 'db_connect.php';

// التأكد من أن المستخدم سجل دخوله
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// جلب بيانات المستخدم الحقيقية
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// إذا لم يكن لديه صورة، نضع صورة افتراضية
$user_img = !empty($user['profile_img']) ? "image/" . $user['profile_img'] : "image/default-avatar.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (نفس الروابط السابقة) ... -->
</head>
<body>
    <!-- الناف بار كما هو -->

    <main class="profile-container">
    <section class="profile-header">
        <div class="banner">
            <img src="./image/pexels-introspectivedsgn-13583313.jpg" alt="Banner">
            <div class="banner-overlay"></div>
        </div>
        <div class="user-info">
            <div class="avatar-wrapper">
                <!-- صورة المستخدم -->
                <img src="<?php echo $user_img; ?>" alt="User">
                <div class="neon-ring"></div>
            </div>
            <div class="user-meta">
                <h1 class="glitch-text"><?php echo $user['fullname']; ?></h1>
                <p class="user-email"><?php echo $user['email']; ?></p>
            </div>
        </div>
    </section>

    <div class="profile-grid">
        <!-- كرت الطلبات الأخيرة (Gear Rack) -->
        <div class="profile-card gear-card">
            <h3><i class="fas fa-box-open"></i> RECENT_PURCHASES</h3>
            <div class="gear-list">
                <!-- هنا ستظهر صور المنتجات التي اشتراها فعلاً -->
                <div class="gear-item"><img src="./image/decks.png" alt="Deck"></div>
                <div class="gear-item"><img src="./image/shoes.png" alt="Shoes"></div>
                <p class="no-gear" style="display:none;">No gear ordered yet.</p>
            </div>
        </div>

        <!-- كرت روابط الوصول السريع -->
        <div class="profile-card settings-card">
            <h3><i class="fas fa-user-cog"></i> ACCOUNT_MANAGEMENT</h3>
            <div class="settings-links">
                <a href="edit-profile.html" class="set-btn"><i class="fas fa-edit"></i> EDIT_PROFILE_INFO</a>
                <a href="orders.html" class="set-btn"><i class="fas fa-history"></i> MY_ORDERS</a>
                <a href="logout.php" class="set-btn logout"><i class="fas fa-sign-out-alt"></i> TERMINATE_SESSION</a>
            </div>
        </div>
    </div>
</main>
</body>
</html>