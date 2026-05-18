
<?php
session_start();
require_once __DIR__ . "/includes/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([]);
    exit();
}

$user_id = intval($_SESSION["user_id"]);

$result = mysqli_query($conn, "
    SELECT *
    FROM notifications
    WHERE user_id='$user_id'
    ORDER BY created_at DESC
    LIMIT 15
");

$notifications = [];

while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

echo json_encode($notifications);
?>