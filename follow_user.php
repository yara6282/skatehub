<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$follower_id = intval($_SESSION["user_id"]);
$following_id = intval($_GET["user_id"] ?? 0);
$back = $_GET["back"] ?? "community.php";

if ($following_id <= 0 || $following_id == $follower_id) {
    header("Location: $back");
    exit();
}

$check = mysqli_query($conn, "
    SELECT * FROM follows
    WHERE follower_id='$follower_id'
    AND following_id='$following_id'
");

if (mysqli_num_rows($check) > 0) {

    mysqli_query($conn, "
        DELETE FROM follows
        WHERE follower_id='$follower_id'
        AND following_id='$following_id'
    ");

} else {

    mysqli_query($conn, "
        INSERT INTO follows (follower_id, following_id)
        VALUES ('$follower_id', '$following_id')
    ");

    $me = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT fullname FROM users WHERE id='$follower_id'
    "));

    $name = mysqli_real_escape_string($conn, $me["fullname"]);
    $message = "$name started following you.";

    mysqli_query($conn, "
        INSERT INTO notifications (user_id, message)
        VALUES ('$following_id', '$message')
    ");
}

header("Location: $back");
exit();
?>