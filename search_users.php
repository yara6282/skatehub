<?php
session_start();
require_once __DIR__ . "/includes/db.php";

header("Content-Type: application/json");

$user_id = intval($_SESSION["user_id"] ?? 0);
$q = mysqli_real_escape_string($conn, $_GET["q"] ?? "");

if (!$user_id || $q == "") {
    echo json_encode([]);
    exit();
}

$result = mysqli_query($conn, "
SELECT id, fullname, email, profile_img
FROM users
WHERE id != '$user_id'
AND fullname LIKE '%$q%'
LIMIT 8
");

$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $row["profile_img"] = !empty($row["profile_img"])
        ? "image/" . $row["profile_img"]
        : "";

    $users[] = $row;
}

echo json_encode($users);
?>