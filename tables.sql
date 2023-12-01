SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Table structure for table `actors`
--

CREATE TABLE `actors` (
  `actorname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `birthyear` year NOT NULL,
  `cityid` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `cityid` bigint UNSIGNED NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`actorname`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`cityid`),
  ADD UNIQUE KEY `city` (`city`,`state`,`country`),
  ADD KEY `country` (`country`),
  ADD KEY `state` (`state`);

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `cityid` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
