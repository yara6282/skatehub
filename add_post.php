<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = intval($_SESSION["user_id"]);
$content = mysqli_real_escape_string($conn, $_POST["content"]);
$location = mysqli_real_escape_string($conn, $_POST["location"]);

$image_path = null;

if (!empty($_FILES["post_img"]["name"])) {
    $img_name = time() . "_" . basename($_FILES["post_img"]["name"]);
    $target = "image/" . $img_name;

    if (move_uploaded_file($_FILES["post_img"]["tmp_name"], $target)) {
        $image_path = $target;
    }
}

mysqli_query($conn, "
    INSERT INTO community_posts (user_id, content, image, location)
    VALUES ('$user_id', '$content', '$image_path', '$location')
");

$me = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT fullname FROM users WHERE id='$user_id'
"));

$name = mysqli_real_escape_string($conn, $me["fullname"]);
$notif = "$name shared a new post.";

$followers = mysqli_query($conn, "
    SELECT follower_id
    FROM follows
    WHERE following_id='$user_id'
");

while ($f = mysqli_fetch_assoc($followers)) {
    mysqli_query($conn, "
        INSERT INTO notifications (user_id, message)
        VALUES ('{$f["follower_id"]}', '$notif')
    ");
}

header("Location: community.php");
exit();
?>