<?php

session_start();

require_once __DIR__ . "/includes/db.php";

$id = intval($_GET["id"]);

mysqli_query(
$conn,
"DELETE FROM tutorials WHERE id='$id'"
);

header("Location: admin-dashboard.php");
exit();

?>