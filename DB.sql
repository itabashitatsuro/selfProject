-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 05, 2021 at 08:35 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fleaMarket`
--

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE `card` (
  `id` int(11) NOT NULL,
  `stripe_id` text,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `kinds` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `kinds`) VALUES
(1, 'メンズ'),
(2, 'レディース'),
(3, 'ベビー・キッズ'),
(4, 'スマホ・タブレット'),
(5, 'インテリア・住まい・小物'),
(6, '自動車・オートバイ'),
(7, 'チケット'),
(8, '公式グッズ'),
(9, '音楽'),
(10, '本・書籍'),
(11, 'ゲーム'),
(12, 'おもちゃ・ホビーグッズ'),
(13, 'タレントグッズ'),
(14, 'コスメ（香水・美容）'),
(15, 'スポーツ・レジャー'),
(16, '家電・カメラ');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `introduce` varchar(300) DEFAULT NULL,
  `status` text,
  `delivery` text,
  `userPrice` int(11) DEFAULT NULL COMMENT 'ユーザーの設定価格',
  `sellPrice` int(11) DEFAULT NULL COMMENT '最終的な出品価格',
  `categories_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `sell_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `purchased_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` int(11) NOT NULL COMMENT '0:一般ユーザー 1:管理者',
  `name` varchar(32) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(256) NOT NULL,
  `tel` char(11) NOT NULL,
  `birthday` text,
  `post_code` int(11) DEFAULT NULL,
  `prefecture` text,
  `city` text,
  `city_block` text,
  `building` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `password`, `tel`, `birthday`, `post_code`, `prefecture`, `city`, `city_block`, `building`) VALUES
(1, 1, 'admin', 'admin@admin.jp', 'admin0000', '00000000000', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 0, 'test', 'test@test.com', 'test00000000', '11111111111', '1980/12/12', 1610055, '東京都', '新宿区神楽坂', '3丁目2番地の1', '新宿タワー102');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_imges_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tel` (`tel`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `card`
--
ALTER TABLE `card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `products_imges_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
