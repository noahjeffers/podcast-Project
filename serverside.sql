-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2020 at 09:38 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `serverside`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `CommentID` int(6) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Content` varchar(1000) NOT NULL,
  `PostDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `PodcastID` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`CommentID`, `Name`, `Content`, `PostDate`, `PodcastID`) VALUES
(1, 'Noah', 'Loved the Episode.', '2020-04-07 20:09:00', '9018-1586272947-^-Episode2.mp3-2020/04/07'),
(3, 'Noah', 'Cool Idea, this is something that I had never heard about before.', '2020-04-08 15:47:14', '9021-1586360747-^-Episode2.mp3-2020/04/08'),
(4, 'Bob', 'I had never seen Psycho before but I decided to give it a watch so that I would know what you were talking about. Great recommendation. ', '2020-04-08 15:48:06', '9019-1586360024-^-Episode3.mp3-2020/04/08'),
(5, 'Noah', 'Great podcast&#13;&#10;', '2020-04-15 19:35:10', '9022-1586360985-^-Episode2.mp3-2020/04/08'),
(6, 'Joe', 'Thanks for the new episode, I&#39;ve been a huge fan of Alexander the Great and its always fun to hear new perspectives', '2020-04-15 19:35:42', '9022-1586360985-^-Episode2.mp3-2020/04/08');

-- --------------------------------------------------------

--
-- Table structure for table `creator`
--

CREATE TABLE `creator` (
  `UserID` int(4) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Logo` varchar(100) NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `GenreID` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `creator`
--

INSERT INTO `creator` (`UserID`, `Password`, `UserName`, `Logo`, `Description`, `GenreID`) VALUES
(9017, '$2y$10$OvWqys.h4L/wmhbQUfFVOOVxrbPW9ldnm2Q9SD2JcF1b44R4HB5dG', 'ADMIN', '', 'New Account', 1),
(9018, '$2y$10$NbJrs1XC8I8cDwC0Y0hpF.WJRzMa5Mac2N73f0KYnlCk5jcwOK4g2', 'Hardcore History', 'images\\80230032.png', 'New Account', 4),
(9019, '$2y$10$QRxDhTV4ShG1kUoWbpoiUeq4AvtRc9oTYCXkZYjfHC/MyYLnDubsO', 'Shudder Bugs', '', 'New Account', 3),
(9020, '$2y$10$dUAylmALbSc2.A3VNHzCrel7ppH.4voVSg3aZhaQu6mvu9IlAa8GS', 'X-Files', '', 'A comedic look at conspiracy theories', 2),
(9021, '$2y$10$Gj7dAQVeeVyJhUeaw.wTX.d3b5gr4zvnoeg1RJN.NoT9.OPEGJ19i', 'Cutting Edge', '', 'An in depth look at new tech products, from Military hardware to the newest gadgets.', 5),
(9022, '$2y$10$mjPoZnYYzF7s0eHTF4xe/eWr77z0/Mcb9iykLW/MZEIW8XG25NcS2', 'History Come Alive', '', 'New Account', 4);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `GenreID` int(4) NOT NULL,
  `Genre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`GenreID`, `Genre`) VALUES
(1, 'Hidden'),
(2, 'Comedy'),
(3, 'Horror'),
(4, 'History'),
(5, 'Tech');

-- --------------------------------------------------------

--
-- Table structure for table `podcast`
--

