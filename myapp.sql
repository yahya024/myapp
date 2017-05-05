-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2017 at 06:42 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `active`) VALUES
(1, 'خدمات', 'تبادل الخدمات بين الاعضاء', 1),
(2, 'البرمجة', 'كل ما يتعلق بالبرمجة', 1),
(3, 'العاب', 'كل ما يخص الالعاب', 1),
(4, 'طلب مساعدة', 'ضع طلبك في هذا القسم', 1),
(5, 'نقاشات', 'اكتب ماتريد مناقشته مع الاعضاء', 1),
(6, 'الكتب', 'قسم خاص بالكتب', 1),
(7, 'تطبيتات وبرامج', 'انشر اعمالك', 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `categories_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_name`, `description`, `image`, `status`, `date`, `categories_id`, `user_id`) VALUES
(105, 'html', 'html', '1_1_1493920882.png', 1, '2017-05-04 18:01:49', 2, 1),
(106, ' php', ' php', '1_1_1493926501.png', 1, '2017-05-04 19:35:20', 2, 1),
(108, 'javaScript', 'javaScript', '20_1_1493926328.png', 1, '2017-05-04 19:32:55', 2, 20),
(109, 'كتاب تعلم البرمجة', 'كتاب تعلم البرمجة', '20_1_1493926399.png', 1, '2017-05-04 19:34:17', 6, 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_group` int(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `user_group`, `status`, `date`) VALUES
(1, 'yahya-dz', 'yahya', 'yahya024@gmail.com', '83c22f5176027cd417cd0dd3b0ed0c6913da0068', 1, 1, '2017-04-15'),
(20, 'karim', 'karim', 'karim2@gmail.com', '83c22f5176027cd417cd0dd3b0ed0c6913da0068', 0, 1, '2017-04-30'),
(21, 'hello', 'bensedira', 'bensedira@gmail.com', '83c22f5176027cd417cd0dd3b0ed0c6913da0068', 0, 1, '2017-04-30'),
(22, 'hello', 'hello', 'yahyaclass@gmail.com', 'aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d', 0, 0, '0000-00-00'),
(26, 'hello', 'class tabesls', 'yahyfaclass@gmail.com', 'aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d', 0, 0, '0000-00-00'),
(36, 'class', 'aasaaclass tabsdesls', 'ydsahsyfacdlass@gmail.com', 'aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d', 0, 0, '0000-00-00'),
(40, 'class', 'aasasaclass tabsdesls', 'ydsashsyfacdlass@gmail.com', 'aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d', 0, 0, '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_id` (`categories_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
