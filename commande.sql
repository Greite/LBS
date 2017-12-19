-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `commande`;
CREATE TABLE `commande` (
  `id` varchar(40) NOT NULL,
  `nom_client` varchar(50) NOT NULL,
  `prenom_client` varchar(50) NOT NULL,
  `mail_client` varchar(50) NOT NULL,
  `date_livraison` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `etat` tinyint(1) NOT NULL,
  `token` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2017-12-19 16:56:41
