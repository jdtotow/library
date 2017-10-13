-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 13 Octobre 2017 à 12:51
-- Version du serveur :  5.7.19-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `library`
--

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `B_ISBN` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `B_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `B_authors` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `books`
--

INSERT INTO `books` (`B_ISBN`, `B_title`, `B_authors`) VALUES
('1-21091-776-6', 'C programming', 'D. Kyriazi'),
('3-49485-696-8', 'Statictics', 'MF'),
('5-52323-958-2', 'Analyse 2', 'MF'),
('8-61901-940-2', 'Web programming 2', 'D.Kyriazi');

-- --------------------------------------------------------

--
-- Structure de la table `copies`
--

CREATE TABLE `copies` (
  `C_id` int(11) UNSIGNED NOT NULL,
  `B_ISBN` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `C_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `copies`
--

INSERT INTO `copies` (`C_id`, `B_ISBN`, `C_number`) VALUES
(1, '3-49485-696-8', 2),
(2, '8-61901-940-2', 13),
(3, '1-21091-776-6', 18),
(4, '5-52323-958-2', 4);

-- --------------------------------------------------------

--
-- Structure de la table `fields`
--

CREATE TABLE `fields` (
  `F_id` int(11) NOT NULL,
  `F_name` varchar(255) NOT NULL,
  `F_stats` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `fields`
--

INSERT INTO `fields` (`F_id`, `F_name`, `F_stats`) VALUES
(1, 'Math', 0),
(2, 'CS', 0);

-- --------------------------------------------------------

--
-- Structure de la table `fields2book`
--

CREATE TABLE `fields2book` (
  `F_id` int(11) NOT NULL,
  `B_ISBN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `loans`
--

CREATE TABLE `loans` (
  `C_Id` int(11) NOT NULL,
  `M_id` int(11) NOT NULL,
  `return_date` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `B_ISBN` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `loans`
--

INSERT INTO `loans` (`C_Id`, `M_id`, `return_date`, `B_ISBN`, `state`) VALUES
(1, 2, '1513029600000', '1-21091-776-6', 'borrow'),
(2, 3, '1512424800000', '3-49485-696-8', 'borrow'),
(3, 4, '1483567200000', '8-61901-940-2', 'borrow'),
(4, 4, '1503867600000', '1-21091-776-6', 'borrow'),
(5, 2, '1483221600000', '8-61901-940-2', 'borrow'),
(6, 4, '1513029600000', '1-21091-776-6', 'borrow');

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

CREATE TABLE `members` (
  `M_id` int(11) UNSIGNED NOT NULL,
  `M_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `M_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `M_stats` int(11) UNSIGNED NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `members`
--

INSERT INTO `members` (`M_id`, `M_name`, `M_role`, `M_stats`, `code`, `email`) VALUES
(2, 'Gianni', 'Student', 0, '1002', 'gianni@yahoo.fr'),
(3, 'Jean-Didier', 'student', 0, '1003', 'jdtotow@yahoo.fr'),
(4, 'Alfred Hugo', 'student', 0, '1004', 'alfred@yahoo.fr');

-- --------------------------------------------------------

--
-- Structure de la table `operator`
--

CREATE TABLE `operator` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `operator`
--

INSERT INTO `operator` (`id`, `username`, `email`, `password`) VALUES
(1, 'user', 'user@lbs.com', 'b51e452789a6a887f04f323e0aa1c0e12751e18426106fa3');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD UNIQUE KEY `B_ISBN` (`B_ISBN`);

--
-- Index pour la table `copies`
--
ALTER TABLE `copies`
  ADD PRIMARY KEY (`C_id`);

--
-- Index pour la table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`F_id`);

--
-- Index pour la table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`C_Id`);

--
-- Index pour la table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`M_id`);

--
-- Index pour la table `operator`
--
ALTER TABLE `operator`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `copies`
--
ALTER TABLE `copies`
  MODIFY `C_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `fields`
--
ALTER TABLE `fields`
  MODIFY `F_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `loans`
--
ALTER TABLE `loans`
  MODIFY `C_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `members`
--
ALTER TABLE `members`
  MODIFY `M_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `operator`
--
ALTER TABLE `operator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
