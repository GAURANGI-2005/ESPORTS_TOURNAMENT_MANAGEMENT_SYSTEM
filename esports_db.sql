-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Mar 14, 2026 at 11:24 AM
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
-- Database: `esports_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `coach_id` int(11) NOT NULL,
  `coach_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coach`
--

INSERT INTO `coach` (`coach_id`, `coach_name`, `email`, `experience_years`, `nationality`) VALUES
(1, 'Hans Monto', 'hansmonto@gmail.com', 5, 'Japan'),
(2, 'Michael Grant', 'michaelgrant@gmail.com', 8, 'USA'),
(3, 'Rahul Verma', 'rahulverma@gmail.com', 6, 'India'),
(4, 'Carlos Diaz', 'carlosdiaz@gmail.com', 7, 'Spain'),
(5, 'Lee Min Ho', 'leemin@gmail.com', 9, 'South Korea'),
(6, 'David Miller', 'davidmiller@gmail.com', 10, 'USA'),
(7, 'Takashi Sato', 'takashisato@gmail.com', 12, 'Japan'),
(8, 'Pierre Laurent', 'pierrel@gmail.com', 9, 'France');

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `game_id` int(11) NOT NULL,
  `game_name` varchar(100) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `developer` varchar(100) DEFAULT NULL,
  `release_year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`game_id`, `game_name`, `genre`, `developer`, `release_year`) VALUES
(1, 'Valorant', 'FPS', 'Riot Games', 2020),
(2, 'CS:GO', 'FPS', 'Valve', 2012),
(3, 'Dota 2', 'MOBA', 'Valve', 2013),
(4, 'League of Legends', 'MOBA', 'Riot Games', 2009),
(5, 'Overwatch', 'FPS', 'Blizzard', 2016);

-- --------------------------------------------------------

--
-- Table structure for table `matchstats`
--

