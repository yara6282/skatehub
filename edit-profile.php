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
            <a href="profile.php"><i class="fas fa-user"></i></a>
            <a href="cart.html"><i class="fas fa-shopping-cart"></i></a>
            <a href="orders.php"><i class="fas fa-box"></i></a>
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

</body>
</html>