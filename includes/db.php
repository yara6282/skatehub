<?php

$conn = mysqli_connect("localhost", "root", "", "skatehub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>