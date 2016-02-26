-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2016 at 08:19 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pokebase`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `Id` int(11) NOT NULL,
  `Zipcode` varchar(6) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `City` varchar(85) NOT NULL,
  `Province` varchar(45) NOT NULL,
  `Country` varchar(45) NOT NULL,
  `Users_Email` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`Id`, `Zipcode`, `Address`, `City`, `Province`, `Country`, `Users_Email`) VALUES
  (1, '5237EW', 'Venetiekade 16', 'Den Bosch', 'Noord-Brabant', 'Nederland', 'Mariodv@hotmail.nl'),
  (2, '4102JA', 'Heimanslaan 26', 'Culemborg', 'Gelderland', 'Nederland', 'patrick.nobbe@hotmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `Name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Name`) VALUES
  ('Berries'),
  ('Held Items'),
  ('Poke Balls'),
  ('TMs & HMs');

-- --------------------------------------------------------

--
-- Table structure for table `itemhistory`
--

CREATE TABLE `itemhistory` (
  `Id` int(11) NOT NULL,
  `Price` decimal(20,2) NOT NULL,
  `Date` datetime NOT NULL,
  `Items_Id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `itemhistory`
--

INSERT INTO `itemhistory` (`Id`, `Price`, `Date`, `Items_Id`) VALUES
  (1, '9.99', '2016-02-16 20:00:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Name` varchar(45) NOT NULL,
  `DescriptionLong` longtext NOT NULL,
  `DescriptionShort` varchar(200) NOT NULL,
  `Price` decimal(20,2) NOT NULL,
  `ImgUrl` varchar(45) NOT NULL,
  `Subcategories_Name` varchar(45) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Id`, `Name`, `DescriptionLong`, `DescriptionShort`, `Price`, `ImgUrl`, `Subcategories_Name`, `Active`) VALUES
  (1, 'Pokeball', '', 'A device for catching wild Pokémon. It''s thrown like a ball at a Pokémon, comfortably encapsulating its target.', '9.99', '/Resources/Images/Pokeballs/pokeball.png', 'Normal', 1);

--
-- Triggers `items`
--
DELIMITER $$
CREATE TRIGGER `AFTER_INSERT_ITEMS` AFTER INSERT ON `items` FOR EACH ROW INSERT INTO itemhistory ( Price, Date, Items_Id)
VALUES ( NEW.Price, Now(), New.Id )
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `AFTER_UPDATE_ITEMS` AFTER UPDATE ON `items` FOR EACH ROW IF (NEW.Price <> OLD.Price) THEN
  INSERT INTO itemhistory ( Price, Date, Items_Id)
  VALUES ( NEW.Price, Now(), New.Id );
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `items_has_orders`
--

CREATE TABLE `items_has_orders` (
  `Items_Id` int(10) UNSIGNED NOT NULL,
  `Orders_Id` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Id` int(11) NOT NULL,
  `Users_Email` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recoverylog`
--

CREATE TABLE `recoverylog` (
  `IP` varchar(45) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `Name` varchar(45) NOT NULL,
  `Categories_Name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`Name`, `Categories_Name`) VALUES
  ('Oran', 'Berries'),
  ('Evolution Items', 'Held Items'),
  ('Normal', 'Poke Balls'),
  ('Special Effect', 'Poke Balls'),
  ('Type Specific', 'Poke Balls'),
  ('Fire', 'TMs & HMs');

-- --------------------------------------------------------

--
-- Table structure for table `timesheet`
--

