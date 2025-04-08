-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 07:30 PM
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
(40, 'maidtest1', 'Kusum', 'Chamara', 'worker', '$2y$10$JiaYQYcsEWJVCawFcp2kCOW8zncI.ep6c6I5IZqvNnFxcFv4d2AtG', 789456123, 'sumeda@gmail.com', '2025-04-08 11:17:30', NULL);

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
(8, 13, 'fvbbcvbv dffdfdf', 'dvgcxfgfdgdfgf', 'kamal@gmail.com', '0718529635', 'male', 'Sinhala', 'kjiuohuytrrr', '0', 'sinhalese', '18-25', 'babysitting', 'entry', 'Anuradhapura', NULL, NULL, 'lcoiuv cxihxchvixchiocxhi', '', '0', '4-6', '4-6', '', 'xvvxfvcdfxvcfvvdxvfvgb', 1, 'pending', '2025-02-27 12:15:35', '2025-02-27 12:16:24'),
(20, 21, 'Ruwan DHA', 'cook24', 'veen1234@gamil.com', '0789456123', 'female', '', 'homagama', '123456963', 'burger', '18-25', 'babysitting', 'entry', 'Ampara,Anuradhapura,Badulla', NULL, NULL, 'kbkbjkbgnb', 'BOC 1235', '2147483647', 'above_12', 'above_12', 'gbjhgjghjgj', 'gjhgjghjghjghj', 1, 'pending', '2025-04-07 14:29:32', '2025-04-08 07:39:52'),
(24, 22, 'Chandunu Sadaruwan', 'nanny1', 'pasan@gmail.com', '0789456123', 'female', 'Tamil', 'homagama', '2147483647', '', '26-35', 'cleaning', 'entry', 'Puttalam', NULL, NULL, 'czxvdfgbhfghfh', 'gfhgfhfghfghfh', '2147483647', '7-9', '10-12', 'ghgfhgfhfghfhf', 'fghgfhgfhfghfgh', 1, 'pending', '2025-04-08 07:56:42', '2025-04-08 07:56:42'),
(25, 23, 'Kusum Chamara', 'maidtest1', 'sumeda@gmail.com', '0789456123', 'male', 'Tamil', 'homagama', '456789123753', 'sinhalese', '26-35', 'babysitting', 'entry', 'Ampara,Galle,Polonnaruwa', NULL, NULL, 'mnvjhbjvfhjyjhgj', 'fghgfhgfhghghgfhf', '9874654736988521', '10-12', '4-6', 'fghgfhgfhgh', 'ghghghhgsdffadwa', 1, 'pending', '2025-04-08 11:20:22', '2025-04-08 11:20:22');

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
(23, 40, '/public/assets/images/avatar-image.png', 'jjdfgkjdsfkdfkjgdkjh', 0);

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
(23, 3);

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
  MODIFY `userID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `verification_requests`
--
ALTER TABLE `verification_requests`
  MODIFY `requestID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `worker`
--
ALTER TABLE `worker`
  MODIFY `workerID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `workingschedule`
--
ALTER TABLE `workingschedule`
  MODIFY `scheduleID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
