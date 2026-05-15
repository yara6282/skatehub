<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . "/includes/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['p_name']);

    $price = $_POST['p_price'];

    $category = $_POST['p_category'];

    $sizes = isset($_POST['sizes'])
        ? implode(',', $_POST['sizes'])
        : 'OS';

    $img_name = time() . "_" . $_FILES['p_img']['name'];

    $tmp_name = $_FILES['p_img']['tmp_name'];

    $target = "image/" . $img_name;

    if (move_uploaded_file($tmp_name, $target)) {

        $sql = "INSERT INTO products
        (name, category, price, sizes, image)

        VALUES

        ('$name', '$category', '$price',
        '$sizes', '$target')";

        if (mysqli_query($conn, $sql)) {

            header("Location: admin-dashboard.php?status=product_added");

            exit();
        }
    }
}
?>