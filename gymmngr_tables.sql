-- phpMyAdmin SQL Dump
-- version 4.7.0-dev
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 25, 2016 at 03:41 PM
-- Server version: 5.7.15-log
-- PHP Version: 7.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sylver_gymmngr`
--
CREATE DATABASE IF NOT EXISTS `sylver_gymmngr_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `sylver_gymmngr`;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `age` int(2) NOT NULL,
  `sex` enum('male','female') COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Keeps track of the clients';

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `contract_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `branch` enum('Punna','Meechoke') COLLATE utf8_unicode_ci NOT NULL,
  `training_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `package` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nb_sessions` int(4) NOT NULL,
  `price_per_session` int(5) NOT NULL,
  `start_date` datetime NOT NULL,
  `expire_date` datetime NOT NULL,
  `remaining_sessions` int(4) NOT NULL,
  `trainer_rate_modifier` int(4) NOT NULL DEFAULT '0',
  `comments` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('owner','trainer') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Owner gets full access, trainer can only log sessions',
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Simple login table for Gym Manager';

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL COMMENT 'unique ID for the session. starts with ses_ followed by the date and time',
  `date` datetime NOT NULL,
  `client_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL COMMENT 'clt_ unique ID for the client. starts with clt_ followed by the date and time',
  `contract_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ctt_',
  `trainer_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL COMMENT 'tnr_',
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores the details of each completed training session';

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `trainer_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sex` enum('male','female') COLLATE utf8_unicode_ci NOT NULL,
  `age` int(2) NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Works for Pilates Plus currently',
  `boxing_rate` int(11) NOT NULL,
  `pilates_rate` int(11) NOT NULL,
  `yoga_rate` int(11) NOT NULL,
  `bodyweight_rate` int(11) NOT NULL,
  `trx_rate` int(11) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'To enter other data not covered by the basic form',
  `creation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Store the trainer''s details and their rates for various services';

-- --------------------------------------------------------

--
-- Table structure for table `training_packages`
--

CREATE TABLE `training_packages` (
  `package_id` varchar(19) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `nb_sessions` int(3) NOT NULL,
  `price_per_session` int(4) NOT NULL,
  `active` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `discount` int(2) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `full_price` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Packages and promotional offers';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD KEY `first_name` (`first_name`,`last_name`,`created_date`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contract_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `client_id_2` (`client_id`,`training_type`,`expire_date`,`remaining_sessions`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`user_name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `date` (`date`,`client_id`,`contract_id`,`trainer_id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`),
  ADD KEY `last_name` (`last_name`,`boxing_rate`,`pilates_rate`,`yoga_rate`,`bodyweight_rate`,`trx_rate`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `training_packages`
--
ALTER TABLE `training_packages`
  ADD PRIMARY KEY (`package_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
