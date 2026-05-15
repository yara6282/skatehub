<?php
session_start();

require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $old_pass = $_POST["old_pass"] ?? "";
    $new_pass = $_POST["new_pass"] ?? "";

    $query = "SELECT password FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    $password_sql = "";

    if (!empty($new_pass)) {

        if (empty($old_pass)) {
            die("Current password is required.");
        }

        if (!password_verify($old_pass, $user["password"])) {
            die("Current password is incorrect.");
        }

        if (strlen($new_pass) < 8) {
            die("New password must be at least 8 characters.");
        }

        $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        $password_sql = ", password = '$hashed_new_pass'";
    }

    $img_sql = "";

    if (!empty($_FILES["profile_img"]["name"])) {

        $img_name = time() . "_" . basename($_FILES["profile_img"]["name"]);
        $target = __DIR__ . "/image/" . $img_name;

        if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target)) {
            $img_sql = ", profile_img = '$img_name'";
        }
    }

    $sql = "UPDATE users
            SET fullname = '$fullname'
            $password_sql
            $img_sql
            WHERE id = '$user_id'";

    if (mysqli_query($conn, $sql)) {

        $_SESSION["fullname"] = $fullname;

        header("Location: profile.php?update=success");
        exit();

    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>