<?php

session_start();

require_once __DIR__ . "/includes/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);

    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {

    echo json_encode([
        "success" => false,
        "message" => "No data received"
    ]);

    exit();
}

$user_id = $_SESSION["user_id"];

$governorate = $data["governorate"];
$address = $data["address"];
$payment_method = $data["payment_method"];
$shipping_method = $data["shipping_method"];

$subtotal = $data["subtotal"];
$shipping_fee = $data["shipping_fee"];
$total = $data["total"];

$cart = $data["cart"];

// حفظ الطلب الرئيسي
$sql = "INSERT INTO orders
(
    user_id,
    governorate,
    address,
    payment_method,
    shipping_method,
    subtotal,
    shipping_fee,
    total
)
VALUES
(
    '$user_id',
    '$governorate',
    '$address',
    '$payment_method',
    '$shipping_method',
    '$subtotal',
    '$shipping_fee',
    '$total'
)";

if (!mysqli_query($conn, $sql)) {

    echo json_encode([
        "success" => false,
        "message" => "Database error"
    ]);

    exit();
}

$order_id = mysqli_insert_id($conn);

// حفظ المنتجات
foreach ($cart as $item) {

    $product_name = $item["name"];
    $product_img = $item["img"];
    $size = $item["size"];
    $price = $item["price"];
    $quantity = $item["qty"];

    $item_total = $price * $quantity;

    $item_sql = "INSERT INTO order_items
    (
        order_id,
        product_name,
        product_img,
        size,
        price,
        quantity,
        item_total
    )
    VALUES
    (
        '$order_id',
        '$product_name',
        '$product_img',
        '$size',
        '$price',
        '$quantity',
        '$item_total'
    )";

    mysqli_query($conn, $item_sql);
}

echo json_encode([
    "success" => true
]);