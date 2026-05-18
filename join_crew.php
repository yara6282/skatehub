<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$crew_id = intval($_POST["crew_id"]);

$check = mysqli_query($conn, "SELECT * FROM crew_members WHERE user_id='$user_id' AND crew_id='$crew_id'");

if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "DELETE FROM crew_members WHERE user_id='$user_id' AND crew_id='$crew_id'");
} else {
    mysqli_query($conn, "INSERT INTO crew_members (crew_id, user_id) VALUES ('$crew_id', '$user_id')");
}

header("Location: skate-crews.php");
exit();
?>