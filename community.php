<?php
session_start();
require_once __DIR__ . "/includes/db.php";
mysqli_query($conn, "DELETE FROM stories WHERE expires_at <= NOW()");
$user_id = $_SESSION["user_id"] ?? null;
$role = $_SESSION["role"] ?? "user";

$me = null;
if ($user_id) {
    $me_result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
    $me = mysqli_fetch_assoc($me_result);
}

$posts_sql = "
SELECT 
    cp.*,
    u.fullname,
    u.email,
    u.profile_img,
    (SELECT COUNT(*) FROM post_likes WHERE post_id = cp.id) AS likes_count,
    (SELECT COUNT(*) FROM post_comments WHERE post_id = cp.id) AS comments_count
FROM community_posts cp
JOIN users u ON cp.user_id = u.id
ORDER BY cp.created_at DESC
";

$posts_result = mysqli_query($conn, $posts_sql);

$posts_count = 0;
$followers_count = 0;
$following_count = 0;

if ($user_id) {
    $posts_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM community_posts WHERE user_id='$user_id'"))["c"];
    $followers_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM follows WHERE following_id='$user_id'"))["c"];
    $following_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM follows WHERE follower_id='$user_id'"))["c"];
}

$my_img = "";
if (isset($me["profile_img"]) && !empty($me["profile_img"])) {
    $my_img = "image/" . $me["profile_img"];
}

$my_name = $me["fullname"] ?? "Guest Skater";
$my_email = $me["email"] ?? "@guest";

$stories_result = null;

