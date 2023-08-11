<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<?php
// Xử lý đăng ký
require_once 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ biểu mẫu đăng ký
    $username = $_POST['username'];
    $password = ($_POST['password']);
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Kiểm tra xem tên đăng nhập đã tồn tại hay chưa
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        // Tên đăng nhập chưa tồn tại, thêm người dùng mới vào cơ sở dữ liệu
        $insertQuery = "INSERT INTO users (username, password, phone, email, address) VALUES ('$username', '$password', '$phone', '$email', '$address')";
        if (mysqli_query($conn, $insertQuery)) {
            // Lấy ID của người dùng vừa mới được thêm vào
            $userId = mysqli_insert_id($conn);
            
            // Cập nhật số dư của người dùng thành 100$
            $updateQuery = "UPDATE users SET balance = 100 WHERE id = '$userId'";
            mysqli_query($conn, $updateQuery);
            
            echo "Đăng ký thành công! Bạn đã được cấp 100$";
            header("Location: login.php"); // Sau khi đăng ký thành công sẽ chuyển đến trang login
            exit();
        } else {
            echo "Đăng ký thất bại!";
        }
    } else {
        // Tên đăng nhập đã tồn tại
        echo "Tên đăng nhập đã tồn tại!";
    }
}
?>
    <div class="register-form">
    <h2>Đăng ký</h2>
    <link rel="stylesheet" type="text/css" href="ConnectAndCss\register.css">
    <form method="POST" action="register.php" >
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" placeholder="Enter Username" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" placeholder="Enter Password" required>

        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" placeholder="Enter Phone" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter Email" required>

        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" name="address" placeholder="Enter Address" required>

        <input type="submit" name="register" value="Đăng ký">
    </form>
   </div>
</body>
</html>