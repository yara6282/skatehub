<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"] ?? "user";

$story_id = intval($_GET["id"] ?? 0);

if ($story_id <= 0) {
    header("Location: community.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM stories WHERE id='$story_id'");
$story = mysqli_fetch_assoc($result);

if (!$story) {
    header("Location: community.php");
    exit();
}

if ($story["user_id"] == $user_id || $role === "admin") {

    if (!empty($story["media"]) && file_exists($story["media"])) {
        unlink($story["media"]);
    }

    mysqli_query($conn, "DELETE FROM stories WHERE id='$story_id'");
}

header("Location: community.php");
exit();
?>