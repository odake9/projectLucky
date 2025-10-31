-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2025 at 04:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `milk_tea_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `date_submitted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `title`, `name`, `email`, `message`, `date_submitted`) VALUES
(1, 'HI', 'LAU', 'lauyijie0259@gmail.com', 'HI HALLO', '2025-09-08 00:53:26'),
(2, 'hallo', 'LAUYIJIE', 'lauyijie0259@gmail.com', 'halooooooo\r\n', '2025-09-08 00:55:07'),
(3, 'TEST 0000', 'LAUYIJIE', 'lauyijie0259@gmail.com', 'HAHAHAHA TEST 0000', '2025-09-13 10:32:52'),
(4, 'HI', 'LAU', 'lauyijie0259@gmail.com', 'HAHAHAAH', '2025-09-13 13:43:03'),
(5, 'hi', 'tyh', 'tayyuheng50@gmail.com', 'good', '2025-09-13 13:47:40');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(6,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `description`, `price`, `category`, `image`, `created_at`) VALUES
(1, 'Orange Juice', 'Freshly Squeezed Orange Juice – a classic, sweet, and tangy favorite, brimming with Vitamin C for a refreshing boost', 5.00, 'Refreshing', '1757244717_Orangejuice.jpg', '2025-09-07 11:31:57'),
(2, 'Lucky Family Milk Tea', 'Black tea with creamy milk and tapioca pearls.', 4.50, 'Signature', '1757244888_xq6.jpeg', '2025-09-07 11:34:48'),
(3, 'Brown Sugar Boba', 'Brown sugar syrup with fresh milk and pearls.', 5.50, 'Signature', '1757244998_Brown-sugar-boba-6.jpg', '2025-09-07 11:36:38'),
(4, 'Taro Milk Tea', 'Sweet taro flavor with chewy boba pearls.', 5.00, 'Signature', '1757245031_Homemade-Taro-Bubble-Tea.jpg', '2025-09-07 11:37:11'),
(5, 'Matcha Latte', 'Rich matcha green tea with smooth milk.', 5.50, 'Signature', '1757245060_iced-matcha-latte.jpg', '2025-09-07 11:37:40'),
(6, 'Mango & Passion Fruit Tea', 'Refreshing tea mixed with the sweetness of mango and passion fruit', 6.00, 'Refreshing', '1757245091_xq1.jpeg', '2025-09-07 11:38:11'),
(7, 'Lime Mojito', 'A daiquiri with mint over crushed ice, topped with soda.', 6.00, 'Refreshing', '1757245116_xq2.jpeg', '2025-09-07 11:38:36'),
(8, 'Strawberry Lemonade', 'Tart lemon juice is blended with ripe strawberries.\r\n\r\n', 6.00, 'Refreshing', '1757245154_strawberry-lemonade-featured.jpg', '2025-09-07 11:39:14'),
(9, 'Peach Iced Tea', 'Tart lemon juice is blended with ripe strawberries.', 6.00, 'Refreshing', '1757245187_peach-iced-tea-hero-1x1-15009_preview_maxWidth_4000_maxHeight_4000_ppi_300_quality_100-0d9f432284a447fc9151868c5acf6c7e.jpg', '2025-09-07 11:39:47'),
(10, 'Strawberry Ice Blended with Ice Cream', 'Strawberry puree blended with milk and ice-cream.', 5.80, 'Ice Blended', '1757245218_xq4.jpeg', '2025-09-07 11:40:18'),
(11, 'Taro Ice Blended with Pudding', 'Chocolate milk blended with ice and topped with cream.', 6.00, 'Ice Blended', '1757245239_xq5.jpeg', '2025-09-07 11:40:39'),
(12, 'Chocolate Ice Blended with Ice cream', 'Chocolate milk blended with ice and topped with cream.', 6.00, 'Ice Blended', '1757245273_product_pure_chocolate_ice_blended_530x430_ce78d130-e76d-4daa-8806-24f4a6f25673.webp', '2025-09-07 11:41:13'),
(13, 'Mango Ice Blended with Ice Cream', 'Mango milk blended with ice and topped with cream.', 10.00, 'Ice Blended', '1757245298_images.jpeg', '2025-09-07 11:41:38'),
(15, 'Jasmine Tea', 'Fragrant, scented tea crafted by infusing the delicate aroma of jasmine blossoms into green, white, or black tea leaves.', 5.00, 'Refreshing', '1758464041_iced-jasmine-tea_1339-82906.jpg', '2025-09-21 14:14:01'),
(20, 'Lemon Tea', 'A refreshing blend of zesty lemon and fine tea, served hot or iced, with a perfect balance of tang and sweetness', 4.00, 'Refreshing', '1761524801_lemontea.jpg', '2025-10-27 00:26:41');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `product_names` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `total`, `status`, `product_names`) VALUES
(5, '2025-08-23 11:54:11', 15.00, 'Pending', NULL),
(6, '2025-08-23 11:57:02', 11.00, 'Pending', NULL),
(7, '2025-08-23 12:06:00', 15.00, 'Pending', NULL),
(8, '2025-08-23 12:19:01', 10.50, 'Pending', NULL),
(9, '2025-08-23 12:21:06', 15.00, 'Pending', NULL),
(10, '2025-08-23 12:22:41', 15.00, 'Pending', NULL),
(11, '2025-08-24 06:18:02', 10.50, 'Pending', NULL),
(12, '2025-08-24 06:18:02', 10.50, 'Pending', NULL),
(13, '2025-08-24 06:18:02', 10.50, 'Pending', NULL),
(14, '2025-08-24 06:18:53', 10.50, 'Pending', NULL),
(15, '2025-08-24 06:19:03', 21.50, 'Pending', NULL),
(16, '2025-08-24 06:19:40', 15.50, 'Pending', NULL),
(17, '2025-08-24 06:20:13', 15.50, 'Pending', NULL),
(18, '2025-08-24 08:03:15', 10.00, 'Pending', NULL),
(19, '2025-08-24 14:13:02', 10.50, 'Cancelled', NULL),
(20, '2025-09-28 17:18:53', 6.00, 'Completed', NULL),
(21, '2025-09-28 17:31:20', 5.00, 'Completed', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
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
-- Dumping data for table `order_items`
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
(43, 20, 'Taro Ice Blended with Pudding', 6.00, 1, ''),
(44, 21, 'Jasmine Tea', 5.00, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT 'default.png',
  `otp` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `date_registered`, `profile_image`, `otp`) VALUES
(3, 'LAU', 'lauyijie@gmail.com', '$2y$10$scJVpajRQVbsM.3/WpC5iONF7k1YdfesiNUVz1rme.2FmN70we2Le', 'admin', '2025-08-24 14:39:03', '1757764207_photo_6284917887705659633_y (2).jpg', NULL),
(4, 'LIO', 'lio@gmail.com', '$2y$10$oADEMtwkYZ.6r6bEQp6YU.OUqVbI6Um91GrlfHKGAnguF.DsQoHPC', 'staff', '2025-08-25 00:30:03', '1756825428_photo_2024-10-17_15-34-50.jpg', NULL),
(8, 'TYH', 'tayyuheng50@gmail.com', '$2y$10$qAvVhYxM0.ZGSxqpr4JHpOLZ.dkL/5llopVUfo92j1Dpod6DiQeAC', 'admin', '2025-08-30 14:26:05', '1761398150_Tay Yu Heng1.jpg', NULL),
(15, 'NG ZONG HENG', 'ngzongheng@gmail.com', '$2y$10$o8JLR4ZYePsAjw9gCDzH7ODMUZTjzrUO5yAfjSInM.5.9Br9b.tpm', 'staff', '2025-10-25 13:30:21', 'default.png', NULL),
(20, 'LIO SEAN GUAN', 'longqifgf@gmail.com', '$2y$10$oW.i8i.bnkFPcicmDR.NlO3xXjSD6hZLhjncfSH690uBJa/rrBETi', 'staff', '2025-10-26 15:41:26', 'default.png', NULL),
(25, 'TAY YU HENG', 'bryantayyh@gmail.com', '$2y$10$Q4wWEg1i5eb3x368S2gu2OtFdJcFQJ2968RS3k5BzGfJcxq43VL3O', 'admin', '2025-10-26 15:59:49', 'default.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
