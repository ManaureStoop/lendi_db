-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2017 at 09:12 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31
USE lendi;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lendi`
--

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE `action` (
  `id` int(11) NOT NULL,
  `lender_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action_type` int(11) NOT NULL,
  `number_of_lenders` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `action`
--

INSERT INTO `action` (`id`, `lender_id`, `request_id`, `object_id`, `created_at`, `action_type`, `number_of_lenders`) VALUES
(0, 1, 1, 1, '2017-08-09 07:00:00', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `object`
--

CREATE TABLE `object` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `object`
--

INSERT INTO `object` (`id`, `name`) VALUES
(1, 'Hammer'),
(2, 'hammer'),
(3, 'hammer'),
(4, 'hammer'),
(5, 'hammer'),
(6, 'hammer'),
(7, 'hammer'),
(8, 'hammer'),
(9, 'hammer'),
(10, 'hammer'),
(11, 'hammer'),
(12, 'hammer');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `user_request_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `why` varchar(250) NOT NULL,
  `state` int(11) NOT NULL,
  `created_at` text NOT NULL,
  `pic_url` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `user_request_id`, `object_id`, `why`, `state`, `created_at`, `pic_url`) VALUES
(1, 1, 1, 'I need it for threee dayws', 1, '2017-08-20 04:22:04', 'pic/url'),
(2, 2, 8, 'i need it for some work', 1, '0000-00-00 00:00:00', 'images/mlna_tariq_jamil.jpg'),
(3, 2, 9, 'i need it for some work', 1, '0000-00-00 00:00:00', ''),
(4, 2, 10, 'i need it for some work', 1, '0000-00-00 00:00:00', ''),
(5, 2, 11, 'i need it for some work', 1, '0000-00-00 00:00:00', ''),
(6, 2, 12, 'i need it for some work', 1, '08-20-2017 16:58:12.000000', ''),
(7, 3, 4, 'asjhdasjkh', 1, '2017-08-20 11:37:57', '');

-- --------------------------------------------------------

--
-- Table structure for table `request_status`
--

CREATE TABLE `request_status` (
  `state_requested` int(11) NOT NULL,
  `state_offered` int(11) NOT NULL,
  `state_lended` int(11) NOT NULL,
  `state_deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` text NOT NULL,
  `pic_url` varchar(250) NOT NULL,
  `phone_number_1` varchar(20) NOT NULL,
  `other_request_notification` tinyint(1) NOT NULL,
  `my_request_notification` tinyint(1) NOT NULL,
  `middle_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `phone` varchar(150) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `createdat` text NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `email`, `password`, `pic_url`, `phone_number_1`, `other_request_notification`, `my_request_notification`, `middle_name`, `last_name`, `phone`, `lat`, `lng`, `createdat`, `token`) VALUES
(1, 'shoaib', 'shaibi3036@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '', '03113313113', 1, 1, '', 'iqbal', '09876543211', 33.635947, 73.070954, '08-19-2017 19:34:12.000000', '616744abd9edfada89794ec2a2c7ba0f'),
(2, 'jalal', 'jalal036@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '', '', 0, 0, '', 'Tauseef', '', 33.637947, 73.070924, '08-20-2017 11:49:34.000000', 'a34694716018a1481a41ca45ea6f4696'),
(3, 'ABCD', 'jalal1234@gmail.com', '25d55ad283aa400af464c76d713c07ad', '', '', 0, 0, '', 'EFGHUJ', '', 33.636553, 73.070974, '08-20-2017 11:56:28.000000', '22842713f0cc240dc3c97619e115fead');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `object`
--
ALTER TABLE `object`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `object`
--
ALTER TABLE `object`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
