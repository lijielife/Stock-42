-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 29 Mai 2012 à 17:30
-- Version du serveur: 5.1.61
-- Version de PHP: 5.3.3-7+squeeze9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `STOCK`
--

-- --------------------------------------------------------

--
-- Structure de la table `ADMINS`
--

DROP TABLE IF EXISTS `ADMINS`;
CREATE TABLE IF NOT EXISTS `ADMINS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USERS` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `ADMINS`
--


-- --------------------------------------------------------

--
-- Structure de la table `CATEGORIES`
--

DROP TABLE IF EXISTS `CATEGORIES`;
CREATE TABLE IF NOT EXISTS `CATEGORIES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE` varchar(40) NOT NULL,
  `DESCRIPTION` varchar(300) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `CATEGORIES`
--


-- --------------------------------------------------------

--
-- Structure de la table `ITEMS`
--

DROP TABLE IF EXISTS `ITEMS`;
CREATE TABLE IF NOT EXISTS `ITEMS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_LIBELLE` int(11) NOT NULL,
  `DATE_ACHAT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DATE_CONSO_DERNIERE` datetime NOT NULL,
  `PER` datetime NOT NULL,
  `ID_USER` int(11) NOT NULL,
  `QUANITITE` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `ITEMS`
--


-- --------------------------------------------------------

--
-- Structure de la table `LIBELLES`
--

DROP TABLE IF EXISTS `LIBELLES`;
CREATE TABLE IF NOT EXISTS `LIBELLES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE` varchar(20) NOT NULL,
  `DESCRIPTION` varchar(100) NOT NULL,
  `ID_CATEGORIE` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `LIBELLES`
--


-- --------------------------------------------------------

--
-- Structure de la table `LOGINS`
--

DROP TABLE IF EXISTS `LOGINS`;
CREATE TABLE IF NOT EXISTS `LOGINS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USERS` int(11) NOT NULL,
  `LOG_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `LOGINS`
--


-- --------------------------------------------------------

--
-- Structure de la table `L_ITEMS_RECETTES`
--

DROP TABLE IF EXISTS `L_ITEMS_RECETTES`;
CREATE TABLE IF NOT EXISTS `L_ITEMS_RECETTES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_ITEM` int(11) NOT NULL,
  `ID_RECETTE` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `L_ITEMS_RECETTES`
--


-- --------------------------------------------------------

--
-- Structure de la table `L_USERS_SERVICES`
--

DROP TABLE IF EXISTS `L_USERS_SERVICES`;
CREATE TABLE IF NOT EXISTS `L_USERS_SERVICES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USER` int(11) NOT NULL,
  `ID_SERVICE` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `L_USERS_SERVICES`
--


-- --------------------------------------------------------

--
-- Structure de la table `RECETTES`
--

DROP TABLE IF EXISTS `RECETTES`;
CREATE TABLE IF NOT EXISTS `RECETTES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(1000) NOT NULL,
  `DATE_REALISATION` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `RECETTES`
--


-- --------------------------------------------------------

--
-- Structure de la table `SERVICES`
--

DROP TABLE IF EXISTS `SERVICES`;
CREATE TABLE IF NOT EXISTS `SERVICES` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LIBELLE` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(500) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `SERVICES`
--


-- --------------------------------------------------------

--
-- Structure de la table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
CREATE TABLE IF NOT EXISTS `USERS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(50) NOT NULL,
  `PRENOM` varchar(50) NOT NULL,
  `LOGIN` varchar(50) NOT NULL,
  `MDP` varchar(32) NOT NULL,
  `MOE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PER` datetime NOT NULL,
  `DERNIERE_CONNEXION` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `USERS`
--

INSERT INTO `USERS` (`ID`, `NOM`, `PRENOM`, `LOGIN`, `MDP`, `MOE`, `PER`, `DERNIERE_CONNEXION`) VALUES
(1, 'Espinet', 'François', 'francois', 'eb7abf5f00d2dd1678fd3763b90d5ea7', '2012-05-29 16:42:57', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
