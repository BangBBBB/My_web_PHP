<?php
session_start(); // Bắt đầu phiên

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connectdb.php';

// Lấy thông tin người dùng từ CSDL
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$userData = mysqli_fetch_assoc($result);

// Xử lý cập nhật thông tin cá nhân
if (isset($_POST['update'])) {
    // Lấy thông tin từ biểu mẫu cập nhật
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $new_phone = $_POST['new_phone'];
    $new_email = $_POST['new_email'];
    $new_address = $_POST['new_address'];
    $new_balance = $_POST['new_balance'];
    // Cập nhật thông tin cá nhân vào CSDL
    $updateQuery = "UPDATE users SET username = '$new_username', password = '$new_password', phone = '$new_phone', email = '$new_email', address = '$new_address',blance ='$new_balance' WHERE id = '$user_id'";

    if (mysqli_query($conn, $updateQuery)) {
        // Cập nhật thông tin trong session
        $_SESSION['username'] = $new_username;
        $_SESSION['email'] = $new_email;
        $_SESSION['phone'] = $new_phone;
        $_SESSION['address'] = $new_address;

        echo "<script> alert('Cập nhật thông tin thành công!')</script>";
    } else {
        echo "<script> alert('Cập nhật thông tin thất bại!')</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" type="text/css" href="ConnectAndCss/profile.css">
</head>

<body>
    <h2 style="color:red; background-size: auto; ">Thông tin cá nhân</h2>
    <p><strong>Username:</strong> <?php echo $userData['username']; ?></p>
    <p><strong>Email:</strong> <?php echo $userData['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $userData['phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $userData['address']; ?></p>
    <p><strong>Balance:</strong> $<?php echo $userData['balance']; ?></p>
    <!-- Button để mở/đóng form cập nhật -->
    <button onclick="toggleUpdateForm()">Cập nhật thông tin</button>

    <!-- Button để chuyển đến trang Home -->
    <a href="home.php">Home</a>

    <!-- Biểu mẫu cập nhật thông tin cá nhân, mặc định ẩn đi -->
    <div class="update-form" style="display:none;">
        <h3>Cập nhật thông tin cá nhân</h3>
        <form method="POST" action="profile.php">
            <label for="new_username">Tên đăng nhập mới:</label>
            <input type="text" id="new_username" name="new_username" value="<?php echo $userData['username']; ?>" required><br>

            <label for="new_password">Mật khẩu mới:</label>
            <input type="password" id="new_password" name="new_password" required><br>

            <label for="new_phone">Số điện thoại mới:</label>
            <input type="text" id="new_phone" name="new_phone" value="<?php echo $userData['phone']; ?>" required><br>

            <label for="new_email">Email mới:</label>
            <input type="email" id="new_email" name="new_email" value="<?php echo $userData['email']; ?>" required><br>

            <label for="new_address">Địa chỉ mới:</label>
            <input type="text" id="new_address" name="new_address" value="<?php echo $userData['address']; ?>" required><br>

            <input type="submit" name="update" value="Cập nhật">
        </form>
    </div>

    <script>
        function toggleUpdateForm() {
            var updateForm = document.querySelector(".update-form");

            if (updateForm.style.display === "none") {
                updateForm.style.display = "block";
            } else {
                updateForm.style.display = "none";
            }
        }
    </script>
</body>
<a href="logout.php">Đăng xuất</a>
</body>

</html>