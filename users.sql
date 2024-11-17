-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2024 at 01:00 AM
-- Server version: 5.7.21
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shiftwave`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` enum('User','Admin','Super Admin') DEFAULT 'User',
  `mobile` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text,
  `gender` enum('Male','Female','Other') NOT NULL,
  `dob` date DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `approved` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pending_changes` json DEFAULT NULL,
  `update_approved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `mobile`, `email`, `address`, `gender`, `dob`, `profile_picture`, `password`, `approved`, `created_at`, `pending_changes`, `update_approved_by`) VALUES
(1, 'hvskk', 'Super Admin', '1234512345', 'hvskk@gmail.com', 'Test', 'Male', NULL, 'investment (1).png', '$2y$10$bQqTs/oKaK4RRLi2ole/ROn.XiUCB7nvkhRsiHyD/af8Mq245Fufm', 1, '2024-11-16 19:20:06', NULL, NULL),
(2, 'User', 'User', '1234512345', 'user@yopmail.com', 'Visakhapatnam', 'Male', NULL, 'rupee (1).png', '$2y$10$0IYbn42T6h4HNhuL8zkZ0.bVJmqcctbM1NvAOty8hwUD4LLs45.Nu', 1, '2024-11-16 19:29:20', NULL, NULL),
(3, 'Admin', 'Admin', '1234123412', 'admin@gmail.com', 'Admin', 'Male', NULL, 'rupee (1).png', '$2y$10$6HwjOLJw5BToYiIaSaasnuP9PvT41qqA9oyOjIiTMwCfUGsqGT2Tq', 1, '2024-11-16 19:41:43', NULL, NULL),
(4, 'User 1', 'User', '9876512345', 'user1@gmail.com', 'Testing', 'Female', '2024-11-03', 'woman.png', '$2y$10$5bap6p4hfkCCCqdqzcoljeh05OJxOSzu.KuHh2R02lgtMnc8cZmZ2', 1, '2024-11-16 19:51:17', NULL, 3),
(5, 'Krishna Kanth', 'User', '9581561544', 'krishnakanthhanumanthu@gmail.com', 'Visakhapatnam', 'Male', '2024-11-04', 'rupee.png', '$2y$10$Lf9gEjZvQ/2o25MhKm0Oce92DQMkedKHhsluh1eTrsmybT3MjLMh.', 1, '2024-11-17 07:42:28', NULL, NULL),
(6, 'Krishna', 'User', '1234512345', 'krishna@gmail.com', 'Vizag', 'Male', NULL, 'rupee.png', '$2y$10$v6yLUPFt/icUZvzjrUSPcueV5cfEp5skB/L1MQyvc4dygtMMDX5Hy', 1, '2024-11-17 10:02:17', NULL, NULL),
(8, 'Krishna', 'User', '1234512345', 'krishna1@gmail.com', 'Vizag', 'Male', '1990-01-01', 'rupee.png', '$2y$10$dzPdzMhA7TwT0HVvmE1kD.TVdwIl7SiEg9QWUV2TOuoFhZAPqAO1m', 1, '2024-11-17 10:03:49', NULL, NULL),
(9, 'Krishna', 'User', '1234512345', 'krishna2@gmail.com', 'Vizag', 'Male', '1990-01-01', 'rupee.png', '$2y$10$vZmv/WVgISaivoJQaFw2UOEJIoZj6OD7XCnGspL8GXH8BlMr6N5uG', 1, '2024-11-17 10:06:02', NULL, NULL),
(10, 'Ravi', 'Admin', '1234598760', 'ravi@gmail.com', 'Visakhapatnam', 'Male', '1990-10-10', 'investment (2).png', '$2y$10$j4OLKQJ8n1cdRhLHb92oTux/ou20F8xZZqehKKwItKzHm8xMFhWoy', 1, '2024-11-17 10:52:09', NULL, NULL),
(12, 'Ravi', 'Admin', '1234598760', 'ravi1@gmail.com', 'Krishna', 'Male', '1990-10-10', 'investment (2).png', '$2y$10$j4OLKQJ8n1cdRhLHb92oTux/ou20F8xZZqehKKwItKzHm8xMFhWoy', 1, '2024-11-17 10:52:09', NULL, NULL),
(13, 'Ravi', 'Admin', '1234598760', 'ravi2@gmail.com', 'Visakhapatnam', 'Male', '1990-10-10', 'investment (2).png', '$2y$10$j4OLKQJ8n1cdRhLHb92oTux/ou20F8xZZqehKKwItKzHm8xMFhWoy', 1, '2024-11-17 10:52:09', NULL, 1),
(14, 'Lakshmi', 'User', '9879879870', 'lakshmi@gmail.com', 'Vizinagaram', 'Male', '1990-01-01', 'rupee (1).png', '$2y$10$jUdN86Drp5bnScctFW3JJunzsxb/rbFB4wSwGGlwL4u1N7POP3kRy', 1, '2024-11-17 16:00:08', NULL, NULL),
(15, 'lakshman', 'User', '1234512345', 'lakshman@gmail.com', 'VSKP', 'Female', '2024-11-17', 'investment.png', '$2y$10$9eBzwaawT092in9ITm6AHuA9ctLFKt8wu6Koz2ZzFUwzy.fDML33C', 1, '2024-11-17 16:06:47', NULL, NULL),
(16, 'shiftwave', 'User', '9876598765', 'shiftwave@gmail.com', 'Vizag', 'Male', '1990-01-01', 'woman.png', '$2y$10$.y.AN3JIBmxi6nrCIdJzDuhHLxyXQNxFCpSs1W/eW7/yCoysn1yma', 1, '2024-11-17 16:32:37', NULL, NULL),
(17, 'Rajeswari User', 'User', '1234567890', 'rajeswari@gmail.com', 'Palasa', 'Male', '2001-01-01', NULL, '$2y$10$5sMgmWR//HX8bVlohoLgc.n/NJzunlgKY4banolEHpebHWmPo4m/a', 1, '2024-11-17 17:38:23', NULL, 1),
(18, 'Priya', 'User', '1234512345', 'priya@gmail.com', 'Test', 'Male', '2001-01-01', 'woman.png', '$2y$10$IIqHUVuNhBbZ5FU/4IYe7emx2ffASFigA06zttalJeOGkeJpfyEMK', 1, '2024-11-17 18:10:39', NULL, NULL),
(19, 'Garvik', 'User', '9581511512', 'garvik@gmail.com', 'Hyderabad, Telengana', 'Male', '1995-01-01', NULL, '$2y$10$S5c7qh2XKZNV36pE8PHRAu0DfAs8Y.gKvn3UF13ke4dprJPd49dYm', 1, '2024-11-17 18:30:33', NULL, 3),
(24, 'Test', 'Admin', '8989898989', 'test1@gmail.com', 'AKP', 'Male', '2002-01-01', 'profile.png', '$2y$10$NVxNZSl9Qp0gjO3D95R7JOwACKh3jm3Q.yoAMh8crlpzInkUg3/gm', 1, '2024-11-17 19:19:22', NULL, NULL),
(25, 'ganesh', 'User', '8877887788', 'ganesh@gmail.com', 'Bheemil', 'Male', '2001-01-01', 'investment (2).png', '$2y$10$81fA/UHb81a3C.RIntJ3quCgv.yzmkfCi3G5/vrohNZmoC5uYhciq', 0, '2024-11-17 19:20:26', NULL, NULL);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
