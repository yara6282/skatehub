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
    die("Invalid post id");
}

$check = mysqli_query(
    $conn,
    "SELECT * FROM post_likes 
     WHERE user_id='$user_id' 
     AND post_id='$post_id'"
);

if (!$check) {
    die(mysqli_error($conn));
}

if (mysqli_num_rows($check) > 0) {

    $delete = mysqli_query(
        $conn,
        "DELETE FROM post_likes 
         WHERE user_id='$user_id' 
         AND post_id='$post_id'"
    );

    if (!$delete) {
        die(mysqli_error($conn));
    }

} else {

    $insert = mysqli_query(
        $conn,
        "INSERT INTO post_likes (user_id, post_id)
         VALUES ('$user_id', '$post_id')"
    );

    if (!$insert) {
        die(mysqli_error($conn));
    }
}

header("Location: $back");
exit();
?>