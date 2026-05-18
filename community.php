<?php
session_start();
require_once __DIR__ . "/includes/db.php";

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
    $pc = mysqli_query($conn, "SELECT COUNT(*) AS c FROM community_posts WHERE user_id='$user_id'");
    $posts_count = mysqli_fetch_assoc($pc)["c"];

    $fc = mysqli_query($conn, "SELECT COUNT(*) AS c FROM follows WHERE following_id='$user_id'");
    $followers_count = mysqli_fetch_assoc($fc)["c"];

    $fg = mysqli_query($conn, "SELECT COUNT(*) AS c FROM follows WHERE follower_id='$user_id'");
    $following_count = mysqli_fetch_assoc($fg)["c"];
}

$my_img = "https://i.pravatar.cc/150?u=me";

$my_name = $me["fullname"] ?? "Guest Skater";
$my_email = $me["email"] ?? "@guest";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkateHub | Ultimate Community</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="./style/global.css">
    <link rel="stylesheet" href="./style/community.css">
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
        <a href="./home.php" class="nav-item">Home</a>
        <a href="./events.php" class="nav-item">Events</a>
        <a href="./community.php" class="nav-item active-link">Community</a>
        <a href="./shop.php" class="nav-item">Shop</a>
        <a href="./tutorials.php" class="nav-item">Tutorials</a>
    </div>

    <div class="nav-icons">
        <?php if ($user_id): ?>
            <a href="profile.php"><i class="fas fa-user"></i></a>
        <?php else: ?>
            <a href="login.html"><i class="fas fa-user"></i></a>
        <?php endif; ?>

        <a href="cart.html"><i class="fas fa-shopping-cart"></i></a>

        <div class="notif-wrapper">
            <button class="notif-btn" id="notifBtn">
                <i class="fas fa-bell"></i>
                <span class="notif-dot"></span>
            </button>

            <div class="notif-panel" id="notifPanel">
                <div class="notif-header">NOTIFICATIONS</div>
                <div class="notif-list" id="notifList">
                    <div class="notif-loading">Loading...</div>
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
                        <img src="<?php echo $my_img; ?>" alt="me">
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

            <div class="stories-scroll">
                <div class="story-item add">
                    <div class="plus-icon"><i class="fa-solid fa-plus"></i></div>
                    <span>Add Story</span>
                </div>
                <div class="story-item"><img src="https://i.pravatar.cc/150?u=1"><span>Tony</span></div>
                <div class="story-item"><img src="https://i.pravatar.cc/150?u=2"><span>Sarah</span></div>
                <div class="story-item"><img src="https://i.pravatar.cc/150?u=3"><span>Alex</span></div>
                <div class="story-item"><img src="https://i.pravatar.cc/150?u=4"><span>Mike</span></div>
            </div>

            <div class="glass-card create-post-v4">
                <form action="add_post.php" method="POST" enctype="multipart/form-data">

                    <div class="post-write-row">
                        <img src="<?php echo $my_img; ?>" class="post-avatar">

                        <textarea name="content" placeholder="Share your trick today, Legend..." required></textarea>
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
                    $post_img = "https://i.pravatar.cc/150?u=" . $post["user_id"];
                    $post_id = $post["id"];
                    ?>

                    <article class="glass-card post-v3 tilt-target">

                        <div class="post-head">
                            <img src="<?php echo $post_img; ?>" class="mini-pfp">

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
                            <button class="interact-btn like" onclick="toggleLike(<?php echo $post['id']; ?>, this)">
                                <i class="fa-regular fa-heart"></i>
                                <span><?php echo $post["likes_count"]; ?></span>
                            </button>

                            <button class="interact-btn" onclick="toggleComments(<?php echo $post['id']; ?>)">
                                <i class="fa-regular fa-comment"></i>
                                <span><?php echo $post["comments_count"]; ?></span>
                            </button>

                            <button class="interact-btn" onclick="fakeShare(this)">
                                <i class="fa-solid fa-share"></i>
                            </button>
                        </div>

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

            <div class="glass-card chat-widget-v4">
                <div class="chat-header-v4">
                    <h3>Messages</h3>
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>

                <div class="chat-body-v4">
                    <div class="chat-users">
                        <div class="chat-user active" onclick="selectChat(this, 'Tony')">
                            <img src="https://i.pravatar.cc/150?u=tony">
                            <span></span>
                        </div>

                        <div class="chat-user" onclick="selectChat(this, 'Alex')">
                            <img src="https://i.pravatar.cc/150?u=alex">
                        </div>

                        <div class="chat-user" onclick="selectChat(this, 'Sarah')">
                            <img src="https://i.pravatar.cc/150?u=sarah">
                        </div>
                    </div>

                    <div class="chat-panel">
                        <div class="chat-with">Chatting with <strong id="chatName">Tony</strong></div>

                        <div class="chat-messages" id="chatContainer">
                            <div class="msg received">Yo! That flip was sick!</div>
                            <div class="msg sent">Thanks bro! Appreciate it.</div>
                        </div>

                        <div class="chat-input-v4">
                            <input type="text" placeholder="Type message...">
                            <button><i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

    </div>
</div>

<script src="./java/community.js"></script>

<script>
function toggleLike(postId, btn) {
    fetch("toggle_like.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({post_id: postId})
    })
    .then(res => res.json())
    .then(data => {
        if (data.login_required) {
            window.location.href = "login.html";
            return;
        }

        const icon = btn.querySelector("i");
        const count = btn.querySelector("span");

        count.innerText = data.likes;

        if (data.status === "liked") {
            icon.classList.remove("fa-regular");
            icon.classList.add("fa-solid");
            icon.style.color = "#ff2d55";
        } else {
            icon.classList.remove("fa-solid");
            icon.classList.add("fa-regular");
            icon.style.color = "white";
        }

        btn.style.transform = "scale(1.2)";
        setTimeout(() => btn.style.transform = "scale(1)", 180);
    });
}

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
</script>

</body>
</html>