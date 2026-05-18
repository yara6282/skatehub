<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$viewer_id = intval($_SESSION["user_id"]);
$profile_id = intval($_GET["id"] ?? 0);

if ($profile_id <= 0) {
    header("Location: community.php");
    exit();
}

$user_result = mysqli_query($conn, "SELECT * FROM users WHERE id='$profile_id'");
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    header("Location: community.php");
    exit();
}

$posts_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM community_posts WHERE user_id='$profile_id'"))["c"];
$followers_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM follows WHERE following_id='$profile_id'"))["c"];
$following_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM follows WHERE follower_id='$profile_id'"))["c"];

$is_following = false;

if ($viewer_id != $profile_id) {
    $follow_check = mysqli_query($conn, "
        SELECT * FROM follows
        WHERE follower_id='$viewer_id'
        AND following_id='$profile_id'
    ");

    $is_following = mysqli_num_rows($follow_check) > 0;
}

$posts_result = mysqli_query($conn, "
SELECT 
    cp.*,
    (SELECT COUNT(*) FROM post_likes WHERE post_id = cp.id) AS likes_count,
    (SELECT COUNT(*) FROM post_comments WHERE post_id = cp.id) AS comments_count
FROM community_posts cp
WHERE cp.user_id='$profile_id'
ORDER BY cp.created_at DESC
");

$joined_crews = mysqli_query($conn, "
SELECT sc.*
FROM crew_members cm
JOIN skate_crews sc ON cm.crew_id = sc.id
WHERE cm.user_id='$profile_id'
ORDER BY cm.joined_at DESC
");

$avatar = "";
if (!empty($user["profile_img"])) {
    $avatar = "image/" . $user["profile_img"];
}

$first_letter = strtoupper(substr($user["fullname"], 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkateHub | User Profile</title>

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/community.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .public-profile-page {
            max-width: 1100px;
            margin: 130px auto 80px;
        }

        .public-profile-hero {
            padding: 35px;
            border-radius: 32px;
            background: rgba(255,255,255,0.035);
            border: 1px solid rgba(255,255,255,0.08);
            position: relative;
            overflow: hidden;
        }

        .public-profile-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(0,242,255,0.18), transparent 35%),
                radial-gradient(circle at bottom left, rgba(255,45,85,0.18), transparent 35%);
            pointer-events: none;
        }

        .public-profile-top {
            position: relative;
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .public-avatar,
        .public-avatar-empty {
            width: 135px;
            height: 135px;
            border-radius: 50%;
            border: 4px solid var(--cyan);
            box-shadow: 0 0 25px rgba(0,242,255,0.35);
            object-fit: cover;
        }

        .public-avatar-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--cyan);
            font-size: 3rem;
            font-weight: 900;
            background: rgba(0,242,255,0.1);
        }

        .public-profile-top h1 {
            font-size: 2.4rem;
            margin-bottom: 8px;
        }

        .public-profile-top p {
            color: rgba(255,255,255,0.6);
        }

        .public-actions {
            display: flex;
            gap: 12px;
            margin-top: 18px;
        }

        .public-btn {
            padding: 12px 22px;
            border-radius: 18px;
            text-decoration: none;
            font-weight: 900;
            background: var(--cyan);
            color: #00101c;
        }

        .public-btn.dark {
            background: rgba(255,255,255,0.08);
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .public-stats {
            position: relative;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 30px;
        }

        .public-stat-card {
            padding: 22px;
            border-radius: 22px;
            background: rgba(0,0,0,0.22);
            border: 1px solid rgba(255,255,255,0.08);
            text-align: center;
        }

        .public-stat-card strong {
            display: block;
            font-size: 2rem;
            color: var(--cyan);
        }

        .public-section {
            margin-top: 35px;
        }

        .public-title {
            color: var(--cyan);
            margin-bottom: 18px;
            font-size: 1.5rem;
        }

        .public-card {
            background: rgba(255,255,255,0.035);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 18px;
        }

        .public-card img {
            width: 100%;
            max-height: 360px;
            object-fit: cover;
            border-radius: 20px;
            margin-top: 14px;
        }

        .crew-mini-card {
            display: flex;
            gap: 14px;
            align-items: center;
            background: rgba(255,255,255,0.04);
            padding: 14px;
            border-radius: 18px;
            margin-bottom: 12px;
        }

        .crew-mini-card img {
            width: 62px;
            height: 62px;
            border-radius: 16px;
            object-fit: cover;
            margin: 0;
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
            <img src="./image/9037278.png" alt="SkateHub Logo">
        </div>
        <span class="site-title">SkateHub</span>
    </a>

    <div class="nav-links">
        <a href="home.php" class="nav-item">Home</a>
        <a href="events.php" class="nav-item">Events</a>
        <a href="community.php" class="nav-item active-link">Community</a>
        <a href="shop.php" class="nav-item">Shop</a>
        <a href="tutorials.php" class="nav-item">Tutorials</a>
    </div>
</nav>

<main class="public-profile-page">

    <a href="community.php" class="back-community">
        <i class="fas fa-arrow-left"></i> Back to Community
    </a>

    <section class="public-profile-hero">
        <div class="public-profile-top">

            <?php if (!empty($avatar)): ?>
                <img src="<?php echo $avatar; ?>" class="public-avatar">
            <?php else: ?>
                <div class="public-avatar-empty"><?php echo $first_letter; ?></div>
            <?php endif; ?>

            <div>
                <h1><?php echo htmlspecialchars($user["fullname"]); ?></h1>
                <p><?php echo htmlspecialchars($user["email"]); ?></p>
                <p>SkateHub community rider</p>

                <?php if ($viewer_id != $profile_id): ?>
                    <div class="public-actions">
                        <a
                            class="public-btn"
                            href="follow_user.php?user_id=<?php echo $profile_id; ?>&back=user-community-profile.php?id=<?php echo $profile_id; ?>"
                        >
                            <?php echo $is_following ? "Following" : "Follow"; ?>
                        </a>

                        <a
                            class="public-btn dark"
                            href="community.php"
                        >
                            Message in Chat
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="public-stats">
            <div class="public-stat-card">
                <strong><?php echo $posts_count; ?></strong>
                <span>Posts</span>
            </div>

            <div class="public-stat-card">
                <strong><?php echo $followers_count; ?></strong>
                <span>Followers</span>
            </div>

            <div class="public-stat-card">
                <strong><?php echo $following_count; ?></strong>
                <span>Following</span>
            </div>
        </div>
    </section>

    <section class="public-section">
        <h2 class="public-title">SKATE_CREWS</h2>

        <?php if (mysqli_num_rows($joined_crews) == 0): ?>
            <div class="public-card">
                <p>This user has not joined any crews yet.</p>
            </div>
        <?php else: ?>
            <?php while ($crew = mysqli_fetch_assoc($joined_crews)): ?>
                <div class="crew-mini-card">
                    <img src="<?php echo $crew["image"]; ?>">
                    <div>
                        <strong><?php echo htmlspecialchars($crew["name"]); ?></strong>
                        <p><?php echo htmlspecialchars($crew["location"]); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </section>

    <section class="public-section">
        <h2 class="public-title">POSTS</h2>

        <?php if (mysqli_num_rows($posts_result) == 0): ?>
            <div class="public-card">
                <p>No posts yet.</p>
            </div>
        <?php else: ?>
            <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
                <div class="public-card">
                    <p><?php echo htmlspecialchars($post["content"]); ?></p>

                    <?php if (!empty($post["image"])): ?>
                        <img src="<?php echo $post["image"]; ?>">
                    <?php endif; ?>

                    <p style="margin-top:14px;color:rgba(255,255,255,0.65);">
                        <i class="fas fa-heart"></i> <?php echo $post["likes_count"]; ?>
                        &nbsp;&nbsp;
                        <i class="fas fa-comment"></i> <?php echo $post["comments_count"]; ?>
                        &nbsp;&nbsp;
                        <i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($post["location"]); ?>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </section>

</main>

<script src="./java/community.js"></script>

</body>
</html>