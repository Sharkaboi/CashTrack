-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2020 at 09:11 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cashtrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `log_id` int(11) NOT NULL COMMENT 'Auto incremented log id.',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Username FK for relation',
  `type` int(11) NOT NULL COMMENT '1->Add, 2->Subtract',
  `account` int(11) NOT NULL COMMENT '1->Cash, 2->Debit, 3->Credit',
  `amount` int(11) NOT NULL COMMENT 'Amount in transaction',
  `log_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Timestamp of transaction input',
  `description` varchar(75) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description of transaction',
  `balance_before` bigint(20) NOT NULL COMMENT 'Total Balance before transaction',
  `balance_after` bigint(20) NOT NULL COMMENT 'Total Balance after transaction'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table of transaction logs';

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Primary identifier username',
  `hash_pwd` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Hashed password',
  `currency_default` int(11) NOT NULL COMMENT 'Currency choice',
  `credit_bal` int(11) NOT NULL DEFAULT 0 COMMENT 'Credit due balance',
  `cash_bal` int(11) NOT NULL DEFAULT 0 COMMENT 'In hand cash balance',
  `debit_bal` int(11) NOT NULL DEFAULT 0 COMMENT 'Debit account balance'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='User base management table';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto incremented log id.';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
