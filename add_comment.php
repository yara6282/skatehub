<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$post_id = intval($_POST["post_id"]);
$comment = mysqli_real_escape_string($conn, $_POST["comment"]);

if (!empty($comment)) {
    mysqli_query(
        $conn,
        "INSERT INTO post_comments (post_id, user_id, comment)
         VALUES ('$post_id', '$user_id', '$comment')"
    );
}

header("Location: community.php");
exit();
?>