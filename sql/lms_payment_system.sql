-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2024 at 07:39 AM
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
-- Database: `lms_payment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `file_path`, `link`, `created_at`) VALUES
(4, 'Urgent Notice, Regarding Technical Issue', 'uploads/Urgent_notice.pdf', '', '2024-10-18 18:57:31');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `order_id`, `amount`, `status`, `created_at`, `payment_date`) VALUES
(23, 2, 'order_PAWMNy4uG5dvxZ', 10000, 'paid', '2024-10-18 13:41:47', '2024-10-18 14:05:17'),
(25, 4, 'order_PAWrfEsibL8MvY', 10000, 'paid', '2024-10-18 14:11:24', '2024-10-18 14:11:24'),
(26, 5, 'order_PAaB2CpJCayPRI', 10000, 'paid', '2024-10-18 17:26:09', '2024-10-18 17:26:09'),
(27, 7, 'order_PAaxZuph22zuMT', 10000, 'paid', '2024-10-18 18:12:06', '2024-10-18 18:12:06'),
(28, 8, 'order_PAbOPMhfdWaJSc', 10000, 'paid', '2024-10-18 18:37:30', '2024-10-18 18:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `amount_due` int(11) DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `amount_due`, `created_at`) VALUES
(2, 'Alok Guha Roy', 'hardc6700@gmail.com', '8250554784', '$2y$10$Do6Cds7svm9teHRxfRABseVS2xVnksBSPF.uujRwcCIQ0ul6PGUkq', 150, '2024-10-18 09:15:27'),
(4, 'Muffakerul Islam', 'pm@gmail.com', '9652525252', '$2y$10$a/d3.pA5bhqw4rHuIl41GeD.N3yOkqtAUr6i0s7K3ldvg5aKly2Fm', 150, '2024-10-18 10:22:35'),
(5, 'Rahul Bose', 'guhaalok19@gmail.com', '7047022898', '$2y$10$xrH2DUlSnnvvaDJUBEGu/uDAu.LbIGqdT9nZGHs02VZO9hU/PKXS.', 100, '2024-10-18 17:25:30'),
(7, 'Hiramoti Dutta', 'hd@ac.in', '8565656588', '$2y$10$r/WGr9OFYVxOq0W/DAPvWeTeg8Clt/XdQA/jK/Cm7ovJt8.iGPcXC', 100, '2024-10-18 18:11:09'),
(8, 'Pritam Mandal', 'pkm@gmail.com', '7364085174', '$2y$10$3g8E.l30rtR0lyFDBPRutuQjTamOXPneV3flHVle4pdVll9KggtYq', 100, '2024-10-18 18:19:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
