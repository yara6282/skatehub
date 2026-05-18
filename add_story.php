<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
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
}

header("Location: community.php");
exit();
?>