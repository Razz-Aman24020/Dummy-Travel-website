-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2026 at 03:22 AM
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
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_type` varchar(20) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `passengers` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `booking_type`, `item_id`, `booking_date`, `travel_date`, `passengers`, `total_amount`, `status`) VALUES
(1, 1, 'Flight', 1, '2026-09-19', '2026-10-05', 2, 8500.00, 'Confirmed'),
(2, 2, 'Hotel', 1, '2026-09-19', '2026-10-12', 1, 4200.00, 'Confirmed'),
(3, 1, 'Bus', 1, '2026-09-19', '2026-10-08', 3, 1350.00, 'Pending'),
(4, 3, 'Train', 1, '2026-09-19', '2026-10-20', 2, 3600.00, 'Cancelled'),
(5, 0, 'flight', 1234567890, '2026-09-19', '2026-09-29', 1, 5000.00, 'Pending'),
(6, 0, 'flight', 2, '2026-09-19', '2026-09-26', 1, 5100.00, 'Pending'),
(7, 0, 'flight', 2, '2026-09-25', '2026-09-30', 1, 5100.00, 'Pending'),
(8, 0, 'flight', 3, '2026-09-25', '2026-09-30', 10, 32000.00, 'Pending'),
(9, 0, 'flight', 2, '2026-09-25', '2026-09-30', 5, 25500.00, 'Pending'),
(10, 0, 'flight', 3, '2026-09-25', '2026-09-29', 1, 3200.00, 'Pending'),
(11, 0, 'flight', 3, '2026-09-25', '2026-09-30', 1, 3200.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `flight_id` int(11) DEFAULT NULL,
  `airline` varchar(100) DEFAULT NULL,
  `flight_number` varchar(20) DEFAULT NULL,
  `from_city` varchar(100) DEFAULT NULL,
  `to_city` varchar(100) DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`flight_id`, `airline`, `flight_number`, `from_city`, `to_city`, `departure_time`, `arrival_time`, `price`, `seats`) VALUES
(1, 'IndiGo', '6E-123', 'Patna', 'Delhi', '08:30:00', '10:20:00', 4250.00, 32),
(2, 'Air India', 'AI-456', 'Delhi', 'Mumbai', '12:00:00', '14:15:00', 5100.00, 18),
(3, 'IndiGo', '6E-789', 'Kolkata', 'Patna', '17:30:00', '19:00:00', 3200.00, 29),
(4, 'Air India', 'AI-321', 'Patna', 'Bangalore', '09:00:00', '11:45:00', 6200.00, 20);

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `hotel_id` int(11) DEFAULT NULL,
  `hotel_name` varchar(150) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `room_type` varchar(100) DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `rooms_available` int(11) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `hotel_name`, `city`, `room_type`, `price_per_night`, `rooms_available`, `rating`) VALUES
(1, 'Hotel Grand Patna', 'Patna', 'Deluxe', 2100.00, 8, 4.2),
(2, 'City View Hotel', 'Delhi', 'Premium', 3500.00, 12, 4.5),
(3, 'Gaya Heritage', 'Gaya', 'Standard', 1400.00, 6, 4.0),
(4, 'Royal Palace Hotel', 'Mumbai', 'Deluxe', 4200.00, 10, 4.4),
(5, 'The Bihar Inn', 'Patna', 'Standard', 1200.00, 15, 3.9);

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE `trains` (
  `train_id` int(11) DEFAULT NULL,
  `train_name` varchar(100) DEFAULT NULL,
  `train_number` varchar(20) DEFAULT NULL,
  `from_city` varchar(100) DEFAULT NULL,
  `to_city` varchar(100) DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trains`
--

INSERT INTO `trains` (`train_id`, `train_name`, `train_number`, `from_city`, `to_city`, `departure_time`, `arrival_time`, `price`, `seats`) VALUES
(1, 'Rajdhani Express', '12309', 'Patna', 'Delhi', '19:10:00', '07:40:00', 1800.00, 85),
(2, 'Vikramshila Express', '12367', 'Bhagalpur', 'Delhi', '11:15:00', '05:45:00', 1250.00, 60),
(3, 'Patna Intercity', '13226', 'Patna', 'Ranchi', '06:45:00', '13:20:00', 650.00, 120),
(4, 'Magadh Express', '20802', 'Patna', 'Delhi', '08:00:00', '18:30:00', 950.00, 75);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` varchar(50) NOT NULL,
  `Email` varchar(60) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Phone` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `Email`, `Password`, `Phone`) VALUES
('Akash ', 'abcd@gmail.com', '123456789', 123456789),
('Akash ', 'abc@gmail.com', '123456789', 123567890),
('Akash ', 'abc@gmail.com', '$2y$10$QMqw/RbSi7Jx9khLEPqXyuNcg20pNwTEL8Chb0dPpHR/q9Nr.J2P6', 123456890),
('AMAN RAJ', 'amanrazz3305@gmail.com', '123', 2147483647),
('Aman Raj', 'amanrazz3305@gmail.com', '123', 2147483647);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
