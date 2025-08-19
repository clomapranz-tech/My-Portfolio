-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2024 at 09:23 AM
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
-- Database: `cpu_db`
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
(1, 'College of Computer Studies'),
(2, 'College of Nursing'),
(3, 'College of Engineering');

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
(1, 'Information Technology', 1, 'College of Computer Studies'),
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
(121, 1, 59, 1, 'Sonic Game', ' new', '2024-03-28 20:05:36', 'approved'),
(122, 2, 59, 1, 'Sonic 2 Game', ' new', '2024-03-28 20:05:36', 'pending'),
(123, 1, 60, 2, 'Soap', 'new ', '2024-03-28 20:08:04', 'completed'),
(124, 2, 60, 2, 'Shampoo', 'new ', '2024-03-28 20:08:04', 'pending'),
(125, 1, 61, 1, 'Coca-Cola', 'new ', '2024-03-28 20:13:08', 'pending'),
(126, 1, 62, 1, 'soap', ' new', '2024-03-28 23:28:17', 'pending'),
(127, 2, 62, 1, 'shampoo', ' new', '2024-03-28 23:28:17', 'pending'),
(131, 1, 66, 2, 'Birds', 'New ', '2024-03-29 03:20:15', 'pending'),
(134, 1, 68, 10, 'Beer', ' New', '2024-03-29 03:57:22', 'for_pricing'),
(135, 2, 68, 1, 'Coke', ' New', '2024-03-29 03:57:22', 'pending'),
(136, 1, 69, 1, 'Soap', 'new ', '2024-03-29 14:09:32', 'pending'),
(138, 1, 70, 1, 'Soap', ' New', '2024-03-29 14:13:57', 'pending'),
(139, 2, 70, 2, 'Shampoo', ' New', '2024-03-29 14:13:57', 'pending'),
(140, 1, 71, 1, 'Brand new layer 3 switch', ' new', '2024-04-03 09:24:54', 'pending'),
(141, 1, 72, 1, 'FILE ATTATCHMENTS', ' new', '2024-04-04 12:53:47', 'pending'),
(142, 1, 73, 1, 'Attatchment', 'new', '2024-04-04 12:54:50', 'pending'),
(143, 1, 74, 1, 'attatchment', ' new', '2024-04-04 12:56:11', 'pending'),
(144, 1, 75, 1, 'Attatch', ' new', '2024-04-04 12:58:36', 'pending'),
(145, 1, 76, 1, 'Attach', ' new', '2024-04-04 13:00:16', 'pending'),
(146, 1, 77, 1, 'Soap', ' new', '2024-04-07 16:07:54', 'pending'),
(147, 1, 78, 1, 'Soap', ' new', '2024-04-07 16:12:03', 'pending'),
(148, 1, 79, 1, 'FOR UNIT HEAD', 'new', '2024-04-07 18:52:34', 'completed');

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

--
-- Dumping data for table `items_history`
--

INSERT INTO `items_history` (`id`, `item_id`, `purchase_request_id`, `change_made`, `last_modified_by`, `datetime_occured`) VALUES
(13, 123, 60, 'Item status changed to completed', 'superuser', '2024-03-28 21:45:10'),
(14, 121, 59, 'Item status changed to approved', 'superuser', '2024-03-28 23:13:43'),
(15, 134, 68, 'Item status changed to for_pricing', 'superuser', '2024-04-03 09:47:33'),
(16, 148, 79, 'Item status changed to completed', 'superuser', '2024-04-07 20:02:10');

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
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `assigned_user_id` int(11) DEFAULT NULL COMMENT 'reference user id',
  `assigned_at_date` datetime DEFAULT NULL,
  `sign_status` varchar(191) DEFAULT NULL COMMENT 'signed_1 = Signed by Vice President\r\nsigned_2 = signed by Vice President Administration\r\nsigned_3 = signed by budget controller\r\nsigned_4 = signed by university treasurer\r\nsigned_5 = signed by president',
  `rejection_reason` varchar(191) DEFAULT NULL,
  `approval_remarks` varchar(191) DEFAULT NULL,
  `completed_remarks` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `purchase_request_number`, `requestor_user_id`, `requestor_user_name`, `requestor_user_email`, `printed_name`, `signed_Requestor`, `signed_Requestor_by`, `unit_dept_college`, `iptel_email`, `above_50000`, `status`, `unit_head_approval`, `unit_head`, `unit_head_approval_by`, `vice_president_remarks`, `vice_president_approved`, `signed_1`, `signed_1_by`, `vice_president_administration_remarks`, `vice_president_administration_approved`, `signed_2`, `signed_2_by`, `budget_controller_remarks`, `budget_controller_approved`, `budget_controller_code`, `signed_3`, `signed_3_by`, `university_treasurer_remarks`, `university_treasurer_approved`, `signed_4`, `signed_4_by`, `office_of_the_president_remarks`, `office_of_the_president_approved`, `signed_5`, `signed_5_by`, `requested_date`, `acknowledged_by_cpu`, `acknowledged_at`, `assigned_user_id`, `assigned_at_date`, `sign_status`, `rejection_reason`, `approval_remarks`, `completed_remarks`) VALUES
