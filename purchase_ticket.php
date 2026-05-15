<?php
session_start();
require_once __DIR__ . "/includes/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "login_required"
    ]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION["user_id"];
$event_id = $data["event_id"] ?? null;
$payment_method = $data["payment_method"] ?? "Credit Card";
$quantity = intval($data["quantity"] ?? 1);

if (!$event_id || $quantity < 1) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid ticket data"
    ]);
    exit();
}

$insert = "INSERT INTO event_tickets 
(user_id, event_id, payment_method, quantity)
VALUES
('$user_id', '$event_id', '$payment_method', '$quantity')";

if (mysqli_query($conn, $insert)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "message" => mysqli_error($conn)
    ]);
}
?>