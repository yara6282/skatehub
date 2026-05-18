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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateHub | Edit Skater Info</title>

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/edit-profile.css">
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
        <a href="community.html" class="nav-item">Community</a>
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

<main class="edit-container">
    <div class="edit-box">
        <div class="header">
            <h1 class="glitch-text">EDIT_<span>PROFILE</span></h1>
            <p>Update your skater credentials</p>
        </div>

        <form action="update_profile.php" method="POST" enctype="multipart/form-data">

            <div class="avatar-edit">
                <div class="current-avatar">
                    <img src="<?php echo $user_img; ?>" id="profile-preview" alt="Avatar">

                    <div class="overlay-upload">
                        <label for="avatar-input">
                            <i class="fas fa-camera"></i>
                        </label>

                        <input type="file" id="avatar-input" name="profile_img" accept="image/*" hidden>
                    </div>
                </div>

                <p>Change Profile Photo</p>
            </div>

            <div class="form-grid">

                <div class="input-group">
                    <label>FULL_NAME</label>

                    <div class="input-field">
                        <i class="fas fa-user"></i>

                        <input type="text"
                               name="fullname"
                               value="<?php echo htmlspecialchars($user['fullname']); ?>"
                               required>
                    </div>
                </div>

                <div class="input-group readonly">
                    <label>EMAIL_ADDRESS (LOCKED)</label>

                    <div class="input-field">
                        <i class="fas fa-envelope"></i>

                        <input type="email"
                               value="<?php echo htmlspecialchars($user['email']); ?>"
                               readonly>
                    </div>
                </div>

                <div class="input-group">
                    <label>NEW_PASSWORD</label>

                    <div class="input-field">
                        <i class="fas fa-lock"></i>

                        <input type="password"
                               id="new-pass"
                               name="new_pass"
                               placeholder="Leave blank to keep current">
                    </div>
                </div>

                <div class="input-group" id="old-pass-group">
                    <label id="old-pass-label">CURRENT_PASSWORD (OPTIONAL)</label>

                    <div class="input-field">
                        <i class="fas fa-key"></i>

                        <input type="password"
                               id="old-pass"
                               name="old_pass"
                               placeholder="Only needed to change password">
                    </div>
                </div>

            </div>

            <div class="action-btns">
                <button type="submit" class="save-btn">UPDATE_SYSTEM</button>
                <a href="profile.php" class="cancel-btn">CANCEL</a>
            </div>

        </form>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="./java/cursor.js"></script>

<script>
document.getElementById('avatar-input').onchange = function () {
    const file = this.files[0];

    if (file) {
        document.getElementById('profile-preview').src = URL.createObjectURL(file);
    }
};

const newPassInput = document.getElementById('new-pass');
const oldPassInput = document.getElementById('old-pass');
const oldPassGroup = document.getElementById('old-pass-group');
const oldPassLabel = document.getElementById('old-pass-label');

newPassInput.addEventListener('input', function() {
    if (this.value.length > 0) {
        oldPassInput.required = true;
        oldPassGroup.classList.add('needs-verify');
        oldPassLabel.innerText = "CURRENT_PASSWORD (REQUIRED NOW!)";
    } else {
        oldPassInput.required = false;
        oldPassGroup.classList.remove('needs-verify');
        oldPassLabel.innerText = "CURRENT_PASSWORD (OPTIONAL)";
    }
});
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