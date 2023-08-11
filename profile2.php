<!DOCTYPE html>
<!-- dùng để hiển thị thông tin của user khác -->
<html>
<head>
    <title>Trang cá nhân</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<?php
require_once 'connectdb.php';

if (isset($_GET['user_id'])) {
    $profileUserId = $_GET['user_id'];

    $query = "SELECT username, email, phone, address FROM users WHERE id = '$profileUserId'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $profileUsername = $row['username'];
        $profileEmail = $row['email'];
        $profilePhone = $row['phone'];
        $profileAddress = $row['address'];

        // Kiểm tra xem người dùng có quyền chỉnh sửa thông tin hay không
        session_start();
        $currentUserId = $_SESSION['user_id'];

       
            $profileEmail = $_SESSION['email'];
            $profilePhone = $_SESSION['phone'];
            $profileAddress = $_SESSION['address'];
        
?>

<div class="profile">
    <h2>Thông tin cá nhân của <?php echo $profileUsername; ?></h2>
    <p><strong>Username:</strong> <?php echo $profileUsername; ?></p>
    <p><strong>Email:</strong> <?php echo $profileEmail; ?></p>
    <p><strong>Phone:</strong> <?php echo $profilePhone; ?></p>
    <p><strong>Address:</strong> <?php echo $profileAddress; ?></p>
    <a href="home.php">Quay tro lai</a>
</div>

<?php
    } else {
        echo "Người dùng không tồn tại.";
    }
} else {
    echo "Không có thông tin người dùng được cung cấp.";
}
?>
</body>
</html>