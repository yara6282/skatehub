<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.html");
    exit();
}

require_once __DIR__ . "/includes/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = mysqli_real_escape_string($conn, $_POST['video_title']);

    $style = mysqli_real_escape_string($conn, $_POST['video_style']);

    $desc = mysqli_real_escape_string($conn, $_POST['video_desc']);

    $url = trim($_POST['video_url']);

    function getYoutubeId($url) {

        parse_str(parse_url($url, PHP_URL_QUERY), $vars);

        return $vars['v'] ?? basename($url);
    }

    $video_id = getYoutubeId($url);

    $sql = "INSERT INTO tutorials
    (title, style, video_id, description)

    VALUES

    ('$title', '$style', '$video_id', '$desc')";

    if (mysqli_query($conn, $sql)) {

        header("Location: admin-dashboard.php?status=tutorial_added");

        exit();
    }
}
?>