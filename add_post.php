<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
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

$sql = "INSERT INTO community_posts (user_id, content, image, location)
        VALUES ('$user_id', '$content', '$image_path', '$location')";

mysqli_query($conn, $sql);

header("Location: community.php");
exit();
?>