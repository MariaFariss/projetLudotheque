-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 27, 2022 at 11:39 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ludo`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int(255) NOT NULL,
  `email` varchar(3000) NOT NULL,
  `nom` varchar(3000) NOT NULL,
  `date` date NOT NULL,
  `returndate` date NOT NULL,
  `quantityreserved` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `email`, `nom`, `date`, `returndate`, `quantityreserved`) VALUES
(1, 'mimi@gmail.com', 'Spider-man', '2022-01-10', '2022-02-09', 1),
(2, 'mimi@gmail.com', 'Mincraft', '2022-01-10', '2022-02-09', 1),
(3, 'mimi@gmail.com', 'Fortnite', '2022-12-27', '2022-12-30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gametype`
--

DROP TABLE IF EXISTS `gametype`;
CREATE TABLE IF NOT EXISTS `gametype` (
  `id` int(255) NOT NULL,
  `gametype` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gametype`
--

INSERT INTO `gametype` (`id`, `gametype`) VALUES
(1, 'action role-playing'),
(2, 'Survival'),
(3, 'Actionadventure');

-- --------------------------------------------------------

--
-- Table structure for table `minage`
--

DROP TABLE IF EXISTS `minage`;
CREATE TABLE IF NOT EXISTS `minage` (
  `id` int(255) NOT NULL,
  `minage` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `minage`
--

INSERT INTO `minage` (`id`, `minage`) VALUES
(1, '7 years old'),
(2, '8 years old'),
(3, '13 years old'),
(4, '16 years old');

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prix` int(255) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(255) NOT NULL,
  `stockgame` int(255) NOT NULL,
  `gametype` text NOT NULL,
  `minage` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `image`, `nom`, `prix`, `description`, `quantity`, `stockgame`, `gametype`, `minage`) VALUES
(1, 'Spider-man.png', 'Spider-man', 20, 'Marvel\'s Spider-Man: Miles Morales Launch Trailer I PS5, PS4. Marvels Spider-Man: Miles Morales – Family Behind the Scenes | PS5, PS4. ', 4, 4, 'Actionadventure', '16 years old'),
(2, 'Mincraft.png', 'Mincraft', 17, 'Explore new gaming adventures, accessories, & merchandise on the Minecraft Official Site. Buy & download the game here, or check the site for the latest', 8, 0, 'survival', '8 years old');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` int(255) NOT NULL,
  `membershipnb` int(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `admin` binary(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `pseudo`, `email`, `password`, `membershipnb`, `Address`, `admin`) VALUES
(1, 'mimi', 'mimi@gmail.com', 123, 36518951, '18 hapiness avenue France', 0x0100),
(2, 'Maria', 'maria@gmail.com', 1234, 236535465, '3 hapiness avenue France', 0x0000);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
