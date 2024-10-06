-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2024 at 12:08 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bpsr_inv`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(9, 'Aquarium Accessories'),
(11, 'Aquarium Equipment'),
(13, 'Cat Care'),
(15, 'Cat Food'),
(10, 'Dog Food'),
(6, 'Fish Food'),
(7, 'Fish Pets'),
(8, 'Plants and Decoration'),
(12, 'Small Pets'),
(14, 'Supplements');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remote_ip` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `file_name`, `file_type`) VALUES
(5, 'AquariumAccessories.jpg', 'image/jpeg'),
(6, 'AquariumEquipment.jpg', 'image/jpeg'),
(7, 'CatCare.jpg', 'image/jpeg'),
(8, 'CatFood.jpg', 'image/png'),
(9, 'DogFood.jpg', 'image/jpeg'),
(10, 'FishFood.jpg', 'image/jpeg'),
(11, 'FishPets.jpg', 'image/jpeg'),
(12, 'PetSupplements.jpg', ''),
(13, 'PlantsandDecoration.jpg', 'image/jpeg'),
(14, 'SmallPets.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `paymethod` varchar(10) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `media_id` int(11) DEFAULT '0',
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `category_id`, `media_id`, `date`) VALUES
(1, 'Hikari Wheatgerm', '1', '150.00', '160.00', 6, 10, '2024-10-03 17:28:21'),
(2, 'Neontetra', '6', '50.00', '60.00', 7, 11, '2024-10-03 17:28:21'),
(3, 'Lavarock', '1', '90.00', '100.00', 8, 13, '2024-10-03 17:28:21'),
(4, 'Pebbles', '2', '50.00', '60.00', 8, 13, '2024-10-03 17:28:21'),
(5, 'BBS Decap', '1', '40.00', '50.00', 6, 10, '2024-10-03 17:28:21'),
(6, 'BBS Net', '1', '30.00', '40.00', 9, 5, '2024-10-03 17:28:21'),
(7, '10x10x10 Tank', '1', '300.00', '350.00', 11, 6, '2024-10-03 17:28:21'),
(8, 'Kohaku', '3', '20.00', '30.00', 7, 11, '2024-10-03 17:28:21'),
(9, 'RCS', '3', '20.00', '30.00', 7, 11, '2024-10-03 17:28:21'),
(10, 'Balloon Platty', '3', '20.00', '30.00', 7, 11, '2024-10-03 17:28:21'),
(11, 'Whoopy Adult', '2', '70.00', '80.00', 10, 9, '2024-10-03 17:28:21'),
(14, 'Filter foam', '1', '70.00', '80.00', 11, 6, '2024-10-03 17:28:21'),
(15, 'Super Worm', '1', '40.00', '50.00', 6, 10, '2024-10-03 17:28:21'),
(16, 'Rock Salt', '1', '5.00', '10.00', 11, 6, '2024-10-03 17:28:21'),
(17, 'Convict', '4', '20.00', '25.00', 7, 11, '2024-10-03 17:28:21'),
(18, 'Aqua soil', '1', '150.00', '180.00', 8, 13, '2024-10-03 17:28:21'),
(19, 'Plants', '1', '20.00', '30.00', 8, 13, '2024-10-03 17:28:21'),
(21, 'Bonuses', '1', '30.00', '35.00', 8, 13, '2024-10-03 17:39:17'),
(22, 'Aozi wet food', '3', '40.00', '50.00', 10, 9, '2024-10-03 17:39:17'),
(23, 'AP1000', '1', '300.00', '350.00', 11, 6, '2024-10-03 17:39:17'),
(24, 'Bio gold cichlid', '1', '150.00', '185.00', 6, 10, '2024-10-03 17:39:17'),
(26, 'Molly', '15', '5.00', '10.00', 7, 11, '2024-10-03 17:39:17'),
(27, 'Danio', '5', '15.00', '20.00', 7, 11, '2024-10-03 17:39:17'),
(28, 'Hose', '2', '5.00', '10.00', 11, 6, '2024-10-03 17:39:17'),
(29, 'Glofish', '9', '40.00', '50.00', 7, 11, '2024-10-03 17:39:17'),
(30, 'Single Airpump', '1', '150.00', '170.00', 11, 6, '2024-10-03 17:39:17'),
(31, 'S. Filter', '1', '80.00', '90.00', 11, 6, '2024-10-03 17:39:17'),
(33, 'Mice', '1', '30.00', '40.00', 9, 5, '2024-10-03 17:39:17'),
(34, 'Pingpong', '2', '350.00', '400.00', 7, 11, '2024-10-03 17:39:17'),
(35, 'Bubble eye', '1', '100.00', '150.00', 7, 11, '2024-10-03 17:39:17'),
(36, 'Saki Hikari', '1', '400.00', '460.00', 6, 10, '2024-10-03 17:39:17'),
(37, 'Feeders', '10', '5.00', '10.00', 7, 11, '2024-10-03 17:39:17'),
(38, 'Special dog adult', '1', '120.00', '135.00', 10, 9, '2024-10-03 17:39:17'),
(39, 'Betta', '1', '150.00', '200.00', 7, 11, '2024-10-03 17:39:17'),
(40, '3 Fishnet', '1', '30.00', '30.00', 9, 5, '2024-10-03 17:39:17'),
(41, 'Instant glue', '1', '70.00', '80.00', 9, 5, '2024-10-03 17:39:17'),
(42, 'Special dog puppy', '1', '150.00', '160.00', 10, 9, '2024-10-03 17:39:17'),
(43, 'Kuhli loach', '2', '90.00', '100.00', 7, 11, '2024-10-03 17:39:17'),
(44, 'Kusot', '2', '5.00', '10.00', 9, 5, '2024-10-03 17:39:17'),
(45, 'RB Oranda', '2', '150.00', '180.00', 7, 11, '2024-10-03 17:39:17'),
(46, 'XP-06', '1', '250.00', '300.00', 11, 6, '2024-10-03 17:39:17'),
(47, 'Tweety Wood', '0.25', '700.00', '800.00', 8, 13, '2024-10-03 17:39:17'),
(48, 'Top light', '1', '900.00', '1050.00', 11, 6, '2024-10-03 17:39:17'),
(49, '50W Heater', '1', '400.00', '460.00', 11, 6, '2024-10-03 17:39:17'),
(50, 'Cuties', '1', '120.00', '130.00', 15, 8, '2024-10-03 17:39:17'),
(51, 'Pedigree wet food', '3', '40.00', '50.00', 10, 9, '2024-10-03 17:39:17'),
(52, '20W Powerhead', '1', '300.00', '350.00', 11, 6, '2024-10-03 17:39:17'),
(53, 'Filter wool', '1', '10.00', '15.00', 11, 6, '2024-10-03 17:39:17'),
(56, '4x8x16', '1', '100.00', '120.00', 8, 13, '2024-10-03 17:39:17'),
(57, 'Background', '1', '30.00', '50.00', 8, 13, '2024-10-03 17:39:17'),
(58, 'Ranch', '1', '400.00', '450.00', 7, 11, '2024-10-03 17:39:17'),
(59, 'VGB shrimp', '2', '50.00', '70.00', 7, 11, '2024-10-03 17:39:17'),
(60, 'Cat litter', '1', '250.00', '300.00', 13, 7, '2024-10-03 17:39:17'),
(61, 'Rats', '6', '50.00', '80.00', 9, 6, '2024-10-03 17:39:17'),
(62, 'Sand', '2', '40.00', '60.00', 8, 13, '2024-10-03 17:39:17'),
(63, 'Mondex', '1', '100.00', '120.00', 10, 9, '2024-10-03 17:39:17'),
(64, 'Heater', '1', '400.00', '460.00', 11, 6, '2024-10-03 17:39:17'),
(65, 'Airpump set', '1', '200.00', '250.00', 11, 6, '2024-10-03 17:39:17'),
(66, 'CM-Blue', '1', '60.00', '80.00', 11, 6, '2024-10-03 17:39:17'),
(67, 'M-Blue', '1', '30.00', '35.00', 11, 6, '2024-10-03 17:39:17'),
(69, 'Vitality puppy', '0.5', '100.00', '150.00', 10, 9, '2024-10-03 17:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `comments` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'Admin User', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'z45p4vz31.jpg', 1, '2019-02-14 17:29:10'),
(2, 'Supervisor', 'Special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'vk529aeg2.jpg', 1, '2019-02-06 11:44:19'),
(3, 'Default User', 'user', '12dea96fec20593566ab75692c9949596833adc9', 3, 'q73muz1v3.jpg', 1, '2019-02-06 11:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'Supervisor', 2, 1),
(3, 'User', 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
