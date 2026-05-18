<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$story_id = intval($_GET["story_id"] ?? 0);

$check = mysqli_query($conn, "
    SELECT * FROM story_likes
    WHERE story_id='$story_id'
    AND user_id='$user_id'
");

if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "
        DELETE FROM story_likes
        WHERE story_id='$story_id'
        AND user_id='$user_id'
    ");
} else {
    mysqli_query($conn, "
        INSERT INTO story_likes (story_id, user_id)
        VALUES ('$story_id', '$user_id')
    ");
}

header("Location: view_story.php?id=$story_id");
exit();
?>