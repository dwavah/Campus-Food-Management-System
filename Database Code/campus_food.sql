-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2024 at 11:46 PM
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
-- Database: `campus_food`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `uid` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`uid`, `name`, `timestamp`) VALUES
('\0\0?\0?\0?\0?\0?\0?\0?\0?\0?\0?\0\0?\0\0\0?\0?????', '\0?\0?\0?\0?\0?\0?\0?\0?\0?\0?\0\0?\0\0\0?\0?????', '2024-11-16 14:30:45'),
('3:9C:3B:1A', 'Daniel W, UID: 3:9C:3B:1A', '2024-11-16 13:14:55'),
('60:EA:21:12', 'TIRZAH, UID: 60:EA:21:12', '2024-11-16 13:36:13'),
('73:A4:C3:95', 'James Alala, UID: 73:A4:C3:95', '2024-11-16 13:36:06'),
('BC:3:37:BB', 'SETH, UID: BC:3:37:BB', '2024-11-16 13:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` varchar(50) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `qty` int(10) NOT NULL,
  `total_price` varchar(100) NOT NULL,
  `product_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_name`, `product_price`, `product_image`, `qty`, `total_price`, `product_code`) VALUES
(70, 'Sandwich', '8000', 'image/food-3.png', 1, '8000', 'p1002'),
(71, 'Toast', '5000', 'image/food-4.png', 1, '5000', 'p1003');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `pmode` varchar(50) NOT NULL,
  `products` varchar(255) NOT NULL,
  `amount_paid` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` varchar(100) NOT NULL,
  `product_qty` int(11) NOT NULL DEFAULT 1,
  `product_image` varchar(255) NOT NULL,
  `product_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_name`, `product_price`, `product_qty`, `product_image`, `product_code`) VALUES
(1, 'Burger', '10000', 1, 'image/food-1.png', 'p1000'),
(2, 'Pizza', '12000', 1, 'image/food-2.png', 'p1001'),
(3, 'Sandwich', '8000', 1, 'image/food-3.png', 'p1002'),
(4, 'Toast', '5000', 1, 'image/food-4.png', 'p1003'),
(5, 'Coca Cola', '3000', 1, 'image/food-5.png', 'p1004'),
(6, 'Chips', '5000', 1, 'image/food-6.png', 'p1005'),
(7, 'Gonja', '2000', 1, 'image/gonja.jpg', 'p1006'),
(8, 'Luwombo', '15000', 1, 'image/luwombo.jpg', 'p1007');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `restaurant_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurant_id`, `name`, `balance`, `password`) VALUES
('BBO1', 'Guild', 92000.00, '$2y$10$jPnjwThnUaTU3ny7TQdtyuO.JRacwbdPXHQIBSeTjaZmrE6ZV4hta');

--
-- Triggers `restaurants`
--
DELIMITER $$
CREATE TRIGGER `before_restaurant_insert` BEFORE INSERT ON `restaurants` FOR EACH ROW BEGIN
    -- Insert a new user into the Users table, using the provided password
    INSERT INTO Users (resto_id, role, username, password, name)
    VALUES (NEW.restaurant_id, 'restaurant', NEW.name, NEW.password, NEW.name); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `access_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `uid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `access_number`, `password`, `balance`, `uid`) VALUES
('HRD4', 'qwerty', 'qwert', '$2y$10$5Yh5FaLtkg7O9ZKIrX1K3OzC4n4pfK.Nhz.vAqLbdq6JP3L/SMQme', 29000.00, '3:9C:3B:1A'),
('KWT4', 'qwe', '12345', '$2y$10$SxSHqYQDQTcTwsYmrhXdoOFWLH./0cgA/BXJ.i.vy.RUrmQ64snPK', 20000.00, '60:EA:21:12'),
('MNN3', 'Lo', 'o', '$2y$10$bom44bJXHEhYe.N8aZJ9FOhPFxY4eaCkFUIxGdL9JM3EVXZAtiEu6', 123450.00, 'BC:3:37:BB'),
('PQI5', '111', '111', '$2y$10$YHPwlZR.Rbtsf0C7JKgaaePpf.pKrd/6lv3N9TeJoT3GR2M94ziSW', 128911.00, '73:A4:C3:95'),
('RTA5', 'qwerty', 'qwert', '$2y$10$RYlmnPXd0/6AaM/b5LCpb.Iv.bsezpioTAVRrf7qxjSSi874TuKsa', 29000.00, '3:9C:3B:1A');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(255) NOT NULL,
  `role` enum('admin','student','restaurant','student') NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `resto_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `username`, `password`, `name`, `resto_id`) VALUES
('', 'restaurant', 'Guild', '$2y$10$jPnjwThnUaTU3ny7TQdtyuO.JRacwbdPXHQIBSeTjaZmrE6ZV4hta', 'Guild', 'BBO1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code_2` (`product_code`),
  ADD KEY `product_code` (`product_code`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `fk_students_cards` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_cards` FOREIGN KEY (`uid`) REFERENCES `cards` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