CREATE TABLE `matchstats` (
  `stat_id` int(11) NOT NULL,
  `kills` int(11) DEFAULT NULL,
  `deaths` int(11) DEFAULT NULL,
  `assists` int(11) DEFAULT NULL,
  `damage_done` int(11) DEFAULT NULL,
  `player_id` int(11) DEFAULT NULL,
  `match_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matchstats`
--

INSERT INTO `matchstats` (`stat_id`, `kills`, `deaths`, `assists`, `damage_done`, `player_id`, `match_id`) VALUES
(1, 20, 5, 10, 3500, 1, 1),
(2, 15, 7, 8, 2900, 2, 1),
(3, 18, 6, 9, 3200, 3, 2),
(4, 22, 4, 11, 4000, 4, 3),
(5, 25, 4, 12, 4200, 5, 4),
(6, 19, 6, 10, 3500, 6, 4),
(7, 21, 5, 9, 3800, 7, 5);

-- --------------------------------------------------------

--
-- Table structure for table `match_table`
--

CREATE TABLE `match_table` (
  `match_id` int(11) NOT NULL,
  `match_date` date DEFAULT NULL,
  `match_time` time DEFAULT NULL,
  `round` varchar(50) DEFAULT NULL,
  `tournament_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `match_table`
--

INSERT INTO `match_table` (`match_id`, `match_date`, `match_time`, `round`, `tournament_id`, `venue_id`) VALUES
(1, '2025-08-02', '14:00:00', 'Quarterfinal', 1, 1),
(2, '2025-08-03', '16:00:00', 'Semifinal', 1, 1),
(3, '2025-09-02', '18:00:00', 'Quarterfinal', 2, 2),
(4, '2025-10-02', '15:00:00', 'Quarterfinal', 3, 4),
(5, '2025-10-04', '18:00:00', 'Semifinal', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `player_id` int(11) NOT NULL,
  `player_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `gamer_tag` varchar(50) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`player_id`, `player_name`, `age`, `role`, `nationality`, `gamer_tag`, `team_id`) VALUES
(1, 'Khushi', 19, 'Duelist', 'India', 'KhushiPro', 1),
(2, 'Bhavika', 22, 'Support', 'India', 'BhaViper', 1),
(3, 'Arjun', 21, 'Sniper', 'India', 'ArjunX', 2),
(4, 'Rohan', 23, 'Tank', 'India', 'RohanShield', 2),
(5, 'Kenji', 24, 'Duelist', 'Japan', 'KenjiBlade', 3),
(6, 'Miguel', 22, 'Support', 'Spain', 'MigStorm', 4),
(7, 'Jin Woo', 20, 'Sniper', 'South Korea', 'JinShot', 5),
(8, 'Arjun', 21, 'Sniper', 'India', 'ArjSniper', 2),
(9, 'Kenji Tanaka', 23, 'Support', 'Japan', 'KenjiX', 3),
(10, 'Lucas Martin', 22, 'Entry Fragger', 'France', 'LucX', 4),
(11, 'Rohit Sharma', 24, 'Strategist', 'India', 'RohPro', 2),
(12, 'Daniel Cruz', 25, 'Support', 'Spain', 'CruzX', 5);

-- --------------------------------------------------------

--
-- Table structure for table `prizepool`
--

CREATE TABLE `prizepool` (
  `prizepool_id` int(11) NOT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `first_place_prize` int(11) DEFAULT NULL,
  `second_place_prize` int(11) DEFAULT NULL,
  `third_place_prize` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prizepool`
--

INSERT INTO `prizepool` (`prizepool_id`, `total_amount`, `first_place_prize`, `second_place_prize`, `third_place_prize`) VALUES
(1, 100000, 50000, 30000, 20000),
(2, 150000, 70000, 50000, 30000),
(3, 200000, 100000, 60000, 40000);

-- --------------------------------------------------------

--
-- Table structure for table `sponsor`
--

CREATE TABLE `sponsor` (
  `sponsor_id` int(11) NOT NULL,
  `sponsor_name` varchar(100) DEFAULT NULL,
  `contribution_amount` int(11) DEFAULT NULL,
  `industry` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sponsor`
--

INSERT INTO `sponsor` (`sponsor_id`, `sponsor_name`, `contribution_amount`, `industry`) VALUES
(1, 'Red Bull', 50000, 'Energy Drink'),
(2, 'Intel', 75000, 'Technology'),
(3, 'Nvidia', 60000, 'Technology'),
(4, 'Monster Energy', 65000, 'Energy Drink'),
(5, 'Logitech', 55000, 'Gaming Hardware');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(100) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `ranking` int(11) DEFAULT NULL,
  `founded_year` int(11) DEFAULT NULL,
  `coach_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`team_id`, `team_name`, `region`, `ranking`, `founded_year`, `coach_id`) VALUES
(1, 'Fusion', 'Asia', 1, 2018, 1),
(2, 'Sentinels', 'North America', 2, 2017, 2),
(3, 'Warriors', 'Asia', 3, 2019, 3),
(4, 'Dragons', 'Europe', 4, 2016, 4),
(5, 'Phoenix', 'Asia', 5, 2020, 5),
(6, 'Sentinels', 'North America', 2, 2019, 3),
(7, 'Paper Rex', 'Asia', 4, 2020, 4),
(8, 'Fnatic', 'Europe', 1, 2018, 5);

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `ticket_id` int(11) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `seat_no` varchar(20) DEFAULT NULL,
  `viewer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`ticket_id`, `price`, `purchase_date`, `seat_no`, `viewer_id`) VALUES
(1, 50, '2025-08-02', 'A12', 1),
(2, 75, '2025-08-03', 'B15', 2),
(3, 60, '2025-09-02', 'C10', 3),
(4, 80, '2025-10-02', 'D12', 4),
(5, 90, '2025-10-04', 'E15', 5),
(6, 75, '2025-10-04', 'F10', 6);

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

CREATE TABLE `tournament` (
  `tournament_id` int(11) NOT NULL,
  `tournament_name` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `sponsor_id` int(11) DEFAULT NULL,
  `prizepool_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournament`
--

INSERT INTO `tournament` (`tournament_id`, `tournament_name`, `location`, `start_date`, `end_date`, `sponsor_id`, `prizepool_id`) VALUES
(1, 'Valorant Champions', 'Tokyo', '2025-08-01', '2025-08-10', 1, 1),
(2, 'Global Esports Cup', 'Los Angeles', '2025-09-01', '2025-09-10', 2, 2),
(3, 'World Esports Masters', 'Berlin', '2025-10-01', '2025-10-12', 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venue_id` int(11) NOT NULL,
  `venue_name` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_id`, `venue_name`, `city`, `country`, `capacity`) VALUES
(1, 'Tokyo Arena', 'Tokyo', 'Japan', 20000),
(2, 'LA Esports Stadium', 'Los Angeles', 'USA', 25000),
(3, 'Seoul Gaming Dome', 'Seoul', 'South Korea', 18000),
(4, 'Berlin Esports Arena', 'Berlin', 'Germany', 22000),
(5, 'Mumbai Gaming Center', 'Mumbai', 'India', 15000);

-- --------------------------------------------------------

--
-- Table structure for table `viewer`
--

CREATE TABLE `viewer` (
  `viewer_id` int(11) NOT NULL,
  `viewer_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `viewer`
--

INSERT INTO `viewer` (`viewer_id`, `viewer_name`, `email`, `age`, `country`) VALUES
(1, 'Amit', 'amit@gmail.com', 21, 'India'),
(2, 'Sarah', 'sarah@gmail.com', 25, 'USA'),
(3, 'Kenji', 'kenji@gmail.com', 23, 'Japan'),
(4, 'John Carter', 'john@gmail.com', 27, 'USA'),
(5, 'Priya Patel', 'priya@gmail.com', 22, 'India'),
(6, 'Marco Rossi', 'marco@gmail.com', 29, 'Italy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`coach_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `matchstats`
--
ALTER TABLE `matchstats`
  ADD PRIMARY KEY (`stat_id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `match_id` (`match_id`);

--
-- Indexes for table `match_table`
--
ALTER TABLE `match_table`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`player_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `prizepool`
--
ALTER TABLE `prizepool`
  ADD PRIMARY KEY (`prizepool_id`);

--
-- Indexes for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`sponsor_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `coach_id` (`coach_id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `viewer_id` (`viewer_id`);

--
-- Indexes for table `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`tournament_id`),
  ADD KEY `sponsor_id` (`sponsor_id`),
  ADD KEY `prizepool_id` (`prizepool_id`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venue_id`);

--
-- Indexes for table `viewer`
--
ALTER TABLE `viewer`
  ADD PRIMARY KEY (`viewer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `coach_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `matchstats`
--
ALTER TABLE `matchstats`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `match_table`
--
ALTER TABLE `match_table`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `player`
--
ALTER TABLE `player`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `prizepool`
--
ALTER TABLE `prizepool`
  MODIFY `prizepool_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sponsor`
--
ALTER TABLE `sponsor`
  MODIFY `sponsor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `tournament_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `viewer`
--
ALTER TABLE `viewer`
  MODIFY `viewer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matchstats`
--
ALTER TABLE `matchstats`
  ADD CONSTRAINT `matchstats_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `player` (`player_id`),
  ADD CONSTRAINT `matchstats_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `match_table` (`match_id`);

--
-- Constraints for table `match_table`
--
ALTER TABLE `match_table`
  ADD CONSTRAINT `match_table_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`tournament_id`),
  ADD CONSTRAINT `match_table_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`);

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`);

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`coach_id`) REFERENCES `coach` (`coach_id`);

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`viewer_id`) REFERENCES `viewer` (`viewer_id`);

--
-- Constraints for table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `tournament_ibfk_1` FOREIGN KEY (`sponsor_id`) REFERENCES `sponsor` (`sponsor_id`),
  ADD CONSTRAINT `tournament_ibfk_2` FOREIGN KEY (`prizepool_id`) REFERENCES `prizepool` (`prizepool_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
