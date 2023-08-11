<?php
session_start(); // Bắt đầu phiên

require_once 'connectdb.php';

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra thông tin đăng nhập
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Đăng nhập thành công
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['address'] = $user['address'];
        header("Location: profile.php");
        exit();
    } else {
        // Đăng nhập thất bại
        echo "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" type="text/css" href="ConnectAndCss/login.css">
</head>
<body>
    <div class="container">
        <h2>Đăng nhập</h2>
        <form method="POST" action="login.php" class="login-form">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" placeholder="Enter Username" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>

            <label>
                <input type="checkbox" name="remember"> Ghi nhớ mật khẩu
            </label>

            <input type="submit" name="login" value="Đăng nhập">
        </form>

        <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay!</a></p>
    </div>
</body>
</html>
