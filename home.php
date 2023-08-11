<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ</title>
    <!-- Liên kết tệp CSS -->
    <link rel="stylesheet" type="text/css" href="ConnectAndCss/home.css">
</head>
<body>

<?php
require_once 'connectdb.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý đăng tin
    if (isset($_POST['post'])) {
        handlePost($conn, $userId);
    }
    // Xử lý chuyển tiền
    elseif (isset($_POST['transfer'])) {
        handleTransfer($conn, $userId);
    }
}

$query = "SELECT posts.*, users.username AS author_username FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY post_id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="post-list">
    <h2>Danh sách bài đăng</h2>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <?php if ($row['visibility'] === 'public' || $row['user_id'] === $userId) { ?>
                <li>
                    <?php echo $row['content']; ?>
                    <?php if ($row['visibility'] === 'private' && $row['user_id'] !== $userId) { ?>
                        (Chế độ Private - Chỉ tác giả)
                    <?php } else { ?>
                        <a href="profile2.php?user_id=<?php echo $row['user_id']; ?>"><?php echo $row['author_username']; ?></a>
                    <?php } ?>
                </li>
            <?php } elseif ($row['visibility'] === 'private' && $row['user_id'] === $userId) { ?>
                <li>
                    <?php echo $row['content']; ?>
                    <?php echo " (Chế độ Private)"; ?>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
    <a href="profile.php">Quay trở lại trang cá nhân</a>
</div>

<div class="post-form">
    <h2>Đăng tin</h2>
    <form method="POST" action="home.php">
        <label for="content">Đăng bài viết:</label>
        <textarea id="content" name="content" placeholder="Nhập nội dung tin" required></textarea>

        <label for="visibility">Chế độ:</label>
        <select id="visibility" name="visibility">
            <option value="public">Public</option>
            <option value="private">Private</option>
        </select>

        <input type="submit" name="post" value="Đăng tin">
    </form>
</div>

<div class="transaction-form">
    <h2>Chuyển tiền</h2>
    <form method="POST" action="test.php">
        <label for="receiver">Người nhận (username):</label>
        <input type="text" id="receiver" name="receiver" required>

        <label for="amount">Số tiền:</label>
        <input type="number" id="amount" name="amount" required>

        <input type="submit" name="transfer" value="Chuyển tiền">
    </form>
</div>

<div class="transaction-history">
    <h2>Lịch sử giao dịch</h2>
    <ul>
        <?php displayTransactionHistory($conn, $userId); ?>
    </ul>
</div>

</body>
</html>

<?php
function handlePost($conn, $userId) {
    $content = $_POST['content'];
    $visibility = $_POST['visibility'];

    $insertQuery = "INSERT INTO posts (user_id, content, visibility) VALUES ('$userId', '$content', '$visibility')";
    if (mysqli_query($conn, $insertQuery)) {
        echo "Đăng tin thành công!";
    } else {
        echo "Đăng tin thất bại!";
    }
}

function handleTransfer($conn, $userId) {
    $receiverUsername = $_POST['receiver'];
    $amount = $_POST['amount'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['transfer'])) {
            $receiverUsername = $_POST['receiver'];
            $amount = $_POST['amount'];

            // Kiểm tra xem người nhận có tồn tại hay không
            $receiverQuery = "SELECT * FROM users WHERE username = '$receiverUsername'";
            $receiverResult = mysqli_query($conn, $receiverQuery);
            if (mysqli_num_rows($receiverResult) == 1) {
                $receiverRow = mysqli_fetch_assoc($receiverResult);
                $receiverId = $receiverRow['id'];

                // Kiểm tra số dư người gửi
                $senderQuery = "SELECT * FROM users WHERE id = '$userId'";
                $senderResult = mysqli_query($conn, $senderQuery);
                $senderRow = mysqli_fetch_assoc($senderResult);
                $senderBalance = $senderRow['balance'];

                if ($senderBalance >= $amount) {
                    // Thực hiện giao dịch
                    $newSenderBalance = $senderBalance - $amount;
                    $newReceiverBalance = $receiverRow['balance'] + $amount;

                    // Cập nhật số dư cho người gửi và người nhận
                    $updateSenderQuery = "UPDATE users SET balance = '$newSenderBalance' WHERE id = '$userId'";
                    $updateReceiverQuery = "UPDATE users SET balance = '$newReceiverBalance' WHERE id = '$receiverId'";
                    mysqli_query($conn, $updateSenderQuery);
                    mysqli_query($conn, $updateReceiverQuery);

                    // Ghi thông tin giao dịch vào bảng transaction
                    $timestamp = date("Y-m-d H:i:s");
                    $insertTransactionQuery = "INSERT INTO transactions (sender_id, receiver_id, amount, timestamp) 
                                              VALUES ('$userId', '$receiverId', '$amount', '$timestamp')";
                    mysqli_query($conn, $insertTransactionQuery);

                    echo "<script>alert('Chuyển tiền thành công!')</script>";
                } else {
                    echo "<script>alert('Số dư không đủ chuyển đíu gì chuyển lắm không xem lại ví à!')</script>";
                }
            } else {
                echo "<script>alert('Người nhận không tồn tại!')</script>";
            }
        }
    }

}

function displayTransactionHistory($conn, $userId) {
    $transactionQuery = "SELECT * FROM transactions WHERE sender_id = '$userId' OR receiver_id = '$userId' ORDER BY timestamp DESC";
    $transactionResult = mysqli_query($conn, $transactionQuery);
    while ($transactionRow = mysqli_fetch_assoc($transactionResult)) {
        $transactionType = ($transactionRow['sender_id'] == $userId) ? 'Gửi đi' : 'Nhận vào';
        $transactionAmount = $transactionRow['amount'];
        $transactionTimestamp = $transactionRow['timestamp'];
        $transactionReceiverId = $transactionRow['receiver_id'];
        $transactionSenderQuery = "SELECT username FROM users WHERE id = '$transactionReceiverId'";
        $transactionSenderResult = mysqli_query($conn, $transactionSenderQuery);
        $transactionSenderRow = mysqli_fetch_assoc($transactionSenderResult);
        $transactionReceiverUsername = $transactionSenderRow['username'];
        ?>
        <li>
            <?php echo "$transactionType: $transactionAmount $ to $transactionReceiverUsername lúc $transactionTimestamp"; ?>
        </li>
        <?php
    }
}
?>
