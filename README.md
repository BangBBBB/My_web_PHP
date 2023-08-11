# My_web_PHP
Code web bang php thuan

Trên trang localhost/phpadmin: tạo db có tên là blog.sql
  + trong trang này tạo 1 bảng user: 
        CREATE TABLE `users` (
      `id` int(11) NOT NULL,
      `username` varchar(50) NOT NULL,
      `password` varchar(40) NOT NULL,
      `address` varchar(40) NOT NULL,
      `email` varchar(50) NOT NULL,
      `phone` int(10) DEFAULT NULL,
      `balance` float NOT NULL DEFAULT 100
  )
+ 1 bảng dùng để lưu trữ các bài đăng:
        CREATE TABLE `posts` (
        `post_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `title` varchar(255) NOT NULL,
        `content` text NOT NULL,
        `visibility` enum('private','public') NOT NULL DEFAULT 'public'
      
      ) 
    CREATE TABLE `transactions` (
      `transaction_id` int(11) NOT NULL,
      `sender_id` int(11) NOT NULL,
      `receiver_id` int(11) NOT NULL,
      `amount` float NOT NULL,
      `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
    ) 
+ 1 bảng để lưu trữ nội dung giao dịch:
        CREATE TABLE `transactions` (
           `transaction_id` int(11) NOT NULL,
            `sender_id` int(11) NOT NULL,
            `receiver_id` int(11) NOT NULL,
             `amount` float NOT NULL,
            `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
  )
  
#connectdb.php -> kết nối đến mysqlserver
    

#login.php -> Dùng để đăng nhập

#register.php -> Dùng để đăng ký thành viên

#profile.php-> Hiển thị thông tin user, cập nhật thông tin, liên kết đến trang home.php, log out.php

#profile.php -> hiểnm thị thông tin của user đăng bài

#home.php -> Đăng tin, xem các tin public, chuyển tiền , lịch sử chuyển tiền

#ConnectAndCSS: chứa các tệp css ứng với mỗi tệp php c
