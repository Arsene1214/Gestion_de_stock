-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 26 Octobre 2026 à 08:48
-- Version du serveur: 5.1.36-community-log
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `cici`
--

-- --------------------------------------------------------

--
-- Structure de la table `approvisionnement`
--

CREATE TABLE IF NOT EXISTS `approvisionnement` (
  `id_appro` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `produit` varchar(100) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_achat` decimal(10,2) DEFAULT NULL,
  `id_fournisseur` int(11) DEFAULT NULL,
  `id_devise` int(11) DEFAULT NULL,
  `id_employe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_appro`),
  KEY `id_fournisseur` (`id_fournisseur`),
  KEY `id_devise` (`id_devise`),
  KEY `id_employe` (`id_employe`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `approvisionnement`
--

INSERT INTO `approvisionnement` (`id_appro`, `date`, `produit`, `quantite`, `prix_achat`, `id_fournisseur`, `id_devise`, `id_employe`) VALUES
(1, '2026-01-16', 'KLKJ', 456, '9.00', 2, 8, 2),
(3, '2024-10-10', 'F', 234, '8.00', 1, 6, 10),
(4, '2024-10-10', 'F', 234, '8.00', 1, 6, 10),
(5, '2024-10-10', 'Ftbb', 234, '8.00', 1, 7, 10),
(6, '2026-07-07', 'tel', 12, '5909.00', 1, 1, 2),
(7, '2026-08-07', 'VT4', 14, '34.00', 1, 2, 2),
(8, '2026-08-07', 'tel', 12, '348.00', 4, 7, 52);

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `nom_article` varchar(100) DEFAULT NULL,
  `nombre_article` int(11) DEFAULT NULL,
  `prix_article` decimal(10,2) DEFAULT NULL,
  `id_employe` int(11) DEFAULT NULL,
  `id_stock` int(11) DEFAULT NULL,
  `id_devise` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_article`),
  KEY `id_employe` (`id_employe`),
  KEY `id_stock` (`id_stock`),
  KEY `id_devise` (`id_devise`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`id_article`, `date`, `nom_article`, `nombre_article`, `prix_article`, `id_employe`, `id_stock`, `id_devise`) VALUES
