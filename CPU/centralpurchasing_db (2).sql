-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 03:14 AM
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
-- Database: `centralpurchasing_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `college`
--

CREATE TABLE `college` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `college`
--

INSERT INTO `college` (`id`, `name`) VALUES
(2, 'College of Nursing'),
(3, 'College of Engineering'),
(4, 'College Arts and Science'),
(5, 'School of Education'),
(6, 'College of Computer Studies'),
(7, 'School of Business & Management'),
(8, 'College of Agriculture');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `college_id` int(11) NOT NULL,
  `college_name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `college_id`, `college_name`) VALUES
(2, 'Computer Science', 1, 'College of Computer Studies'),
(3, 'Electrical Engineering', 3, '');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `fname` varchar(191) NOT NULL,
  `lname` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `college_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `role` tinyint(1) NOT NULL COMMENT '0-Faculty\r\n1-Coordinator\r\n2-Department_Head\r\n3-Dean',
  `image` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `fname`, `lname`, `email`, `college_id`, `department_id`, `role`, `image`) VALUES
(1, 'testName', 'testLname', 'testemail@gmail.com', 3, 3, 1, '1707547627.'),
(5, 'test', 'Faculty', 'Faculty@gmail.com', 1, 2, 0, '1707547639.'),
(7, 'Vinni', 'Uba', 'vinniuba1@gmail.com', 0, 0, 0, '1707493975.png');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `price`, `quantity`) VALUES
(999, 'Cisco Layer 3 Switch', 200000, 20);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_number` int(11) DEFAULT NULL,
  `purchase_request_id` int(11) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `item_description` varchar(191) DEFAULT NULL,
  `item_justification` text DEFAULT NULL,
  `item_date_requested` datetime DEFAULT current_timestamp(),
  `item_status` varchar(191) DEFAULT 'pending' COMMENT 'Pending\r\nApproved\r\nFor Pricing\r\nFor Pricing Officer\r\nIssued Pricing Officer\r\nFor Delivery by Supplier\r\nFor Pickup at Supplier\r\nFor Tagging\r\nFor Delivery to Requesting Unit\r\nCompleted\r\nRejected'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_number`, `purchase_request_id`, `item_qty`, `item_description`, `item_justification`, `item_date_requested`, `item_status`) VALUES
(182, 1, 100, 46, 'Final', 'Test', '2025-05-08 03:31:19', 'pending'),
(183, 1, 101, 67, 'asdasdasdasd', 'sadasd', '2025-05-08 03:47:32', 'pending'),
(184, 1, 102, 2, 'res', 'test', '2025-05-26 02:27:39', 'pending'),
(185, 1, 103, 3, 'test2', 'test2', '2025-05-26 22:26:38', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `items_history`
--

CREATE TABLE `items_history` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `purchase_request_id` int(11) NOT NULL,
  `change_made` varchar(191) NOT NULL,
  `last_modified_by` varchar(191) NOT NULL,
  `datetime_occured` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` int(11) NOT NULL,
  `purchase_request_number` varchar(255) DEFAULT '',
  `requestor_user_id` varchar(191) DEFAULT NULL,
  `requestor_user_name` varchar(191) DEFAULT NULL,
  `requestor_user_email` varchar(191) DEFAULT NULL,
  `printed_name` varchar(255) DEFAULT NULL,
  `signed_request` varchar(255) DEFAULT NULL,
  `signed_Requestor` varchar(191) DEFAULT NULL,
  `signed_Requestor_by` varchar(191) DEFAULT NULL,
  `unit_dept_college` varchar(255) DEFAULT NULL,
  `iptel_email` varchar(255) DEFAULT NULL,
  `above_50000` tinyint(1) DEFAULT NULL COMMENT '0=False\r\n1=True',
  `status` varchar(191) DEFAULT 'pending' COMMENT 'pending, approved, rejected, completed, partially-completed',
  `unit_head_approval` varchar(191) DEFAULT 'pending' COMMENT 'Recommending-Approval,\r\nPending,\r\nRejected',
  `unit_head` int(11) DEFAULT NULL COMMENT 'id of unit head user',
  `unit_head_approval_by` varchar(191) DEFAULT NULL,
  `vice_president_remarks` text DEFAULT NULL,
  `vice_president_approved` varchar(255) DEFAULT NULL,
  `signed_1` varchar(255) DEFAULT NULL COMMENT 'Vice President''s Signature',
  `signed_1_by` varchar(191) DEFAULT NULL,
  `vice_president_administration_remarks` text DEFAULT NULL,
  `vice_president_administration_approved` varchar(255) DEFAULT NULL,
  `signed_2` varchar(255) DEFAULT NULL COMMENT ' Vice President for Administration''s signature',
  `signed_2_by` varchar(191) DEFAULT NULL,
  `budget_controller_remarks` text DEFAULT NULL,
  `budget_controller_approved` varchar(255) DEFAULT NULL,
  `budget_controller_code` varchar(255) DEFAULT NULL,
  `signed_3` varchar(255) DEFAULT NULL COMMENT 'Budget Controller''s signature',
  `signed_3_by` varchar(191) DEFAULT NULL,
  `university_treasurer_remarks` text DEFAULT NULL,
  `university_treasurer_approved` varchar(255) DEFAULT NULL,
  `signed_4` varchar(255) DEFAULT NULL COMMENT 'University Treasurer''s signature',
  `signed_4_by` varchar(191) NOT NULL,
  `office_of_the_president_remarks` text DEFAULT NULL,
  `office_of_the_president_approved` varchar(255) DEFAULT NULL,
  `signed_5` varchar(255) DEFAULT NULL COMMENT 'Office of the President''s signature',
  `signed_5_by` varchar(191) DEFAULT NULL,
  `requested_date` datetime DEFAULT current_timestamp(),
  `acknowledged_by_cpu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = true, 0=false',
  `signed_by_cpu` varchar(255) DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `assigned_user_id` int(11) DEFAULT NULL COMMENT 'reference user id',
  `assigned_at_date` datetime DEFAULT NULL,
  `sign_status` varchar(191) DEFAULT NULL COMMENT 'signed_1 = Signed by Vice President\r\nsigned_2 = signed by Vice President Administration\r\nsigned_3 = signed by budget controller\r\nsigned_4 = signed by university treasurer\r\nsigned_5 = signed by president',
  `rejection_reason` varchar(191) DEFAULT NULL,
  `approval_remarks` varchar(191) DEFAULT NULL,
  `completed_remarks` varchar(191) DEFAULT NULL,
  `update_message` varchar(255) NOT NULL,
  `update_at` varchar(255) NOT NULL,
  `notification_read` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `purchase_request_number`, `requestor_user_id`, `requestor_user_name`, `requestor_user_email`, `printed_name`, `signed_request`, `signed_Requestor`, `signed_Requestor_by`, `unit_dept_college`, `iptel_email`, `above_50000`, `status`, `unit_head_approval`, `unit_head`, `unit_head_approval_by`, `vice_president_remarks`, `vice_president_approved`, `signed_1`, `signed_1_by`, `vice_president_administration_remarks`, `vice_president_administration_approved`, `signed_2`, `signed_2_by`, `budget_controller_remarks`, `budget_controller_approved`, `budget_controller_code`, `signed_3`, `signed_3_by`, `university_treasurer_remarks`, `university_treasurer_approved`, `signed_4`, `signed_4_by`, `office_of_the_president_remarks`, `office_of_the_president_approved`, `signed_5`, `signed_5_by`, `requested_date`, `acknowledged_by_cpu`, `signed_by_cpu`, `acknowledged_at`, `assigned_user_id`, `assigned_at_date`, `sign_status`, `rejection_reason`, `approval_remarks`, `completed_remarks`, `update_message`, `update_at`, `notification_read`) VALUES
(100, 'req-#00001', '2', 'usersuser', 'user@gmail.com', 'Grid Jezreel Gamutin', '681bb506f2eb3.png', '681bb538b6175.png', NULL, 'College of Computer Studies', 'user@gmail.com', NULL, 'completed', 'recommending-approval', 13, 'Ms Meldie Apag', NULL, '1', '6834ae02a2db4.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'No comment', '1', '681bb597d2958.png', '', NULL, NULL, NULL, NULL, '2025-05-08 03:31:18', 1, '681bb56935dfd.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Updated Requests', '2025-05-27 02:08:02', 1),
(101, 'req-#00002', '2', '', 'user@gmail.com', 'Anthony sign test', '681bb8d4840a1.png', '681bba09e4c36.png', NULL, 'College of Computer Studies', 'user@gmail.com', NULL, 'completed', 'recommending-approval', 13, 'testing v1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Goods', '1', '681bbada71c43.png', '', NULL, NULL, NULL, NULL, '2025-05-08 03:47:32', 1, '681bbac2d14b4.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 0),
(102, 'req-#00004', '2', '', 'user@gmail.com', 'test test', '6833611bb4667.png', NULL, NULL, 'College of Nursing', 'user@gmail.com', NULL, 'pending', 'pending', 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Goods', '1', '68350edf7ab80.png', '', NULL, NULL, NULL, NULL, '2025-05-26 02:27:39', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Updated Requests', '2025-05-27 09:01:19', 1),
(103, 'req-#00003', '2', '', 'user@gmail.com', 'test2', '68347a1e24794.png', '68349311e3c28.png', NULL, 'College of Engineering', 'user@gmail.com', NULL, 'completed', 'pending', 13, 'testdephead', NULL, '1', '6834ad64819a2.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N/A', '1', '6834b7953202c.png', '', NULL, NULL, NULL, NULL, '2025-05-26 22:26:38', 1, '6834b882e21a1.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Updated Requests', '2025-05-27 02:53:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests_attachments`
--

