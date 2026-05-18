<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"] ?? "user";

$comment_id = intval($_GET["id"] ?? 0);

$result = mysqli_query($conn, "SELECT * FROM post_comments WHERE id='$comment_id'");
$comment = mysqli_fetch_assoc($result);

if (!$comment) {
    header("Location: community.php");
    exit();
}

$is_owner = $comment["user_id"] == $user_id;
$is_admin = $role === "admin";

if ($is_owner || $is_admin) {
    mysqli_query($conn, "DELETE FROM post_comments WHERE id='$comment_id'");
}

header("Location: community.php");
exit();
?>