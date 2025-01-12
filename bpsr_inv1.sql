-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2025 at 02:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bpsr_inv1`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(9, 'Aquarium Accessories'),
(11, 'Aquarium Equipment'),
(17, 'Aquarium Water Treatment'),
(13, 'Cat Care'),
(15, 'Cat Food'),
(10, 'Dog Food'),
(6, 'Fish Food'),
(7, 'Fish Pets'),
(18, 'Pet Care'),
(8, 'Plants and Decoration'),
(19, 'Small Mammal Accessories'),
(12, 'Small Pets'),
(20, 'Snake Pets'),
(14, 'Supplements'),
(16, 'Tools for Maintenance');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
(14, 'SmallPets.jpg', ''),
(15, 'AquariumToolsandMaintenance.jpg', 'image/jpeg'),
(16, 'AquariumWaterTreatment.jpg', 'image/jpeg'),
(17, 'PetCare.jpg', 'image/jpeg'),
(18, 'PetSnake.jpg', 'image/jpeg'),
(19, 'SmallMammalAccessories.jpg', 'image/jpeg');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer`, `notes`, `paymethod`, `date`) VALUES
(1, 'Customer 1', '', 'Cash', '2025-01-12');

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
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `category_id`, `media_id`, `date`) VALUES
(1, 'Hikari Wheatgerm', '5048', 140.00, 175.00, 6, 10, '2024-10-03 17:28:21'),
(2, 'Neontetra', '5004', 40.00, 60.00, 7, 11, '2024-10-03 17:28:21'),
(3, 'Lavarock', '5023', 60.00, 100.00, 8, 13, '2024-10-03 17:28:21'),
(4, 'Pebbles', '5032', 35.00, 60.00, 8, 13, '2024-10-03 17:28:21'),
(5, 'BBS Decap', '5001', 30.00, 50.00, 6, 10, '2024-10-03 17:28:21'),
(6, 'BBS Net', '5000', 20.00, 40.00, 9, 5, '2024-10-03 17:28:21'),
(7, '10x10x10 Tank', '4995', 285.00, 350.00, 11, 6, '2024-10-03 17:28:21'),
(8, 'Kohaku', '4999', 20.00, 30.00, 7, 11, '2024-10-03 17:28:21'),
(9, 'RCS', '4999', 20.00, 30.00, 7, 11, '2024-10-03 17:28:21'),
(10, 'Balloon Platty', '5000', 20.00, 30.00, 7, 11, '2024-10-03 17:28:21'),
(11, 'Whoopy Adult', '5000', 60.00, 80.00, 10, 9, '2024-10-03 17:28:21'),
(12, 'Filter foam', '5000', 60.00, 80.00, 11, 6, '2024-10-03 17:28:21'),
(13, 'Rock Salt', '5000', 5.00, 15.00, 11, 6, '2024-10-03 17:28:21'),
(14, 'Convict', '5000', 20.00, 30.00, 7, 11, '2024-10-03 17:28:21'),
(15, 'Aqua soil', '5000', 145.00, 180.00, 8, 13, '2024-10-03 17:28:21'),
(16, 'Plants', '5000', 20.00, 30.00, 8, 13, '2024-10-03 17:28:21'),
(17, 'Bonuses', '5000', 20.00, 35.00, 8, 13, '2024-10-03 17:39:17'),
(18, 'Aozi wet food', '5000', 30.00, 50.00, 10, 9, '2024-10-03 17:39:17'),
(19, 'AP1000', '5000', 285.00, 350.00, 11, 6, '2024-10-03 17:39:17'),
(20, 'Bio gold cichlid', '5000', 145.00, 185.00, 6, 10, '2024-10-03 17:39:17'),
(21, 'Molly', '5000', 3.00, 10.00, 7, 11, '2024-10-03 17:39:17'),
(22, 'Danio', '5000', 10.00, 20.00, 7, 11, '2024-10-03 17:39:17'),
(23, 'Hose', '5000', 5.00, 10.00, 11, 6, '2024-10-03 17:39:17'),
(24, 'Glofish', '5000', 35.00, 50.00, 7, 11, '2024-10-03 17:39:17'),
(25, 'Single Airpump', '5000', 130.00, 170.00, 11, 6, '2024-10-03 17:39:17'),
(26, 'S. Filter', '5000', 65.00, 90.00, 11, 6, '2024-10-03 17:39:17'),
(27, 'Mice', '5000', 25.00, 40.00, 9, 5, '2024-10-03 17:39:17'),
(28, 'Pingpong', '5000', 170.00, 200.00, 7, 11, '2024-10-03 17:39:17'),
(29, 'Bubble eye', '5000', 100.00, 150.00, 7, 11, '2024-10-03 17:39:17'),
(30, 'Saki Hikari', '5000', 390.00, 460.00, 6, 10, '2024-10-03 17:39:17'),
(31, 'Feeders', '5000', 4.00, 10.00, 7, 11, '2024-10-03 17:39:17'),
(32, 'Special dog adult', '5000', 115.00, 135.00, 10, 9, '2024-10-03 17:39:17'),
(33, 'Betta', '5000', 150.00, 200.00, 7, 11, '2024-10-03 17:39:17'),
(34, '3 Fishnet', '500', 15.00, 30.00, 9, 5, '2024-10-03 17:39:17'),
(35, 'Instant glue', '5000', 60.00, 80.00, 9, 5, '2024-10-03 17:39:17'),
(36, 'Special dog puppy', '5000', 135.00, 160.00, 10, 9, '2024-10-03 17:39:17'),
(37, 'Kuhli loach', '5000', 75.00, 100.00, 7, 11, '2024-10-03 17:39:17'),
(38, 'Kusot', '5000', 5.00, 10.00, 9, 5, '2024-10-03 17:39:17'),
(39, 'RB Oranda', '5000', 150.00, 180.00, 7, 11, '2024-10-03 17:39:17'),
(40, 'XP-06', '5000', 240.00, 300.00, 11, 6, '2024-10-03 17:39:17'),
(41, 'Tweety Wood', '500.25', 650.00, 800.00, 8, 13, '2024-10-03 17:39:17'),
(42, 'Top light', '5000', 900.00, 1050.00, 11, 6, '2024-10-03 17:39:17'),
(43, '50W Heater', '5000', 390.00, 460.00, 11, 6, '2024-10-03 17:39:17'),
(44, 'Cuties', '5000', 100.00, 130.00, 15, 8, '2024-10-03 17:39:17'),
(45, 'Pedigree wet food', '5000', 30.00, 50.00, 10, 9, '2024-10-03 17:39:17'),
(46, '20W Powerhead', '4997', 280.00, 350.00, 11, 6, '2024-10-03 17:39:17'),
(47, 'Filter wool', '5000', 5.00, 15.00, 11, 6, '2024-10-03 17:39:17'),
(48, '4x8x16', '4997', 90.00, 120.00, 8, 13, '2024-10-03 17:39:17'),
(49, 'Background', '5000', 30.00, 50.00, 8, 13, '2024-10-03 17:39:17'),
(50, 'Ranch', '5000', 390.00, 450.00, 7, 11, '2024-10-03 17:39:17'),
(51, 'VGB shrimp', '5000', 45.00, 70.00, 7, 11, '2024-10-03 17:39:17'),
(52, 'Cat litter', '5000', 250.00, 300.00, 13, 7, '2024-10-03 17:39:17'),
(53, 'Rats', '5000', 50.00, 80.00, 9, 6, '2024-10-03 17:39:17'),
(54, 'Sand', '5000', 40.00, 60.00, 8, 13, '2024-10-03 17:39:17'),
(55, 'Mondex', '5000', 90.00, 120.00, 10, 9, '2024-10-03 17:39:17'),
(56, 'Heater', '5000', 400.00, 460.00, 11, 6, '2024-10-03 17:39:17'),
(57, 'Airpump set', '5000', 200.00, 250.00, 11, 6, '2024-10-03 17:39:17'),
(58, 'CM-Blue', '5000', 60.00, 80.00, 11, 6, '2024-10-03 17:39:17'),
(59, 'M-Blue', '5000', 20.00, 35.00, 11, 6, '2024-10-03 17:39:17'),
(60, 'Vitality puppy', '5000.5', 100.00, 150.00, 10, 9, '2024-10-03 17:39:17'),
(61, '15 gallon stand', '5000', 900.00, 1100.00, 11, 6, '2024-10-05 20:37:07'),
(62, '15 gallon tank', '5000', 470.00, 570.00, 11, 6, '2024-10-05 20:37:07'),
(63, 'Albino cory', '5000', 50.00, 100.00, 7, 11, '2024-10-05 20:37:07'),
(64, 'Angel fish', '5000', 25.00, 50.00, 7, 11, '2024-10-05 20:37:07'),
(65, 'Anti chlorine', '5000', 25.00, 35.00, 17, 16, '2024-10-05 20:37:07'),
(66, 'Anubias coin leaf', '5000', 300.00, 350.00, 8, 13, '2024-10-05 20:37:07'),
(67, 'Balloon Molly', '5000', 20.00, 30.00, 7, 11, '2024-10-05 20:37:07'),
(68, 'Banded file snake', '5000', 150.00, 200.00, 20, 18, '2024-10-05 20:37:07'),
(69, 'Ember tetra', '5000', 30.00, 60.00, 7, 11, '2024-10-05 20:37:07'),
(70, 'Guppy', '5000', 30.00, 50.00, 7, 11, '2024-10-05 20:37:07'),
(71, 'Humpy head', '5000', 80.00, 100.00, 18, 17, '2024-10-05 20:37:07'),
(72, 'Koi King', '5000', 280.00, 360.00, 7, 11, '2024-10-05 20:37:07'),
(73, 'Krusty Krab Resto', '5000', 40.00, 70.00, 9, 5, '2024-10-05 20:37:07'),
(74, 'Lucky bamboo', '5000', 10.00, 20.00, 8, 13, '2024-10-05 20:37:07'),
(75, 'Moss ball', '5000', 200.00, 250.00, 8, 13, '2024-10-05 20:37:07'),
(76, 'Oscar', '5000', 200.00, 250.00, 7, 11, '2024-10-05 20:37:07'),
(77, 'Panda cory', '5000', 100.00, 150.00, 7, 11, '2024-10-05 20:37:07'),
(78, 'Spirulina', '5000', 40.00, 60.00, 6, 10, '2024-10-05 20:37:07'),
(79, 'Syphon pump', '5000', 100.00, 120.00, 16, 15, '2024-10-05 20:37:07'),
(80, 'Top filter', '5000', 400.00, 500.00, 11, 6, '2024-10-05 20:37:07'),
(81, 'Vet core soap', '5000', 125.00, 150.00, 18, 17, '2024-10-05 20:59:48'),
(82, 'Sword tail', '50001', 80.00, 100.00, 7, 11, '2024-10-05 20:59:48'),
(83, 'Root tab', '500', 10.00, 20.00, 18, 17, '2024-10-05 20:59:48'),
(84, 'Siamese algae eater', '500', 60.00, 80.00, 7, 11, '2024-10-05 20:59:48'),
(85, 'Red cap', '500', 125.00, 150.00, 7, 11, '2024-10-05 20:59:48'),
(86, 'Okiko', '5000', 130.00, 170.00, 7, 11, '2024-10-05 20:59:48'),
(87, 'Optimum betta', '5000', 30.00, 50.00, 6, 10, '2024-10-05 20:59:48'),
(88, 'Nematocide', '5000', 80.00, 120.00, 17, 16, '2024-10-05 20:59:48'),
(89, 'Black neon', '500', 40.00, 60.00, 7, 11, '2024-10-05 20:59:48'),
(90, 'AC/DC Airpump 30H', '5000', 850.00, 1000.00, 16, 15, '2024-10-05 20:59:48'),
(91, 'Air Stone', '50002', 20.00, 30.00, 16, 15, '2024-10-05 20:59:48'),
(92, 'S. Air Stone', '5000', 10.00, 20.00, 16, 15, '2024-10-06 17:35:19'),
(93, 'S. Airpump set', '5000', 140.00, 170.00, 11, 6, '2024-10-06 17:38:24'),
(94, '1 Aozi wet food', '493', 140.00, 170.00, 10, 9, '2024-10-06 17:41:17'),
(95, 'L. Bonuses', '5000', 90.00, 120.00, 8, 13, '2024-10-06 17:44:23'),
(96, 'S. Molly', '5000', 2.00, 7.00, 7, 11, '2024-10-06 17:49:20'),
(97, 'S. RCS', '5000', 15.00, 25.00, 7, 11, '2024-10-06 17:59:09'),
(98, 'Super Worm', '5000', 0.80, 1.00, 6, 10, '2024-10-06 17:40:46');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `order_id`, `product_id`, `qty`, `price`, `date`) VALUES
(20897, 1, 9, 1, 30.00, '2025-01-12'),
(20898, 1, 4, 1, 60.00, '2025-01-12'),
(20899, 1, 8, 1, 30.00, '2025-01-12');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'Admin User', 'Admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'mgtu7mae1.png', 1, '2024-11-16 00:13:22'),
(10, 'Employee User', 'Employee', 'caf322f0bbed721eac4a36bf7aff1103079faf25', 2, 'no_image.jpg', 1, '2024-11-13 10:11:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(7, 'Admin', 1, 1),
(8, 'Employee', 2, 1);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20900;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
