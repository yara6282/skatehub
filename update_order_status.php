<?php

session_start();

if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "admin"
) {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . "/includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $order_id = intval($_POST["order_id"]);

    $status = mysqli_real_escape_string(
        $conn,
        $_POST["status"]
    );

    $order_sql =
    "SELECT * FROM orders WHERE id = '$order_id'";

    $order_result =
    mysqli_query($conn, $order_sql);

    $order =
    mysqli_fetch_assoc($order_result);

    if ($order) {

        mysqli_query(
            $conn,
            "UPDATE orders
            SET status='$status'
            WHERE id='$order_id'"
        );

        $user_id = $order["user_id"];

        $message =
        "Your order #SK-$order_id is now $status";

        mysqli_query(
            $conn,
            "INSERT INTO notifications
            (user_id, message)

            VALUES

            ('$user_id', '$message')"
        );
    }
}

header("Location: admin-dashboard.php");
exit();

?>