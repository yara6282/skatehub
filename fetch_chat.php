<?php
session_start();
require_once __DIR__ . "/includes/db.php";

header("Content-Type: application/json");

$user_id = intval($_SESSION["user_id"] ?? 0);
$with_id = intval($_GET["with_id"] ?? 0);

if (!$user_id || !$with_id) {
    echo json_encode([]);
    exit();
}

$result = mysqli_query($conn, "
SELECT *
FROM chat_messages
WHERE 
(sender_id='$user_id' AND receiver_id='$with_id')
OR
(sender_id='$with_id' AND receiver_id='$user_id')
ORDER BY created_at ASC
");

$messages = [];

while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

echo json_encode($messages);
?>