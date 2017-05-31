-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2017 at 06:04 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `protrade`
--

-- --------------------------------------------------------

--
-- Table structure for table `market`
--

CREATE TABLE `market` (
  `id` int(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `timing_start` time NOT NULL,
  `timing_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `market`
--

INSERT INTO `market` (`id`, `name`, `timing_start`, `timing_end`) VALUES
(1, 'NSE', '00:00:01', '23:59:59'),
(2, 'BSE', '03:00:00', '23:59:59'),
(3, 'MCX', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `id` int(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `duration` tinyint(2) NOT NULL,
  `rate` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`id`, `name`, `description`, `duration`, `rate`) VALUES
(1, 'Monthly', 'User can access resources for 1 month.', 1, 100),
(2, 'Quarterly', 'User can access resources for 3 months.', 3, 80),
(3, 'Yearly', 'User can access resources for 12 months.', 12, 70);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `contactNo` bigint(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('MALE','FEMALE','OTHER') NOT NULL,
  `designation` varchar(20) NOT NULL,
  `department` varchar(20) NOT NULL,
  `user_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `contactNo`, `email`, `address`, `dob`, `gender`, `designation`, `department`, `user_id`) VALUES
(1, 'Gaurang Ribadiya', 9712905846, 'gribadiya50@gmail.com', 'Surat', '1996-04-14', 'MALE', 'webdeveloper', 'website', 3);

-- --------------------------------------------------------

--
-- Table structure for table `subscriber`
--

CREATE TABLE `subscriber` (
  `id` int(20) NOT NULL,
  `type` enum('ADMIN','STAFF','SUBSCRIBER') NOT NULL,
  `name` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `contactNo` bigint(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `twitter` varchar(50) NOT NULL,
  `user_id` int(20) NOT NULL,
  `plan_id` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subscriber`
--

INSERT INTO `subscriber` (`id`, `type`, `name`, `address`, `contactNo`, `email`, `twitter`, `user_id`, `plan_id`) VALUES
(1, 'SUBSCRIBER', 'Harsh Virani', 'Surat', 8238912372, 'harshvirani33@gmail.com', 'harsh_virani', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `id` int(20) NOT NULL,
  `subscriber_id` int(20) NOT NULL,
  `plan_id` int(20) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_script_specific` tinyint(1) NOT NULL,
  `is_market_specific` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`id`, `subscriber_id`, `plan_id`, `date`, `expiry_date`, `is_script_specific`, `is_market_specific`) VALUES
(1, 1, 3, '2017-05-22 03:25:12', '0000-00-00 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptionSymbol`
--

CREATE TABLE `subscriptionSymbol` (
  `id` int(20) NOT NULL,
  `subscription_id` int(20) NOT NULL,
  `symbol_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subscriptionSymbol`
--

INSERT INTO `subscriptionSymbol` (`id`, `subscription_id`, `symbol_id`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `symbol`
--

CREATE TABLE `symbol` (
  `id` int(20) NOT NULL,
  `market_id` int(20) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` varchar(20) NOT NULL,
  `lot_size` varchar(20) NOT NULL,
  `tick_size` varchar(20) NOT NULL,
  `margin` varchar(20) NOT NULL,
  `price_quote` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `symbol`
--

INSERT INTO `symbol` (`id`, `market_id`, `code`, `name`, `category`, `lot_size`, `tick_size`, `margin`, `price_quote`) VALUES
(1, 3, 'ALUMINI 1', 'ALUMINI 1', '', '', '', '', 100),
(2, 3, 'ALUMINIUM 1', 'ALUMINIUM 1', '', '', '', '', 100),
(3, 3, 'CARDAMOM 1', 'CARDAMOM 1', '', '', '', '', 100),
(4, 3, 'COPPER 1', 'COPPER 1', '', '', '', '', 200),
(5, 3, 'COPPERM 1', 'COPPERM 1', '', '', '', '', 300),
(6, 3, 'COTTON 1', 'COTTON 1', '', '', '', '', 200),
(7, 3, 'CPO 1', 'CPO 1', '', '', '', '', 100),
(8, 3, 'CRUDEOIL 1', 'CRUDEOIL 1', '', '', '', '', 400),
(9, 3, 'GOLD 1', 'GOLD 1', '', '', '', '', 300),
(10, 3, 'GOLDGUINEA 1', 'GOLDGUINEA 1', '', '', '', '', 200),
(11, 3, 'GOLDM 1', 'GOLDM 1', '', '', '', '', 100),
(12, 3, 'GOLDPETAL 1', 'GOLDPETAL 1', '', '', '', '', 200),
(13, 3, 'LEAD 1', 'LEAD 1', '', '', '', '', 300),
(14, 3, 'LEADMINI 1', 'LEADMINI 1', '', '', '', '', 100),
(15, 3, 'MENTHAOIL 1', 'MENTHAOIL 1', '', '', '', '', 100),
(16, 3, 'NATURALGAS 1', 'NATURALGAS 1', '', '', '', '', 200),
(17, 3, 'NICKEL 1', 'NICKEL 1', '', '', '', '', 300),
(18, 3, 'NICKELM 1', 'NICKELM 1', '', '', '', '', 400),
(19, 3, 'SILVER 1', 'SILVER 1', '', '', '', '', 500),
(20, 3, 'SILVERM 1', 'SILVERM 1', '', '', '', '', 300),
(21, 3, 'SILVERMIC 1', 'SILVERMIC 1', '', '', '', '', 200),
(22, 3, 'ZINC 1', 'ZINC 1', '', '', '', '', 300),
(23, 3, 'ZINCMINI 1', 'ZINCMINI 1', '', '', '', '', 200),
(24, 2, 'temp', 'temp', '', '', '', '', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(20) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contactNo` bigint(10) NOT NULL,
  `type` enum('ADMIN','STAFF','SUBSCRIBER') NOT NULL,
  `status` enum('ACTIVE','LOGGED IN','BLOCKED') NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `profile` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `uname`, `password`, `email`, `contactNo`, `type`, `status`, `is_deleted`, `profile`) VALUES
(1, 'admin', 'admin', 'sharad.paghadal@gmail.com', 8488855723, 'ADMIN', 'ACTIVE', 0, '1profile.png'),
(2, 'temp', 'temp', 'harshvirani33@gmail.com', 8238912372, 'SUBSCRIBER', 'ACTIVE', 0, '2profile.png'),
(3, 'staff', 'staff', 'gribadiya50@gmail.com', 9712905846, 'STAFF', 'ACTIVE', 0, '3profile.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `market`
--
ALTER TABLE `market`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subscriber`
--
ALTER TABLE `subscriber`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriber_id` (`subscriber_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `subscriptionSymbol`
--
ALTER TABLE `subscriptionSymbol`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_id` (`subscription_id`),
  ADD KEY `symbol_id` (`symbol_id`);

--
-- Indexes for table `symbol`
--
ALTER TABLE `symbol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `market_id` (`market_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `market`
--
ALTER TABLE `market`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subscriber`
--
ALTER TABLE `subscriber`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subscriptionSymbol`
--
ALTER TABLE `subscriptionSymbol`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `symbol`
--
ALTER TABLE `symbol`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staffUserConstraint` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `subscriber`
--
ALTER TABLE `subscriber`
  ADD CONSTRAINT `planConstraint` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `userConstraint` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `planSubscriptionConstraint` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `subscriberSubscriptionConstraint` FOREIGN KEY (`subscriber_id`) REFERENCES `subscriber` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `subscriptionSymbol`
--
ALTER TABLE `subscriptionSymbol`
  ADD CONSTRAINT `subscriptionSymbol_Subcription` FOREIGN KEY (`subscription_id`) REFERENCES `subscription` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `subscriptionSymbol_Symbol` FOREIGN KEY (`symbol_id`) REFERENCES `symbol` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `symbol`
--
ALTER TABLE `symbol`
  ADD CONSTRAINT `symbolMarketConstraint` FOREIGN KEY (`market_id`) REFERENCES `market` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