CREATE TABLE `timesheet` (
  `Name` varchar(45) NOT NULL,
  `Task` varchar(45) NOT NULL,
  `Time` int(11) NOT NULL,
  `Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `timesheet`
--

INSERT INTO `timesheet` (`Name`, `Task`, `Time`, `Date`) VALUES
  ('Marius', 'Database Klasse (PDO)', 2, '2016-02-04 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Email` varchar(45) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Surname` varchar(45) NOT NULL,
  `RecoveryHash` varchar(32) DEFAULT NULL,
  `RecoveryDate` datetime DEFAULT NULL,
  `ValidationHash` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Email`, `Password`, `Name`, `Surname`, `RecoveryHash`, `RecoveryDate`, `ValidationHash`) VALUES
  ('Mariodv@hotmail.nl', '$2y$10$KpdhEX517I6Ph1Nh8Ln90Of6QYQyKMyhTer6iD.qQ2OKZbQ677go6', 'Marius', 'de Vogel', NULL, NULL, NULL),
  ('patrick.nobbe@hotmail.com', '$2y$10$mVCwDMdo2Sk/cQz9vQc6RuJ96oFoydLq.CfDZOEMyH4I6O2ftHXRC', 'Patrick Nobbe', 'Nobbe', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `Items_Id` int(10) UNSIGNED NOT NULL,
  `Users_Email` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
ADD PRIMARY KEY (`Id`),
ADD KEY `fk_Adres_Users1_idx` (`Users_Email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
ADD PRIMARY KEY (`Name`);

--
-- Indexes for table `itemhistory`
--
ALTER TABLE `itemhistory`
ADD PRIMARY KEY (`Id`),
ADD KEY `fk_ItemHistory_Items1_idx` (`Items_Id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
ADD PRIMARY KEY (`Id`),
ADD UNIQUE KEY `Id_UNIQUE` (`Id`),
ADD KEY `fk_Items_Subcategories1_idx` (`Subcategories_Name`);

--
-- Indexes for table `items_has_orders`
--
ALTER TABLE `items_has_orders`
ADD PRIMARY KEY (`Items_Id`,`Orders_Id`),
ADD KEY `fk_Items_has_Orders_Orders1_idx` (`Orders_Id`),
ADD KEY `fk_Items_has_Orders_Items1_idx` (`Items_Id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
ADD PRIMARY KEY (`Id`),
ADD KEY `fk_Orders_Users1_idx` (`Users_Email`);

--
-- Indexes for table `recoverylog`
--
ALTER TABLE `recoverylog`
ADD PRIMARY KEY (`IP`,`Date`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
ADD PRIMARY KEY (`Name`),
ADD KEY `fk_Subcategories_Categories_idx` (`Categories_Name`);

--
-- Indexes for table `timesheet`
--
ALTER TABLE `timesheet`
ADD PRIMARY KEY (`Name`,`Task`,`Time`,`Date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`Email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
ADD PRIMARY KEY (`Items_Id`,`Users_Email`),
ADD KEY `fk_Items_has_Users_Users1_idx` (`Users_Email`),
ADD KEY `fk_Items_has_Users_Items1_idx` (`Items_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `itemhistory`
--
ALTER TABLE `itemhistory`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
ADD CONSTRAINT `fk_Adres_Users1` FOREIGN KEY (`Users_Email`) REFERENCES `users` (`Email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `itemhistory`
--
ALTER TABLE `itemhistory`
ADD CONSTRAINT `fk_ItemHistory_Items1` FOREIGN KEY (`Items_Id`) REFERENCES `items` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
ADD CONSTRAINT `fk_Items_Subcategories1` FOREIGN KEY (`Subcategories_Name`) REFERENCES `subcategories` (`Name`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `items_has_orders`
--
ALTER TABLE `items_has_orders`
ADD CONSTRAINT `fk_Items_has_Orders_Items1` FOREIGN KEY (`Items_Id`) REFERENCES `items` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_Items_has_Orders_Orders1` FOREIGN KEY (`Orders_Id`) REFERENCES `orders` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
ADD CONSTRAINT `fk_Orders_Users1` FOREIGN KEY (`Users_Email`) REFERENCES `users` (`Email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
ADD CONSTRAINT `fk_Subcategories_Categories` FOREIGN KEY (`Categories_Name`) REFERENCES `categories` (`Name`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
ADD CONSTRAINT `fk_Items_has_Users_Items1` FOREIGN KEY (`Items_Id`) REFERENCES `items` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_Items_has_Users_Users1` FOREIGN KEY (`Users_Email`) REFERENCES `users` (`Email`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
