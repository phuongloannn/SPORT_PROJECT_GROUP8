<?php 
session_start();
include("./functions/userfunctions.php");

if(isset($_SESSION['auth']))
{
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    redirect("login.php", "Đăng xuất thành công");
    exit(); // Đảm bảo dừng thực thi sau khi redirect
}

// Nếu không có session, cũng chuyển đến login.php
header('Location: login.php');
exit();
?>