(4, NULL, 'ing', 34, '9.00', 2, 7, 1),
(5, NULL, 'tec', 39, '68.00', 2, 1, 1),
(7, NULL, 'UI', 20000, '900.00', 5, 2, 9),
(8, NULL, 'ITE', 12, '23.00', 2, 1, 3),
(9, '2026-07-02', 'yuyu', 34, '23.00', 2, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

CREATE TABLE IF NOT EXISTS `caisse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `type_operation` enum('ENTREE','SORTIE') NOT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `montant` decimal(15,2) NOT NULL,
  `devise` enum('USD','CDF') DEFAULT 'USD',
  `taux_change` decimal(10,2) DEFAULT '1.00',
  `montant_cdf` decimal(15,2) DEFAULT NULL,
  `mode_paiement` varchar(50) DEFAULT NULL,
  `reference_operation` varchar(100) DEFAULT NULL,
  `description` text,
  `id_employe` int(11) DEFAULT NULL,
  `statut` enum('VALIDÉ','EN ATTENTE') DEFAULT 'EN ATTENTE',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_date` (`date`),
  KEY `idx_type` (`type_operation`),
  KEY `idx_statut` (`statut`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `caisse`
--

INSERT INTO `caisse` (`id`, `date`, `type_operation`, `categorie`, `montant`, `devise`, `taux_change`, `montant_cdf`, `mode_paiement`, `reference_operation`, `description`, `id_employe`, `statut`, `date_creation`, `date_modification`) VALUES
(1, '2026-07-05', 'SORTIE', 'Salaire', '50.00', 'USD', '2350.00', '117500.00', 'Espèces', 'CAI20260710113842', 'pour avenacé de salaire ', 2, 'VALIDÉ', '2026-07-10 11:38:42', NULL),
(2, '2026-07-05', 'SORTIE', 'Salaire', '50.00', 'USD', '2350.00', '117500.00', 'Espèces', 'CAI20260710114118', 'pour avenacé de salaire ', 2, 'VALIDÉ', '2026-07-10 11:41:18', NULL),
(3, '2026-07-04', 'SORTIE', 'Salaire', '50.00', 'USD', '2850.00', '142500.00', 'Espèces', 'CAI20260710114951', 'un travailleur recalme son salaire ', 2, 'VALIDÉ', '2026-07-10 11:49:51', NULL),
(4, '2026-07-03', 'SORTIE', 'Salaire', '50.00', 'USD', '2850.00', '142500.00', 'Espèces', 'CAI20260710130904', 'VENTE', 2, 'VALIDÉ', '2026-07-10 13:09:04', NULL),
(5, '2026-08-09', 'SORTIE', 'Achat', '2367.00', 'USD', '6.00', '14202.00', '> Mobile Money', 'CAI20260711051455', 'FACTURE NURE 8', 11, 'VALIDÉ', '2026-07-11 05:14:55', NULL),
(6, '2026-08-15', 'ENTREE', 'Vente', '235.00', 'USD', '2300.00', '540500.00', '> Banque', 'CAI20260729100244', 'PEYEMENT DE FACTURE NUM 1', 1, 'VALIDÉ', '2026-07-29 10:02:44', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `adresse` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mail` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `client`
--

INSERT INTO `client` (`id_client`, `date`, `nom`, `adresse`, `telephone`, `mail`) VALUES
(7, '2026-07-02', 'NTI', 'kab', '9999999999', 'HHHHH@gmail.com'),
(8, '2026-06-30', 'CICI', 'BUKAVU', '9999999999', 'nnnn@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `depenses`
--

CREATE TABLE IF NOT EXISTS `depenses` (
  `id_depense` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `categorie` varchar(50) NOT NULL,
  `montant` decimal(12,2) NOT NULL,
  `devise` enum('USD','CDF') NOT NULL,
  `taux_change` decimal(12,2) DEFAULT '1.00',
  `montant_converti` decimal(12,2) DEFAULT NULL,
  `mode_paiement` enum('ESPECES','MOBILE_MONEY','BANQUE') NOT NULL,
  `beneficiaire` varchar(100) DEFAULT NULL,
  `reference_paiement` varchar(100) DEFAULT NULL,
  `description` text,
  `justificatif` varchar(255) DEFAULT NULL,
  `statut` enum('VALIDE','ANNULE') DEFAULT 'VALIDE',
  `id_employe` int(11) NOT NULL,
  `id` int(11) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_depense`),
  KEY `id_employe` (`id_employe`),
  KEY `id_caisse` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `depenses`
--

INSERT INTO `depenses` (`id_depense`, `date`, `categorie`, `montant`, `devise`, `taux_change`, `montant_converti`, `mode_paiement`, `beneficiaire`, `reference_paiement`, `description`, `justificatif`, `statut`, `id_employe`, `id`, `date_creation`) VALUES
(1, '2026-06-05', 'Achat', '230000.00', '', '100.00', '23000000.00', 'ESPECES', 'furniseur', 'id_fff n 5', 'pour achat', 'gghh', '', 10, 1, '2026-06-18 12:46:01');

-- --------------------------------------------------------

--
-- Structure de la table `dette`
--

CREATE TABLE IF NOT EXISTS `dette` (
  `id_dette` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `montant_dette` decimal(10,2) DEFAULT NULL,
  `devise` text,
  `nature_dete` varchar(250) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `id_employe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_dette`),
  KEY `id_client` (`id_client`),
  KEY `id_employe` (`id_employe`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `dette`
--

INSERT INTO `dette` (`id_dette`, `date`, `montant_dette`, `devise`, `nature_dete`, `id_client`, `id_employe`) VALUES
(4, '2026-06-13', '1200000.00', 'CDF', 'pour un client', 1, 11);

-- --------------------------------------------------------

--
-- Structure de la table `devise`
--

CREATE TABLE IF NOT EXISTS `devise` (
  `id_devise` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `nom_devise` enum('USD','CDF') DEFAULT NULL,
  `taux` decimal(10,2) DEFAULT NULL,
  `id_employe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_devise`),
  KEY `id_employe` (`id_employe`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `devise`
--

INSERT INTO `devise` (`id_devise`, `date`, `nom_devise`, `taux`, `id_employe`) VALUES
(5, NULL, 'CDF', '4500.00', 9),
(7, NULL, 'USD', '450.00', 3),
(6, NULL, 'CDF', '4500.00', 6),
(8, NULL, 'CDF', '4500.00', 6),
(9, NULL, 'USD', '5789.00', 14),
(10, '2026-08-16', 'CDF', '2300.00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE IF NOT EXISTS `employe` (
  `id_employe` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `nom` varchar(50) NOT NULL,
  `postnom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `fonction` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_employe`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Contenu de la table `employe`
--

INSERT INTO `employe` (`id_employe`, `date`, `nom`, `postnom`, `prenom`, `fonction`, `telephone`, `mail`, `salaire`) VALUES
(1, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(2, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(3, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(4, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(5, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(6, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(7, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(8, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(9, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(10, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(11, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(12, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(52, NULL, 'NEE', 'cirhuwa', 'arsene', 'nnnn', '09999999999', 'FDTEJ@gmail.com', '800.00'),
(24, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(30, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(31, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(35, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(49, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(37, NULL, 'C', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00'),
(50, NULL, 'cici', 'G', 'arsene', 'nnnn', '09999999999', 'ciciarsene@gmail.com', '23.00');

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE IF NOT EXISTS `facture` (
  `id_facture` int(11) NOT NULL AUTO_INCREMENT,
  `desingation` varchar(250) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `QT` int(11) DEFAULT NULL,
  `PU` decimal(10,0) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `devise` enum('USD','CDF') NOT NULL,
  `id_vente` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `id_employe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_facture`),
  KEY `id_vente` (`id_vente`),
  KEY `id_employe` (`id_employe`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `facture`
--

INSERT INTO `facture` (`id_facture`, `desingation`, `date`, `QT`, `PU`, `total`, `devise`, `id_vente`, `id_client`, `id_employe`) VALUES
(1, '1', '2026-06-05', 34, '8', '272.00', 'USD', 1, 1, 2),
(3, 'BATERIE', '2026-06-23', 50, '6', '300.00', 'USD', 1, 1, 52),
(4, '1', '2026-06-20', 234, '20', '4680.00', 'USD', 1, 1, 24),
(5, 'Achat', '2026-06-14', 48, '10', '480.00', 'USD', 3, 7, 24);

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE IF NOT EXISTS `fournisseur` (
  `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `adresse` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_employe` int(11) DEFAULT NULL,
  `id_devise` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_fournisseur`),
  KEY `id_employe` (`id_employe`),
  KEY `id_devise` (`id_devise`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id_fournisseur`, `date`, `nom`, `adresse`, `telephone`, `email`, `id_employe`, `id_devise`) VALUES
(4, NULL, 'serondo', 'I', '9999999999', 'vuhj@ggg', 24, 8);

-- --------------------------------------------------------

--
-- Structure de la table `payement_dette`
--

CREATE TABLE IF NOT EXISTS `payement_dette` (
  `id_dette_pey` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `montant_peye` decimal(10,2) DEFAULT NULL,
  `reste` decimal(10,2) DEFAULT NULL,
  `devise` text,
  `id_dette` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_dette_pey`),
  KEY `id_dette` (`id_dette`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `payement_dette`
--

INSERT INTO `payement_dette` (`id_dette_pey`, `date`, `montant_peye`, `reste`, `devise`, `id_dette`) VALUES
(1, '2026-06-14', '230000.00', '8000.00', 'CDF', 1),
(2, '2026-06-28', '458.00', '1199542.00', 'CDF', 4);

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id_stock` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `nom_produit` varchar(100) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `id_appro` int(11) DEFAULT NULL,
  `id_devise` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_stock`),
  KEY `id_devise` (`id_devise`),
  KEY `id_approvisionnement` (`id_appro`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `stock`
--

INSERT INTO `stock` (`id_stock`, `date`, `nom_produit`, `quantite`, `prix_unitaire`, `id_appro`, `id_devise`) VALUES
(1, '2026-06-13', 'HJ', 12, '567.00', 6, 5),
(3, '2026-06-07', 'HH', 12, '35.00', 5, 7),
(4, '2026-06-07', 'H', 12, '37.00', 5, 7);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `username`, `password`) VALUES
(1, 'Arsene CICI', 'CICI2025'),
(2, 'cici', 'd3e889e763df5df77fb044e969fad8bc');

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

CREATE TABLE IF NOT EXISTS `vente` (
  `id_vente` int(11) NOT NULL AUTO_INCREMENT,
  `produit` varchar(250) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `PU` decimal(10,0) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `devise` enum('USD','CDF') DEFAULT NULL,
  `id_article` int(11) NOT NULL,
  `id_employe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_vente`),
  KEY `id_employe` (`id_employe`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `vente`
--

INSERT INTO `vente` (`id_vente`, `produit`, `date`, `quantite`, `PU`, `montant`, `devise`, `id_article`, `id_employe`) VALUES
(3, 'teck', '2026-06-11', 12, '89', '1068.00', 'USD', 1, 24);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

