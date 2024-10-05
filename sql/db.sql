DROP DATABASE IF EXISTS `dboo`;
CREATE DATABASE IF NOT EXISTS `iap` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `iap`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `fullname` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  `username` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',

  PRIMARY KEY (`fullname`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
