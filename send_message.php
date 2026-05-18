<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    exit();
}

$sender_id = intval($_SESSION["user_id"]);
$receiver_id = intval($_POST["receiver_id"] ?? 0);
$message = mysqli_real_escape_string($conn, $_POST["message"] ?? "");

if ($receiver_id > 0 && $message != "") {

    mysqli_query($conn, "
        INSERT INTO chat_messages (sender_id, receiver_id, message)
        VALUES ('$sender_id', '$receiver_id', '$message')
    ");

    $me = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT fullname FROM users WHERE id='$sender_id'
    "));

    $name = mysqli_real_escape_string($conn, $me["fullname"]);
    $notif = "$name sent you a message.";

    mysqli_query($conn, "
        INSERT INTO notifications (user_id, message)
        VALUES ('$receiver_id', '$notif')
    ");
}
?>