-- phpMyAdmin SQL Dump
-- version 4.5.3.1
-- http://www.phpmyadmin.net
--
-- Host: 10.3.0.62
-- Gegenereerd op: 29 feb 2016 om 00:55
-- Serverversie: 5.5.40
-- PHP-versie: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `maxbets2_Aladdin`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `recoverylog`
--

CREATE TABLE `recoverylog` (
  `IP` varchar(45) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `Email` varchar(45) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Surname` varchar(45) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postalcode` varchar(20) NOT NULL,
  `country` varchar(60) NOT NULL,
  `city` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(6) NOT NULL,
  `handicap` tinyint(1) NOT NULL,
  `RecoveryHash` varchar(32) DEFAULT NULL,
  `RecoveryDate` datetime DEFAULT NULL,
  `ValidationHash` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`Email`, `Password`, `Name`, `Surname`, `address`, `postalcode`, `country`, `city`, `dob`, `gender`, `handicap`, `RecoveryHash`, `RecoveryDate`, `ValidationHash`) VALUES
  ('mariodv@hotmail.nl', '$2y$10$F2vMQAjzpum4tHcJYpR3FOGNR0O//leD6hqx3nlRurQzuRQE2LfmO', 'marius', 'de vogel', 'Venetiekade 16', '5237 EW', 'Netherlands', 's-Hertogenbosch', '1997-04-23', 'male', 0, NULL, NULL, NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `recoverylog`
--
ALTER TABLE `recoverylog`
ADD PRIMARY KEY (`IP`,`Date`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`Email`);
