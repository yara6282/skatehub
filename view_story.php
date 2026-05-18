<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$story_id = intval($_GET["id"] ?? 0);

$story_result = mysqli_query($conn, "
SELECT s.*, u.fullname, u.profile_img
FROM stories s
JOIN users u ON s.user_id = u.id
WHERE s.id='$story_id'
AND s.expires_at > NOW()
");

$story = mysqli_fetch_assoc($story_result);

if (!$story) {
    header("Location: community.php");
    exit();
}

if ($story["user_id"] != $user_id) {
    mysqli_query($conn, "
        INSERT IGNORE INTO story_views (story_id, viewer_id)
        VALUES ('$story_id', '$user_id')
    ");
}

$owner_img = "image/user-avatar.jpg";
if (!empty($story["profile_img"])) {
    $owner_img = "image/" . $story["profile_img"];
}

$likes_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS c FROM story_likes WHERE story_id='$story_id'
"))["c"];

$views_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS c FROM story_views WHERE story_id='$story_id'
"))["c"];

$liked = mysqli_num_rows(mysqli_query($conn, "
    SELECT * FROM story_likes
    WHERE story_id='$story_id'
    AND user_id='$user_id'
")) > 0;

$likes_users = mysqli_query($conn, "
SELECT u.fullname
FROM story_likes sl
JOIN users u ON sl.user_id = u.id
WHERE sl.story_id='$story_id'
");

$views_users = mysqli_query($conn, "
SELECT u.fullname
FROM story_views sv
JOIN users u ON sv.viewer_id = u.id
WHERE sv.story_id='$story_id'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Story</title>
    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/community.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background:#020c1b;
            color:white;
            font-family:Poppins, sans-serif;
        }

        .story-view-page {
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px;
        }

        .story-view-card {
            width:420px;
            background:#0a192f;
            border:1px solid rgba(0,242,255,0.25);
            border-radius:30px;
            overflow:hidden;
            box-shadow:0 0 35px rgba(0,242,255,0.18);
            position:relative;
        }

        .story-top {
            display:flex;
            align-items:center;
            gap:12px;
            padding:16px;
        }

        .story-top img {
            width:45px;
            height:45px;
            border-radius:50%;
            object-fit:cover;
            border:2px solid #00f2ff;
        }

        .story-media {
            width:100%;
            height:580px;
            object-fit:cover;
            display:block;
        }

        .story-caption {
            padding:16px;
            font-size:15px;
        }

        .story-actions {
            padding:15px;
            display:flex;
            gap:12px;
            align-items:center;
            border-top:1px solid rgba(255,255,255,0.08);
        }

        .story-btn {
            background:rgba(255,255,255,0.06);
            color:white;
            padding:10px 15px;
            border-radius:14px;
            text-decoration:none;
            font-weight:900;
        }

        .story-btn.like i {
            color:#ff2d55;
        }

        .story-lists {
            padding:15px;
            font-size:13px;
            color:rgba(255,255,255,0.75);
        }

        .story-lists strong {
            color:#00f2ff;
        }

        .back-story {
            position:absolute;
            top:20px;
            left:20px;
            color:#00f2ff;
            text-decoration:none;
            font-weight:900;
        }
        .cursor,
.cursor-follower {
    z-index: 999999999 !important;
    pointer-events: none !important;
}
    </style>
</head>

<body>
<div class="cursor"></div>
<div class="cursor-follower"></div>

<a href="community.php" class="back-story">
    <i class="fas fa-arrow-left"></i> Back
</a>

<div class="story-view-page">
    <div class="story-view-card">

        <div class="story-top">
            <img src="<?php echo $owner_img; ?>">
            <div>
                <strong><?php echo htmlspecialchars($story["fullname"]); ?></strong>
                <?php
$time = strtotime($story["created_at"]);
$diff = time() - $time;

if ($diff < 60) {
    $story_time = $diff . "s ago";
} elseif ($diff < 3600) {
    $story_time = floor($diff / 60) . "m ago";
} elseif ($diff < 86400) {
    $story_time = floor($diff / 3600) . "h ago";
} else {
    $story_time = floor($diff / 86400) . "d ago";
}
?>

<p style="font-size:12px;color:#8892b0;">
    <?php echo $story_time; ?>
</p>
            </div>
        </div>

        <img src="<?php echo $story["media"]; ?>" class="story-media">

        <?php if (!empty($story["caption"])): ?>
            <div class="story-caption">
                <?php echo htmlspecialchars($story["caption"]); ?>
            </div>
        <?php endif; ?>

        <div class="story-actions">
            <a href="toggle_story_like.php?story_id=<?php echo $story_id; ?>" class="story-btn like">
                <i class="<?php echo $liked ? 'fas' : 'far'; ?> fa-heart"></i>
                <?php echo $likes_count; ?>
            </a>

            <span class="story-btn">
                <i class="fas fa-eye"></i>
                <?php echo $views_count; ?>
            </span>

            <?php if ($story["user_id"] == $user_id || ($_SESSION["role"] ?? "user") === "admin"): ?>
                <a href="./delete_story.php?id=<?php echo $story_id; ?>" class="story-btn" onclick="return confirm('Delete story?')">
                    <i class="fas fa-trash"></i>
                </a>
            <?php endif; ?>
        </div>

        <?php if ($story["user_id"] == $user_id): ?>
            <div class="story-lists">
                <p><strong>Viewed by:</strong>
                    <?php
                    $views = [];
                    while ($v = mysqli_fetch_assoc($views_users)) {
                        $views[] = $v["fullname"];
                    }
                    echo count($views) ? htmlspecialchars(implode(", ", $views)) : "No views yet.";
                    ?>
                </p>

                <p style="margin-top:8px;"><strong>Liked by:</strong>
                    <?php
                    $likes = [];
                    while ($l = mysqli_fetch_assoc($likes_users)) {
                        $likes[] = $l["fullname"];
                    }
                    echo count($likes) ? htmlspecialchars(implode(", ", $likes)) : "No likes yet.";
                    ?>
                </p>
            </div>
        <?php endif; ?>

    </div>
</div>
<script src="./java/community.js"></script>
</body>
</html>