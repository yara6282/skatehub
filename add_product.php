<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['p_name'];
    $price = $_POST['p_price'];
    $category = $_POST['p_category'];
    
    // تحويل مصفوفة المقاسات لنص واحد مفصول بفاصلة (S,M,L)
    $sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : 'OS';

    // معالجة الصورة
    $img_name = $_FILES['p_img']['name'];
    $tmp_name = $_FILES['p_img']['tmp_name'];
    $target = "image/" . basename($img_name);

    if (move_uploaded_file($tmp_name, $target)) {
        $sql = "INSERT INTO products (name, category, price, sizes, image) 
                VALUES ('$name', '$category', '$price', '$sizes', '$img_name')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: admin.html?status=product_added");
        }
    }
}
?>