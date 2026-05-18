<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = intval($_SESSION["user_id"]);
$caption = mysqli_real_escape_string($conn, $_POST["caption"] ?? "");

if (empty($_FILES["story_media"]["name"])) {
    header("Location: community.php");
    exit();
}

$file_name = time() . "_" . basename($_FILES["story_media"]["name"]);
$target = "image/" . $file_name;

if (move_uploaded_file($_FILES["story_media"]["tmp_name"], $target)) {

    mysqli_query($conn, "
        INSERT INTO stories (user_id, media, caption, expires_at)
        VALUES ('$user_id', '$target', '$caption', DATE_ADD(NOW(), INTERVAL 24 HOUR))
    ");

    $me = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT fullname FROM users WHERE id='$user_id'
    "));

    $name = mysqli_real_escape_string($conn, $me["fullname"]);
    $notif = "$name added a new story.";

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
}

header("Location: community.php");
exit();
?>