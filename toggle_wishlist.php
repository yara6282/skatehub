<?php
session_start();
require_once __DIR__ . "/includes/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "login_required"]);
    exit();
}

$user_id = $_SESSION["user_id"];

$data = json_decode(file_get_contents("php://input"), true);

$product_id = $data["product_id"];
$product_name = $data["product_name"];
$product_img = $data["product_img"];
$product_price = $data["product_price"];

$check = "SELECT id FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'";
$result = mysqli_query($conn, $check);

if (mysqli_num_rows($result) > 0) {
    $delete = "DELETE FROM wishlist WHERE user_id='$user_id' AND product_id='$product_id'";
    mysqli_query($conn, $delete);

    echo json_encode(["success" => true, "status" => "removed"]);
    exit();
}

$insert = "INSERT INTO wishlist 
(user_id, product_id, product_name, product_img, product_price)
VALUES
('$user_id', '$product_id', '$product_name', '$product_img', '$product_price')";

mysqli_query($conn, $insert);

echo json_encode(["success" => true, "status" => "added"]);
?>