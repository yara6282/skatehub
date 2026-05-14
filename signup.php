<?php

require_once __DIR__ . "/includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // التحقق من الحقول الفارغة
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required'); window.history.back();</script>";
        exit();
    }

    // التحقق من الإيميل
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.history.back();</script>";
        exit();
    }

    // التحقق من تطابق الباسورد
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.history.back();</script>";
        exit();
    }

    // طول الباسورد
    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters'); window.history.back();</script>";
        exit();
    }

    // حرف كبير
    if (!preg_match('/[A-Z]/', $password)) {
        echo "<script>alert('Password must contain at least one uppercase letter'); window.history.back();</script>";
        exit();
    }

    // حرف صغير
    if (!preg_match('/[a-z]/', $password)) {
        echo "<script>alert('Password must contain at least one lowercase letter'); window.history.back();</script>";
        exit();
    }

    // رقم
    if (!preg_match('/[0-9]/', $password)) {
        echo "<script>alert('Password must contain at least one number'); window.history.back();</script>";
        exit();
    }

    // التحقق إذا الإيميل موجود
    $check = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists'); window.history.back();</script>";
        exit();
    }

    // تشفير الباسورد
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // إدخال البيانات
    $sql = "INSERT INTO users (fullname, email, password)
            VALUES ('$fullname', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {

        echo "<script>alert('Account created successfully'); window.location.href='login.html';</script>";
        exit();

    } else {

        echo "<script>alert('Database Error'); window.history.back();</script>";
        exit();

    }

}

?>