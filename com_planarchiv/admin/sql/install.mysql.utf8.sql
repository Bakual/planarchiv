DROP TABLE IF EXISTS `#__planarchiv_plan`;
DROP TABLE IF EXISTS `#__planarchiv_anlagetyp`;
DROP TABLE IF EXISTS `#__planarchiv_dfa`;
DROP TABLE IF EXISTS `#__planarchiv_didok`;
DROP TABLE IF EXISTS `#__planarchiv_dokutyp`;
DROP TABLE IF EXISTS `#__planarchiv_stockwerk`;

CREATE TABLE `#__planarchiv_plan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ErstellDatum` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ersteller_id` int(11) NOT NULL,
  `PlanErsteller` varchar(255) DEFAULT NULL,
  `Index1` varchar(1) DEFAULT NULL,
  `AenderungsDatum` datetime DEFAULT '0000-00-00 00:00:00',
  `CAD_Auftrag` varchar(30) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT 'AKS Code',
  `Maengelliste` tinyint(1) DEFAULT '0',
  `aktualisiert` tinyint(1) DEFAULT '0',
  `Bemerkung` longtext,
  `didok_id` int(10) NOT NULL,
  `stockwerk_id` int(10) NOT NULL,
  `dfa_id` int(10) NOT NULL,
  `GebDfaLfnr` varchar(255) DEFAULT NULL,
  `Strecke` varchar(40) DEFAULT NULL,
  `km` double DEFAULT NULL,
  `richtung_didok_id` int(10) NOT NULL,
  `anlagetyp_id` int(10) NOT NULL,
  `AnlageLfnr` varchar(255) DEFAULT NULL,
  `DokuTypNr` varchar(255) DEFAULT NULL,
  `dokutyp_id` int(11) DEFAULT NULL,
  `zurzeitbei_id` int(10) NOT NULL,
  `zurzeitbei_date` datetime DEFAULT NULL,
  `files` varchar(50) NOT NULL,
  `original` tinyint(1) NOT NULL,
  `alignment` varchar(1) NOT NULL,
  `size` varchar(50) NOT NULL,
  `catid` int(10) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `checked_out` int(10) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL,
  `language` char(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__planarchiv_anlagetyp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title_de` varchar(255) DEFAULT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `title_it` varchar(255) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `catid` int(10) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `checked_out` int(10) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__planarchiv_dfa` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code_de` varchar(255) NOT NULL,
  `title_de` varchar(255) DEFAULT NULL,
  `code_fr` varchar(255) DEFAULT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `code_it` varchar(255) DEFAULT NULL,
  `title_it` varchar(255) DEFAULT NULL,
  `catid` int(10) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `checked_out` int(10) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__planarchiv_didok` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `didok` varchar(255) DEFAULT NULL,
  `ktu` varchar(255) DEFAULT NULL,
  `catid` int(10) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `checked_out` int(10) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `didok` (`didok`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__planarchiv_dokutyp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code_de` varchar(255) DEFAULT NULL,
  `title_de` varchar(255) DEFAULT NULL,
  `code_fr` varchar(255) DEFAULT NULL,
  `title_fr` varchar(255) DEFAULT NULL,
  `code_it` varchar(255) DEFAULT NULL,
  `title_it` varchar(255) DEFAULT NULL,
  `catid` int(10) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `checked_out` int(10) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__planarchiv_stockwerk` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` double NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `catid` int(10) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) NOT NULL DEFAULT '0',
  `checked_out` int(10) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
