-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2024 at 05:13 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bart_gain`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `fav_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_random_id` varchar(255) DEFAULT NULL,
  `item_user_id` int(11) DEFAULT NULL,
  `item_title` varchar(255) NOT NULL,
  `item_url_picture` varchar(255) DEFAULT NULL,
  `item_category` varchar(100) DEFAULT NULL,
  `item_swap_option` enum('Swap','Donation') NOT NULL,
  `item_condition` varchar(50) DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `item_address_posted` varchar(255) DEFAULT NULL,
  `item_preferred_meet_up` varchar(255) DEFAULT NULL,
  `item_status` varchar(50) DEFAULT 'available',
  `item_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_random_id`, `item_user_id`, `item_title`, `item_url_picture`, `item_category`, `item_swap_option`, `item_condition`, `item_description`, `item_address_posted`, `item_preferred_meet_up`, `item_status`, `item_created_at`) VALUES
(7, 'cVaEu6M6xYASAhA2xTScSPZ0Kxdcbbdvpp7Ypt23ieKzraYdzq', 31, 'Voluptas dolore est ', 'Screenshot (1).png', 'Electronics', 'Swap', 'Good', 'Et eaque est est au', NULL, 'Voluptatem enim aliq', 'available', '2024-06-15 00:24:48'),
(8, 'xrzWDpg1uC9e8CADHiDNdnMJxAkyZRGZVR8LDW0ST8Z6gZRxgH', 31, 'Adipisci iure accusa', '666ce0ff41b04_Screenshot (2).png', 'Electronics', 'Swap', 'Good', 'Ipsam velit sit la', NULL, 'Minus nihil quod qui', 'available', '2024-06-15 00:31:59'),
(9, 'SupRUv5SDYJ6N0tabahdxhN10QStMhiNaEa9p3iMTQkEfcihmQ', 31, 'Consequatur minim mo', '666ce4be1e16c_Screenshot (3).png', 'Electronics', 'Swap', 'Fair', 'Obcaecati molestiae ', NULL, 'Et quidem duis lorem', 'available', '2024-06-15 00:47:58'),
(10, '2WjB2HT9w0YFzZWRpGbxj3TpG1UW2rQHjAHcyBMGgCbBv74Txr', 31, 'Quaerat id pariatur', '666ce4c81e59c_Screenshot (5).png', 'Electronics', 'Swap', 'Good', 'Facilis et consequun', NULL, 'Nostrud distinctio ', 'available', '2024-06-15 00:48:08'),
(11, 'yvjxSYh4W1QAGBSrpyJxdgNziUB5rJkBjeNKtHxnXYVvXqLVk0', 31, 'Eos itaque voluptat', '666ce4d47bc3c_Screenshot (8).png', 'Electronics', 'Swap', 'Good', 'Repellendus Dolor m', NULL, 'Corrupti quia id s', 'available', '2024-06-15 00:48:20'),
(12, 'uf21E3YxQXgjfQnRPkM3hE8QXaiMPaYHRu4iP1CgGNqLpa6x54', 31, 'Nostrud atque ullamc', '666ce6241ecd3_Screenshot (14).png', 'Electronics', 'Swap', 'Fair', 'Ea ut omnis qui et', NULL, 'Ea ut tenetur conseq', 'available', '2024-06-15 00:53:56'),
(13, 'PiMJMBKWUT53cQn8pywyB2fT9kJ9XDB6XcbTZZdcApVLj3uwRg', 31, 'Ipsam est voluptatem', '666ce62f26c11_Screenshot (7).png', 'Electronics', 'Swap', 'Fair', 'Nemo laudantium aut', NULL, 'Aliqua Quasi pariat', 'available', '2024-06-15 00:54:07'),
(14, 'e2MC9VzXNf8d7b6eBAhSUccMXDjmy2eySbfqpJMSTS7F0AqWJx', 31, 'Reprehenderit nobis', '666ce6371b35a_Screenshot (9).png', 'Electronics', 'Swap', 'Good', 'Fugiat consequat L', NULL, 'Repellendus Rerum e', 'available', '2024-06-15 00:54:15'),
(15, 'JgbzUJ0frEybZ6KbqmR0TkKmZNGQkQefzPPHukMf8WE6Lym1Kj', 31, 'Elit similique aut ', '666ce6405916e_Screenshot (22).png', 'Electronics', 'Swap', 'Fair', 'Deserunt quis ipsum ', NULL, 'Neque itaque nisi mo', 'available', '2024-06-15 00:54:24'),
(16, '9YCPq9cpvRcYK6enDDKV5iMxWyxz5kSfd33GNx0k025E0ZEPcN', 31, 'Qui beatae incididun', '666ce648096eb_Screenshot (16).png', 'Electronics', 'Swap', 'Fair', 'Eu in repudiandae so', NULL, 'Cum sint molestias ', 'available', '2024-06-15 00:54:32'),
(17, 'LU2HyTbSWCaUJky04MLLGMyxkjyjGgSXEynmLHuKT4RqZg06tL', 31, 'Adipisci iusto qui l', '666ce6578bdf1_Screenshot (5).png', 'Electronics', 'Swap', 'Fair', 'Non voluptatem lorem', NULL, 'Est dolore et conseq', 'available', '2024-06-15 00:54:47'),
(18, 'VdQJaDP771CbqiYtYwwAQM8udbTNytJPvLayqPYKybyWjk1FS5', 31, 'Harum est harum obc', '666ce67c46e69_Screenshot (1).png', 'Electronics', 'Swap', 'Good', 'Consequatur consequ', NULL, 'Rem accusantium exce', 'available', '2024-06-15 00:55:24'),
(19, '4bbdfNi5QQrTY14AQ8LjVqu4YNwqczJ3fASL1eu3AqXhzDXGPu', 31, 'Cupidatat doloribus ', '666ce67f2aa5b_Screenshot (1).png', 'Electronics', 'Swap', 'Fair', 'Amet sit placeat u', NULL, 'Rem aut exercitation', 'available', '2024-06-15 00:55:27'),
(20, 'NSmuZn783bCwuPLKGJ0C53iyi4i3d5cdYTxq98e3y8nT6wJad8', 31, 'Sunt sunt officia qu', '666cee31aa109_Screenshot (1).png', 'Electronics', 'Swap', 'Good', 'Voluptate laborum in', NULL, 'Qui enim possimus i', 'available', '2024-06-15 01:28:17'),
(21, 'TxukWW2jZF2vnfDSVay6MhLPYJ1BqbPSvzyXySA0qZiXcv457X', 32, 'Quisquam ad pariatur', '666cf1cd3af7a_Screenshot (2).png', 'Electronics', 'Swap', 'Fair', 'Commodo rerum harum ', NULL, 'Omnis molestiae expe', 'available', '2024-06-15 01:43:41'),
(22, 'LrN1ZEneHNU25y5HevWkHpVUu62BUJj8DDAct3kPK9RZUULjZB', 32, 'Nulla neque amet ha', '666cf1d582fe2_Screenshot (42).png', 'Electronics', 'Swap', 'Good', 'Omnis optio quia ea', NULL, 'Nam consequatur Err', 'available', '2024-06-15 01:43:49'),
(23, 'zTuwU92Y83AVhUyhwZ6F5NWVU19FQyi22mLGRKbmvS78T0Et79', 31, 'Laptop', '666d0916829b7_laptop.jpg', 'Electronics', 'Swap', 'Good', 'Maganda ang Laptop', NULL, 'Walter Mart Dasma', 'available', '2024-06-15 03:23:02'),
(24, 'ePaBSbbrac6JqHPn0S8SDgBm5d4gKAujeS6mCmWV4KVChcXTmK', 33, 'Iphone malake', '666d3d4504cd5_iphone.jpg', 'Electronics', 'Swap', 'Good', 'Ang Iphone ay malaki', NULL, 'Gentri', 'available', '2024-06-15 07:05:41'),
(25, 'cwYVKk9HAHXFLrcDWTJGkwWwa3anbigVygGyL8LRhe0tEWidXJ', 33, 'Aut harum omnis dict', '666d4433933bc_iphone.jpg', 'Electronics', 'Swap', 'Good', 'Nihil est est delec', NULL, 'Non accusamus nostru', 'available', '2024-06-15 07:35:15'),
(29, 'YRf4R9PSjQh86dxrZM7H6WreDKEYYhZcB80umbPKGGpMj87Dux', 31, 'Relief Goods', 'img_6675f49b3c18b7.87922169.jpg', 'Foods', 'Donation', 'Very Good', 'Free Relief goods', NULL, 'Walter Mart Dasma', 'completed', '2024-06-21 21:46:03'),
(30, NULL, 31, 'Test new Dumbell', 'img_667cd807344b37.42122465.jpg', 'Appliance', 'Swap', 'Good', 'very new', NULL, 'NCST', 'available', '2024-06-27 03:09:59'),
(31, NULL, 31, 'Upuan', 'img_667e63fc53c216.57209598.jpg', 'Furniture', 'Swap', 'Good', 'Vintage Upuan', NULL, 'Walter Mart Dasma', 'completed', '2024-06-28 07:19:24'),
(32, NULL, 37, 'Jacket', 'img_667f2f5294dc97.61551084.jpg', 'Clothing and Accessories', 'Swap', 'Good', 'Penshopee Jacket. Only used twice', NULL, '', 'available', '2024-06-28 21:46:58'),
(33, NULL, 50, 'Red', 'img_667f6feb18bea0.09260666.jpg', 'Clothing and Accessories', 'Swap', 'Good', 'damit na pula', NULL, 'Nisi qui voluptas au', 'completed', '2024-06-29 02:22:35'),
(34, NULL, 50, 'A voluptatem qui tem', 'img_667f74cd0f1088.20127330.jpg', 'Electronics', 'Donation', 'Very Bad', 'Suscipit aperiam vel', NULL, 'Enim dignissimos cul', 'available', '2024-06-29 02:43:25');

-- --------------------------------------------------------

--
-- Table structure for table `meet_up`
--

CREATE TABLE `meet_up` (
  `meet_up_id` int(11) NOT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `meet_up_place` varchar(255) DEFAULT NULL,
  `meet_up_date` datetime DEFAULT NULL,
  `meet_up_lng` double DEFAULT NULL,
  `meet_up_lat` double DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `qrCode` varchar(100) DEFAULT NULL,
  `sender_rating` int(11) DEFAULT NULL,
  `sender_rating_message` text DEFAULT NULL,
  `receiver_rating` int(11) DEFAULT NULL,
  `receiver_rating_message` text DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `meet_up_status` varchar(50) DEFAULT 'on-going',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meet_up`
