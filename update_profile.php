<?php
session_start(); // لبدء الجلسة ومعرفة من هو المستخدم الحالي
include 'db_connect.php'; // ملف الاتصال بقاعدة البيانات (سننشئه في الخطوة 3)

// التحقق من أن المستخدم سجل دخوله أصلاً
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // 1. جلب بيانات المستخدم الحالية من قاعدة البيانات للتأكد من الباسورد
    $query = "SELECT password FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    $update_password = false;

    // 2. إذا أراد المستخدم تغيير كلمة المرور
    if (!empty($new_pass)) {
        // التأكد من كلمة المرور القديمة
        if (!password_verify($old_pass, $user['password'])) {
            die("Error: Current password is incorrect!");
        }
        // التأكد من تطابق كلمتي المرور الجديدتين
        if ($new_pass !== $confirm_pass) {
            die("Error: New passwords do not match!");
        }
        $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_password = true;
    }

    // 3. معالجة رفع الصورة (إذا اختار صورة جديدة)
    $img_query = "";
    if (!empty($_FILES['profile_img']['name'])) {
        $img_name = time() . '_' . $_FILES['profile_img']['name'];
        $target = "image/" . $img_name;
        if (move_uploaded_file($_FILES['profile_img']['tmp_手p_name'], $target)) {
            $img_query = ", profile_img = '$img_name'";
        }
    }

    // 4. تنفيذ التحديث في قاعدة البيانات
    $sql = "UPDATE users SET fullname = '$full_name' $img_query";
    if ($update_password) {
        $sql .= ", password = '$hashed_new_pass'";
    }
    $sql .= " WHERE id = '$user_id'";

    if (mysqli_query($conn, $sql)) {
        // نجاح التحديث: العودة لصفحة البروفايل
        header("Location: profile.html?update=success");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>