if ($user_id) {
    $stories_result = mysqli_query($conn, "
        SELECT s.*, u.fullname, u.profile_img
        FROM stories s
        JOIN users u ON s.user_id = u.id
        WHERE s.expires_at > NOW()
        AND (
            s.user_id = '$user_id'
            OR s.user_id IN (
                SELECT following_id
                FROM follows
                WHERE follower_id = '$user_id'
            )
        )
        ORDER BY s.created_at DESC
    ");
}

$chat_users = null;

if ($user_id) {
    $chat_users = mysqli_query($conn, "
        SELECT DISTINCT u.id, u.fullname, u.email, u.profile_img
        FROM follows f
        JOIN users u ON (
            (u.id = f.following_id AND f.follower_id = '$user_id')
            OR
            (u.id = f.follower_id AND f.following_id = '$user_id')
        )
        WHERE u.id != '$user_id'
        LIMIT 20
    ");
}

function userAvatarBlock($img, $name, $className = "avatar-empty") {
    if (!empty($img)) {
        return "<img src='" . htmlspecialchars($img) . "' alt='user'>";
    }

    $letter = strtoupper(substr($name, 0, 1));

    return "
        <div class='$className'>
            " . htmlspecialchars($letter) . "
        </div>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkateHub | Ultimate Community</title>

    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@300;600;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/community.css">

    <style>
        .avatar-empty,
        .chat-avatar-empty,
        .chat-main-avatar-empty {
            border-radius: 50%;
            background: rgba(0,242,255,0.12);
            border: 2px solid var(--cyan);
            color: var(--cyan);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
        }

        .avatar-empty {
            width: 52px;
            height: 52px;
            min-width: 52px;
        }

        .chat-avatar-empty {
            width: 58px;
            height: 58px;
            min-width: 58px;
        }

        .chat-main-avatar-empty {
            width: 52px;
            height: 52px;
            min-width: 52px;
        }

        .real-chat-holder {
            width: 100%;
        }

        .real-chat-widget {
            width: 100%;
            margin-top: 0;
            padding: 22px;
        }

        .chat-layout {
            display: block;
        }

        .chat-sidebar {
            width: 100%;
            min-width: 100%;
            max-height: 260px;
            overflow-y: auto;
            overflow-x: hidden;
            margin-bottom: 18px;
            padding-right: 6px;
        }

        .chat-user-card {
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            padding: 14px;
            border-radius: 18px;
            background: rgba(255,255,255,0.04);
            margin-bottom: 12px;
            cursor: pointer;
            transition: .3s;
            overflow: hidden;
        }

        .chat-user-card:hover {
            transform: translateX(5px);
            background: rgba(0,242,255,.08);
        }

        .chat-user-card img {
            width: 58px !important;
            height: 58px !important;
            min-width: 58px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--cyan);
        }

        .chat-user-card strong {
            color: white;
            display: block;
        }

        .chat-user-card p {
            color: rgba(255,255,255,0.55);
            margin-top: 3px;
            font-size: 12px;
        }

        .chat-main {
            width: 100%;
        }

        .chat-main-top {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 72px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding-bottom: 14px;
        }

        .chat-main-top img {
            width: 52px !important;
            height: 52px !important;
            min-width: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--cyan);
        }

        .real-chat-messages {
            height: 280px;
            overflow-y: auto;
            padding: 18px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .chat-bubble {
            max-width: 78%;
            padding: 13px 15px;
            border-radius: 18px;
            line-height: 1.5;
        }

        .chat-bubble.sent {
            align-self: flex-end;
            background: var(--cyan);
            color: #00101c;
        }

        .chat-bubble.received {
            align-self: flex-start;
            background: rgba(255,255,255,.08);
            color: white;
        }

        .real-chat-input {
            display: flex;
            gap: 10px;
        }

        .real-chat-input input {
            flex: 1;
            background: rgba(255,255,255,.05);
            color: white;
            border: none;
            outline: none;
            border-radius: 16px;
            padding: 14px;
        }

        .real-chat-input button {
            width: 55px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg,#00f2ff,#00a8ff);
            color: #00101c;
            font-size: 17px;
        }

        .chat-top-bar h3 {
            color: var(--cyan);
            margin-bottom: 15px;
        }

        .chat-search-wrap {
            position: relative;
            margin-bottom: 15px;
        }

        #chatSearch {
            width: 100%;
            padding: 14px;
            border: none;
            outline: none;
            border-radius: 16px;
            background: rgba(255,255,255,0.05);
            color: white;
        }

        #searchResults {
            position: absolute;
            top: 110%;
            left: 0;
            width: 100%;
            background: #081423;
            border-radius: 18px;
            overflow: hidden;
            z-index: 999;
        }

        .search-user-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            cursor: pointer;
            transition: .3s;
        }

        .search-user-item:hover {
            background: rgba(255,255,255,.05);
        }

        .search-user-item img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--cyan);
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
<div class="ultimate-wrapper">
    <div class="main-grid">

        <aside class="sidebar-left">
            <div class="profile-card-v3">
                <div class="banner"></div>

                <div class="user-data">
                    <div class="pfp-holder">
                        <?php if (!empty($my_img)): ?>
                            <img src="<?php echo $my_img; ?>" alt="me">
                        <?php else: ?>
                            <div class="avatar-empty"><?php echo strtoupper(substr($my_name, 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>

                    <h3><?php echo htmlspecialchars($my_name); ?></h3>
                    <p><?php echo htmlspecialchars($my_email); ?></p>

                    <div class="user-stats">
                        <div><strong><?php echo $posts_count; ?></strong><span>Posts</span></div>
                        <div><strong><?php echo $followers_count; ?></strong><span>Followers</span></div>
                        <div><strong><?php echo $following_count; ?></strong><span>Following</span></div>
                    </div>

                    <a href="my-community-profile.php" class="btn-profile-neon">My Profile</a>
                </div>
            </div>

            <nav class="glass-nav-side">
                <h5>EXPLORE HUB</h5>

                <a href="community.php" class="side-link active">
                    <i class="fa-solid fa-house-chimney-window"></i>
                    Discover Feed
                </a>

                <a href="skate-crews.php" class="side-link">
                    <i class="fa-solid fa-users"></i>
                    Skate Crews
                </a>

                <a href="events.php" class="side-link">
                    <i class="fa-solid fa-fire"></i>
                    Challenges
                </a>
            </nav>
        </aside>

        <main class="feed-area">
            <div class="community-user-search">
    <input type="text" id="topUserSearch" placeholder="Search skaters...">
    <div id="topUserResults"></div>
</div>

            <div class="stories-scroll">

                <?php if ($user_id): ?>
                    <div class="story-item add" onclick="openStoryPopup()">
                        <div class="plus-icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <span>Add Story</span>
                    </div>
                <?php endif; ?>

                <?php if ($stories_result && mysqli_num_rows($stories_result) > 0): ?>
                    <?php while ($story = mysqli_fetch_assoc($stories_result)): ?>

                        <?php
                        $story_img = "";
                        if (!empty($story["profile_img"])) {
                            $story_img = "image/" . $story["profile_img"];
                        }
                        ?>

                        <a href="view_story.php?id=<?php echo $story['id']; ?>" class="story-item">
                            <?php if (!empty($story_img)): ?>
                                <img src="<?php echo $story_img; ?>">
                            <?php else: ?>
                                <div class="story-avatar-empty">
                                    <?php echo strtoupper(substr($story["fullname"], 0, 1)); ?>
                                </div>
                            <?php endif; ?>

                            <span><?php echo htmlspecialchars($story["fullname"]); ?></span>
                        </a>

                    <?php endwhile; ?>
                <?php endif; ?>

            </div>

            <div class="story-popup" id="storyPopup">
                <div class="story-popup-card">

                    <button class="close-story-popup" onclick="closeStoryPopup()">
                        <i class="fas fa-times"></i>
                    </button>

                    <h2>Create Story</h2>

                    <form action="add_story.php" method="POST" enctype="multipart/form-data">

                        <label class="story-upload-label">
                            <i class="fas fa-image"></i>
                            Choose Story Image

                            <input
                                type="file"
                                name="story_media"
                                id="storyFileInput"
                                accept="image/*"
                                required
                                hidden
                                onchange="previewStoryImage(event)"
                            >
                        </label>

                        <img
                            id="storyPreview"
                            style="width:100%; margin-top:18px; border-radius:20px; display:none; max-height:260px; object-fit:cover;"
                        >

                        <textarea
                            name="caption"
                            placeholder="Write a caption..."
                            maxlength="180"
                        ></textarea>

                        <button type="submit" class="publish-story-btn">
                            Publish Story 🚀
                        </button>

                    </form>
                </div>
            </div>

            <div class="glass-card create-post-v4">
                <form action="add_post.php" method="POST" enctype="multipart/form-data">

                    <div class="post-write-row">
                        <?php if (!empty($my_img)): ?>
                            <img src="<?php echo $my_img; ?>" class="post-avatar">
                        <?php else: ?>
                            <div class="post-avatar avatar-empty"><?php echo strtoupper(substr($my_name, 0, 1)); ?></div>
                        <?php endif; ?>

                        <textarea
                            name="content"
                            placeholder="Share your trick today, Legend..."
                            required
                        ></textarea>
                    </div>

                    <div class="post-divider"></div>

                    <div class="post-actions-v4">
                        <div class="media-pickers">
                            <label>
                                <i class="fa-solid fa-image"></i> Photo
                                <input type="file" name="post_img" accept="image/*" hidden>
                            </label>

                            <select name="location">
                                <option value="Nablus">Nablus</option>
                                <option value="Ramallah">Ramallah</option>
                                <option value="Tulkarm">Tulkarm</option>
                                <option value="Jerusalem">Jerusalem</option>
                                <option value="Bethlehem">Bethlehem</option>
                            </select>
                        </div>

                        <button type="submit" class="drop-btn">
                            DROP IT 🔥
                        </button>
                    </div>

                </form>
            </div>

            <?php if (mysqli_num_rows($posts_result) == 0): ?>

                <div class="glass-card">
                    <p>No community posts yet. Be the first skater to post 🔥</p>
                </div>

            <?php else: ?>

                <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>

                    <?php
                    $post_id = $post["id"];

                    $post_img = "";
                    if (isset($post["profile_img"]) && !empty($post["profile_img"])) {
                        $post_img = "image/" . $post["profile_img"];
                    }

                    $liked = false;
                    if ($user_id) {
                        $check_like = mysqli_query(
                            $conn,
                            "SELECT * FROM post_likes WHERE user_id='$user_id' AND post_id='$post_id'"
                        );
                        $liked = mysqli_num_rows($check_like) > 0;
                    }

                    $is_following = false;
                    if ($user_id && $post["user_id"] != $user_id) {
                        $follow_check = mysqli_query($conn, "
                            SELECT * FROM follows
                            WHERE follower_id='$user_id'
                            AND following_id='{$post["user_id"]}'
                        ");

                        $is_following = mysqli_num_rows($follow_check) > 0;
                    }

                    $likes_users = mysqli_query($conn, "
                        SELECT u.fullname
                        FROM post_likes pl
                        JOIN users u ON pl.user_id = u.id
                        WHERE pl.post_id = '$post_id'
                        LIMIT 8
                    ");

                    $liked_names = [];
                    while ($lu = mysqli_fetch_assoc($likes_users)) {
                        $liked_names[] = $lu["fullname"];
                    }
                    ?>

                    <article class="glass-card post-v3 tilt-target">

                        <div class="post-head">
                            <?php if (!empty($post_img)): ?>
                                <img src="<?php echo $post_img; ?>" class="mini-pfp">
                            <?php else: ?>
                                <div class="mini-pfp avatar-empty"><?php echo strtoupper(substr($post["fullname"], 0, 1)); ?></div>
                            <?php endif; ?>

                            <div class="info">
                                <h4>
                                    <?php echo htmlspecialchars($post["fullname"]); ?>
                                    <i class="fa-solid fa-circle-check verify"></i>
                                </h4>

                                <span>
                                    <?php echo $post["created_at"]; ?>
                                    •
                                    <?php echo htmlspecialchars($post["location"]); ?>
                                </span>
                            </div>

                            <?php if ($user_id && $post["user_id"] != $user_id): ?>

                                <a
                                    href="follow_user.php?user_id=<?php echo $post['user_id']; ?>&back=community.php"
                                    class="follow-btn-small"
                                >
                                    <?php echo $is_following ? "Following" : "Follow"; ?>
                                </a>

                            <?php endif; ?>

                            <?php if ($user_id && ($post["user_id"] == $user_id || $role === "admin")): ?>
                                <a
                                    href="delete_post.php?id=<?php echo $post['id']; ?>"
                                    class="delete-post-btn"
                                    onclick="return confirm('Delete this post?');"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>

                        <p class="post-text">
                            <?php echo htmlspecialchars($post["content"]); ?>
                        </p>

                        <?php if (!empty($post["image"])): ?>
                            <div class="post-media-v3">
                                <img src="<?php echo $post["image"]; ?>" alt="Skate Media">
                            </div>
                        <?php endif; ?>

                        <div class="post-foot">
                            <a
                                class="interact-btn like"
                                href="toggle_like_redirect.php?post_id=<?php echo $post['id']; ?>&back=community.php"
                            >
                                <i
                                    class="<?php echo $liked ? 'fa-solid' : 'fa-regular'; ?> fa-heart"
                                    style="color: <?php echo $liked ? '#ff2d55' : 'white'; ?>;"
                                ></i>

                                <span><?php echo $post["likes_count"]; ?></span>
                            </a>

                            <button class="interact-btn" onclick="toggleComments(<?php echo $post['id']; ?>)">
                                <i class="fa-regular fa-comment"></i>
                                <span><?php echo $post["comments_count"]; ?></span>
                            </button>

                            <button class="interact-btn" onclick="fakeShare(this)">
                                <i class="fa-solid fa-share"></i>
                            </button>
                        </div>

                        <?php if (count($liked_names) > 0): ?>
                            <div class="liked-by-box">
                                <i class="fa-solid fa-heart"></i>
                                Liked by
                                <strong><?php echo htmlspecialchars(implode(", ", $liked_names)); ?></strong>
                            </div>
                        <?php endif; ?>

                        <div class="comments-box" id="comments-<?php echo $post['id']; ?>">

                            <?php
                            $comments_sql = "
                            SELECT pc.*, u.fullname
                            FROM post_comments pc
                            JOIN users u ON pc.user_id = u.id
                            WHERE pc.post_id = '$post_id'
                            ORDER BY pc.created_at ASC
                            ";

                            $comments_result = mysqli_query($conn, $comments_sql);
                            ?>

                            <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>

                                <div class="comment-item">
                                    <strong><?php echo htmlspecialchars($comment["fullname"]); ?></strong>
                                    <p><?php echo htmlspecialchars($comment["comment"]); ?></p>

                                    <?php if ($user_id && ($comment["user_id"] == $user_id || $role === "admin")): ?>
                                        <a
                                            href="delete_comment.php?id=<?php echo $comment['id']; ?>"
                                            class="delete-comment-btn"
                                            onclick="return confirm('Delete this comment?');"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>

                            <?php endwhile; ?>

                            <?php if ($user_id): ?>

                                <form action="add_comment.php" method="POST" class="comment-form">
                                    <input type="hidden" name="post_id" value="<?php echo $post["id"]; ?>">

                                    <input
                                        type="text"
                                        name="comment"
                                        placeholder="Write a comment..."
                                        required
                                    >

                                    <button type="submit">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </button>
                                </form>

                            <?php else: ?>

                                <p class="login-comment-msg">
                                    Login to comment.
                                </p>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endwhile; ?>

            <?php endif; ?>

        </main>

        <aside class="sidebar-right">

            <div class="glass-card widget-v3">
                <h3 class="w-title">Leaderboard 🏆</h3>

                <div class="rank-list">
                    <div class="rank-user"><span>01</span> <strong>Yousef Tony</strong> <small>4.5k XP</small></div>
                    <div class="rank-user"><span>02</span> <strong>Sara Roller</strong> <small>3.2k XP</small></div>
                </div>
            </div>

            <div class="real-chat-holder">

                <div class="glass-card real-chat-widget">

                    <div class="chat-top-bar">
                        <h3>Private Chat</h3>
                    </div>

                    <div class="chat-search-wrap">
                        <input
                            type="text"
                            id="chatSearch"
                            placeholder="Search people..."
                            autocomplete="off"
                        >

                        <div id="searchResults"></div>
                    </div>

                    <div class="chat-layout">

                        <div class="chat-sidebar" id="chatSidebar">

                            <?php if ($chat_users && mysqli_num_rows($chat_users) > 0): ?>

                                <?php while($cu = mysqli_fetch_assoc($chat_users)): ?>

                                    <?php
                                    $cimg = "";
                                    if(!empty($cu["profile_img"])){
                                        $cimg = "image/" . $cu["profile_img"];
                                    }
                                    ?>

                                    <div
                                        class="chat-user-card"
                                        onclick="openPrivateChat(
                                            <?php echo $cu['id']; ?>,
                                            '<?php echo htmlspecialchars($cu['fullname'], ENT_QUOTES); ?>',
                                            '<?php echo htmlspecialchars($cimg, ENT_QUOTES); ?>'
                                        )"
                                    >
                                        <?php if (!empty($cimg)): ?>
                                            <img src="<?php echo $cimg; ?>">
                                        <?php else: ?>
                                            <div class="chat-avatar-empty">
                                                <?php echo strtoupper(substr($cu["fullname"], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div>
                                            <strong><?php echo htmlspecialchars($cu["fullname"]); ?></strong>
                                            <p>Following</p>
                                        </div>
                                    </div>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <p style="color:rgba(255,255,255,.6);">
                                    Follow someone to start chatting.
                                </p>

                            <?php endif; ?>

                        </div>

                        <div class="chat-main">

                            <div class="chat-main-top">
                                <div id="chatUserAvatar">
                                    <div class="chat-main-avatar-empty">?</div>
                                </div>

                                <div>
                                    <strong id="chatUserName">Select a chat</strong>
                                    <p>Private conversation</p>
                                </div>
                            </div>

                            <div class="real-chat-messages" id="realChatMessages">
                                <div class="empty-chat">
                                    Start chatting with your friends 🔥
                                </div>
                            </div>

                            <form id="sendMessageForm" class="real-chat-input">

                                <input type="hidden" id="receiverId">

                                <input
                                    type="text"
                                    id="chatMessageInput"
                                    placeholder="Type a message..."
                                    autocomplete="off"
                                >

                                <button type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </aside>

    </div>
</div>

<script src="./java/community.js"></script>

<script>
function toggleComments(postId) {
    const box = document.getElementById("comments-" + postId);

    if (box) {
        box.classList.toggle("show-comments");
    }
}

function fakeShare(btn) {
    btn.style.transform = "scale(1.2)";
    btn.style.color = "#00f2ff";

    setTimeout(() => {
        btn.style.transform = "scale(1)";
        btn.style.color = "white";
    }, 250);

    alert("Post link copied conceptually 🔗");
}

function openStoryPopup(){
    document.getElementById("storyPopup").classList.add("show-story-popup");
    document.body.style.cursor = "none";
}

function closeStoryPopup(){
    document.getElementById("storyPopup").classList.remove("show-story-popup");
}

function previewStoryImage(event){
    const file = event.target.files[0];

    if(!file) return;

    const reader = new FileReader();

    reader.onload = function(e){
        const preview = document.getElementById("storyPreview");

        preview.src = e.target.result;
        preview.style.display = "block";
    }

    reader.readAsDataURL(file);
}

let activeChatUser = null;

function setChatAvatar(name, img){
    const avatarBox = document.getElementById("chatUserAvatar");

    if(img && img.trim() !== ""){
        avatarBox.innerHTML = `<img src="${img}" alt="chat user">`;
    } else {
        const letter = name ? name.charAt(0).toUpperCase() : "?";
        avatarBox.innerHTML = `<div class="chat-main-avatar-empty">${letter}</div>`;
    }
}

async function openPrivateChat(id, name, img){
    activeChatUser = id;

    document.getElementById("receiverId").value = id;
    document.getElementById("chatUserName").textContent = name;

    setChatAvatar(name, img);

    loadMessages();
}

async function loadMessages(){
    if(!activeChatUser) return;

    const res = await fetch(`fetch_chat.php?with_id=${activeChatUser}`);
    const data = await res.json();

    const box = document.getElementById("realChatMessages");

    box.innerHTML = "";

    data.forEach(msg => {
        const div = document.createElement("div");

        div.className =
            "chat-bubble " +
            (msg.sender_id == <?php echo intval($user_id ?? 0); ?>
                ? "sent"
                : "received");

        div.innerText = msg.message;

        box.appendChild(div);
    });

    box.scrollTop = box.scrollHeight;
}

document.getElementById("sendMessageForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const receiver = document.getElementById("receiverId").value;
    const input = document.getElementById("chatMessageInput");

    if(!receiver){
        alert("Select a chat first.");
        return;
    }

    if(input.value.trim() == "") return;

    const formData = new FormData();

    formData.append("receiver_id", receiver);
    formData.append("message", input.value);

    await fetch("send_message.php", {
        method: "POST",
        body: formData
    });

    input.value = "";

    loadMessages();
});

setInterval(loadMessages, 2000);

document.getElementById("chatSearch").addEventListener("input", async function(){
    const q = this.value;

    if(q.trim() == ""){
        document.getElementById("searchResults").innerHTML = "";
        return;
    }

    const res = await fetch(`search_users.php?q=${encodeURIComponent(q)}`);
    const users = await res.json();

    let html = "";

    users.forEach(user => {
        const safeName = user.fullname.replace(/'/g, "\\'");
        const safeImg = user.profile_img ? user.profile_img.replace(/'/g, "\\'") : "";

        html += `
            <div
                class="search-user-item"
                onclick="
                    openPrivateChat(
                        ${user.id},
                        '${safeName}',
                        '${safeImg}'
                    );
                    document.getElementById('searchResults').innerHTML='';
                    document.getElementById('chatSearch').value='';
                "
            >
                ${
                    user.profile_img
                    ? `<img src="${user.profile_img}">`
                    : `<div class="chat-avatar-empty">${user.fullname.charAt(0).toUpperCase()}</div>`
                }

                <div>
                    <strong>${user.fullname}</strong>
                    <p>${user.email}</p>
                </div>
            </div>
        `;
    });

    document.getElementById("searchResults").innerHTML = html;
});
</script>
<script>
document.getElementById("topUserSearch").addEventListener("input", async function(){
    const q = this.value.trim();
    const box = document.getElementById("topUserResults");

    if(q === ""){
        box.innerHTML = "";
        return;
    }

    const res = await fetch(`search_users.php?q=${encodeURIComponent(q)}`);
    const users = await res.json();

    box.innerHTML = users.map(user => `
        <div class="top-user-result">
    <a href="user-community-profile.php?id=${user.id}" style="display:flex;align-items:center;gap:14px;text-decoration:none;color:white;">
        ${
            user.profile_img
            ? `<img src="${user.profile_img}">`
            : `<div class="story-avatar-empty">${user.fullname.charAt(0).toUpperCase()}</div>`
        }

        <div>
            <strong>${user.fullname}</strong>
            <p>${user.email}</p>
        </div>
    </a>

    <a href="follow_user.php?user_id=${user.id}&back=community.php">
        Follow
    </a>
</div>
    `).join("");
});
</script>
<<<<<<< HEAD
<?php include "ai-widget.php"; ?>
=======
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
>>>>>>> f0ef18a98c622d1173468a683d7b9672cced67cb
</body>
</html>