--

INSERT INTO `meet_up` (`meet_up_id`, `offer_id`, `meet_up_place`, `meet_up_date`, `meet_up_lng`, `meet_up_lat`, `sender_id`, `qrCode`, `sender_rating`, `sender_rating_message`, `receiver_rating`, `receiver_rating_message`, `receiver_id`, `meet_up_status`, `created_at`) VALUES
(3, 17, 'SM City Dasmariñas, Aguinaldo Highway, Sampaloc 1, Sampaloc, Dasmariñas, Cavite, Calabarzon, 4114, Philippines', '2024-07-13 07:47:00', 120.95676065965, 14.3018481, 37, 'nLdTH5lfIs', NULL, NULL, NULL, NULL, 31, 'completed', '2024-06-28 01:17:17'),
(7, 7, 'Nam consequatur Err', NULL, NULL, NULL, 31, '6mGNhHgPFG', NULL, NULL, NULL, NULL, 32, 'on-going', '2024-06-28 06:41:16'),
(8, 18, 'SM City Dasmariñas, Main Roadway, Sampaloc 1, Sampaloc, Dasmariñas, Cavite, Calabarzon, 4114, Philippines', '2024-07-13 14:36:00', 120.95676065965, 14.3018481, 31, 'wRmmWslg0U', NULL, NULL, NULL, NULL, 33, 'on-going', '2024-06-28 06:45:40'),
(9, 19, 'NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines', '2024-07-05 15:27:00', 120.940092, 14.3278906, 37, 'ZWnIq6aWBC', NULL, NULL, NULL, NULL, 31, 'completed', '2024-06-28 07:28:21'),
(10, 20, 'NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines', '2024-07-06 10:28:00', 120.940092, 14.3278906, 52, 'yWF2U77nkp', NULL, NULL, NULL, NULL, 50, 'completed', '2024-06-29 02:31:49'),
(11, 21, NULL, NULL, NULL, NULL, 31, 'RHA3uUoASg', NULL, NULL, NULL, NULL, 50, 'on-going', '2024-06-29 02:32:11');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `sender_user_id` int(11) DEFAULT NULL,
  `receiver_user_id` int(11) DEFAULT NULL,
  `sender_message` text DEFAULT NULL,
  `sender_picture_url` varchar(255) DEFAULT NULL,
  `receiver_message` text DEFAULT NULL,
  `receiver_picture_url` varchar(255) DEFAULT NULL,
  `message_type` varchar(100) NOT NULL DEFAULT 'normal',
  `message_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `offer_id`, `sender_user_id`, `receiver_user_id`, `sender_message`, `sender_picture_url`, `receiver_message`, `receiver_picture_url`, `message_type`, `message_created_at`) VALUES
(1, 6, 31, 33, 'Velit eos esse ex', NULL, NULL, NULL, 'title', '2024-06-21 22:28:03'),
(2, 7, 31, 32, 'Sint officia veniam', NULL, NULL, NULL, 'title', '2024-06-21 23:10:14'),
(3, 8, 33, 31, 'Eius incididunt in e', NULL, NULL, NULL, 'title', '2024-06-22 00:10:23'),
(4, 9, 37, 33, 'Quae veniam cillum ', NULL, NULL, NULL, 'title', '2024-06-22 06:37:25'),
(5, 10, 37, 31, 'Ex dignissimos offic', NULL, NULL, NULL, 'title', '2024-06-22 08:59:33'),
(7, 12, 37, 31, 'Test dumbbell', NULL, NULL, NULL, 'title', '2024-06-25 11:53:17'),
(8, 13, 32, 31, 'Test my Ryan', NULL, NULL, NULL, 'title', '2024-06-25 21:32:12'),
(9, 14, 32, 31, 'test dumbbell Ryan', NULL, NULL, NULL, 'title', '2024-06-25 22:06:44'),
(12, 17, 37, 31, 'Distinctio Ut sint ', NULL, NULL, NULL, 'title', '2024-06-26 23:54:10'),
(13, 18, 31, 33, 'Ullam rerum cupidata', NULL, NULL, NULL, 'title', '2024-06-27 00:30:29'),
(14, 17, 37, 31, NULL, NULL, 'test', NULL, 'normal', '2024-06-27 02:52:21'),
(15, 17, 37, 31, NULL, NULL, 'perkpek', NULL, 'normal', '2024-06-27 02:54:35'),
(16, 14, 32, 31, NULL, NULL, 'sige po kuya', NULL, 'normal', '2024-06-27 02:54:47'),
(17, 17, 37, 31, NULL, NULL, 'Try ko ulit', NULL, 'normal', '2024-06-27 03:27:01'),
(18, 13, 32, 31, NULL, NULL, 'Off na po', NULL, 'normal', '2024-06-27 03:27:28'),
(19, 17, 37, 31, NULL, NULL, 'hello world', NULL, 'normal', '2024-06-27 10:55:04'),
(20, 17, 37, 31, NULL, NULL, NULL, NULL, 'normal', '2024-06-27 13:37:03'),
(21, 17, 37, 31, NULL, NULL, NULL, NULL, 'normal', '2024-06-27 13:37:26'),
(22, 17, 37, 31, NULL, NULL, NULL, NULL, 'normal', '2024-06-27 13:37:28'),
(23, 17, 37, 31, 'try', NULL, NULL, NULL, 'normal', '2024-06-27 13:38:03'),
(24, 17, 37, 31, 'test my propsal', NULL, NULL, NULL, 'normal', '2024-06-27 13:38:10'),
(25, 17, 37, 31, 'sige po kuya', NULL, NULL, NULL, 'normal', '2024-06-27 13:56:47'),
(26, 17, 37, 31, 'hello world', NULL, NULL, NULL, 'normal', '2024-06-27 14:55:30'),
(27, 17, 37, 31, NULL, NULL, 'test', NULL, 'normal', '2024-06-27 16:04:51'),
(28, 18, 31, 33, 'test', NULL, NULL, NULL, 'normal', '2024-06-27 16:11:42'),
(29, 17, 37, 31, NULL, NULL, 'Sa islang pantropiko', NULL, 'normal', '2024-06-27 16:38:21'),
(30, 17, 37, 31, NULL, NULL, 'Meet up: SM City Dasmariñas, Main Roadway, Sampaloc 1, Sampaloc, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-06-29T08:19', NULL, 'update', '2024-06-27 22:19:20'),
(31, 17, 37, 31, NULL, NULL, 'Meet up: NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-06-29T06:24', NULL, 'update', '2024-06-27 22:24:26'),
(32, 17, 37, 31, NULL, NULL, 'Meet up: Imus, Cavite, Calabarzon, 4103, Philippines, date and time: 2024-06-29T07:40', NULL, 'update', '2024-06-27 23:40:26'),
(33, 17, 37, 31, NULL, NULL, 'Meet up: NCST Campus Road, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-06-29T07:43', NULL, 'update', '2024-06-27 23:43:26'),
(34, 17, 37, 31, 'Meet up: Imus, Cavite, Calabarzon, 4103, Philippines, date and time: 2024-06-30T07:47', NULL, NULL, NULL, 'update', '2024-06-27 23:47:12'),
(35, 17, 37, 31, 'Meet up: NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-07-01T07:47', NULL, NULL, NULL, 'update', '2024-06-28 00:02:04'),
(36, 17, 37, 31, NULL, NULL, 'test', NULL, 'normal', '2024-06-28 00:05:48'),
(37, 17, 37, 31, 'Meet up: Imus, Cavite, Calabarzon, 4103, Philippines, date and time: 2024-07-01T07:47', NULL, NULL, NULL, 'update', '2024-06-28 00:11:58'),
(38, 17, 37, 31, NULL, NULL, 'Meet up: NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-06-12T07:47', NULL, 'update', '2024-06-28 00:12:35'),
(39, 17, 37, 31, NULL, NULL, 'Meet up: SM City Dasmariñas, Aguinaldo Highway, Sampaloc 1, Sampaloc, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-07-13T07:47', NULL, 'update', '2024-06-28 00:14:27'),
(40, 17, 37, 31, 'test', NULL, NULL, NULL, 'normal', '2024-06-28 00:20:45'),
(41, 18, 31, 33, 'Meet up: , date and time: 2024-07-13T14:36', NULL, NULL, NULL, 'update', '2024-06-28 06:38:19'),
(42, 18, 31, 33, 'Meet up: SM City Dasmariñas, Main Roadway, Sampaloc 1, Sampaloc, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-07-13T14:36', NULL, NULL, NULL, 'update', '2024-06-28 06:38:46'),
(43, 19, 37, 31, 'Lamesa', NULL, NULL, NULL, 'title', '2024-06-28 07:21:52'),
(44, 19, 37, 31, NULL, NULL, 'Meet up: NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: ', NULL, 'update', '2024-06-28 07:26:30'),
(45, 19, 37, 31, 'sige po', NULL, NULL, NULL, 'normal', '2024-06-28 07:26:42'),
(46, 19, 37, 31, 'palitan ko po yung date', NULL, NULL, NULL, 'normal', '2024-06-28 07:27:37'),
(47, 19, 37, 31, '', NULL, NULL, NULL, 'normal', '2024-06-28 07:28:04'),
(48, 19, 37, 31, '', NULL, NULL, NULL, 'normal', '2024-06-28 07:28:05'),
(49, 19, 37, 31, 'Meet up: NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-07-05T15:27', NULL, NULL, NULL, 'update', '2024-06-28 07:28:07'),
(50, 19, 37, 31, NULL, NULL, 'okay', NULL, 'normal', '2024-06-28 07:28:15'),
(51, 19, 37, 31, NULL, NULL, 'accept ko na', NULL, 'normal', '2024-06-28 07:28:18'),
(52, 14, 32, 31, NULL, NULL, 'test', NULL, 'normal', '2024-06-29 02:05:49'),
(53, 20, 52, 50, 'Jacket', NULL, NULL, NULL, 'title', '2024-06-29 02:27:10'),
(54, 20, 52, 50, NULL, NULL, 'ang panget', NULL, 'normal', '2024-06-29 02:27:37'),
(55, 20, 52, 50, 'lol', NULL, NULL, NULL, 'normal', '2024-06-29 02:27:50'),
(56, 20, 52, 50, NULL, NULL, 'Meet up: NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines, date and time: 2024-07-06T10:28', NULL, 'update', '2024-06-29 02:28:32'),
(57, 21, 31, 50, 'Monkey', NULL, NULL, NULL, 'title', '2024-06-29 02:31:32');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `offer_title` varchar(255) NOT NULL,
  `offer_url_picture` varchar(255) DEFAULT NULL,
  `offer_category` varchar(100) DEFAULT NULL,
  `offer_item_condition` varchar(50) DEFAULT NULL,
  `offer_description` text DEFAULT NULL,
  `address_posted` varchar(255) DEFAULT NULL,
  `r_receiver_id` int(11) DEFAULT NULL,
  `r_title` varchar(255) DEFAULT NULL,
  `r_url_picture` varchar(255) DEFAULT NULL,
  `r_category` varchar(100) DEFAULT NULL,
  `r_item_condition` varchar(50) DEFAULT NULL,
  `r_description` text DEFAULT NULL,
  `r_address_posted` varchar(255) DEFAULT NULL,
  `offer_meet_up_place` varchar(255) DEFAULT NULL,
  `offer_lng` double DEFAULT NULL,
  `offer_lat` double DEFAULT NULL,
  `offer_date_time_meet` datetime DEFAULT NULL,
  `s_agreement` int(11) DEFAULT NULL,
  `r_agreement` int(11) DEFAULT NULL,
  `offer_status` varchar(50) DEFAULT 'pending',
  `offer_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `offer_updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`offer_id`, `item_id`, `sender_id`, `offer_title`, `offer_url_picture`, `offer_category`, `offer_item_condition`, `offer_description`, `address_posted`, `r_receiver_id`, `r_title`, `r_url_picture`, `r_category`, `r_item_condition`, `r_description`, `r_address_posted`, `offer_meet_up_place`, `offer_lng`, `offer_lat`, `offer_date_time_meet`, `s_agreement`, `r_agreement`, `offer_status`, `offer_created_at`, `offer_updated_at`) VALUES
(5, 25, 31, 'Sed eligendi delenit', '6675f7ddd8bc5-sword.jpg', 'Furniture', 'Good', 'Reiciendis cupiditat', NULL, 33, 'Aut harum omnis dict', '666d4433933bc_iphone.jpg', 'Electronics', 'Good', 'Nihil est est delec', NULL, 'Non accusamus nostru', NULL, NULL, NULL, NULL, NULL, 'pending', '2024-06-21 21:59:57', '2024-06-21 21:59:57'),
(6, 25, 31, 'Velit eos esse ex', '6675fe7375893-sword.jpg', 'Furniture', 'Fair', 'Nisi est laboriosam', NULL, 33, 'Aut harum omnis dict', '666d4433933bc_iphone.jpg', 'Electronics', 'Good', 'Nihil est est delec', NULL, 'Non accusamus nostru', NULL, NULL, NULL, NULL, NULL, 'pending', '2024-06-21 22:28:03', '2024-06-21 22:28:03'),
(7, 22, 31, 'Sint officia veniam', '6676085659448-reliefgoods.jpg', 'Toys and Games', 'Very Bad', 'Irure ad et nesciunt', NULL, 32, 'Nulla neque amet ha', '666cf1d582fe2_Screenshot (42).png', 'Electronics', 'Good', 'Omnis optio quia ea', NULL, 'Nam consequatur Err', NULL, NULL, NULL, NULL, NULL, 'accepted', '2024-06-21 23:10:14', '2024-06-21 23:10:14'),
(8, 29, 33, 'Eius incididunt in e', '6676166f24950-laptop.jpg', 'Electronics', 'Bad', 'Veritatis do volupta', NULL, 31, 'Relief Goods', 'img_6675f49b3c18b7.87922169.jpg', 'Foods', 'Very Good', 'Free Relief goods', NULL, 'Walter Mart Dasma', NULL, NULL, NULL, NULL, NULL, 'pending', '2024-06-22 00:10:23', '2024-06-22 00:10:23'),
(9, 25, 37, 'Quae veniam cillum ', '66767125e74b4-reliefgoods.jpg', 'Furniture', 'Very Good', 'Sed eligendi sed ill', NULL, 33, 'Aut harum omnis dict', '666d4433933bc_iphone.jpg', 'Electronics', 'Good', 'Nihil est est delec', NULL, 'Non accusamus nostru', NULL, NULL, NULL, NULL, NULL, 'pending', '2024-06-22 06:37:25', '2024-06-22 06:37:25'),
(10, 29, 37, 'Ex dignissimos offic', '667692759b7df-sword.jpg', 'Foods', 'Good', 'Anim adipisci labori', NULL, 31, 'Relief Goods', 'img_6675f49b3c18b7.87922169.jpg', 'Foods', 'Very Good', 'Free Relief goods', NULL, 'Walter Mart Dasma', NULL, NULL, NULL, NULL, NULL, 'pending', '2024-06-22 08:59:33', '2024-06-22 08:59:33'),
(12, 29, 37, 'Test dumbbell', '667aafad3c3a5-dumbell.jpg', 'Electronics', 'Good', 'Test dumbbell description', NULL, 31, 'Relief Goods', 'img_6675f49b3c18b7.87922169.jpg', 'Foods', 'Very Good', 'Free Relief goods', NULL, 'Walter Mart Dasma', NULL, NULL, NULL, NULL, NULL, 'pending', '2024-06-25 11:53:17', '2024-06-25 11:53:17'),
(13, 23, 32, 'Test my Ryan', '667b375cc4e3d-iphone.jpg', 'Electronics', 'Fair', 'Kinda new but goods', NULL, 31, 'Laptop', '666d0916829b7_laptop.jpg', 'Electronics', 'Good', 'Maganda ang Laptop', NULL, 'Walter Mart Dasma', NULL, NULL, NULL, NULL, NULL, 'pending', '2024-06-25 21:32:12', '2024-06-25 21:32:12'),
(14, 29, 32, 'test dumbbell Ryan', '667b3f74a52eb-dumbell.jpg', 'Electronics', 'Good', 'test dumbell', NULL, 31, 'Relief Goods', 'img_6675f49b3c18b7.87922169.jpg', 'Foods', 'Very Good', 'Free Relief goods', NULL, 'Walter Mart Dasma', NULL, NULL, NULL, NULL, NULL, 'pending', '2024-06-25 22:06:44', '2024-06-25 22:06:44'),
(17, 29, 37, 'Distinctio Ut sint ', '667caa22d4add-dumbell.jpg', 'Clothing and Accessories', 'Very Bad', 'Voluptatem enim mag', NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL, 'SM City Dasmariñas, Aguinaldo Highway, Sampaloc 1, Sampaloc, Dasmariñas, Cavite, Calabarzon, 4114, Philippines', 120.95676065965102, 14.3018481, '2024-07-13 07:47:00', NULL, NULL, 'accepted', '2024-06-26 23:54:10', '2024-06-26 23:54:10'),
(18, 25, 31, 'Ullam rerum cupidata', '667cb2a52ef96-dumbell.jpg', 'Foods', 'Fair', 'Fugit laboriosam d', NULL, 33, NULL, NULL, NULL, NULL, NULL, NULL, 'SM City Dasmariñas, Main Roadway, Sampaloc 1, Sampaloc, Dasmariñas, Cavite, Calabarzon, 4114, Philippines', 120.95676065965102, 14.3018481, '2024-07-13 14:36:00', NULL, NULL, 'accepted', '2024-06-27 00:30:29', '2024-06-27 00:30:29'),
(19, 31, 37, 'Lamesa', '667e6490242bf-lamesa.png', 'Furniture', 'Good', 'Vintage Lamesa rin', NULL, 31, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines', 120.940092, 14.3278906, '2024-07-05 15:27:00', NULL, NULL, 'accepted', '2024-06-28 07:21:52', '2024-06-28 07:21:52'),
(20, 33, 52, 'Jacket', '667f70fed1645-jacket.jpg', 'Toys and Games', 'Good', 'pink na jacket', NULL, 50, NULL, NULL, NULL, NULL, NULL, NULL, 'NCST, Aguinaldo Highway, Zone 4, Poblacion, Dasmariñas, Cavite, Calabarzon, 4114, Philippines', 120.940092, 14.3278906, '2024-07-06 10:28:00', NULL, NULL, 'accepted', '2024-06-29 02:27:10', '2024-06-29 02:27:10'),
(21, 33, 31, 'Monkey', '667f7204dcdb0-IMG_20240613_121325.jpg', 'Electronics', 'Very Good', 'Fully functional', NULL, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'accepted', '2024-06-29 02:31:32', '2024-06-29 02:31:32');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rate_id` int(11) NOT NULL,
  `meet_up_id` int(11) DEFAULT NULL,
  `rate_your_id` int(11) DEFAULT NULL,
  `rate_partner_id` int(11) DEFAULT NULL,
  `rate_ratings` int(11) DEFAULT NULL,
  `rate_feedback` text DEFAULT NULL,
  `rate_status` varchar(100) DEFAULT NULL,
  `rate_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rate_id`, `meet_up_id`, `rate_your_id`, `rate_partner_id`, `rate_ratings`, `rate_feedback`, `rate_status`, `rate_created_at`) VALUES
