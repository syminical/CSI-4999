-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2018 at 02:43 PM
-- Server version: 5.7.23
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `SeniorProject`
--

-- --------------------------------------------------------

--
-- Table structure for table `Addon`
--

CREATE TABLE `Addon` (
  `Addon_ID` int(5) NOT NULL,
  `Addon_Room_ID` int(5) NOT NULL,
  `Addon_Name` varchar(20) NOT NULL,
  `Addon_Description` varchar(40) NOT NULL,
  `Addon_Pin` int(2) NOT NULL,
  `Addon_State` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Addon`
--

INSERT INTO `Addon` (`Addon_ID`, `Addon_Room_ID`, `Addon_Name`, `Addon_Description`, `Addon_Pin`, `Addon_State`) VALUES
(1, 1, 'Lamp #1', 'The lamp by the armchair.', 1, 0),
(2, 1, 'Lamp #2', 'The lamp by the table.', 2, 0),
(3, 2, 'Door #1', 'Front door lock.', 3, 1),
(4, 2, 'Fan #1', 'The giant fan.', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `House`
--

CREATE TABLE `House` (
  `House_ID` int(5) NOT NULL,
  `House_Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `House`
--

INSERT INTO `House` (`House_ID`, `House_Name`) VALUES
(1, 'Miami City House');

-- --------------------------------------------------------

--
-- Table structure for table `House_Assignment`
--

CREATE TABLE `House_Assignment` (
  `Assign_House_ID` int(5) NOT NULL,
  `Assign_User_ID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `House_Assignment`
--

INSERT INTO `House_Assignment` (`Assign_House_ID`, `Assign_User_ID`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Room`
--

CREATE TABLE `Room` (
  `Room_ID` int(5) NOT NULL,
  `House_ID` int(5) NOT NULL,
  `Room_Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Room`
--

INSERT INTO `Room` (`Room_ID`, `House_ID`, `Room_Name`) VALUES
(1, 1, 'Reptile Room'),
(2, 1, 'Entrance Room');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `User_ID` int(5) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`User_ID`, `Username`, `Password`, `Admin`) VALUES
(1, 'fake', 'password', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Addon`
--
ALTER TABLE `Addon`
  ADD PRIMARY KEY (`Addon_ID`),
  ADD KEY `Addon_Room_ID` (`Addon_Room_ID`);

--
-- Indexes for table `House`
--
ALTER TABLE `House`
  ADD PRIMARY KEY (`House_ID`);

--
-- Indexes for table `House_Assignment`
--
ALTER TABLE `House_Assignment`
  ADD PRIMARY KEY (`Assign_House_ID`,`Assign_User_ID`),
  ADD KEY `Assign_User_ID` (`Assign_User_ID`);

--
-- Indexes for table `Room`
--
ALTER TABLE `Room`
  ADD PRIMARY KEY (`Room_ID`),
  ADD KEY `House_ID` (`House_ID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Addon`
--
ALTER TABLE `Addon`
  MODIFY `Addon_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `House`
--
ALTER TABLE `House`
  MODIFY `House_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Room`
--
ALTER TABLE `Room`
  MODIFY `Room_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `User_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Addon`
--
ALTER TABLE `Addon`
  ADD CONSTRAINT `addon_ibfk_1` FOREIGN KEY (`Addon_Room_ID`) REFERENCES `Room` (`Room_ID`);

--
-- Constraints for table `House_Assignment`
--
ALTER TABLE `House_Assignment`
  ADD CONSTRAINT `house_assignment_ibfk_1` FOREIGN KEY (`Assign_House_ID`) REFERENCES `House` (`House_ID`),
  ADD CONSTRAINT `house_assignment_ibfk_2` FOREIGN KEY (`Assign_User_ID`) REFERENCES `User` (`User_ID`);

--
-- Constraints for table `Room`
--
ALTER TABLE `Room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`House_ID`) REFERENCES `House` (`House_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;