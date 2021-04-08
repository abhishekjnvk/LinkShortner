-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 08, 2023 at 10:14 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `linkShortner`
--

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `id` int(11) NOT NULL,
  `code` varchar(30) NOT NULL,
  `time` varchar(20) NOT NULL,
  `data` text NOT NULL,
  `ip` varchar(100) NOT NULL,
  `times` int(11) NOT NULL DEFAULT '1',
  `url_params` text,
  `utm_source` varchar(55) DEFAULT NULL,
  `country` varchar(80) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `region` varchar(80) DEFAULT NULL,
  `timezone` varchar(80) DEFAULT NULL,
  `continent` varchar(80) DEFAULT NULL,
  `is_bot` varchar(8) NOT NULL DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `link` varchar(350) NOT NULL,
  `title` varchar(200) NOT NULL,
  `timestamp` varchar(20) NOT NULL,
  `isAnalyticsEnabled` int(11) NOT NULL,
  `password` varchar(200) NOT NULL,
  `expiry` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analytics`
--
ALTER TABLE `analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
