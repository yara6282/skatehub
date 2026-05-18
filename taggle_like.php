<?php
session_start();
require_once __DIR__ . "/includes/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["login_required" => true]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION["user_id"];
$post_id = intval($data["post_id"]);

$check = mysqli_query($conn, "SELECT * FROM post_likes WHERE user_id='$user_id' AND post_id='$post_id'");

if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "DELETE FROM post_likes WHERE user_id='$user_id' AND post_id='$post_id'");
    $status = "unliked";
} else {
    mysqli_query($conn, "INSERT INTO post_likes (user_id, post_id) VALUES ('$user_id', '$post_id')");
    $status = "liked";
}

$count_result = mysqli_query($conn, "SELECT COUNT(*) AS likes FROM post_likes WHERE post_id='$post_id'");
$likes = mysqli_fetch_assoc($count_result)["likes"];

echo json_encode([
    "status" => $status,
    "likes" => $likes
]);
?>