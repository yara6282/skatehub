<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['video_title'];
    $style = $_POST['video_style'];
    $desc = mysqli_real_escape_string($conn, $_POST['video_desc']);
    $url = $_POST['video_url'];

    // دالة لاستخراج معرف الفيديو من رابط يوتيوب
    function getYoutubeId($url) {
        parse_str(parse_url($url, PHP_URL_QUERY), $vars);
        return $vars['v'] ?? basename($url);
    }

    $video_id = getYoutubeId($url);

    $sql = "INSERT INTO tutorials (title, style, video_id, description) 
            VALUES ('$title', '$style', '$video_id', '$desc')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: admin.html?status=tutorial_added");
    }
}
?>