(1, 9, 37, 31, NULL, NULL, 'pending', '2024-06-29 00:21:22'),
(2, 9, 31, 37, NULL, NULL, 'pending', '2024-06-29 00:21:22'),
(3, 3, 37, 31, NULL, NULL, 'pending', '2024-06-29 00:29:57'),
(4, 3, 31, 37, NULL, NULL, 'pending', '2024-06-29 00:29:57'),
(5, 3, 37, 31, NULL, NULL, 'pending', '2024-06-29 00:29:57'),
(6, 3, 31, 37, NULL, NULL, 'pending', '2024-06-29 00:29:57'),
(7, 10, 52, 50, NULL, NULL, 'pending', '2024-06-29 02:40:08'),
(8, 10, 50, 52, NULL, NULL, 'pending', '2024-06-29 02:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `verified` enum('Y','N') NOT NULL DEFAULT 'N',
  `user_rating` int(11) DEFAULT NULL,
  `user_rate_count` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `fullname`, `birth_date`, `age`, `profile_picture`, `email`, `password`, `address`, `lng`, `lat`, `verified`, `user_rating`, `user_rate_count`, `created_at`, `updated_at`) VALUES
(31, 1, 'Kane Gerickson Tagay', NULL, NULL, NULL, 'kanetagay5@gmail.com', '$2y$10$1ny7YWI2dJvoClSA7edsFujRDI8VyGhKnmpfOsno8W3kizHBr6SsS', 'General Trias, Cavite', 120.91392, 14.352384, 'Y', NULL, NULL, '2024-06-14 13:35:24', '2024-06-30 02:26:51'),
(32, 1, 'Ryan Ballero', NULL, NULL, NULL, 'ahnyudaengz5@gmail.com', '$2y$10$KCLo3Loif2E0qrsucnD7R.MEwfeFwIysBQ67rCyvoscI6Z2YxGM.e', 'Imus, Cavite', 120.946688, 14.385152, 'Y', NULL, NULL, '2024-06-15 01:43:21', '2024-06-19 23:50:27'),
(33, 1, 'Sherwin Eguna', NULL, NULL, NULL, 'yudingahn5@gmail.com', '$2y$10$OC08Znl5n0bs7GH9zZU/J.vVXW9Ceef9q0O36JEDOr1N1CY4YqMWm', 'Dasmarinas, Cavite', 120.9404785, 14.3272554, 'Y', NULL, NULL, '2024-06-15 07:01:31', '2024-06-19 23:50:37'),
(37, 1, 'Billy Joshua Mahinay', NULL, 22, NULL, 'billy@gmail.com', '$2y$10$fxrlkr/awdoxcKAp21x08./vbSjcCk0dP9QGSP6rYJ.EKsQE1zJrW', 'General Trias, Cavite', 120.917489, 14.3341786, 'Y', NULL, NULL, '2024-06-19 23:33:21', '2024-06-29 00:26:22'),
(38, 1, 'Mori Jin Bab', NULL, 22, NULL, 'morijin0109@gmail.com', '$2y$10$ZbRO6WZq5mY./HWGwTTWLu1EX6oCl7TREOU4ElSz6CmFLT8IQiblu', 'Bacoor, Cavite', 120.9860096, 14.401536, 'Y', NULL, NULL, '2024-06-21 03:29:29', '2024-06-21 09:14:02'),
(39, 1, 'mark cuevas', NULL, NULL, NULL, 'markcuevas@gmail.com', '$2y$10$Ck1U9Rw7nC0vGzE6tHgnZOSBsWjpjy.VSPQossyvbN7brXzhQRyEi', 'Dasmarinas, Cavite', 120.9404686, 14.3272099, 'N', NULL, NULL, '2024-06-22 08:43:09', '2024-06-22 08:43:09'),
(42, 1, 'cutiepie go', NULL, 25, NULL, 'cutiepie@gmail.com', '$2y$10$4x.DqDnUObtzZ8Y9I/lAyeUjWBhIERo8ycBa1BlmPEr8RcZFH8G8W', 'Dasmarinas, Cavite', 120.9404773, 14.3272028, 'Y', NULL, NULL, '2024-06-22 08:44:01', '2024-06-22 08:54:30'),
(46, 1, 'qweqwe', NULL, 22, NULL, 'qweqwe@gmail.com', '$2y$10$7y3dFFerZSTLOcS9RRs6p.KWQDslLK/JYeHDl.KQ3RnO0AjREjE12', 'Dasmarinas, Cavite', 120.9404701, 14.3272069, 'Y', NULL, NULL, '2024-06-22 08:56:41', '2024-06-22 08:57:30'),
(47, 2, 'admin try fullname', NULL, NULL, NULL, 'tagay.kanegerickson@ncst.edu.ph', '$2y$10$NG7HO4PNieimRdPrvL8RWu0aMOBPj1MK87/XizBbX5BfVMu3J0v0u', 'General Trias, Cavite', 120.9072928, 14.342091, 'Y', NULL, NULL, '2024-06-28 01:58:39', '2024-06-29 00:17:17'),
(48, 1, 'Mark Cuevas', '2019-01-30', NULL, NULL, 'mark1@gmail.com', '$2y$10$eLYD4Zof/Q4yrAEeEx89juuR837e5vm6JC7km12KVKD99DTNmaRjO', 'General Trias, Cavite', 120.9072928, 14.342091, 'Y', NULL, NULL, '2024-06-28 22:01:22', '2024-06-28 22:27:42'),
(49, 1, 'joe', NULL, NULL, NULL, 'pogi', '$2y$10$t7J7DJRoWy44WPcVAEQpb.TCzGarKN/sNmRWeuJMnuRMLrH17ZbK2', 'Dasmarinas, Cavite', 120.9407422, 14.3272457, 'N', NULL, NULL, '2024-06-29 02:09:33', '2024-06-29 02:09:33'),
(50, 1, 'sheen', '2003-03-29', NULL, NULL, 'ballero.ryancarl@ncst.edu.ph', '$2y$10$XhH8/umxA0gCHJQrtXiLKe6cgMgdl4meLVIUMbtbqi9hM0LJTZyiW', 'Calamba, Laguna', 121.1247233, 14.1869747, 'Y', NULL, NULL, '2024-06-29 02:10:19', '2024-06-29 02:43:57'),
(51, 1, 'jie', NULL, NULL, NULL, '', '$2y$10$BMkFl4tMesIDi01UJASUA.A2aCfqF3wwNImDy3qnjC6C7VHZ2.eNC', 'Calamba, Laguna', 121.1247233, 14.1869747, 'N', NULL, NULL, '2024-06-29 02:23:40', '2024-06-29 02:23:40'),
(52, 1, 'Joey ', '2024-06-05', NULL, NULL, 'joey@gmail.com', '$2y$10$6wj6qI9ld9FoG.lF4NtpIu4O8HvsLbzZH87TU/olzC.UXzwtdJH6u', 'Calamba, Laguna', 121.1247233, 14.1869747, 'Y', NULL, NULL, '2024-06-29 02:24:01', '2024-06-29 02:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `dashboard_can_access` enum('Y','N') NOT NULL DEFAULT 'Y',
  `dashboard_can_read` enum('own','all') NOT NULL DEFAULT 'own',
  `dashboard_can_edit` enum('own','all') NOT NULL DEFAULT 'own',
  `dashboard_can_delete` enum('own','all') NOT NULL DEFAULT 'own',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`role_id`, `role_name`, `dashboard_can_access`, `dashboard_can_read`, `dashboard_can_edit`, `dashboard_can_delete`, `date_created`) VALUES
