-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 11:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aideaura`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `profileImage` varchar(255) NOT NULL DEFAULT '/public/assets/images/avatar-image.png',
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `userID`, `profileImage`, `address`) VALUES
(3, 24, '/public/assets/images/avatar-image.png', '20, embillawatta road');

-- --------------------------------------------------------

--
-- Table structure for table `customercomplaints`
--

CREATE TABLE `customercomplaints` (
  `complaintID` bigint(20) UNSIGNED NOT NULL,
  `customerID` bigint(20) UNSIGNED NOT NULL,
  `issue_type` varchar(100) NOT NULL,
  `issue` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `priority` enum('Low','Medium','High','Critical') DEFAULT 'Medium',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customercomplaints_updates`
--

CREATE TABLE `customercomplaints_updates` (
  `updateID` bigint(20) UNSIGNED NOT NULL,
  `complaintID` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'In Progress',
  `comments` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobroles`
--

CREATE TABLE `jobroles` (
  `roleID` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '/public/assets/images/avatar-image.png',
  `description` varchar(255) NOT NULL,
  `isDelete` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobroles`
--

INSERT INTO `jobroles` (`roleID`, `name`, `image`, `description`, `isDelete`) VALUES
(1, 'Cook', 'assets/images/service_cook.png', 'Prepares and cooks meals according to dietary preferences', NULL),
(2, 'Cook 24-hour Live in', 'assets/images/service_cook24.png', 'Provides round-the-clock meal preparation and kitchen care.', NULL),
(3, 'Maid', 'assets/images/service_maid.png', 'Manages cleaning, laundry, and household upkeep', NULL),
(4, 'Nanny', 'assets/images/service_nanny.png', 'Cares for children, including supervision, meals, and activities', NULL),
(5, 'All rounder', 'assets/images/service_allrounder.png', 'Performs various household tasks and maintenance as needed', NULL),
(6, 'test', 'public/assets/images/roles/20230816_125947.jpg', '                 kjsffjgjhsdflkgjhfdlkghdlksghlkfjglk          ', NULL),
(8, 'test2', 'public/assets/images/roles/20230816_125947.jpg', '                            isfgkdjgdkjgkjfgkfng', NULL),
(9, 'test3', 'public/assets/images/roles/', '                            agfsdgsdgfsggfg', NULL),
(11, 'test4', 'public/assets/images/roles/', '                        gghgkjhgkjgkjhgkjg    ', NULL),
(13, 'test5', 'public/assets/images/roles/', '                            hjbhhgkhgjhgjhgjhbjhjhb', 1),
(15, 'test6', 'public/assets/images/roles/', '                            hjbhhgkhgjhgjhgjhbjhjhb', 1),
(17, 'test7', 'public/assets/images/roles/678ba62c9d8d9_20230816_125951.jpg', 'vfgzdsgfdgdfgfdgf', 1),
(18, 'test8', 'public/assets/images/roles/678ba66df3833_20230816_125951.jpg', 'dhghdghhhdgghd', 1),
(19, 'test10', 'public/assets/images/roles/678ba6d536779_20230816_125947.jpg', 'guiggggghgvhghg', 1),
(20, 'test11', 'public/assets/images/roles/678ba7111b07f_20230816_125947.jpg', 'kjjkkjkjkjnkn', 1),
(53, 'Cleaner1H', 'public/assets/images/roles/67a0c9eb6a121_20230816_125947.jpg', 'dfsfdsgfdgdfghhdhdhhh', 1),
(54, 'cleaner2,5', 'public/assets/images/roles/67a0cb549a71e_20230816_125947.jpg', 'fhgdshfghdfhgdfhgkdfhghdfkghkg', NULL),
(55, 'testcase 3', 'public/assets/images/roles/67a0cc487114e_20230816_125947.jpg', 'dhsafhjkdfkjasfkjhdaslkjfjkdaflkjdsaflkasdlkfjhlkjfhlkasdjdfhkajhfgdk', 1),
(56, 'test001', 'public/assets/images/roles/67ae21d830cf1_20230816_125951.jpg', 'ujfjsfjdsjklksdjlksdjflkjsdkljsajkfdslkjfsljfdslakflk', 1),
(57, 'test case 000', 'public/assets/images/roles/67ae2d36d0c3e_WhatsApp Image 2025-02-05 at 21.41.00_a7ba958f.jpg', 'dhuadfashfdsahfkjshakjfhaskhdkh', 1);

--
-- Triggers `jobroles`
--
DELIMITER $$
CREATE TRIGGER `after_jobrole_insert` AFTER INSERT ON `jobroles` FOR EACH ROW BEGIN
    INSERT INTO payment_rates (ServiceType, BasePrice, BaseHours) 
    VALUES (NEW.name, 0.00, 0.00);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_payment_rates_isDelete` AFTER UPDATE ON `jobroles` FOR EACH ROW BEGIN
    -- Check if the isDelete column is updated to 1
    IF NEW.isDelete = 1 AND (OLD.isDelete IS NULL) THEN
        UPDATE payment_rates 
        SET isDelete = 1
        WHERE ServiceType = NEW.name;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_rates`
--

CREATE TABLE `payment_rates` (
  `ServiceID` int(11) NOT NULL,
  `ServiceType` varchar(50) NOT NULL,
  `BasePrice` decimal(11,2) DEFAULT NULL,
  `BaseHours` decimal(11,2) DEFAULT NULL,
  `CreatedDate` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isDelete` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_rates`
--

INSERT INTO `payment_rates` (`ServiceID`, `ServiceType`, `BasePrice`, `BaseHours`, `CreatedDate`, `isDelete`) VALUES
(1, 'cook', 200.00, 2.50, '2025-02-03 17:22:38', NULL),
(2, 'Nanny', 100.00, 1.00, '2025-02-03 16:57:58', NULL),
(3, 'Cleaner1H', 150.00, 1.50, '2025-02-13 23:21:56', 1),
(4, 'cleaner2,5', 0.00, 0.00, '2025-02-03 19:27:40', NULL),
(5, 'testcase 3', 0.00, 0.00, '2025-02-03 19:31:44', NULL),
(6, 'test001', 8000.00, 1.25, '2025-02-13 22:20:22', NULL),
(7, 'test case 000', 0.00, 0.00, '2025-02-13 23:15:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `role` enum('admin','hrManager','opManager','financeManager','customer','worker') NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `isDelete` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `firstName`, `lastName`, `role`, `password`, `phone`, `email`, `createdAt`, `isDelete`) VALUES
(16, 'kavee', 'Kavee', 'Nirmal', 'worker', '$2y$10$jZFjSgr4CZ6w4n5C4Wn/HOJJpcsB7q.dHiqkTIAWvU1td7m84V5fG', 779230256, 'kaveesha@gmail.com', '2024-11-16 12:19:52', NULL),
(20, 'admin', 'Kamala', 'Gunaratnet', 'admin', '$2y$10$tTsTz97ibBL/WAsXWvuKN.II41Z1FmIvyZV78II1.xqo8rcjzQsJK', 771234562, 'admin@aideaura.com', '2024-11-19 23:42:09', NULL),
(24, 'hasitha', 'hasitha', 'dananjaya', 'customer', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 78, 'kaveesha@gmail.com', '2024-11-27 08:11:30', NULL),
(25, 'finance', 'Hasitha', 'DN', 'financeManager', '$2y$10$MlbY8W3gvJ67M8jYU6QNze9s8SGK76vt32yzODTyOPMewPB3gaxpC', 248794658, 'hasitha@gamil.com', '2024-12-28 09:32:24', NULL),
(26, 'hrmanager', 'Pasindu', 'Sadaruwan', 'hrManager', '$2y$10$l5coHb38RCH0QHIG1wDYwOBA3NwKud36/LrXvQ1vKV.8fqpea2mF6', 248794651, 'kasun@gmail.com', '2024-12-28 09:33:03', NULL),
(27, 'opmanager', 'Waruna', 'Chathuranga', 'financeManager', '$2y$10$qh.mR.ZUZMbWHUNVHPSCZe9Ho29DsO45Y/7ZqPmZE1HtZxp6wVE8e', 248794653, 'kamal@gmail.com', '2024-12-28 09:33:50', NULL),
(28, 'test', 'Ruwan', 'Sadaruwan', 'admin', '$2y$10$r93SpHH/9KL4rSu5AaoQ..YuTCySumkJ//0FQRZ5u9l.n/iuXAKni', 789456123, 'veen1234@gamil.com', '2024-12-28 09:35:11', 1),
(29, 'testPasindu', 'Pasindu', 'DHA', 'hrManager', '$2y$10$kB4RgT/XgQpHqDAZL0d3J.pBjR46hwsYe9VrhF0GJ0I38s2DlWPPm', 248794653, 'veen1234@gamil.com', '2025-02-03 11:36:42', 1),
(30, 'test1', 'Kusum', 'Chathuranga', 'worker', '$2y$10$iwhY2D/6lW.i1gOIgQaZN.Kbae8XGJ.YEu1RadUI2QBDSy3s/zJCK', 248794653, 'pasan@gmai.com', '2025-02-17 16:20:01', NULL),
(31, 'test2', 'Waruna', 'Chamara', 'worker', '$2y$10$Y/uOVONTCQSusabbH7KiyeakBdaqB4yPxEtgjURFT4Q9OoH8GUHrG', 248794653, 'kamal@gmail.com', '2025-02-17 16:22:15', NULL),
(32, 'AllROund', 'iujdgdfsjgfjdgj', 'sfgdgadsgfagagafgag', 'worker', '$2y$10$kizxu7n/24eUB/PDwp9yse420S0XPYX2Va2DHOkdYpMXrwa4wEL36', 248794653, 'kamal@gmail.com', '2025-02-20 13:27:52', NULL),
(33, 'maid123', 'gfdhgfdd', 'dfghdgfdhhg', 'worker', '$2y$10$qn304.MqVXIpkyVSXMve6evIkTKayO3GP.ImXoYyFH531RpIfHuKW', 248794653, 'kasun@gmail.com', '2025-02-20 13:28:55', NULL),
(34, 'nani', 'Pasindu', 'Chamara', 'worker', '$2y$10$v4Jj2XxvN2g7xv.2ZlhWheV2AHy7VYVQ/xpY8vtCa8uXVDZzCIxP6', 248794653, 'kamal@gmail.com', '2025-02-20 13:29:49', NULL),
(35, 'nanny1234', 'Kusum', 'Sadakan', 'worker', '$2y$10$OW9L9ZR4oPrzYu3qw1T/ruCv5OPtFhve8bYZiqo/I/i/ecfDfAtM6', 789456123, 'hasitha@gamil.com', '2025-04-07 11:27:15', NULL),
(36, 'cook24', 'Ruwan', 'DHA', 'worker', '$2y$10$PVNYHs6jn/ZivtVwgHdDAOToUfRadRAO0R0jV1oVsqPxIlV7HE.x2', 789456123, 'veen1234@gamil.com', '2025-04-07 14:27:46', NULL),
(39, 'nanny1', 'Chandunu', 'Sadaruwan', 'worker', '$2y$10$yy437jDMe3r8pwLklFb3Y.rRDbRnGABi/nSFjAzR.v3F3LqdwnFTO', 789456123, 'pasan@gmail.com', '2025-04-08 07:32:49', NULL),
(40, 'maidtest1', 'Kusum', 'Chamara', 'worker', '$2y$10$JiaYQYcsEWJVCawFcp2kCOW8zncI.ep6c6I5IZqvNnFxcFv4d2AtG', 789456123, 'sumeda@gmail.com', '2025-04-08 11:17:30', NULL),
(41, 'nimali', 'Nimali', 'Perera', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 771234567, 'nimali.p@gmail.com', '2025-04-09 21:35:19', NULL),
(42, 'kamal', 'Kamal', 'Silva', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 772345678, 'kamal.s@gmail.com', '2025-04-09 21:35:19', NULL),
(43, 'suneetha', 'Suneetha', 'Fernando', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 773456789, 'suneetha.f@gmail.com', '2025-04-09 21:35:19', NULL),
(44, 'ranjit', 'Ranjit', 'De Silva', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 774567890, 'ranjit.d@gmail.com', '2025-04-09 21:35:19', NULL),
(45, 'priyanka', 'Priyanka', 'Ratnayake', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 775678901, 'priyanka.r@gmail.com', '2025-04-09 21:35:19', NULL),
(46, 'saman', 'Saman', 'Bandara', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 776789012, 'saman.b@gmail.com', '2025-04-09 21:35:19', NULL),
(47, 'kumari', 'Kumari', 'Wijesinghe', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 777890123, 'kumari.w@gmail.com', '2025-04-09 21:35:19', NULL),
(48, 'dinesh', 'Dinesh', 'Gunawardena', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 778901234, 'dinesh.g@gmail.com', '2025-04-09 21:35:19', NULL),
(49, 'chamari', 'Chamari', 'Jayawardena', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 779012345, 'chamari.j@gmail.com', '2025-04-09 21:35:19', NULL),
(50, 'ruwan', 'Ruwan', 'Rajapakse', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 770123456, 'ruwan.r@gmail.com', '2025-04-09 21:35:19', NULL),
(51, 'nilmini', 'Nilmini', 'Herath', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 711234567, 'nilmini.h@gmail.com', '2025-04-09 21:35:19', NULL),
(52, 'sunil', 'Sunil', 'Peiris', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 712345678, 'sunil.p@gmail.com', '2025-04-09 21:35:19', NULL),
(53, 'anuradha', 'Anuradha', 'Weerasinghe', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 713456789, 'anuradha.w@gmail.com', '2025-04-09 21:35:19', NULL),
(54, 'lasantha', 'Lasantha', 'Alwis', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 714567890, 'lasantha.a@gmail.com', '2025-04-09 21:35:19', NULL),
(55, 'manori', 'Manori', 'Dissanayake', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 715678901, 'manori.d@gmail.com', '2025-04-09 21:35:19', NULL),
(56, 'jagath', 'Jagath', 'Karunaratne', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 716789012, 'jagath.k@gmail.com', '2025-04-09 21:35:19', NULL),
(57, 'dilani', 'Dilani', 'Perera', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 717890123, 'dilani.p@gmail.com', '2025-04-09 21:35:19', NULL),
(58, 'chaminda', 'Chaminda', 'Vithanage', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 718901234, 'chaminda.v@gmail.com', '2025-04-09 21:35:19', NULL),
(59, 'sandamali', 'Sandamali', 'Gamage', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 719012345, 'sandamali.g@gmail.com', '2025-04-09 21:35:19', NULL),
(60, 'prasanna', 'Prasanna', 'Jayasuriya', 'worker', '$2y$10$BUvaId1ikQEasDptqMg0Sewmr0m8vQoGf1qlsIyOHfolv3R6fXM/W', 710123456, 'prasanna.j@gmail.com', '2025-04-09 21:35:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `verification_requests`
--

CREATE TABLE `verification_requests` (
  `requestID` bigint(20) UNSIGNED NOT NULL,
  `workerID` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `gender` enum('male','female','prefer-not-to-say') NOT NULL,
  `spokenLanguages` varchar(255) NOT NULL,
  `hometown` varchar(255) NOT NULL,
  `nic` varchar(12) NOT NULL,
  `nationality` enum('sinhalese',' tamil','muslim','burger','other') NOT NULL,
  `age_range` enum('18-25','26-35','36-50','above_50') NOT NULL,
  `service_type` enum('babysitting','cleaning','gardening','cooking','housekeeping') NOT NULL,
  `experience_level` enum('entry','intermediate','expert') NOT NULL,
  `workLocations` varchar(255) NOT NULL,
  `certificates_path` varchar(255) DEFAULT NULL,
  `medical_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `bankNameCode` varchar(255) NOT NULL,
  `accountNumber` varchar(16) NOT NULL,
  `working_weekdays` enum('4-6','7-9','10-12','above_12') NOT NULL,
  `working_weekends` enum('4-6','7-9','10-12','above_12') NOT NULL,
  `allergies` text NOT NULL,
  `special_notes` text DEFAULT NULL,
  `isEditable` tinyint(1) DEFAULT 1,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verification_requests`
--

INSERT INTO `verification_requests` (`requestID`, `workerID`, `full_name`, `username`, `email`, `phone_number`, `gender`, `spokenLanguages`, `hometown`, `nic`, `nationality`, `age_range`, `service_type`, `experience_level`, `workLocations`, `certificates_path`, `medical_path`, `description`, `bankNameCode`, `accountNumber`, `working_weekdays`, `working_weekends`, `allergies`, `special_notes`, `isEditable`, `status`, `created_at`, `updated_at`) VALUES
(20, 21, 'Ruwan DHA', 'cook24', 'veen1234@gamil.com', '0789456123', 'female', '', 'homagama', '123456963', 'burger', '18-25', 'babysitting', 'entry', 'Ampara,Anuradhapura,Badulla', NULL, NULL, 'kbkbjkbgnb', 'BOC 1235', '2147483647', 'above_12', 'above_12', 'gbjhgjghjgj', 'gjhgjghjghjghj', 1, 'pending', '2025-04-07 14:29:32', '2025-04-08 07:39:52'),
(24, 22, 'Chandunu Sadaruwan', 'nanny1', 'pasan@gmail.com', '0789456123', 'female', 'Tamil', 'homagama', '2147483647', '', '26-35', 'cleaning', 'entry', 'Puttalam', NULL, NULL, 'czxvdfgbhfghfh', 'gfhgfhfghfghfh', '2147483647', '7-9', '10-12', 'ghgfhgfhfghfhf', 'fghgfhgfhfghfgh', 1, 'pending', '2025-04-08 07:56:42', '2025-04-08 07:56:42'),
(25, 23, 'Kusum Chamara', 'maidtest1', 'sumeda@gmail.com', '0789456123', 'male', 'Tamil', 'homagama', '456789123753', 'sinhalese', '26-35', 'babysitting', 'entry', 'Ampara,Galle,Polonnaruwa', NULL, NULL, 'mnvjhbjvfhjyjhgj', 'fghgfhgfhghghgfhf', '9874654736988521', '10-12', '4-6', 'fghgfhgfhgh', 'ghghghhgsdffadwa', 1, 'pending', '2025-04-08 11:20:22', '2025-04-08 11:20:22'),
(26, 13, 'Kaveesha Nirmal', 'kavee', 'kaveesha@gmail.com', '0774871617', 'male', 'Sinhala,English', 'Colombo', '200222202975', 'sinhalese', '26-35', 'babysitting', 'intermediate', 'Ratnapura', NULL, NULL, '', 'Com bank', '2211123455522234', '10-12', '7-9', 'No allergies', '', 1, 'pending', '2025-04-09 20:59:26', '2025-04-09 20:59:26'),
(27, 24, 'Nimali Perera', 'nimali', 'nimali.p@gmail.com', '0771234567', 'female', 'Sinhala,English', 'Colombo', '198022202975', 'sinhalese', '36-50', 'cooking', 'expert', 'Colombo,Galle,Kandy', NULL, NULL, 'Experienced cook specializing in Sri Lankan cuisine. Can prepare both traditional and modern dishes.', 'BOC 4567', '1234567890123456', 'above_12', '7-9', 'None', 'Available for special events and parties', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(28, 25, 'Kamal Silva', 'kamal', 'kamal.s@gmail.com', '0772345678', 'male', 'Sinhala,English,Tamil', 'Kandy', '198122202975', 'sinhalese', '26-35', 'cooking', 'intermediate', 'Kandy,Colombo,Nuwara Eliya', NULL, NULL, 'Specializes in both Sri Lankan and Western cuisine. Good with dietary restrictions.', 'Commercial 7890', '2345678901234567', '10-12', '4-6', 'Shellfish', 'Can prepare diabetic-friendly meals', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(29, 26, 'Suneetha Fernando', 'suneetha', 'suneetha.f@gmail.com', '0773456789', 'female', 'Sinhala,English', 'Anuradhapura', '198222202975', 'sinhalese', 'above_50', 'cooking', 'expert', 'Anuradhapura,Polonnaruwa,Dambulla', NULL, NULL, 'Traditional Sri Lankan cook with 30 years experience. Expert in village-style cooking.', 'NSB 1234', '3456789012345678', '7-9', '4-6', 'None', 'Best at preparing rice and curry meals', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(30, 27, 'Ranjit De Silva', 'ranjit', 'ranjit.d@gmail.com', '0774567890', 'male', 'Sinhala,Tamil', 'Galle', '198322202975', 'sinhalese', '36-50', 'cooking', 'intermediate', 'Galle,Matara,Hambantota', NULL, NULL, 'Specializes in seafood and coastal cuisine. Can prepare authentic Southern dishes.', 'HNB 5678', '4567890123456789', '10-12', '7-9', 'None', 'Can cook for large gatherings', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(31, 28, 'Priyanka Ratnayake', 'priyanka', 'priyanka.r@gmail.com', '0775678901', 'female', 'Sinhala,English', 'Nuwara Eliya', '198422202975', 'sinhalese', '26-35', 'cooking', 'intermediate', 'Nuwara Eliya,Kandy,Badulla', NULL, NULL, 'Live-in cook with experience in both Sri Lankan and Indian cuisine. Good with vegetarian dishes.', 'Sampath 9012', '5678901234567890', 'above_12', 'above_12', 'None', 'Available for long-term contracts', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(32, 29, 'Saman Bandara', 'saman', 'saman.b@gmail.com', '0776789012', 'male', 'Sinhala,English', 'Negombo', '198522202975', 'sinhalese', '36-50', 'cooking', 'expert', 'Negombo,Colombo,Gampaha', NULL, NULL, 'Expert in preparing meals for large families. Specializes in both local and continental cuisine.', 'Peoples 3456', '6789012345678901', 'above_12', '10-12', 'Peanuts', 'Can manage kitchen inventory', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(33, 30, 'Kumari Wijesinghe', 'kumari', 'kumari.w@gmail.com', '0777890123', 'female', 'Sinhala', 'Kurunegala', '198622202975', 'sinhalese', '26-35', 'cleaning', 'intermediate', 'Kurunegala,Colombo,Puttalam', NULL, NULL, 'Professional cleaner with experience in both homes and offices. Very thorough and detail-oriented.', 'DFCC 7890', '7890123456789012', '10-12', '4-6', 'Chemical cleaners', 'Brings own eco-friendly cleaning supplies', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(34, 31, 'Dinesh Gunawardena', 'dinesh', 'dinesh.g@gmail.com', '0778901234', 'male', 'Sinhala,Tamil', 'Matara', '198722202975', 'sinhalese', '18-25', 'cleaning', 'entry', 'Matara,Hambantota,Galle', NULL, NULL, 'Young and energetic cleaner. Willing to learn and take on any cleaning task.', 'Seylan 1234', '8901234567890123', '7-9', '4-6', 'None', 'Available for deep cleaning services', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(35, 32, 'Chamari Jayawardena', 'chamari', 'chamari.j@gmail.com', '0779012345', 'female', 'Sinhala,English', 'Ratnapura', '198822202975', 'sinhalese', '36-50', 'cleaning', 'expert', 'Ratnapura,Colombo,Kalutara', NULL, NULL, 'Experienced cleaner with specialization in post-construction cleaning and move-in/move-out cleaning.', 'NDB 5678', '9012345678901234', 'above_12', '7-9', 'None', 'Can organize and declutter spaces', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(36, 33, 'Ruwan Rajapakse', 'ruwan', 'ruwan.r@gmail.com', '0770123456', 'male', 'Sinhala,English,Tamil', 'Badulla', '198922202975', 'sinhalese', '26-35', 'cleaning', 'intermediate', 'Badulla,Monaragala,Ampara', NULL, NULL, 'Reliable cleaner with experience in both residential and commercial spaces.', 'HDFC 9012', '0123456789012345', '10-12', '4-6', 'None', 'Can work flexible hours', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(37, 34, 'Nilmini Herath', 'nilmini', 'nilmini.h@gmail.com', '0711234567', 'female', 'Sinhala,English', 'Trincomalee', '199022202975', 'sinhalese', '36-50', 'babysitting', 'expert', 'Trincomalee,Batticaloa,Anuradhapura', NULL, NULL, 'Experienced nanny with 15 years of childcare experience. Good with newborns and toddlers.', 'BOC 2345', '1234567890123456', 'above_12', 'above_12', 'None', 'Can help with homework and early education', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(38, 35, 'Sunil Peiris', 'sunil', 'sunil.p@gmail.com', '0712345678', 'male', 'Sinhala,English', 'Polonnaruwa', '199122202975', 'sinhalese', '26-35', 'babysitting', 'intermediate', 'Polonnaruwa,Dambulla,Anuradhapura', NULL, NULL, 'Male nanny specializing in caring for school-age children. Good at organizing educational activities.', 'Commercial 6789', '2345678901234567', '10-12', '7-9', 'None', 'Can teach basic computer skills', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(39, 36, 'Anuradha Weerasinghe', 'anuradha', 'anuradha.w@gmail.com', '0713456789', 'female', 'Sinhala,Tamil', 'Kegalle', '199222202975', 'sinhalese', 'above_50', 'babysitting', 'expert', 'Kegalle,Colombo,Kandy', NULL, NULL, 'Grandmotherly nanny with extensive experience. Very patient and loving with children.', 'NSB 0123', '3456789012345678', '7-9', '4-6', 'None', 'Can prepare special meals for children', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(40, 37, 'Lasantha Alwis', 'lasantha', 'lasantha.a@gmail.com', '0714567890', 'male', 'Sinhala,English', 'Kalutara', '199322202975', 'sinhalese', '26-35', 'housekeeping', 'intermediate', 'Kalutara,Colombo,Galle', NULL, NULL, 'All-rounder who can handle cleaning, cooking, and basic maintenance tasks.', 'HNB 4567', '4567890123456789', 'above_12', '10-12', 'None', 'Can do grocery shopping and errands', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(41, 38, 'Manori Dissanayake', 'manori', 'manori.d@gmail.com', '0715678901', 'female', 'Sinhala,English,Tamil', 'Ampara', '199422202975', 'sinhalese', '36-50', 'housekeeping', 'expert', 'Ampara,Batticaloa,Monaragala', NULL, NULL, 'Experienced all-rounder with skills in cooking, cleaning, and childcare. Very reliable.', 'Sampath 8901', '5678901234567890', 'above_12', 'above_12', 'None', 'Can handle all household tasks', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(42, 39, 'Jagath Karunaratne', 'jagath', 'jagath.k@gmail.com', '0716789012', 'male', 'Sinhala', 'Monaragala', '199522202975', 'sinhalese', '26-35', 'gardening', 'intermediate', 'Monaragala,Wellawaya,Buttala', NULL, NULL, 'Skilled in both indoor plant care and outdoor gardening. Can do basic landscaping.', 'Peoples 2345', '6789012345678901', '10-12', '4-6', 'Pollen', 'Can maintain vegetable gardens', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(43, 40, 'Dilani Perera', 'dilani', 'dilani.p@gmail.com', '0717890123', 'female', 'Sinhala,English', 'Hambantota', '199622202975', 'sinhalese', '18-25', 'housekeeping', 'entry', 'Hambantota,Matara,Galle', NULL, NULL, 'Young and energetic all-rounder willing to learn various household tasks.', 'DFCC 6789', '7890123456789012', '7-9', '4-6', 'None', 'Quick learner and adaptable', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(44, 41, 'Chaminda Vithanage', 'chaminda', 'chaminda.v@gmail.com', '0718901234', 'male', 'Sinhala,Tamil', 'Puttalam', '199722202975', 'sinhalese', '36-50', 'housekeeping', 'expert', 'Puttalam,Chilaw,Kurunegala', NULL, NULL, 'Experienced in managing entire households. Can cook, clean, and do minor repairs.', 'Seylan 0123', '8901234567890123', 'above_12', '7-9', 'None', 'Can supervise other household staff', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(45, 42, 'Sandamali Gamage', 'sandamali', 'sandamali.g@gmail.com', '0719012345', 'female', 'Sinhala,English', 'Batticaloa', '199822202975', 'sinhalese', '26-35', 'housekeeping', 'intermediate', 'Batticaloa,Ampara,Trincomalee', NULL, NULL, 'Skilled in both Eastern and Western cooking styles. Also good at household organization.', 'NDB 4567', '9012345678901234', '10-12', '4-6', 'None', 'Can prepare traditional Eastern dishes', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(46, 43, 'Prasanna Jayasuriya', 'prasanna', 'prasanna.j@gmail.com', '0710123456', 'male', 'Sinhala,English,Tamil', 'Vavuniya', '199922202975', 'sinhalese', '26-35', 'gardening', 'intermediate', 'Vavuniya,Anuradhapura,Polonnaruwa', NULL, NULL, 'Specializes in maintaining both ornamental and productive gardens. Knowledgeable about local plants.', 'HDFC 7890', '0123456789012345', '7-9', '4-6', 'None', 'Can advise on suitable plants for your area', 0, 'approved', '2025-04-09 21:35:20', '2025-04-09 21:35:20');

--
-- Triggers `verification_requests`
--
DELIMITER $$
CREATE TRIGGER `after_verification_update` AFTER UPDATE ON `verification_requests` FOR EACH ROW BEGIN
  DECLARE workerImg VARCHAR(255);
  DECLARE worker_address VARCHAR(255);
  IF NEW.status = 'approved' AND OLD.status != 'approved' THEN
    SELECT profileImage, address INTO workerImg, worker_address FROM worker w
    WHERE NEW.workerID = w.workerID;
    INSERT INTO `verified_workers` (
      `workerID`,
      `full_name`,
      `username`,
      `profileImage`,
      `address`,
      `email`,
      `phone_number`,
      `gender`,
      `spokenLanguages`,
      `hometown`,
      `nic`,
      `nationality`,
      `age_range`,
      `service_type`,
      `experience_level`,
      `workLocations`,
      `certificates_path`,
      `medical_path`,
      `description`,
      `bankNameCode`,
      `accountNumber`,
      `working_weekdays`,
      `working_weekends`,
      `created_at`
    )
    VALUES (
      NEW.workerID,
      NEW.full_name,
      NEW.username,
      workerImg,
      worker_address,
      NEW.email,
      NEW.phone_number,
      NEW.gender,
      NEW.spokenLanguages,
      NEW.hometown,
      NEW.nic,
      NEW.nationality,
      NEW.age_range,
      NEW.service_type,
      NEW.experience_level,
      NEW.workLocations,
      NEW.certificates_path,
      NEW.medical_path,
      NEW.description,
      NEW.bankNameCode,
      NEW.accountNumber,
      NEW.working_weekdays,
      NEW.working_weekends,
      NEW.created_at
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `verified_workers`
--

CREATE TABLE `verified_workers` (
  `workerID` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `profileImage` varchar(255) DEFAULT '/public/assets/images/avatar-image.png',
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `gender` enum('male','female','prefer-not-to-say') NOT NULL,
  `spokenLanguages` varchar(255) NOT NULL,
  `hometown` varchar(255) NOT NULL,
  `nic` varchar(12) NOT NULL,
  `nationality` enum('sinhalese',' tamil','muslim','burger','other') NOT NULL,
  `age_range` enum('18-25','26-35','36-50','above_50') NOT NULL,
  `service_type` enum('babysitting','cleaning','gardening','cooking','housekeeping') NOT NULL,
  `experience_level` enum('entry','intermediate','expert') NOT NULL,
  `workLocations` varchar(255) NOT NULL,
  `certificates_path` varchar(255) DEFAULT NULL,
  `medical_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `bankNameCode` varchar(255) NOT NULL,
  `accountNumber` varchar(16) NOT NULL,
  `working_weekdays` enum('4-6','7-9','10-12','above_12') NOT NULL,
  `working_weekends` enum('4-6','7-9','10-12','above_12') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verified_workers`
--

INSERT INTO `verified_workers` (`workerID`, `full_name`, `username`, `profileImage`, `address`, `email`, `phone_number`, `gender`, `spokenLanguages`, `hometown`, `nic`, `nationality`, `age_range`, `service_type`, `experience_level`, `workLocations`, `certificates_path`, `medical_path`, `description`, `bankNameCode`, `accountNumber`, `working_weekdays`, `working_weekends`, `created_at`, `verified_at`) VALUES
(24, 'Nimali Perera', 'nimali', '/public/assets/images/avatar-image.png', '123/1, Galle Road, Colombo 03', 'nimali.p@gmail.com', '0771234567', 'female', 'Sinhala,English', 'Colombo', '198022202975', 'sinhalese', '36-50', 'cooking', 'expert', 'Colombo,Galle,Kandy', NULL, NULL, 'Experienced cook specializing in Sri Lankan cuisine. Can prepare both traditional and modern dishes.', 'BOC 4567', '1234567890123456', 'above_12', '7-9', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(25, 'Kamal Silva', 'kamal', '/public/assets/images/avatar-image.png', '45, Main Street, Kandy', 'kamal.s@gmail.com', '0772345678', 'male', 'Sinhala,English,Tamil', 'Kandy', '198122202975', 'sinhalese', '26-35', 'cooking', 'intermediate', 'Kandy,Colombo,Nuwara Eliya', NULL, NULL, 'Specializes in both Sri Lankan and Western cuisine. Good with dietary restrictions.', 'Commercial 7890', '2345678901234567', '10-12', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(26, 'Suneetha Fernando', 'suneetha', '/public/assets/images/avatar-image.png', '78, Temple Road, Anuradhapura', 'suneetha.f@gmail.com', '0773456789', 'female', 'Sinhala,English', 'Anuradhapura', '198222202975', 'sinhalese', 'above_50', 'cooking', 'expert', 'Anuradhapura,Polonnaruwa,Dambulla', NULL, NULL, 'Traditional Sri Lankan cook with 30 years experience. Expert in village-style cooking.', 'NSB 1234', '3456789012345678', '7-9', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(27, 'Ranjit De Silva', 'ranjit', '/public/assets/images/avatar-image.png', '12, Beach Road, Galle', 'ranjit.d@gmail.com', '0774567890', 'male', 'Sinhala,Tamil', 'Galle', '198322202975', 'sinhalese', '36-50', 'cooking', 'intermediate', 'Galle,Matara,Hambantota', NULL, NULL, 'Specializes in seafood and coastal cuisine. Can prepare authentic Southern dishes.', 'HNB 5678', '4567890123456789', '10-12', '7-9', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(28, 'Priyanka Ratnayake', 'priyanka', '/public/assets/images/avatar-image.png', '34, Hill Street, Nuwara Eliya', 'priyanka.r@gmail.com', '0775678901', 'female', 'Sinhala,English', 'Nuwara Eliya', '198422202975', 'sinhalese', '26-35', 'cooking', 'intermediate', 'Nuwara Eliya,Kandy,Badulla', NULL, NULL, 'Live-in cook with experience in both Sri Lankan and Indian cuisine. Good with vegetarian dishes.', 'Sampath 9012', '5678901234567890', 'above_12', 'above_12', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(29, 'Saman Bandara', 'saman', '/public/assets/images/avatar-image.png', '56, Lake Road, Negombo', 'saman.b@gmail.com', '0776789012', 'male', 'Sinhala,English', 'Negombo', '198522202975', 'sinhalese', '36-50', 'cooking', 'expert', 'Negombo,Colombo,Gampaha', NULL, NULL, 'Expert in preparing meals for large families. Specializes in both local and continental cuisine.', 'Peoples 3456', '6789012345678901', 'above_12', '10-12', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(30, 'Kumari Wijesinghe', 'kumari', '/public/assets/images/avatar-image.png', '89, Park Avenue, Kurunegala', 'kumari.w@gmail.com', '0777890123', 'female', 'Sinhala', 'Kurunegala', '198622202975', 'sinhalese', '26-35', 'cleaning', 'intermediate', 'Kurunegala,Colombo,Puttalam', NULL, NULL, 'Professional cleaner with experience in both homes and offices. Very thorough and detail-oriented.', 'DFCC 7890', '7890123456789012', '10-12', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(31, 'Dinesh Gunawardena', 'dinesh', '/public/assets/images/avatar-image.png', '23, River Street, Matara', 'dinesh.g@gmail.com', '0778901234', 'male', 'Sinhala,Tamil', 'Matara', '198722202975', 'sinhalese', '18-25', 'cleaning', 'entry', 'Matara,Hambantota,Galle', NULL, NULL, 'Young and energetic cleaner. Willing to learn and take on any cleaning task.', 'Seylan 1234', '8901234567890123', '7-9', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(32, 'Chamari Jayawardena', 'chamari', '/public/assets/images/avatar-image.png', '67, Flower Lane, Ratnapura', 'chamari.j@gmail.com', '0779012345', 'female', 'Sinhala,English', 'Ratnapura', '198822202975', 'sinhalese', '36-50', 'cleaning', 'expert', 'Ratnapura,Colombo,Kalutara', NULL, NULL, 'Experienced cleaner with specialization in post-construction cleaning and move-in/move-out cleaning.', 'NDB 5678', '9012345678901234', 'above_12', '7-9', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(33, 'Ruwan Rajapakse', 'ruwan', '/public/assets/images/avatar-image.png', '90, Mountain View, Badulla', 'ruwan.r@gmail.com', '0770123456', 'male', 'Sinhala,English,Tamil', 'Badulla', '198922202975', 'sinhalese', '26-35', 'cleaning', 'intermediate', 'Badulla,Monaragala,Ampara', NULL, NULL, 'Reliable cleaner with experience in both residential and commercial spaces.', 'HDFC 9012', '0123456789012345', '10-12', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(34, 'Nilmini Herath', 'nilmini', '/public/assets/images/avatar-image.png', '11, Ocean Drive, Trincomalee', 'nilmini.h@gmail.com', '0711234567', 'female', 'Sinhala,English', 'Trincomalee', '199022202975', 'sinhalese', '36-50', 'babysitting', 'expert', 'Trincomalee,Batticaloa,Anuradhapura', NULL, NULL, 'Experienced nanny with 15 years of childcare experience. Good with newborns and toddlers.', 'BOC 2345', '1234567890123456', 'above_12', 'above_12', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(35, 'Sunil Peiris', 'sunil', '/public/assets/images/avatar-image.png', '22, Garden Path, Polonnaruwa', 'sunil.p@gmail.com', '0712345678', 'male', 'Sinhala,English', 'Polonnaruwa', '199122202975', 'sinhalese', '26-35', 'babysitting', 'intermediate', 'Polonnaruwa,Dambulla,Anuradhapura', NULL, NULL, 'Male nanny specializing in caring for school-age children. Good at organizing educational activities.', 'Commercial 6789', '2345678901234567', '10-12', '7-9', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(36, 'Anuradha Weerasinghe', 'anuradha', '/public/assets/images/avatar-image.png', '33, Valley Road, Kegalle', 'anuradha.w@gmail.com', '0713456789', 'female', 'Sinhala,Tamil', 'Kegalle', '199222202975', 'sinhalese', 'above_50', 'babysitting', 'expert', 'Kegalle,Colombo,Kandy', NULL, NULL, 'Grandmotherly nanny with extensive experience. Very patient and loving with children.', 'NSB 0123', '3456789012345678', '7-9', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(37, 'Lasantha Alwis', 'lasantha', '/public/assets/images/avatar-image.png', '44, Sunset Boulevard, Kalutara', 'lasantha.a@gmail.com', '0714567890', 'male', 'Sinhala,English', 'Kalutara', '199322202975', 'sinhalese', '26-35', 'housekeeping', 'intermediate', 'Kalutara,Colombo,Galle', NULL, NULL, 'All-rounder who can handle cleaning, cooking, and basic maintenance tasks.', 'HNB 4567', '4567890123456789', 'above_12', '10-12', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(38, 'Manori Dissanayake', 'manori', '/public/assets/images/avatar-image.png', '55, Sunrise Avenue, Ampara', 'manori.d@gmail.com', '0715678901', 'female', 'Sinhala,English,Tamil', 'Ampara', '199422202975', 'sinhalese', '36-50', 'housekeeping', 'expert', 'Ampara,Batticaloa,Monaragala', NULL, NULL, 'Experienced all-rounder with skills in cooking, cleaning, and childcare. Very reliable.', 'Sampath 8901', '5678901234567890', 'above_12', 'above_12', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(39, 'Jagath Karunaratne', 'jagath', '/public/assets/images/avatar-image.png', '66, Forest Lane, Monaragala', 'jagath.k@gmail.com', '0716789012', 'male', 'Sinhala', 'Monaragala', '199522202975', 'sinhalese', '26-35', 'gardening', 'intermediate', 'Monaragala,Wellawaya,Buttala', NULL, NULL, 'Skilled in both indoor plant care and outdoor gardening. Can do basic landscaping.', 'Peoples 2345', '6789012345678901', '10-12', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(40, 'Dilani Perera', 'dilani', '/public/assets/images/avatar-image.png', '77, Hilltop Road, Hambantota', 'dilani.p@gmail.com', '0717890123', 'female', 'Sinhala,English', 'Hambantota', '199622202975', 'sinhalese', '18-25', 'housekeeping', 'entry', 'Hambantota,Matara,Galle', NULL, NULL, 'Young and energetic all-rounder willing to learn various household tasks.', 'DFCC 6789', '7890123456789012', '7-9', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(41, 'Chaminda Vithanage', 'chaminda', '/public/assets/images/avatar-image.png', '88, Lakeview Drive, Puttalam', 'chaminda.v@gmail.com', '0718901234', 'male', 'Sinhala,Tamil', 'Puttalam', '199722202975', 'sinhalese', '36-50', 'housekeeping', 'expert', 'Puttalam,Chilaw,Kurunegala', NULL, NULL, 'Experienced in managing entire households. Can cook, clean, and do minor repairs.', 'Seylan 0123', '8901234567890123', 'above_12', '7-9', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(42, 'Sandamali Gamage', 'sandamali', '/public/assets/images/avatar-image.png', '99, Riverside, Batticaloa', 'sandamali.g@gmail.com', '0719012345', 'female', 'Sinhala,English', 'Batticaloa', '199822202975', 'sinhalese', '26-35', 'housekeeping', 'intermediate', 'Batticaloa,Ampara,Trincomalee', NULL, NULL, 'Skilled in both Eastern and Western cooking styles. Also good at household organization.', 'NDB 4567', '9012345678901234', '10-12', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20'),
(43, 'Prasanna Jayasuriya', 'prasanna', '/public/assets/images/avatar-image.png', '100, Mountain Peak, Vavuniya', 'prasanna.j@gmail.com', '0710123456', 'male', 'Sinhala,English,Tamil', 'Vavuniya', '199922202975', 'sinhalese', '26-35', 'gardening', 'intermediate', 'Vavuniya,Anuradhapura,Polonnaruwa', NULL, NULL, 'Specializes in maintaining both ornamental and productive gardens. Knowledgeable about local plants.', 'HDFC 7890', '0123456789012345', '7-9', '4-6', '2025-04-09 21:35:20', '2025-04-09 21:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `worker`
--

CREATE TABLE `worker` (
  `workerID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `profileImage` varchar(255) NOT NULL DEFAULT '/public/assets/images/avatar-image.png',
  `address` varchar(255) NOT NULL,
  `isVerified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worker`
--

INSERT INTO `worker` (`workerID`, `userID`, `profileImage`, `address`, `isVerified`) VALUES
(13, 16, '/public/assets/images/avatar-image.png', '679/1, Ambillawatta Road, Boralesgamuwa, Colombo, Sri Lanka', 1),
(15, 30, '/public/assets/images/avatar-image.png', 'jjdfgkjdsfkdfkjgdkjh', 0),
(16, 31, '/public/assets/images/avatar-image.png', 'jkjhkjhlkhlkhlkhlk', 0),
(17, 32, '/public/assets/images/avatar-image.png', 'jkjhkjhlkhlkhlkhlk', 0),
(18, 33, '/public/assets/images/avatar-image.png', 'jjdfgkjdsfkdfkjgdkjh', 0),
(19, 34, '/public/assets/images/avatar-image.png', 'jkjhkjhlkhlkhlkhlk', 0),
(20, 35, '/public/assets/images/avatar-image.png', 'jkjhkjhlkhlkhlkhlk', 0),
(21, 36, '/public/assets/images/avatar-image.png', 'jjdfgkjdsfkdfkjgdkjh', 0),
(22, 39, '/public/assets/images/avatar-image.png', 'jjdfgkjdsfkdfkjgdkjh', 0),
(23, 40, '/public/assets/images/avatar-image.png', 'jjdfgkjdsfkdfkjgdkjh', 0),
(24, 41, '/public/assets/images/avatar-image.png', '123/1, Galle Road, Colombo 03', 1),
(25, 42, '/public/assets/images/avatar-image.png', '45, Main Street, Kandy', 1),
(26, 43, '/public/assets/images/avatar-image.png', '78, Temple Road, Anuradhapura', 1),
(27, 44, '/public/assets/images/avatar-image.png', '12, Beach Road, Galle', 1),
(28, 45, '/public/assets/images/avatar-image.png', '34, Hill Street, Nuwara Eliya', 1),
(29, 46, '/public/assets/images/avatar-image.png', '56, Lake Road, Negombo', 1),
(30, 47, '/public/assets/images/avatar-image.png', '89, Park Avenue, Kurunegala', 1),
(31, 48, '/public/assets/images/avatar-image.png', '23, River Street, Matara', 1),
(32, 49, '/public/assets/images/avatar-image.png', '67, Flower Lane, Ratnapura', 1),
(33, 50, '/public/assets/images/avatar-image.png', '90, Mountain View, Badulla', 1),
(34, 51, '/public/assets/images/avatar-image.png', '11, Ocean Drive, Trincomalee', 1),
(35, 52, '/public/assets/images/avatar-image.png', '22, Garden Path, Polonnaruwa', 1),
(36, 53, '/public/assets/images/avatar-image.png', '33, Valley Road, Kegalle', 1),
(37, 54, '/public/assets/images/avatar-image.png', '44, Sunset Boulevard, Kalutara', 1),
(38, 55, '/public/assets/images/avatar-image.png', '55, Sunrise Avenue, Ampara', 1),
(39, 56, '/public/assets/images/avatar-image.png', '66, Forest Lane, Monaragala', 1),
(40, 57, '/public/assets/images/avatar-image.png', '77, Hilltop Road, Hambantota', 1),
(41, 58, '/public/assets/images/avatar-image.png', '88, Lakeview Drive, Puttalam', 1),
(42, 59, '/public/assets/images/avatar-image.png', '99, Riverside, Batticaloa', 1),
(43, 60, '/public/assets/images/avatar-image.png', '100, Mountain Peak, Vavuniya', 1);

-- --------------------------------------------------------

--
-- Table structure for table `worker_roles`
--

CREATE TABLE `worker_roles` (
  `workerID` bigint(20) UNSIGNED NOT NULL,
  `roleID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worker_roles`
--

INSERT INTO `worker_roles` (`workerID`, `roleID`) VALUES
(13, 1),
(13, 3),
(13, 5),
(15, 4),
(16, 2),
(17, 5),
(18, 3),
(19, 4),
(20, 4),
(21, 2),
(22, 4),
(23, 3),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 2),
(29, 2),
(30, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 4),
(35, 4),
(36, 4),
(37, 5),
(38, 5),
(39, 5),
(40, 5),
(41, 5),
(42, 5),
(43, 5);

-- --------------------------------------------------------

--
-- Table structure for table `workingschedule`
--

CREATE TABLE `workingschedule` (
  `scheduleID` bigint(20) UNSIGNED NOT NULL,
  `workerID` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workingschedule`
--

INSERT INTO `workingschedule` (`scheduleID`, `workerID`, `day_of_week`, `start_time`, `end_time`) VALUES
(1, 24, 'Monday', '08:00:00', '17:00:00'),
(2, 24, 'Tuesday', '08:00:00', '17:00:00'),
(3, 24, 'Wednesday', '08:00:00', '17:00:00'),
(4, 24, 'Thursday', '08:00:00', '17:00:00'),
(5, 24, 'Friday', '08:00:00', '17:00:00'),
(6, 25, 'Monday', '09:00:00', '18:00:00'),
(7, 25, 'Tuesday', '09:00:00', '18:00:00'),
(8, 25, 'Wednesday', '09:00:00', '18:00:00'),
(9, 25, 'Thursday', '09:00:00', '18:00:00'),
(10, 25, 'Friday', '09:00:00', '18:00:00'),
(11, 26, 'Monday', '07:00:00', '16:00:00'),
(12, 26, 'Tuesday', '07:00:00', '16:00:00'),
(13, 26, 'Wednesday', '07:00:00', '16:00:00'),
(14, 26, 'Thursday', '07:00:00', '16:00:00'),
(15, 26, 'Friday', '07:00:00', '16:00:00'),
(16, 27, 'Monday', '10:00:00', '19:00:00'),
(17, 27, 'Tuesday', '10:00:00', '19:00:00'),
(18, 27, 'Wednesday', '10:00:00', '19:00:00'),
(19, 27, 'Thursday', '10:00:00', '19:00:00'),
(20, 27, 'Friday', '10:00:00', '19:00:00'),
(21, 27, 'Saturday', '11:00:00', '16:00:00'),
(22, 27, 'Sunday', '11:00:00', '16:00:00'),
(23, 28, 'Monday', '00:00:00', '23:59:59'),
(24, 28, 'Tuesday', '00:00:00', '23:59:59'),
(25, 28, 'Wednesday', '00:00:00', '23:59:59'),
(26, 28, 'Thursday', '00:00:00', '23:59:59'),
(27, 28, 'Friday', '00:00:00', '23:59:59'),
(28, 28, 'Saturday', '00:00:00', '23:59:59'),
(29, 28, 'Sunday', '00:00:00', '23:59:59'),
(30, 29, 'Monday', '09:30:00', '18:30:00'),
(31, 29, 'Tuesday', '09:30:00', '18:30:00'),
(32, 29, 'Wednesday', '09:30:00', '18:30:00'),
(33, 29, 'Thursday', '09:30:00', '18:30:00'),
(34, 29, 'Friday', '09:30:00', '18:30:00'),
(35, 29, 'Saturday', '10:00:00', '17:00:00'),
(36, 29, 'Sunday', '10:00:00', '17:00:00'),
(37, 30, 'Monday', '08:00:00', '16:00:00'),
(38, 30, 'Tuesday', '08:00:00', '16:00:00'),
(39, 30, 'Wednesday', '08:00:00', '16:00:00'),
(40, 30, 'Thursday', '08:00:00', '16:00:00'),
(41, 30, 'Friday', '08:00:00', '16:00:00'),
(42, 31, 'Monday', '09:00:00', '17:00:00'),
(43, 31, 'Tuesday', '09:00:00', '17:00:00'),
(44, 31, 'Wednesday', '09:00:00', '17:00:00'),
(45, 31, 'Thursday', '09:00:00', '17:00:00'),
(46, 31, 'Friday', '09:00:00', '17:00:00'),
(47, 31, 'Saturday', '10:00:00', '14:00:00'),
(48, 32, 'Monday', '07:30:00', '16:30:00'),
(49, 32, 'Tuesday', '07:30:00', '16:30:00'),
(50, 32, 'Wednesday', '07:30:00', '16:30:00'),
(51, 32, 'Thursday', '07:30:00', '16:30:00'),
(52, 32, 'Friday', '07:30:00', '16:30:00'),
(53, 32, 'Saturday', '08:00:00', '12:00:00'),
(54, 33, 'Monday', '10:00:00', '18:00:00'),
(55, 33, 'Tuesday', '10:00:00', '18:00:00'),
(56, 33, 'Wednesday', '10:00:00', '18:00:00'),
(57, 33, 'Thursday', '10:00:00', '18:00:00'),
(58, 33, 'Friday', '10:00:00', '18:00:00'),
(59, 33, 'Sunday', '14:00:00', '18:00:00'),
(60, 34, 'Monday', '07:00:00', '19:00:00'),
(61, 34, 'Tuesday', '07:00:00', '19:00:00'),
(62, 34, 'Wednesday', '07:00:00', '19:00:00'),
(63, 34, 'Thursday', '07:00:00', '19:00:00'),
(64, 34, 'Friday', '07:00:00', '19:00:00'),
(65, 34, 'Saturday', '08:00:00', '16:00:00'),
(66, 35, 'Monday', '09:00:00', '17:00:00'),
(67, 35, 'Tuesday', '09:00:00', '17:00:00'),
(68, 35, 'Wednesday', '09:00:00', '17:00:00'),
(69, 35, 'Thursday', '09:00:00', '17:00:00'),
(70, 35, 'Friday', '09:00:00', '17:00:00'),
(71, 35, 'Saturday', '10:00:00', '14:00:00'),
(72, 35, 'Sunday', '10:00:00', '14:00:00'),
(73, 36, 'Monday', '08:30:00', '16:30:00'),
(74, 36, 'Tuesday', '08:30:00', '16:30:00'),
(75, 36, 'Wednesday', '08:30:00', '16:30:00'),
(76, 36, 'Thursday', '08:30:00', '16:30:00'),
(77, 36, 'Friday', '08:30:00', '16:30:00'),
(78, 37, 'Monday', '08:00:00', '17:00:00'),
(79, 37, 'Tuesday', '08:00:00', '17:00:00'),
(80, 37, 'Wednesday', '08:00:00', '17:00:00'),
(81, 37, 'Thursday', '08:00:00', '17:00:00'),
(82, 37, 'Friday', '08:00:00', '17:00:00'),
(83, 38, 'Monday', '07:00:00', '19:00:00'),
(84, 38, 'Tuesday', '07:00:00', '19:00:00'),
(85, 38, 'Wednesday', '07:00:00', '19:00:00'),
(86, 38, 'Thursday', '07:00:00', '19:00:00'),
(87, 38, 'Friday', '07:00:00', '19:00:00'),
(88, 38, 'Saturday', '09:00:00', '17:00:00'),
(89, 38, 'Sunday', '09:00:00', '17:00:00'),
(90, 39, 'Monday', '08:00:00', '16:00:00'),
(91, 39, 'Tuesday', '08:00:00', '16:00:00'),
(92, 39, 'Wednesday', '08:00:00', '16:00:00'),
(93, 39, 'Thursday', '08:00:00', '16:00:00'),
(94, 39, 'Friday', '08:00:00', '16:00:00'),
(95, 39, 'Saturday', '09:00:00', '13:00:00'),
(96, 40, 'Monday', '09:00:00', '17:00:00'),
(97, 40, 'Tuesday', '09:00:00', '17:00:00'),
(98, 40, 'Wednesday', '09:00:00', '17:00:00'),
(99, 40, 'Thursday', '09:00:00', '17:00:00'),
(100, 40, 'Friday', '09:00:00', '17:00:00'),
(101, 41, 'Monday', '06:00:00', '18:00:00'),
(102, 41, 'Tuesday', '06:00:00', '18:00:00'),
(103, 41, 'Wednesday', '06:00:00', '18:00:00'),
(104, 41, 'Thursday', '06:00:00', '18:00:00'),
(105, 41, 'Friday', '06:00:00', '18:00:00'),
(106, 41, 'Saturday', '06:00:00', '14:00:00'),
(107, 42, 'Monday', '08:30:00', '17:30:00'),
(108, 42, 'Tuesday', '08:30:00', '17:30:00'),
(109, 42, 'Wednesday', '08:30:00', '17:30:00'),
(110, 42, 'Thursday', '08:30:00', '17:30:00'),
(111, 42, 'Friday', '08:30:00', '17:30:00'),
(112, 42, 'Sunday', '09:00:00', '13:00:00'),
(113, 43, 'Monday', '07:00:00', '15:00:00'),
(114, 43, 'Tuesday', '07:00:00', '15:00:00'),
(115, 43, 'Wednesday', '07:00:00', '15:00:00'),
(116, 43, 'Thursday', '07:00:00', '15:00:00'),
(117, 43, 'Friday', '07:00:00', '15:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `customercomplaints`
--
ALTER TABLE `customercomplaints`
  ADD PRIMARY KEY (`complaintID`),
  ADD KEY `customerID` (`customerID`);

--
-- Indexes for table `customercomplaints_updates`
--
ALTER TABLE `customercomplaints_updates`
  ADD PRIMARY KEY (`updateID`),
  ADD KEY `complaintID` (`complaintID`);

--
-- Indexes for table `jobroles`
--
ALTER TABLE `jobroles`
  ADD PRIMARY KEY (`roleID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `payment_rates`
--
ALTER TABLE `payment_rates`
  ADD PRIMARY KEY (`ServiceID`),
  ADD UNIQUE KEY `serviceType` (`ServiceType`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `verification_requests`
--
ALTER TABLE `verification_requests`
  ADD PRIMARY KEY (`requestID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD KEY `workerID` (`workerID`);

--
-- Indexes for table `verified_workers`
--
ALTER TABLE `verified_workers`
  ADD PRIMARY KEY (`workerID`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`workerID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `worker_roles`
--
ALTER TABLE `worker_roles`
  ADD PRIMARY KEY (`workerID`,`roleID`),
  ADD KEY `roleID` (`roleID`);

--
-- Indexes for table `workingschedule`
--
ALTER TABLE `workingschedule`
  ADD PRIMARY KEY (`scheduleID`),
  ADD UNIQUE KEY `workerID` (`workerID`,`day_of_week`,`start_time`,`end_time`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customercomplaints`
--
ALTER TABLE `customercomplaints`
  MODIFY `complaintID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customercomplaints_updates`
--
ALTER TABLE `customercomplaints_updates`
  MODIFY `updateID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobroles`
--
ALTER TABLE `jobroles`
  MODIFY `roleID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `payment_rates`
--
ALTER TABLE `payment_rates`
  MODIFY `ServiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `verification_requests`
--
ALTER TABLE `verification_requests`
  MODIFY `requestID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `worker`
--
ALTER TABLE `worker`
  MODIFY `workerID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `workingschedule`
--
ALTER TABLE `workingschedule`
  MODIFY `scheduleID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `customercomplaints`
--
ALTER TABLE `customercomplaints`
  ADD CONSTRAINT `customercomplaints_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`) ON DELETE CASCADE;

--
-- Constraints for table `customercomplaints_updates`
--
ALTER TABLE `customercomplaints_updates`
  ADD CONSTRAINT `customercomplaints_updates_ibfk_1` FOREIGN KEY (`complaintID`) REFERENCES `customercomplaints` (`complaintID`) ON DELETE CASCADE;

--
-- Constraints for table `verification_requests`
--
ALTER TABLE `verification_requests`
  ADD CONSTRAINT `verification_requests_ibfk_1` FOREIGN KEY (`workerID`) REFERENCES `worker` (`workerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `verified_workers`
--
ALTER TABLE `verified_workers`
  ADD CONSTRAINT `verified_workers_ibfk_1` FOREIGN KEY (`workerID`) REFERENCES `worker` (`workerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `worker`
--
ALTER TABLE `worker`
  ADD CONSTRAINT `worker_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `worker_roles`
--
ALTER TABLE `worker_roles`
  ADD CONSTRAINT `worker_roles_ibfk_1` FOREIGN KEY (`workerID`) REFERENCES `worker` (`workerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `worker_roles_ibfk_2` FOREIGN KEY (`roleID`) REFERENCES `jobroles` (`roleID`) ON DELETE CASCADE;

--
-- Constraints for table `workingschedule`
--
ALTER TABLE `workingschedule`
  ADD CONSTRAINT `workingschedule_ibfk_1` FOREIGN KEY (`workerID`) REFERENCES `worker` (`workerID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