CREATE TABLE `podcast` (
  `PodcastID` varchar(200) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `GenreID` int(4) NOT NULL,
  `UploadDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastEdited` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `podcast`
--

INSERT INTO `podcast` (`PodcastID`, `Title`, `Description`, `GenreID`, `UploadDate`, `LastEdited`) VALUES
('9018-1586272947-^-Episode2.mp3-2020/04/07', 'Supernova In the East', 'A look at the culture of Japan leading up to WW2', 4, '2020-04-07 15:22:27', '2020-04-07 15:22:27'),
('9018-1586273015-^-Episode3.mp3-2020/04/07', 'Supernova In the East Part 2', 'A look at Japans expansion into Manchuria', 4, '2020-04-07 15:23:35', '2020-04-08 07:56:52'),
('9018-1586359847-^-Episode4.mp3-2020/04/08', 'Supernova In the East Part 3', 'A look at the aftermath of Pearl Harbor, and Japans unchecked expansion into the Southern Pacific.', 1, '2020-04-08 15:30:48', '2020-04-08 15:30:48'),
('9018-1586359913-^-Episode5.mp3-2020/04/08', 'Supernova In the East Part 4', 'The tide begins to turn on the Japanese as the Battle of Midway destroys a substantial portion of their aircraft carriers.', 1, '2020-04-08 15:31:53', '2020-04-08 15:31:53'),
('9019-1586359977-^-Episode2.mp3-2020/04/08', 'Intro to Shudder Bugs', 'Just a brief introduction to the cast of Shudder Bugs', 2, '2020-04-08 15:32:57', '2020-04-08 15:32:57'),
('9019-1586360024-^-Episode3.mp3-2020/04/08', 'Episode 2-Psycho', 'We take a look at the classic Psycho.', 3, '2020-04-08 15:33:44', '2020-04-08 15:33:44'),
('9019-1586360087-^-Episode4.mp3-2020/04/08', 'Episode 3-Vertigo', 'Our second movie in our Hitchcock series. We look at Vertigo, discuss some to the different choices made during the filming. ', 1, '2020-04-08 15:34:47', '2020-04-08 15:34:47'),
('9019-1586360161-^-Episode4.mp3-2020/04/08', 'Episode 4-Rear Window', 'The third installment in our Hitchcock series. Jimmy Stewart as a bedridden photographer sees something that he shouldn\'t.', 1, '2020-04-08 15:36:01', '2020-04-08 15:36:01'),
('9020-1586360598-^-Episode2.mp3-2020/04/08', 'Flat Earth', 'We hit the ground running with our first episode and discuss everyone\'s favourite conspiracy theory. Flat earthers? idiots or did they somehow find something that every other person in history has missed.', 2, '2020-04-08 15:43:18', '2020-04-08 15:43:18'),
('9021-1586286736-^-Episode3.mp3-2020/04/07', 'F-22', 'A look at the American Air Forces newest toy. Plus a little comparison against the F-35', 1, '2020-04-07 19:12:16', '2020-04-08 02:28:41'),
('9021-1586286783-^-Episode3.mp3-2020/04/07', 'DARPA', 'We have a special guest on to discuss the cool new things that DARPA is doing', 5, '2020-04-07 19:13:03', '2020-04-07 19:13:03'),
('9021-1586360747-^-Episode2.mp3-2020/04/08', 'Lustron Houses', 'Not exactly \"Cutting Edge\" but we recently learned about the Lustron Company. In the late 1940s and 1950s they built houses entirely out of enamel covered steel. We discuss pros and cons. Stay Tuned', 5, '2020-04-08 15:45:47', '2020-04-08 15:45:47'),
('9022-1586360985-^-Episode2.mp3-2020/04/08', 'Alexander the Great', 'What made Alexander great, or was he over-rated? Lets find out.', 4, '2020-04-08 15:49:45', '2020-04-08 15:49:45'),
('9022-1586361039-^-Episode3.mp3-2020/04/08', 'Julius Caesar', 'We are a little late for the Ides of March, but we still wanted to talk about the man who crossed the Rubicon.', 4, '2020-04-08 15:50:39', '2020-04-08 22:53:07'),
('9022-1586361106-^-Episode4.mp3-2020/04/08', 'Hannibal', 'No, not that Hannibal, we will leave that for Shudder Bugs. We are talking about the greatest General to come out of Carthage and possibly the greatest single foe that the Romans faced.', 4, '2020-04-08 15:51:46', '2020-04-08 15:51:46'),
('9022-1586361172-^-Episode5.mp3-2020/04/08', 'Vercingetorix', 'The Gaul that gives Hannibal a run for his money as the greatest foe to the ancient Romans', 4, '2020-04-08 15:52:52', '2020-04-08 15:52:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `PodcastIDFK` (`PodcastID`);

--
-- Indexes for table `creator`
--
ALTER TABLE `creator`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `GenreFK` (`GenreID`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`GenreID`);

--
-- Indexes for table `podcast`
--
ALTER TABLE `podcast`
  ADD UNIQUE KEY `PodcastID_PK` (`PodcastID`),
  ADD KEY `PodcastGenreFK` (`GenreID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `creator`
--
ALTER TABLE `creator`
  MODIFY `UserID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9023;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `GenreID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `PodcastIDFK` FOREIGN KEY (`PodcastID`) REFERENCES `podcast` (`PodcastID`) ON DELETE CASCADE;

--
-- Constraints for table `creator`
--
ALTER TABLE `creator`
  ADD CONSTRAINT `GenreFK` FOREIGN KEY (`GenreID`) REFERENCES `genre` (`GenreID`) ON UPDATE CASCADE;

--
-- Constraints for table `podcast`
--
ALTER TABLE `podcast`
  ADD CONSTRAINT `PodcastGenreFK` FOREIGN KEY (`GenreID`) REFERENCES `genre` (`GenreID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
