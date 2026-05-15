<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . "/includes/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = mysqli_real_escape_string($conn, $_POST['event_title']);

    $category = mysqli_real_escape_string($conn, $_POST['event_category']);

    $location = mysqli_real_escape_string($conn, $_POST['event_location']);

    $date = $_POST['event_date'];

    $price = $_POST['event_price'];

    $desc = mysqli_real_escape_string($conn, $_POST['event_desc']);

    $img_name = time() . "_" . $_FILES['event_bg']['name'];

    $tmp_name = $_FILES['event_bg']['tmp_name'];

    $target = "image/" . $img_name;

    if (move_uploaded_file($tmp_name, $target)) {

        $sql = "INSERT INTO events
        (title, category, event_date, location, price, image, description)

        VALUES

        ('$title', '$category', '$date', '$location',
        '$price', '$target', '$desc')";

        if (mysqli_query($conn, $sql)) {

            header("Location: admin-dashboard.php?status=event_added");

            exit();
        }
    }
}
?>