CREATE TABLE `purchase_requests_attachments` (
  `id` int(11) NOT NULL,
  `purchase_request_id` int(11) DEFAULT NULL,
  `file_name` varchar(191) DEFAULT NULL,
  `file_type` varchar(191) DEFAULT NULL,
  `file_size` varchar(191) DEFAULT NULL,
  `file_path` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requests_attachments`
--

INSERT INTO `purchase_requests_attachments` (`id`, `purchase_request_id`, `file_name`, `file_type`, `file_size`, `file_path`) VALUES
(22, 100, 'OIP (1).jpg', 'image/jpeg', '17893', 'uploads/request_documents/OIP (1).jpg'),
(23, 101, 'images (1).jpg', 'image/jpeg', '9461', 'uploads/request_documents/images (1).jpg'),
(24, 102, 'images (1).jpg', 'image/jpeg', '9461', 'uploads/request_documents/images (1).jpg'),
(25, 103, 'AS_267467836854273@1440780709178_l.png', 'image/png', '52239', 'uploads/request_documents/AS_267467836854273@1440780709178_l.png');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests_history`
--

CREATE TABLE `purchase_requests_history` (
  `id` int(11) NOT NULL,
  `purchase_request_id` int(11) NOT NULL,
  `change_made` varchar(191) DEFAULT NULL,
  `last_modified_by` varchar(191) DEFAULT NULL,
  `datetime_occured` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `college_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0-Received\r\n1-Registrar\r\n2- ETC',
  `request_received_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `actual_delivery_date` date NOT NULL,
  `semester` tinyint(1) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `assigned_user` int(11) DEFAULT NULL COMMENT 'Reference User ID whose admin',
  `gcalendar_eventID` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_status_history`
--

CREATE TABLE `request_status_history` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `old_status` tinyint(4) DEFAULT NULL,
  `new_status` tinyint(4) DEFAULT NULL,
  `change_date` datetime DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL COMMENT 'Reference to User ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_status_history`
--

INSERT INTO `request_status_history` (`id`, `request_id`, `old_status`, `new_status`, `change_date`, `edited_by`) VALUES
(11, 9, 0, 1, '2024-02-26 23:12:26', NULL),
(12, 9, 0, 1, '2024-02-26 23:12:26', 4),
(13, 9, 1, 2, '2024-02-26 23:13:12', 4),
(14, 10, 0, 0, '2024-02-26 23:14:53', 4),
(15, 10, 0, 1, '2024-02-26 23:15:10', 4),
(16, 9, 2, 2, '2024-02-26 23:20:31', 3),
(17, 10, 1, 1, '2024-02-26 23:20:36', 3),
(18, 1, 0, 2, '2024-02-26 23:21:25', 4),
(19, 1, 2, 3, '2024-02-26 23:21:44', 4),
(20, 29, 0, 0, '2024-03-03 00:40:47', 3),
(21, 30, 0, 0, '2024-03-03 02:13:19', 3),
(22, 30, 0, 0, '2024-03-03 02:15:58', 3),
(23, 30, 0, 0, '2024-03-03 02:23:06', 3),
(24, 9, 2, 8, '2024-03-03 03:16:57', 3),
(25, 30, 0, 2, '2024-03-11 16:56:44', 3);

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `id` int(11) NOT NULL,
  `school_year` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`id`, `school_year`) VALUES
(1, '2023-2024'),
(2, '2022-2023'),
(3, '2021-2022'),
(4, '2020-2021'),
(5, '2019-2020'),
(6, '2018-2019'),
(7, '2017-2018'),
(8, '2016-2017'),
(9, '2015-2016');

-- --------------------------------------------------------

--
-- Table structure for table `signatures`
--

CREATE TABLE `signatures` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `filename` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `signatures`
--

INSERT INTO `signatures` (`id`, `request_id`, `filename`) VALUES
(124, 59, '66055d10e1e1c.png'),
(125, 60, '66055da419169.png'),
(126, 61, '66055ed4c78ef.png'),
(127, 62, '66058c917e8e4.png'),
(128, 64, '6605c138c7907.png'),
(129, 65, '6605c205b7f95.png'),
(130, 67, '6605ca7715b3f.png'),
(131, 68, '6605cba2aeca4.png'),
(132, 68, '6605cc223bca6.png'),
(133, 68, '6605cce13c787.png'),
(134, 68, '6605cfb25b154.png'),
(135, 68, '6605d096e9e1c.png'),
(136, 69, '66065b1ca0be3.png'),
(137, 70, '66065c25e0dc2.png'),
(138, 70, '66065c6b5f28f.png'),
(139, 71, '660cafe6540e1.png'),
(140, 71, '660cb32ec702b.png'),
(141, 70, '66125f2f9fbc9.png'),
(142, 79, '661281515dc31.png'),
(143, 79, '66128209de860.png'),
(144, 77, '6612878483c01.png'),
(145, 77, '661287c46db69.png'),
(146, 77, '661287c8e8668.png'),
(147, 76, '6612914aa80d3.png'),
(148, 76, '66129ad893e48.png'),
(149, 84, '680a7e175097e.png'),
(150, 88, '680cf8c5ba4d9.png'),
(151, 88, '680d7d7894841.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(191) NOT NULL,
  `lname` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) DEFAULT NULL,
  `role_as` tinyint(4) DEFAULT 0 COMMENT '0 user\r\n1 Admin\r\n2 Super Admin\r\n3 Department Editor\r\n4 Unit Head\r\n5 Finance Department',
  `unit_dept_college` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `password`, `role_as`, `unit_dept_college`, `created_at`) VALUES