(60, '22', '3', 'superuser', 'superuser@gmail.com', 'BOb the builder', '66055da419169.png', NULL, 'Department of Greendale', 'bob@gmail.com', 0, 'partially-completed', 'pending', 7, ' ', '', '', '', NULL, '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', NULL, '2024-03-15 20:08:04', 1, '2024-04-07 03:42:54', NULL, NULL, 'WARNING: Sequence of signatures not followed!', NULL, NULL, NULL),
(61, '321', '3', 'superuser', 'superuser@gmail.com', 'Batman', '66055ed4c78ef.png', NULL, 'Department of Homeland Security', 'vinniuba1@gmail.com', 0, 'approved', 'pending', 7, 'Bruce Wayne', '', '', '', NULL, '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', NULL, '2024-03-15 20:13:08', 1, '2024-03-28 13:16:34', NULL, NULL, 'WARNING: Sequence of signatures not followed!', NULL, '', NULL),
(62, '', '3', 'superuser', 'superuser@gmail.com', 'Jeff', '66058c917e8e4.png', NULL, 'Department of Greendale', 'jeff@gmail.com', NULL, 'pending', 'pending', 7, 'Mr.Dean', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '2024-03-28 23:28:17', 0, NULL, NULL, NULL, 'WARNING: Sequence of signatures not followed!', NULL, NULL, NULL),
(66, '', '3', 'superuser', 'superuser@gmail.com', 'Jeff', ', ', NULL, 'Department of Greendale', 'vinniuba1@gmail.com', NULL, 'pending', 'pending', 7, 'Mr.Dean', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '2024-03-29 03:20:15', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, '', '6', 'Vinni Uba', 'vinniuba2@gmail.com', 'Vinni Uba', '6605ca7715b3f.png', NULL, 'Department of Greendale', 'vinniuba2@gmail.com', NULL, 'rejected', 'pending', 7, 'Mr.Dean', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '2024-03-29 03:52:23', 0, NULL, NULL, NULL, NULL, '', NULL, NULL),
(68, '32134', '6', 'Vinni Uba', 'vinniuba2@gmail.com', 'Vinni Uba', '6605cba2aeca4.png', NULL, 'Department of Greendale', 'vinniuba2@gmail.com', 0, 'approved', 'pending', 7, 'Bruce Wayne', 'HAHA', 'VP person', '6605cfb25b154.png', 'superuser@gmail.com', 'REMARKS', 'VPa Person', '6605d096e9e1c.png', 'superuser@gmail.com', '', '', '', '', NULL, '', '', '', '', '', '', '', NULL, '2024-03-29 03:57:22', 1, '2024-03-28 14:03:56', NULL, NULL, 'Signed by Vice President ', NULL, '', NULL),
(70, '12312321', '6', 'Vinni Uba', 'vinniuba2@gmail.com', 'Vinni Uba', '66065c25e0dc2.png', NULL, 'Department of Greendale', 'vinniuba2@gmail.com', 0, 'approved', 'pending', 7, 'Mr.Dean', 'HAHA', 'VP person', '66065c6b5f28f.png', 'superuser@gmail.com', 'editor', 'editor', '66125f2f9fbc9.png', 'editor@gmail.com', '', '', '', '', NULL, '', '', '', '', '', '', '', NULL, '2024-03-29 14:13:57', 1, '2024-03-28 23:15:03', 6, '2024-04-07 19:10:02', 'Signed by Vice President ', NULL, '', NULL),
(71, '', '6', 'Vinni Uba', 'vinniuba2@gmail.com', 'Requestor name', '660cafe6540e1.png', NULL, 'Department of COmpstud', '0968767', 0, 'pending', 'pending', 7, 'Unit head', 'GOod', 'asdasd', '660cb32ec702b.png', 'superuser@gmail.com', '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', NULL, '2024-04-03 09:24:54', 0, NULL, 1, NULL, 'Signed by Vice President ', NULL, NULL, NULL),
(76, '', '3', 'superuser', 'superuser@gmail.com', 'Jeff', '66129ad893e48.png', 'unithead@gmail.com', 'Department of Greendale', 'superuser@gmail.com', 0, 'pending', 'pending', 7, 'E  ', '', '', '', NULL, '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', NULL, '2024-04-04 13:00:16', 0, NULL, NULL, NULL, 'WARNING: Sequence of signatures not followed!', NULL, NULL, NULL),
(77, '', '3', 'superuser', 'superuser@gmail.com', 'BOb the builder', '661287c8e8668.png', NULL, 'Department of Greendale', 'superuser@gmail.com', 0, 'pending', 'recommending-approval', 7, 'UNIT HEAD GUY   ', '', '', '', NULL, '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', NULL, '2024-04-07 16:07:54', 0, NULL, NULL, NULL, 'WARNING: Sequence of signatures not followed!', NULL, NULL, NULL),
(78, '', '3', 'superuser', 'superuser@gmail.com', 'BOb the builder', NULL, NULL, 'Department of Greendale', 'superuser@gmail.com', NULL, 'pending', 'recommending-approval', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '2024-04-07 16:12:03', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, '', '3', 'superuser', 'superuser@gmail.com', 'Jeff', '', NULL, 'Department of Greendale', 'superuser@gmail.com', 0, 'completed', '', 7, ' UNIT HEAD NAME PLEASE UWU     ', '', '', '', NULL, '', '', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', NULL, '2024-04-07 18:52:34', 1, '2024-04-07 06:02:27', NULL, NULL, 'WARNING: Sequence of signatures not followed!', NULL, NULL, NULL);

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
(1, 78, 'images (1).jpg', 'image/jpeg', '9461', 'uploads/request_documents/images (1).jpg'),
(2, 79, 'Jolly.png', 'image/png', '93712', 'uploads/request_documents/Jolly.png');

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

--
-- Dumping data for table `purchase_requests_history`
--

INSERT INTO `purchase_requests_history` (`id`, `purchase_request_id`, `change_made`, `last_modified_by`, `datetime_occured`) VALUES
(45, 61, 'Request Approved', 'superuser', '2024-03-28 21:44:09'),
(46, 68, 'Request Approved', 'superuser', '2024-03-29 04:44:03'),
(47, 67, 'Request Rejected', 'superuser', '2024-03-29 04:46:29'),
(48, 67, 'Request Rejected', 'superuser', '2024-03-29 04:57:01'),
(49, 67, 'Request Rejected', 'superuser', '2024-03-29 04:59:55'),
(50, 70, 'Request Approved', 'superuser', '2024-03-29 14:15:53'),
(51, 79, 'Request Details Updated', 'unithead', '2024-04-07 19:33:52'),
(52, 79, 'Request Details Updated', 'unithead', '2024-04-07 19:39:45'),
(53, 77, 'Request Details Updated', 'unithead', '2024-04-07 19:47:39'),
(54, 77, 'Request Details Updated', 'unithead', '2024-04-07 19:48:02'),
(55, 79, 'Request Details Updated', 'superuser', '2024-04-07 20:02:30'),
(56, 76, 'Request Details Updated', 'unithead', '2024-04-07 21:08:40'),
(57, 79, 'Request Details Updated', 'departmenteditor', '2024-04-09 15:01:26'),
(58, 79, 'Request Details Updated', 'departmenteditor', '2024-04-09 15:01:35');

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

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `inventory_id`, `name`, `college_id`, `department_id`, `status`, `request_received_date`, `expected_delivery_date`, `actual_delivery_date`, `semester`, `school_year_id`, `assigned_user`, `gcalendar_eventID`) VALUES
(1, 999, 'Cisco Layer 3 Switch', 1, 1, 3, '2024-02-10', '2024-02-17', '2024-02-18', 2, 1, 1, NULL),
(9, 999, 'no assigned', 1, 1, 8, '2024-02-26', '2024-02-26', '2024-02-26', 1, 1, 1, NULL),
(10, 999, 'test super user 3', 1, 1, 1, '2024-01-18', '2024-02-26', '2024-02-26', 1, 1, 1, NULL),
(17, 999, 'CALENDAR TEST 2 WASSUP', 1, 1, 0, '2024-02-28', '2024-02-28', '2024-02-28', 2, 1, 1, NULL),
(18, 999, 'New Item 1', 2, 2, 1, '2024-03-01', '2024-03-05', '2024-03-05', 1, 2, 2, NULL),
(19, 999, 'New Item 2', 2, 2, 0, '2024-03-01', '2024-03-05', '2024-03-05', 1, 2, 2, NULL),
(20, 999, 'New Item 3', 2, 3, 3, '2024-03-02', '2024-03-07', '2024-03-07', 2, 2, 3, NULL),
(21, 999, 'New Item 4', 2, 3, 2, '2024-03-02', '2024-03-07', '2024-03-07', 2, 2, 3, NULL),
(22, 999, 'New Item 5', 2, 2, 1, '2024-03-03', '2024-03-08', '2024-03-08', 1, 2, 4, NULL),
(23, 999, 'New Item 6', 2, 3, 0, '2024-03-03', '2024-03-08', '2024-03-08', 1, 2, 4, NULL),
(24, 999, 'New Item 7', 2, 2, 3, '2024-03-04', '2024-03-09', '2024-03-09', 2, 2, 5, NULL),
(25, 999, 'New Item 8', 2, 3, 2, '2024-03-04', '2024-03-09', '2024-03-09', 2, 2, 5, NULL),
(26, 999, 'New Item 9', 2, 2, 1, '2024-03-05', '2024-03-10', '2024-03-10', 1, 2, 6, NULL),
(30, 999, 'GOOGLE CALANDAR ID', 1, 1, 2, '2024-03-13', '2024-03-14', '2024-03-04', 1, 1, 1, 'vm87iu2fpo852f45pba6nce9gs'),
(31, 999, 'NIBBA', 1, 1, 0, '2024-03-14', '2024-03-15', '2024-03-15', 2, 1, 1, '4sc8v9s1e5edc68kvpug8tkbac');

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
(148, 76, '66129ad893e48.png');

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
  `role_as` tinyint(4) DEFAULT 0 COMMENT '0 user\r\n1 Admin\r\n2 Super Admin\r\n3 Department Editor\r\n4 Unit Head',
  `unit_dept_college` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `password`, `role_as`, `unit_dept_college`, `created_at`) VALUES
(1, 'Vinni', 'Uba', 'vinniuba1@gmail.com', '1234', 1, '1', '2024-02-04 07:38:18'),
(2, 'users', 'user', 'user@gmail.com', 'user', 0, '0', '2024-02-04 08:15:55'),
(3, 'super', 'user', 'superuser@gmail.com', '1234', 2, '0', '2024-02-26 10:46:40'),
(4, 'department', 'editor', 'editor@gmail.com', '1234', 3, '0', '2024-02-26 14:15:23'),
(6, 'Vinni', 'Uba', 'vinniuba2@gmail.com', NULL, 1, '0', '2024-03-05 13:58:44'),
(7, 'unit', 'head', 'unithead@gmail.com', '1234', 4, '0', '2024-04-07 08:30:08'),
(8, 'VINNI  AGAWIN', 'UBA', '20180015014@my.xu.edu.ph', NULL, 0, NULL, '2024-04-07 13:55:53');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `items_history`
--
ALTER TABLE `items_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `purchase_requests_attachments`
--
ALTER TABLE `purchase_requests_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_requests_history`
--
ALTER TABLE `purchase_requests_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
