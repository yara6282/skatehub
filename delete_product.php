<?php

session_start();

if (!isset($_SESSION["user_id"]) ||
    $_SESSION["role"] !== "admin") {

    header("Location: login.html");
    exit();
}

require_once __DIR__ . "/includes/db.php";

if (isset($_GET["id"])) {

    $id = intval($_GET["id"]);

    mysqli_query(
        $conn,
        "DELETE FROM products WHERE id = '$id'"
    );
}

header("Location: admin-dashboard.php");
exit();
?>