(2, 'users', 'user', 'user@gmail.com', 'user', 0, '0', '2024-02-04 08:15:55'),
(3, 'super', 'user', 'superuser@gmail.com', '1234', 2, '0', '2024-02-26 10:46:40'),
(4, 'department', 'editor', 'editor@gmail.com', '1234', 3, '0', '2024-02-26 14:15:23'),
(7, 'dep', 'head', 'dephead@gmail.com', '1234', 4, '0', '2024-04-07 08:30:08'),
(10, 'Joe', 'Quintilla', 'joe@gmail.com', '1234', 5, NULL, '2025-04-26 14:51:59'),
(11, 'Grid Jezreel', 'Gamutin', 'grid@gmail.com', '1234', 0, NULL, '2025-05-07 17:41:24'),
(12, 'Pranz Angelo', 'Cloma', 'pranz@gmail.com', '1234', 2, NULL, '2025-05-07 17:42:14'),
(13, 'Ray Anthony', 'Fuerzas', 'ray@gmail.com', '1234', 4, NULL, '2025-05-07 17:42:41'),
(14, 'Joe Vincent', 'Quintilla', 'joe2@gmail.com', '1234', 5, NULL, '2025-05-07 17:43:28'),
(15, 'Anthony', 'Davinci', 'anthonydavinci@gmail.com', 'gwapo', 0, NULL, '2025-05-07 19:06:02'),
(16, 'Anthony Chello', 'Navale', 'princejacob9112@gmail.com', '123', 6, NULL, '2025-05-26 17:04:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `college`
--
ALTER TABLE `college`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_history`
--
ALTER TABLE `items_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_requests_attachments`
--
ALTER TABLE `purchase_requests_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_requests_history`
--
ALTER TABLE `purchase_requests_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `request_id_2` (`request_id`);

--
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signatures`
--
ALTER TABLE `signatures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `college`
--
ALTER TABLE `college`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `items_history`
--
ALTER TABLE `items_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `purchase_requests_attachments`
--
ALTER TABLE `purchase_requests_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `purchase_requests_history`
--
ALTER TABLE `purchase_requests_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `request_status_history`
--
ALTER TABLE `request_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `signatures`
--
ALTER TABLE `signatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
