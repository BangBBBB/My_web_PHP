<?php
session_start();

// Xóa tất cả các session liên quan đến người dùng
session_unset();

// Hủy phiên làm việc
session_destroy();

// Chuyển hướng về trang đăng nhập sau khi đăng xuất thành công
header("Location: login.php");
exit();
?>
