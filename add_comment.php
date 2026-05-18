<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = intval($_SESSION["user_id"]);
$post_id = intval($_POST["post_id"]);
$comment = mysqli_real_escape_string($conn, $_POST["comment"]);

if (!empty($comment)) {

    mysqli_query($conn, "
        INSERT INTO post_comments (post_id, user_id, comment)
        VALUES ('$post_id', '$user_id', '$comment')
    ");

    $post = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT user_id FROM community_posts WHERE id='$post_id'
    "));

    if ($post && $post["user_id"] != $user_id) {

        $me = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT fullname FROM users WHERE id='$user_id'
        "));

        $name = mysqli_real_escape_string($conn, $me["fullname"]);
        $notif = "$name commented on your post.";

        mysqli_query($conn, "
            INSERT INTO notifications (user_id, message)
            VALUES ('{$post["user_id"]}', '$notif')
        ");
    }
}

header("Location: community.php");
exit();
?>