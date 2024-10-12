-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2024 at 07:09 PM
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer`, `notes`, `paymethod`, `date`) VALUES
(1, 'Rochelles', 'NA', 'Cash', '2024-10-03'),
(2, 'Rochelle', 'NA', 'Cash', '2024-10-04'),
(3, 'Rochelle', '1', 'Cash', '2024-10-05'),
(4, 'Rochelle', '1', 'Cash', '2024-10-05'),
(5, 'Rochelle', '1', 'Cash', '2024-10-05'),
(6, '1', '', 'Cash', '2024-10-05'),
(7, '1', '1', 'Cash', '2024-10-05'),
(8, '1', '1', 'Cash', '2024-10-06'),
(9, '1', '1', 'Cash', '2024-10-06'),
(10, 'waw', 'meow', 'Cash', '2024-10-06'),
(11, 'waw', '1', 'Cash', '2024-10-06'),
(12, '13', '12', 'Cash', '2024-10-07'),
(13, '3', '45', 'Cash', '2024-10-07'),
(14, '23', '2345', 'Cash', '2024-10-08'),
(15, '1234', '124', 'Cash', '2024-10-08'),
(16, 'asd', '12', 'Cash', '2024-10-08'),
(17, '123', 'meow meow', 'Cash', '2024-10-08'),
(18, '12', '12', 'Cash', '2024-10-11'),
(19, '2', '3', 'Cash', '2024-10-11'),
(20, '23', '23', 'Cash', '2024-10-11'),
(21, '12', '23', 'Cash', '2024-10-11'),
(22, '23', '23', 'Gcash', '2024-10-11'),
(23, '123', 'ad', 'Cash', '2024-10-11'),
(24, '123', '23', 'Gcash', '2024-10-11'),
(25, '23', '3', 'Cash', '2024-10-11'),
(26, 'as', '23', 'Cash', '2024-10-11'),
(27, '23', '23', 'Cash', '2024-10-11'),
(28, '23', '32', 'Cash', '2024-10-11'),
(29, '12', '2', 'Cash', '2024-10-11'),
(30, 'marvin', 'a', 'Cash', '2024-10-11'),
(31, 'marvsssssssssss', '12', 'Cash', '2024-10-11'),
(32, '1', '2', 'Cash', '2024-10-12'),
(33, 'ar', '24', 'Cash', '2024-10-12'),
(34, 'arr', '123', 'Cash', '2024-10-12'),
(35, '23', '23', 'Cash', '2024-10-12');

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
(1, 'Hikari Wheatgerm', '4965', '150.00', '160.00', 6, 10, '2024-10-03 17:28:21'),
(2, 'Neontetra', '4969', '50.00', '60.00', 7, 11, '2024-10-03 17:28:21'),
(3, 'Lavarock', '4993', '90.00', '100.00', 8, 13, '2024-10-03 17:28:21'),
(4, 'Pebbles', '4996', '50.00', '60.00', 8, 13, '2024-10-03 17:28:21'),
(5, 'BBS Decap', '4999', '40.00', '50.00', 6, 10, '2024-10-03 17:28:21'),
(6, 'BBS Net', '5000', '30.00', '40.00', 9, 5, '2024-10-03 17:28:21'),
(7, '10x10x10 Tank', '4973', '300.00', '350.00', 11, 6, '2024-10-03 17:28:21'),
(8, 'Kohaku', '5000', '20.00', '30.00', 7, 11, '2024-10-03 17:28:21'),
(9, 'RCS', '5000', '25.00', '30.00', 7, 11, '2024-10-03 17:28:21'),
(10, 'Balloon Platty', '5000', '20.00', '30.00', 7, 11, '2024-10-03 17:28:21'),
(11, 'Whoopy Adult', '4999', '70.00', '80.00', 10, 9, '2024-10-03 17:28:21'),
(14, 'Filter foam', '5000', '70.00', '80.00', 11, 6, '2024-10-03 17:28:21'),
(16, 'Rock Salt', '5000', '5.00', '10.00', 11, 6, '2024-10-03 17:28:21'),
(17, 'Convict', '5000', '20.00', '25.00', 7, 11, '2024-10-03 17:28:21'),
(18, 'Aqua soil', '5000', '150.00', '180.00', 8, 13, '2024-10-03 17:28:21'),
(19, 'Plants', '5000', '20.00', '30.00', 8, 13, '2024-10-03 17:28:21'),
(21, 'Bonuses', '5000', '30.00', '35.00', 8, 13, '2024-10-03 17:39:17'),
(22, 'Aozi wet food', '5000', '40.00', '50.00', 10, 9, '2024-10-03 17:39:17'),
(23, 'AP1000', '5000', '300.00', '350.00', 11, 6, '2024-10-03 17:39:17'),
(24, 'Bio gold cichlid', '5000', '150.00', '185.00', 6, 10, '2024-10-03 17:39:17'),
(26, 'Molly', '5000', '7.00', '10.00', 7, 11, '2024-10-03 17:39:17'),
(27, 'Danio', '5000', '15.00', '20.00', 7, 11, '2024-10-03 17:39:17'),
(28, 'Hose', '5000', '5.00', '10.00', 11, 6, '2024-10-03 17:39:17'),
(29, 'Glofish', '5000', '40.00', '50.00', 7, 11, '2024-10-03 17:39:17'),
(30, 'Single Airpump', '5000', '150.00', '170.00', 11, 6, '2024-10-03 17:39:17'),
(31, 'S. Filter', '5000', '80.00', '90.00', 11, 6, '2024-10-03 17:39:17'),
(33, 'Mice', '5000', '30.00', '40.00', 9, 5, '2024-10-03 17:39:17'),
(34, 'Pingpong', '5000', '170.00', '200.00', 7, 11, '2024-10-03 17:39:17'),
(35, 'Bubble eye', '5000', '100.00', '150.00', 7, 11, '2024-10-03 17:39:17'),
(36, 'Saki Hikari', '5000', '400.00', '460.00', 6, 10, '2024-10-03 17:39:17'),
(37, 'Feeders', '5000', '5.00', '10.00', 7, 11, '2024-10-03 17:39:17'),
(38, 'Special dog adult', '5000', '120.00', '135.00', 10, 9, '2024-10-03 17:39:17'),
(39, 'Betta', '5000', '150.00', '200.00', 7, 11, '2024-10-03 17:39:17'),
(40, '3 Fishnet', '5000', '30.00', '30.00', 9, 5, '2024-10-03 17:39:17'),
(41, 'Instant glue', '5000', '70.00', '80.00', 9, 5, '2024-10-03 17:39:17'),
(42, 'Special dog puppy', '5000', '150.00', '160.00', 10, 9, '2024-10-03 17:39:17'),
(43, 'Kuhli loach', '5000', '90.00', '100.00', 7, 11, '2024-10-03 17:39:17'),
(44, 'Kusot', '5000', '5.00', '10.00', 9, 5, '2024-10-03 17:39:17'),
(45, 'RB Oranda', '5000', '150.00', '180.00', 7, 11, '2024-10-03 17:39:17'),
(46, 'XP-06', '5000', '250.00', '300.00', 11, 6, '2024-10-03 17:39:17'),
(47, 'Tweety Wood', '500.25', '700.00', '800.00', 8, 13, '2024-10-03 17:39:17'),
(48, 'Top light', '5000', '900.00', '1050.00', 11, 6, '2024-10-03 17:39:17'),
(49, '50W Heater', '5000', '400.00', '460.00', 11, 6, '2024-10-03 17:39:17'),
(50, 'Cuties', '5000', '120.00', '130.00', 15, 8, '2024-10-03 17:39:17'),
(51, 'Pedigree wet food', '5000', '40.00', '50.00', 10, 9, '2024-10-03 17:39:17'),
(52, '20W Powerhead', '5000', '300.00', '350.00', 11, 6, '2024-10-03 17:39:17'),
(53, 'Filter wool', '5000', '10.00', '15.00', 11, 6, '2024-10-03 17:39:17'),
(56, '4x8x16', '5000', '100.00', '120.00', 8, 13, '2024-10-03 17:39:17'),
(57, 'Background', '5000', '30.00', '50.00', 8, 13, '2024-10-03 17:39:17'),
(58, 'Ranch', '5000', '400.00', '450.00', 7, 11, '2024-10-03 17:39:17'),
(59, 'VGB shrimp', '5000', '50.00', '70.00', 7, 11, '2024-10-03 17:39:17'),
(60, 'Cat litter', '5000', '250.00', '300.00', 13, 7, '2024-10-03 17:39:17'),
(61, 'Rats', '5000', '50.00', '80.00', 9, 6, '2024-10-03 17:39:17'),
(62, 'Sand', '5000', '40.00', '60.00', 8, 13, '2024-10-03 17:39:17'),
(63, 'Mondex', '5000', '100.00', '120.00', 10, 9, '2024-10-03 17:39:17'),
(64, 'Heater', '5000', '400.00', '460.00', 11, 6, '2024-10-03 17:39:17'),
(65, 'Airpump set', '5000', '200.00', '250.00', 11, 6, '2024-10-03 17:39:17'),
(66, 'CM-Blue', '5000', '60.00', '80.00', 11, 6, '2024-10-03 17:39:17'),
(67, 'M-Blue', '5000', '30.00', '35.00', 11, 6, '2024-10-03 17:39:17'),
(69, 'Vitality puppy', '5000.5', '100.00', '150.00', 10, 9, '2024-10-03 17:39:17'),
(70, '15 gallon stand', '5000', '950.00', '1100.00', 11, 6, '2024-10-05 20:37:07'),
(71, '15 gallon tank', '5000', '500.00', '570.00', 11, 6, '2024-10-05 20:37:07'),
(72, 'Albino cory', '5000', '55.00', '70.00', 7, 11, '2024-10-05 20:37:07'),
(73, 'Angel fish', '5000', '25.00', '30.00', 7, 11, '2024-10-05 20:37:07'),
(74, 'Anti chlorine', '4996', '25.00', '35.00', 17, 16, '2024-10-05 20:37:07'),
(75, 'Anubias coin leaf', '5000', '330.00', '350.00', 8, 13, '2024-10-05 20:37:07'),
(76, 'Balloon Molly', '5000', '25.00', '30.00', 7, 11, '2024-10-05 20:37:07'),
(77, 'Banded file snake', '4996', '170.00', '200.00', 20, 18, '2024-10-05 20:37:07'),
(78, 'Ember tetra', '5000', '50.00', '60.00', 7, 11, '2024-10-05 20:37:07'),
(79, 'Guppy', '5000', '40.00', '50.00', 7, 11, '2024-10-05 20:37:07'),
(80, 'Humpy head', '4378', '85.00', '100.00', 18, 17, '2024-10-05 20:37:07'),
(81, 'Koi King', '5000', '300.00', '360.00', 7, 11, '2024-10-05 20:37:07'),
(82, 'Krusty Krab Resto', '5000', '50.00', '70.00', 9, 5, '2024-10-05 20:37:07'),
(83, 'Lucky bamboo', '5000', '15.00', '20.00', 8, 13, '2024-10-05 20:37:07'),
(85, 'Moss ball', '5000', '230.00', '250.00', 8, 13, '2024-10-05 20:37:07'),
(86, 'Oscar', '5000', '230.00', '250.00', 7, 11, '2024-10-05 20:37:07'),
(87, 'Panda cory', '5000', '130.00', '150.00', 7, 11, '2024-10-05 20:37:07'),
(88, 'Spirulina', '5000', '50.00', '60.00', 6, 10, '2024-10-05 20:37:07'),
(89, 'Syphon pump', '5000', '100.00', '120.00', 16, 15, '2024-10-05 20:37:07'),
(90, 'Top filter', '5000', '450.00', '500.00', 11, 6, '2024-10-05 20:37:07'),
(91, 'Vet core soap', '5000', '135.00', '150.00', 18, 17, '2024-10-05 20:59:48'),
(92, 'Sword tail', '5000', '85.00', '100.00', 7, 11, '2024-10-05 20:59:48'),
(93, 'Root tab', '4977', '15.00', '20.00', 18, 17, '2024-10-05 20:59:48'),
(94, 'Siamese algae eater', '5000', '70.00', '80.00', 7, 11, '2024-10-05 20:59:48'),
(95, 'Red cap', '5000', '130.00', '150.00', 7, 11, '2024-10-05 20:59:48'),
(96, 'Okiko', '5000', '150.00', '170.00', 7, 11, '2024-10-05 20:59:48'),
(97, 'Optimum betta', '5000', '40.00', '45.00', 6, 10, '2024-10-05 20:59:48'),
(98, 'Nematocide', '5000', '100.00', '120.00', 17, 16, '2024-10-05 20:59:48'),
(99, 'Black neon', '5000', '50.00', '60.00', 7, 11, '2024-10-05 20:59:48'),
(100, 'AC/DC Airpump 30H', '5000', '900.00', '1000.00', 16, 15, '2024-10-05 20:59:48'),
(101, 'Air Stone', '5000', '25.00', '30.00', 16, 15, '2024-10-05 20:59:48'),
(103, 'S. Air Stone', '5000', '10.00', '15.00', 16, 15, '2024-10-06 17:35:19'),
(104, 'S. Airpump set', '5000', '150.00', '170.00', 11, 6, '2024-10-06 17:38:24'),
(105, '1 Aozi wet food', '484', '150.00', '170.00', 10, 9, '2024-10-06 17:41:17'),
(106, 'L. Bonuses', '5000', '100.00', '120.00', 8, 13, '2024-10-06 17:44:23'),
(107, 'S. Molly', '5000', '5.00', '7.00', 7, 11, '2024-10-06 17:49:20'),
(108, 'S. RCS', '5000', '20.00', '25.00', 7, 11, '2024-10-06 17:59:09'),
(110, 'Super Worm', '4500', '0.80', '1.00', 6, 10, '2024-10-06 17:40:46');

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

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `order_id`, `product_id`, `qty`, `price`, `date`) VALUES
(1, 4, 1, 1, '160.00', '2024-09-12'),
(2, 5, 2, 1, '60.00', '2024-09-25'),
(3, 6, 2, 2, '120.00', '2024-10-05'),
(4, 7, 2, 1, '60.00', '2024-10-05'),
(5, 10, 110, 500, '500.00', '2024-10-06'),
(6, 12, 1, 1, '160.00', '2024-10-07'),
(7, 13, 2, 23, '1380.00', '2024-10-07'),
(8, 14, 1, 1, '160.00', '2024-10-08'),
(9, 15, 1, 2, '320.00', '2024-10-08'),
(10, 15, 2, 2, '120.00', '2024-10-08'),
(11, 15, 4, 3, '180.00', '2024-10-08'),
(12, 16, 1, 4, '640.00', '2024-10-08'),
(13, 17, 2, 1, '60.00', '2024-10-08'),
(14, 17, 3, 1, '100.00', '2024-10-08'),
(15, 21, 1, 1, '160.00', '2024-10-11'),
(16, 22, 1, 1, '160.00', '2024-10-11'),
(18, 23, 1, 1, '160.00', '2024-10-11'),
(19, 23, 3, 2, '200.00', '2024-10-11'),
(20, 23, 5, 2, '100.00', '2024-10-11'),
(21, 25, 1, 2, '320.00', '2024-10-11'),
(22, 26, 1, 1, '160.00', '2024-10-11'),
(23, 27, 2, 3, '180.00', '2024-10-11'),
(24, 27, 3, 3, '300.00', '2024-10-11'),
(25, 27, 7, 3, '1050.00', '2024-10-11'),
(26, 28, 1, 23, '3680.00', '2024-10-11'),
(27, 28, 2, 2, '120.00', '2024-10-11'),
(28, 28, 3, 1, '100.00', '2024-10-11'),
(29, 28, 7, 1, '350.00', '2024-10-11'),
(30, 28, 80, -1, '-100.00', '2024-10-11'),
(31, 28, 4, 1, '60.00', '2024-10-11'),
(32, 28, 11, 1, '80.00', '2024-10-11'),
(33, 29, 80, 23, '2300.00', '2024-10-11'),
(34, 29, 105, 2, '340.00', '2024-10-11'),
(35, 29, 93, 23, '460.00', '2024-10-11'),
(36, 29, 7, 23, '8050.00', '2024-10-11'),
(37, 30, 105, 9, '1530.00', '2024-10-11'),
(38, 32, 105, 5, '850.00', '2024-10-12'),
(39, 33, 74, 1, '35.00', '2024-10-12'),
(40, 34, 74, 3, '105.00', '2024-10-12'),
(41, 34, 77, 4, '800.00', '2024-10-12'),
(42, 35, 80, 600, '60000.00', '2024-10-12');

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

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `product_id`, `quantity`, `comments`, `date`) VALUES
(1, 101, '5000', 'initial stock', '2024-10-06 17:34:19'),
(2, 103, '5000', 'initial stock', '2024-10-06 17:35:19'),
(3, 104, '5000', 'initial stock', '2024-10-06 17:38:24'),
(4, 105, '500', 'initial stock', '2024-10-06 17:41:17'),
(5, 106, '5000', 'initial stock', '2024-10-06 17:44:23'),
(6, 107, '5000', 'initial stock', '2024-10-06 17:49:20'),
(7, 108, '5000', 'initial stock', '2024-10-06 17:59:09'),
(8, 108, '5000', 'initial stock', '2024-10-06 17:40:18'),
(9, 110, '5000', 'initial stock', '2024-10-06 17:40:46'),
(10, 111, '2', 'initial stock', '2024-10-09 07:59:51'),
(11, 112, '1', 'initial stock', '2024-10-09 08:00:46'),
(12, 113, '1', 'initial stock', '2024-10-09 08:03:35'),
(13, 1, '2', 'meow', '2024-10-11 03:11:45'),
(15, 0, '1', '1', '2024-10-11 03:48:23'),
(16, 0, '1', '1', '2024-10-11 03:48:30'),
(17, 5, '1', '1', '2024-10-11 03:48:34');

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
(1, 'Admin User', 'Admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'mgtu7mae1.png', 1, '2024-10-12 05:25:46'),
(2, 'Employee', 'Employee', '079711ea16f37fe42258753446c0815351870043', 2, 'vk529aeg2.jpg', 1, '2024-10-11 04:55:53'),
(3, 'Marvin1', 'Marvin1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 'no_image.jpg', 0, '2024-10-08 00:35:41'),
(10, '123asddddddddddddddddddddddddddddddddddddddddddddddddddddddd', 'asdasdasdddddddddddddddddddddddddddddddddddddddddd', 'f10e2821bbbea527ea02200352313bc059445190', 1, 'no_image.jpg', 1, NULL);

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
(2, 'Employee', 2, 1),
(3, 'User', 3, 1),
(6, 'Aww', 4, 1);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
