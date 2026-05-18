<?php
session_start();

require_once __DIR__ . "/includes/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM notifications
        WHERE user_id = '$user_id'
        ORDER BY created_at DESC
        LIMIT 8";

$result = mysqli_query($conn, $sql);

$notifications = [];

while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = '$user_id'");

echo json_encode($notifications);
?>