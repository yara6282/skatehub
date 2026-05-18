<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = intval($_SESSION["user_id"]);
$post_id = intval($_GET["post_id"] ?? 0);
$back = $_GET["back"] ?? "community.php";

if ($post_id <= 0) {
    header("Location: $back");
    exit();
}

$check = mysqli_query($conn, "
    SELECT * FROM post_likes
    WHERE user_id='$user_id'
    AND post_id='$post_id'
");

if (mysqli_num_rows($check) > 0) {

    mysqli_query($conn, "
        DELETE FROM post_likes
        WHERE user_id='$user_id'
        AND post_id='$post_id'
    ");

} else {

    mysqli_query($conn, "
        INSERT INTO post_likes (user_id, post_id)
        VALUES ('$user_id', '$post_id')
    ");

    $post = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT user_id FROM community_posts WHERE id='$post_id'
    "));

    if ($post && $post["user_id"] != $user_id) {

        $me = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT fullname FROM users WHERE id='$user_id'
        "));

        $name = mysqli_real_escape_string($conn, $me["fullname"]);
        $notif = "$name liked your post.";

        mysqli_query($conn, "
            INSERT INTO notifications (user_id, message)
            VALUES ('{$post["user_id"]}', '$notif')
        ");
    }
}

header("Location: $back");
exit();
?>