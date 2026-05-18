<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

$user_result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_result);

$posts_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM community_posts WHERE user_id='$user_id'"))["c"];
$followers_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM follows WHERE following_id='$user_id'"))["c"];
$following_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM follows WHERE follower_id='$user_id'"))["c"];
$joined_crews = mysqli_query($conn, "
SELECT sc.*
FROM crew_members cm
JOIN skate_crews sc ON cm.crew_id = sc.id
WHERE cm.user_id='$user_id'
ORDER BY cm.joined_at DESC
");
$followers_result = mysqli_query($conn, "
SELECT u.fullname, u.email, u.profile_img
FROM follows f
JOIN users u ON f.follower_id = u.id
WHERE f.following_id='$user_id'
ORDER BY f.created_at DESC
");

$following_result = mysqli_query($conn, "
SELECT u.fullname, u.email, u.profile_img
FROM follows f
JOIN users u ON f.following_id = u.id
WHERE f.follower_id='$user_id'
ORDER BY f.created_at DESC
");
$posts_result = mysqli_query($conn, "
SELECT 
    cp.*,
    (SELECT COUNT(*) FROM post_likes WHERE post_id = cp.id) AS likes_count,
    (SELECT COUNT(*) FROM post_comments WHERE post_id = cp.id) AS comments_count
FROM community_posts cp
WHERE cp.user_id='$user_id'
ORDER BY cp.created_at DESC
");

$avatar = "image/user-avatar.jpg";
if (isset($user["profile_img"]) && !empty($user["profile_img"])) {
    $avatar = "image/" . $user["profile_img"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkateHub | My Community Profile</title>

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/community.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .profile-hero-community {
            max-width: 1100px;
            margin: 130px auto 40px;
            padding: 35px;
            border-radius: 32px;
            background: rgba(255,255,255,0.035);
            border: 1px solid rgba(255,255,255,0.08);
            position: relative;
            overflow: hidden;
        }

        .profile-hero-community::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(0,242,255,0.18), transparent 35%),
                radial-gradient(circle at bottom left, rgba(255,45,85,0.18), transparent 35%);
            pointer-events: none;
        }

        .profile-top {
            position: relative;
            display: flex;
            align-items: center;
            gap: 28px;
        }

        .profile-top img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 4px solid var(--cyan);
            box-shadow: 0 0 25px rgba(0,242,255,0.35);
            object-fit: cover;
        }

        .profile-top h1 {
            font-size: 2.4rem;
            margin-bottom: 8px;
        }

        .profile-top p {
            color: rgba(255,255,255,0.6);
        }

        .community-stats-row {
            position: relative;
            display: flex;
            gap: 18px;
            margin-top: 30px;
        }

        .community-stat-card {
            flex: 1;
            padding: 22px;
            border-radius: 22px;
            background: rgba(0,0,0,0.22);
            border: 1px solid rgba(255,255,255,0.08);
            text-align: center;
        }

        .community-stat-card strong {
            display: block;
            font-size: 2rem;
            color: var(--cyan);
        }

        .profile-posts-wrap {
            max-width: 1100px;
            margin: 0 auto 80px;
        }

        .profile-posts-title {
            font-size: 1.6rem;
            margin-bottom: 20px;
            color: var(--cyan);
        }

        .profile-post-card {
            background: rgba(255,255,255,0.035);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 25px;
            padding: 25px;
            margin-bottom: 22px;
            position: relative;
        }

        .profile-post-card img {
            width: 100%;
            max-height: 360px;
            object-fit: cover;
            border-radius: 22px;
            margin-top: 15px;
        }

        .profile-post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 16px;
            align-items: center;
            color: rgba(255,255,255,0.75);
        }

        .back-community {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            color: var(--cyan);
            text-decoration: none;
            font-weight: 900;
        }

        .profile-hidden-box {
            display: none;
            margin-top: 14px;
            padding: 15px;
            border-radius: 14px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            animation: fadeBox 0.25s ease;
        }

        .profile-hidden-box.show-profile-box {
            display: block;
        }

        @keyframes fadeBox {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-comment-item {
            background: rgba(255,255,255,0.045);
            padding: 12px 15px;
            border-radius: 14px;
            margin-top: 10px;
        }

        .profile-comment-item strong {
            color: var(--cyan);
            font-size: 13px;
        }

        .profile-comment-item p {
            margin-top: 5px;
            font-size: 13px;
        }

        .liked-by-box {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }

        .liked-by-box i {
            color: #ff2d55;
            margin-right: 6px;
        }

        .liked-by-box strong {
            color: white;
        }
        .follow-person-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px;
    margin-top: 12px;
    border-radius: 18px;
    background: rgba(255,255,255,0.045);
    border: 1px solid rgba(255,255,255,0.08);
}

.follow-person-card img {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--cyan);
}

.follow-person-card strong {
    color: white;
}

.follow-person-card p {
    color: rgba(255,255,255,0.55);
    font-size: 13px;
    margin-top: 4px;
}

.community-stat-card {
    cursor: pointer;
    transition: 0.3s;
}

.community-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 20px rgba(0,242,255,0.18);
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

    <div class="nav-icons">
        <a href="profile.php"><i class="fas fa-user"></i></a>
        <a href="cart.html"><i class="fas fa-shopping-cart"></i></a>
    </div>
</nav>

<section class="profile-hero-community">
    
    <div class="profile-top">
        <img src="<?php echo $avatar; ?>" alt="avatar">

        <div>
            <h1><?php echo htmlspecialchars($user["fullname"]); ?></h1>
            <p><?php echo htmlspecialchars($user["email"]); ?></p>
            <p>Concrete storyteller • SkateHub community rider</p>
        </div>
    </div>
    <div class="profile-hidden-box show-profile-box">
    <h3>MY SKATE CREWS</h3>

    <?php if (mysqli_num_rows($joined_crews) == 0): ?>
        <p>You have not joined any crews yet.</p>
    <?php else: ?>
        <?php while ($crew = mysqli_fetch_assoc($joined_crews)): ?>
            <div class="follow-person-card">
                <img src="<?php echo $crew['image']; ?>">
                <div>
                    <strong><?php echo htmlspecialchars($crew['name']); ?></strong>
                    <p><?php echo htmlspecialchars($crew['location']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

    <div class="community-stats-row">
        <div id="followers-box" class="profile-hidden-box">
    <h3>Followers</h3>

    <?php if (mysqli_num_rows($followers_result) == 0): ?>
        <p>No followers yet.</p>
    <?php else: ?>
        <?php while ($f = mysqli_fetch_assoc($followers_result)): ?>
            <?php
            $img = "image/user-avatar.jpg";
            if (!empty($f["profile_img"])) {
                $img = "image/" . $f["profile_img"];
            }
            ?>
            <div class="follow-person-card">
                <img src="<?php echo $img; ?>">
                <div>
                    <strong><?php echo htmlspecialchars($f["fullname"]); ?></strong>
                    <p><?php echo htmlspecialchars($f["email"]); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<div id="following-box" class="profile-hidden-box">
    <h3>Following</h3>

    <?php if (mysqli_num_rows($following_result) == 0): ?>
        <p>You are not following anyone yet.</p>
    <?php else: ?>
        <?php while ($f = mysqli_fetch_assoc($following_result)): ?>
            <?php
            $img = "image/user-avatar.jpg";
            if (!empty($f["profile_img"])) {
                $img = "image/" . $f["profile_img"];
            }
            ?>
            <div class="follow-person-card">
                <img src="<?php echo $img; ?>">
                <div>
                    <strong><?php echo htmlspecialchars($f["fullname"]); ?></strong>
                    <p><?php echo htmlspecialchars($f["email"]); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
        <div class="community-stat-card">
            <strong><?php echo $posts_count; ?></strong>
            <span>Posts</span>
        </div>
<div class="community-stat-card" onclick="toggleProfileBox('followers-box')">
    <strong><?php echo $followers_count; ?></strong>
    <span>Followers</span>
</div>

<div class="community-stat-card" onclick="toggleProfileBox('following-box')">
    <strong><?php echo $following_count; ?></strong>
    <span>Following</span>
</div>
    </div>
</section>

<section class="profile-posts-wrap">
    <a href="community.php" class="back-community">
        <i class="fas fa-arrow-left"></i> Back to Community
    </a>

    <h2 class="profile-posts-title">MY_POSTS</h2>

    <?php if (mysqli_num_rows($posts_result) == 0): ?>

        <div class="profile-post-card">
            <p>You have no posts yet. Drop your first trick 🔥</p>
        </div>

    <?php else: ?>

        <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>

            <?php
            $post_id = $post["id"];

            $likes_people = mysqli_query($conn, "
                SELECT u.fullname
                FROM post_likes pl
                JOIN users u ON pl.user_id = u.id
                WHERE pl.post_id = '$post_id'
                LIMIT 8
            ");

            $likes_names = [];
            while ($lp = mysqli_fetch_assoc($likes_people)) {
                $likes_names[] = $lp["fullname"];
            }

            $comments_result = mysqli_query($conn, "
                SELECT pc.*, u.fullname
                FROM post_comments pc
                JOIN users u ON pc.user_id = u.id
                WHERE pc.post_id = '$post_id'
                ORDER BY pc.created_at ASC
            ");
            ?>

            <div class="profile-post-card">
                <a
                    href="delete_post.php?id=<?php echo $post['id']; ?>"
                    class="delete-post-btn"
                    onclick="return confirm('Delete this post?');"
                >
                    <i class="fas fa-trash"></i>
                </a>

                <p><?php echo htmlspecialchars($post["content"]); ?></p>

                <?php if (!empty($post["image"])): ?>
                    <img src="<?php echo $post["image"]; ?>" alt="post">
                <?php endif; ?>

                <div class="profile-post-meta">
                    <button class="interact-btn" onclick="toggleProfileBox('likes-<?php echo $post_id; ?>')">
                        <i class="fas fa-heart"></i>
                        <?php echo $post["likes_count"]; ?> Likes
                    </button>

                    <button class="interact-btn" onclick="toggleProfileBox('comments-<?php echo $post_id; ?>')">
                        <i class="fas fa-comment"></i>
                        <?php echo $post["comments_count"]; ?> Comments
                    </button>

                    <span><i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($post["location"]); ?></span>
                    <span><?php echo $post["created_at"]; ?></span>
                </div>

                <div id="likes-<?php echo $post_id; ?>" class="profile-hidden-box">
                    <div class="liked-by-box">
                        <i class="fas fa-heart"></i>

                        <?php if (count($likes_names) > 0): ?>
                            Liked by:
                            <strong><?php echo htmlspecialchars(implode(", ", $likes_names)); ?></strong>
                        <?php else: ?>
                            No likes yet.
                        <?php endif; ?>
                    </div>
                </div>

                <div id="comments-<?php echo $post_id; ?>" class="profile-hidden-box">
                    <?php if (mysqli_num_rows($comments_result) > 0): ?>
                        <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                            <div class="profile-comment-item">
                                <strong><?php echo htmlspecialchars($comment["fullname"]); ?></strong>
                                <p><?php echo htmlspecialchars($comment["comment"]); ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color:rgba(255,255,255,0.65);">No comments yet.</p>
                    <?php endif; ?>
                </div>
            </div>

        <?php endwhile; ?>

    <?php endif; ?>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="./java/cursor.js"></script>

<script>
function toggleProfileBox(id) {
    const box = document.getElementById(id);

    if (box) {
        box.classList.toggle("show-profile-box");
    }
}
</script>

</body>
</html>