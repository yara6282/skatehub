<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['event_title'];
    $type = $_POST['event_type'];
    $date = $_POST['event_date'];
    $price = $_POST['event_price'];
    $desc = mysqli_real_escape_string($conn, $_POST['event_desc']);

    $img_name = $_FILES['event_bg']['name'];
    $tmp_name = $_FILES['event_bg']['tmp_name'];
    $target = "image/" . basename($img_name);

    if (move_uploaded_file($tmp_name, $target)) {
        $sql = "INSERT INTO events (title, type, date, price, image, description) 
                VALUES ('$title', '$type', '$date', '$price', '$img_name', '$desc')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: admin.html?status=event_added");
        }
    }
}
?>