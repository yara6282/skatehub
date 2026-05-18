<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"] ?? "user";

if (!isset($_GET["id"])) {
    header("Location: community.php");
    exit();
}

$post_id = intval($_GET["id"]);

$post_result = mysqli_query($conn, "SELECT * FROM community_posts WHERE id = '$post_id'");
$post = mysqli_fetch_assoc($post_result);

if (!$post) {
    header("Location: community.php");
    exit();
}

$is_owner = $post["user_id"] == $user_id;
$is_admin = $role === "admin";

if (!$is_owner && !$is_admin) {
    header("Location: community.php");
    exit();
}

if (!empty($post["image"]) && file_exists($post["image"])) {
    unlink($post["image"]);
}

if ($is_admin && !$is_owner) {
    $message = "Your community post was removed by admin.";

    mysqli_query(
        $conn,
        "INSERT INTO notifications (user_id, message)
         VALUES ('{$post["user_id"]}', '$message')"
    );
}

mysqli_query($conn, "DELETE FROM community_posts WHERE id = '$post_id'");

header("Location: community.php");
exit();
?>