(1, 'Standard', 'Y', 'all', 'own', 'own', '2024-06-08 14:39:45'),
(2, 'Admin', 'Y', 'all', 'all', 'all', '2024-06-20 07:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

CREATE TABLE `verifications` (
  `verification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `verification_birth_date` date NOT NULL,
  `verification_age` int(11) NOT NULL,
  `id_picture_path` varchar(255) NOT NULL,
  `capture_image_path` varchar(255) NOT NULL,
  `verification_status` varchar(50) NOT NULL DEFAULT 'pending',
  `reject_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`verification_id`, `user_id`, `verification_birth_date`, `verification_age`, `id_picture_path`, `capture_image_path`, `verification_status`, `reject_reason`, `created_at`) VALUES
(495, 48, '0000-00-00', 0, 'verification-capture-image/667f33707d63b_fake id.jpg', 'capture_images/667f33707d2dd.png', 'pending', NULL, '2024-06-28 22:04:32'),
(496, 48, '2019-01-30', 0, 'verification-capture-image/667f3567a38a3_fake id.jpg', 'capture_images/667f3567a3596.png', 'accept', NULL, '2024-06-28 22:12:55'),
(497, 50, '2003-03-29', 0, 'verification-capture-image/667f6dcd91849_fake id.jpg', 'capture_images/667f6dcd9118e.png', 'accept', NULL, '2024-06-29 02:13:33'),
(498, 52, '2024-06-05', 0, 'verification-capture-image/667f70607abca_fake id.jpg', 'capture_images/667f70607a9e3.png', 'accept', NULL, '2024-06-29 02:24:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`fav_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `user_id` (`item_user_id`);

--
-- Indexes for table `meet_up`
--
ALTER TABLE `meet_up`
  ADD PRIMARY KEY (`meet_up_id`),
  ADD KEY `offer_id` (`offer_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `offer_id` (`offer_id`),
  ADD KEY `receiver_user_id` (`receiver_user_id`),
  ADD KEY `sender_user_id` (`sender_user_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `r_receiver_id` (`r_receiver_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rate_id`),
  ADD KEY `meet_up_id` (`meet_up_id`),
  ADD KEY `rate_your_id` (`rate_your_id`),
  ADD KEY `rate_partner_id` (`rate_partner_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `verifications`
--
ALTER TABLE `verifications`
  ADD PRIMARY KEY (`verification_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `fav_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `meet_up`
--
ALTER TABLE `meet_up`
  MODIFY `meet_up_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `verifications`
--
ALTER TABLE `verifications`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=499;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`item_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `meet_up`
--
ALTER TABLE `meet_up`
  ADD CONSTRAINT `meet_up_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`offer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `meet_up_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `meet_up_ibfk_3` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `offer_id` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`offer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `receiver_user_id` FOREIGN KEY (`receiver_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sender_user_id` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `r_receiver_id` FOREIGN KEY (`r_receiver_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sender_id` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`meet_up_id`) REFERENCES `meet_up` (`meet_up_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`rate_your_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`rate_partner_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
