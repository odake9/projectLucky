-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2025-08-26 17:30:07
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `milk_tea_shop`
--

-- --------------------------------------------------------

--
-- 表的结构 `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_submitted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `feedback`
--

INSERT INTO `feedback` (`id`, `title`, `name`, `email`, `message`, `date_submitted`) VALUES
(4, 'TEST', 'LAU', 'lauyijie@gmail.com', 'HI', '2025-08-26 12:50:08');

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `total`) VALUES
(5, '2025-08-23 11:54:11', 15.00),
(6, '2025-08-23 11:57:02', 11.00),
(7, '2025-08-23 12:06:00', 15.00),
(8, '2025-08-23 12:19:01', 10.50),
(9, '2025-08-23 12:21:06', 15.00),
(10, '2025-08-23 12:22:41', 15.00),
(11, '2025-08-24 06:18:02', 10.50),
(12, '2025-08-24 06:18:02', 10.50),
(13, '2025-08-24 06:18:02', 10.50),
(14, '2025-08-24 06:18:53', 10.50),
(15, '2025-08-24 06:19:03', 21.50),
(16, '2025-08-24 06:19:40', 15.50),
(17, '2025-08-24 06:20:13', 15.50),
(18, '2025-08-24 08:03:15', 10.00),
(19, '2025-08-24 14:13:02', 10.50),
(20, '2025-08-25 01:43:22', 11.00),
(21, '2025-08-26 15:12:49', 25.50);

-- --------------------------------------------------------

--
-- 表的结构 `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `remark` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `name`, `price`, `quantity`, `remark`) VALUES
(9, 5, 'Lucky Family Milk Tea', 4.50, 1, ''),
(10, 5, 'Brown Sugar Boba', 5.50, 1, ''),
(11, 5, 'Taro Milk Tea', 5.00, 1, ''),
(12, 6, 'Brown Sugar Boba', 5.50, 1, ''),
(13, 6, 'Matcha Latte', 5.50, 1, ''),
(14, 7, 'Lucky Family Milk Tea', 4.50, 1, ''),
(15, 7, 'Taro Milk Tea', 5.00, 1, ''),
(16, 7, 'Matcha Latte', 5.50, 1, ''),
(17, 8, 'Brown Sugar Boba', 5.50, 1, ''),
(18, 8, 'Taro Milk Tea', 5.00, 1, ''),
(19, 9, 'Lucky Family Milk Tea', 4.50, 1, ''),
(20, 9, 'Brown Sugar Boba', 5.50, 1, ''),
(21, 9, 'Taro Milk Tea', 5.00, 1, ''),
(22, 10, 'Lucky Family Milk Tea', 4.50, 1, ''),
(23, 10, 'Brown Sugar Boba', 5.50, 1, ''),
(24, 10, 'Taro Milk Tea', 5.00, 1, ''),
(25, 12, 'Brown Sugar Boba', 5.50, 1, ''),
(26, 11, 'Brown Sugar Boba', 5.50, 1, ''),
(27, 12, 'Taro Milk Tea', 5.00, 1, ''),
(28, 11, 'Taro Milk Tea', 5.00, 1, ''),
(29, 13, 'Brown Sugar Boba', 5.50, 1, ''),
(30, 13, 'Taro Milk Tea', 5.00, 1, ''),
(31, 14, 'Taro Milk Tea', 5.00, 1, ''),
(32, 14, 'Matcha Latte', 5.50, 1, ''),
(33, 15, 'Brown Sugar Boba', 5.50, 3, ''),
(34, 15, 'Taro Milk Tea', 5.00, 1, ''),
(35, 16, 'Brown Sugar Boba', 5.50, 1, ''),
(36, 16, 'Taro Milk Tea', 5.00, 2, ''),
(37, 17, 'Matcha Latte', 5.50, 2, ''),
(38, 17, 'Lucky Family Milk Tea', 4.50, 1, ''),
(39, 18, 'Lucky Family Milk Tea', 4.50, 1, ''),
(40, 18, 'Brown Sugar Boba', 5.50, 1, ''),
(41, 19, 'Brown Sugar Boba', 5.50, 1, ''),
(42, 19, 'Taro Milk Tea', 5.00, 1, ''),
(43, 20, 'Brown Sugar Boba', 5.50, 2, ''),
(44, 21, 'Brown Sugar Boba', 5.50, 2, ''),
(45, 21, 'Taro Milk Tea', 5.00, 2, ''),
(46, 21, 'Lucky Family Milk Tea', 4.50, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `date_registered`, `profile_image`) VALUES
(3, 'LAUYIJIE', 'lauyijie@gmail.com', '$2y$10$G8dpeRTmtBiU4A6QavHiG.qjRSKh1VJoeF6I5LnrtlclpafWdQcC.', 'admin', '2025-08-24 14:39:03', '1756208800_photo_6284917887705659633_y (2).jpg'),
(7, 'NG', 'ng1234@gmail.com', '$2y$10$IOleh5kOtXrUmCip3/38c.rM8ZUWcMh4dvMBALURviOeS0y9RBPn2', 'staff', '2025-08-26 12:08:21', '1756211423_WhatsApp Image 2025-08-07 at 1.51.00 PM (2).jpeg'),
(9, 'LAU', 'lauyijie0259@gmail.com', '$2y$10$OXgZnHiGIx4up6.oiEtbA.sk.iBXa1Lk7qlZ0Hv47dDGbHlSWI0nm', 'staff', '2025-08-26 14:09:26', 'default.png'),
(10, 'LIO', 'longqifgf@gmail.com', '$2y$10$yG0OyMOrFKlCZ/854NmKp.sU3XAMVzALRbEC20SwBgjGY0X7ikV1O', 'staff', '2025-08-26 14:41:25', 'default.png'),
(11, 'LAU', 'yj0259@gmail.com', '$2y$10$Edt2sfTvn10UzcyX5EG.jOHqEyjI3s/eZb.5eBb7VXPfltWbJYAZ2', 'staff', '2025-08-26 15:09:48', 'default.png');

--
-- 转储表的索引
--

--
-- 表的索引 `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- 表的索引 `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- 表的索引 `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用表AUTO_INCREMENT `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- 使用表AUTO_INCREMENT `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 限制导出的表
--

--
-- 限制表 `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
