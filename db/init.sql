-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Feb 28, 2018 at 05:49 PM
-- Server version: 5.7.21
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omcrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_apiKey`
--

CREATE TABLE `omcrs_apiKey` (
  `id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `isSessionCreator` tinyint(1) NOT NULL DEFAULT '0',
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `key` varchar(64) NOT NULL,
  `created` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_questions`
--

CREATE TABLE `omcrs_questions` (
  `questionID` int(11) NOT NULL,
  `question` varchar(80) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `created` bigint(20) NOT NULL,
  `lastUpdate` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_questionsMcqChoices`
--

CREATE TABLE `omcrs_questionsMcqChoices` (
  `ID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `choice` varchar(80) NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_questionTypes`
--

CREATE TABLE `omcrs_questionTypes` (
  `ID` int(11) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `omcrs_questionTypes`
--

INSERT INTO `omcrs_questionTypes` (`ID`, `name`) VALUES
(1, 'mcq'),
(2, 'text'),
(3, 'textlong'),
(4, 'mrq');

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_response`
--

CREATE TABLE `omcrs_response` (
  `ID` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `sessionQuestionID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `response` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_responseMcq`
--

CREATE TABLE `omcrs_responseMcq` (
  `ID` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `sessionQuestionID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `choiceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_sessionAlias`
--

CREATE TABLE `omcrs_sessionAlias` (
  `ID` int(11) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `sessionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_sessionHistory`
--

CREATE TABLE `omcrs_sessionHistory` (
  `ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `time` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_sessionIdentifier`
--

CREATE TABLE `omcrs_sessionIdentifier` (
  `sessionIdentifier` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_sessionQuestions`
--

CREATE TABLE `omcrs_sessionQuestions` (
  `ID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `questionID` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_sessionQuestionScreenshot`
--

CREATE TABLE `omcrs_sessionQuestionScreenshot` (
  `ID` int(11) NOT NULL,
  `sessionQuestionID` int(11) NOT NULL,
  `screenshotID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_sessions`
--

CREATE TABLE `omcrs_sessions` (
  `sessionID` int(11) NOT NULL,
  `ownerID` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `courseID` varchar(20) NOT NULL,
  `allowGuests` tinyint(1) NOT NULL,
  `onSessionList` tinyint(1) NOT NULL,
  `questionControlMode` int(11) NOT NULL,
  `defaultTimeLimit` int(11) NOT NULL,
  `allowModifyAnswer` tinyint(1) NOT NULL,
  `allowQuestionReview` tinyint(1) NOT NULL,
  `classDiscussionEnabled` tinyint(1) NOT NULL,
  `created` bigint(20) NOT NULL DEFAULT '0',
  `lastUpdate` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_sessionsAdditionalUsers`
--

CREATE TABLE `omcrs_sessionsAdditionalUsers` (
  `ID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_uploads`
--

CREATE TABLE `omcrs_uploads` (
  `ID` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `omcrs_user`
--

CREATE TABLE `omcrs_user` (
  `userID` int(11) NOT NULL,
  `username` varchar(80) DEFAULT NULL,
  `givenName` varchar(64) DEFAULT NULL,
  `surname` varchar(64) DEFAULT NULL,
  `isSessionCreatorOverride` tinyint(1) DEFAULT NULL,
  `isAdminOverride` tinyint(1) DEFAULT NULL,
  `isGuest` tinyint(4) NOT NULL DEFAULT '0',
  `password` char(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `omcrs_apiKey`
--
ALTER TABLE `omcrs_apiKey`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `omcrs_questions`
--
ALTER TABLE `omcrs_questions`
  ADD PRIMARY KEY (`questionID`),
  ADD KEY `omcrs_questions_type` (`type`);

--
-- Indexes for table `omcrs_questionsMcqChoices`
--
ALTER TABLE `omcrs_questionsMcqChoices`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Structure omcrs_questionsMcqChoice_questionID` (`questionID`);

--
-- Indexes for table `omcrs_questionTypes`
--
ALTER TABLE `omcrs_questionTypes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `omcrs_response`
--
ALTER TABLE `omcrs_response`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `omcrs_responseMcq`
--
ALTER TABLE `omcrs_responseMcq`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `omcrs_sessionAlias`
--
ALTER TABLE `omcrs_sessionAlias`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `alias` (`alias`),
  ADD KEY `omcrs_sessionAlias_sessionID` (`sessionID`);

--
-- Indexes for table `omcrs_sessionHistory`
--
ALTER TABLE `omcrs_sessionHistory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `omcrs_sessionIdentifier`
--
ALTER TABLE `omcrs_sessionIdentifier`
  ADD PRIMARY KEY (`sessionIdentifier`);

--
-- Indexes for table `omcrs_sessionQuestions`
--
ALTER TABLE `omcrs_sessionQuestions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `omcrs_sessionQuestions_sessionID` (`sessionID`),
  ADD KEY `omcrs_sessionQuestions_questionID` (`questionID`);

--
-- Indexes for table `omcrs_sessionQuestionScreenshot`
--
ALTER TABLE `omcrs_sessionQuestionScreenshot`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `omcrs_sessions`
--
ALTER TABLE `omcrs_sessions`
  ADD PRIMARY KEY (`sessionID`),
  ADD KEY `omcrs_sessions_ownerID` (`ownerID`);

--
-- Indexes for table `omcrs_sessionsAdditionalUsers`
--
ALTER TABLE `omcrs_sessionsAdditionalUsers`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `omcrs_sessionsAdditionalUsers_sessionID` (`sessionID`),
  ADD KEY `omcrs_sessionsAdditionalUsers_userID` (`userID`);

--
-- Indexes for table `omcrs_uploads`
--
ALTER TABLE `omcrs_uploads`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `omcrs_user`
--
ALTER TABLE `omcrs_user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `omcrs_apiKey`
--
ALTER TABLE `omcrs_apiKey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_questions`
--
ALTER TABLE `omcrs_questions`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_questionsMcqChoices`
--
ALTER TABLE `omcrs_questionsMcqChoices`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_questionTypes`
--
ALTER TABLE `omcrs_questionTypes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `omcrs_response`
--
ALTER TABLE `omcrs_response`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_responseMcq`
--
ALTER TABLE `omcrs_responseMcq`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_sessionAlias`
--
ALTER TABLE `omcrs_sessionAlias`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_sessionHistory`
--
ALTER TABLE `omcrs_sessionHistory`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_sessionIdentifier`
--
ALTER TABLE `omcrs_sessionIdentifier`
  MODIFY `sessionIdentifier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_sessionQuestions`
--
ALTER TABLE `omcrs_sessionQuestions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_sessionQuestionScreenshot`
--
ALTER TABLE `omcrs_sessionQuestionScreenshot`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_sessions`
--
ALTER TABLE `omcrs_sessions`
  MODIFY `sessionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_sessionsAdditionalUsers`
--
ALTER TABLE `omcrs_sessionsAdditionalUsers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_uploads`
--
ALTER TABLE `omcrs_uploads`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `omcrs_user`
--
ALTER TABLE `omcrs_user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `omcrs_questions`
--
ALTER TABLE `omcrs_questions`
  ADD CONSTRAINT `omcrs_questions_type` FOREIGN KEY (`type`) REFERENCES `omcrs_questionTypes` (`ID`);

--
-- Constraints for table `omcrs_questionsMcqChoices`
--
ALTER TABLE `omcrs_questionsMcqChoices`
  ADD CONSTRAINT `Structure omcrs_questionsMcqChoice_questionID` FOREIGN KEY (`questionID`) REFERENCES `omcrs_questions` (`questionID`);

--
-- Constraints for table `omcrs_sessionAlias`
--
ALTER TABLE `omcrs_sessionAlias`
  ADD CONSTRAINT `omcrs_sessionAlias_sessionID` FOREIGN KEY (`sessionID`) REFERENCES `omcrs_sessions` (`sessionID`);

--
-- Constraints for table `omcrs_sessionQuestions`
--
ALTER TABLE `omcrs_sessionQuestions`
  ADD CONSTRAINT `omcrs_sessionQuestions_questionID` FOREIGN KEY (`questionID`) REFERENCES `omcrs_questions` (`questionID`),
  ADD CONSTRAINT `omcrs_sessionQuestions_sessionID` FOREIGN KEY (`sessionID`) REFERENCES `omcrs_sessions` (`sessionID`);

--
-- Constraints for table `omcrs_sessions`
--
ALTER TABLE `omcrs_sessions`
  ADD CONSTRAINT `omcrs_sessions_ownerID` FOREIGN KEY (`ownerID`) REFERENCES `omcrs_user` (`userID`);

--
-- Constraints for table `omcrs_sessionsAdditionalUsers`
--
ALTER TABLE `omcrs_sessionsAdditionalUsers`
  ADD CONSTRAINT `omcrs_sessionsAdditionalUsers_sessionID` FOREIGN KEY (`sessionID`) REFERENCES `omcrs_sessions` (`sessionID`),
  ADD CONSTRAINT `omcrs_sessionsAdditionalUsers_userID` FOREIGN KEY (`userID`) REFERENCES `omcrs_user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
