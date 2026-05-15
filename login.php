<?php

session_start();
require_once __DIR__ . "/includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        header("Location: login.html?email=notfound");
        exit();
    }

    $user = mysqli_fetch_assoc($result);

    if (!password_verify($password, $user["password"])) {
        header("Location: login.html?password=wrong");
        exit();
    }

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["fullname"] = $user["fullname"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["role"] = $user["role"];

    if ($user["role"] === "admin") {
        header("Location: admin-dashboard.php");
        exit();
    }

    header("Location: home.php");
    exit();
}
?>