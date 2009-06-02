-- MySQL dump 10.11
--
-- Host: localhost    Database: typo3
-- ------------------------------------------------------
-- Server version	5.0.67-0ubuntu6

--
-- Table structure for table `tx_holidays_holidays`
--
DROP TABLE IF EXISTS `tx_holidays_holidays`;
CREATE TABLE `tx_holidays_holidays` (
  `uid` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL default '0',
  `tstamp` int(11) NOT NULL default '0',
  `crdate` int(11) NOT NULL default '0',
  `cruser_id` int(11) NOT NULL default '0',
  `deleted` tinyint(4) NOT NULL default '0',
  `hidden` tinyint(4) NOT NULL default '0',
  `type` int(11) NOT NULL default '0',
  `day` int(11) NOT NULL default '0',
  `country_exclude` text,
  `country_only` text,
  `name` text,
  PRIMARY KEY  (`uid`),
  KEY `parent` (`pid`)
);

--
-- Dumping data for table `tx_holidays_holidays`
--
INSERT INTO `tx_holidays_holidays` VALUES (1,0,0,0,1,0,0,0,1,'','','Neujahr');
INSERT INTO `tx_holidays_holidays` VALUES (2,0,0,0,1,0,0,1,0,'','','Ostersonntag');
INSERT INTO `tx_holidays_holidays` VALUES (3,0,0,0,1,0,0,0,6,'','','Heilige drei Könige');
INSERT INTO `tx_holidays_holidays` VALUES (4,0,0,0,1,0,0,0,228,'','','Mariä Himmelfahrt');
INSERT INTO `tx_holidays_holidays` VALUES (5,0,0,0,1,0,0,0,122,'','','Tag der Arbeit');
INSERT INTO `tx_holidays_holidays` VALUES (6,0,0,0,1,0,0,0,276,'','54','Tag der deutschen Einheit');
INSERT INTO `tx_holidays_holidays` VALUES (7,0,0,0,1,0,0,0,305,'','','Reformationstag');
INSERT INTO `tx_holidays_holidays` VALUES (8,0,0,0,1,0,0,0,306,'','','Allerheiligen');
INSERT INTO `tx_holidays_holidays` VALUES (9,0,0,0,1,0,0,0,359,'','','Heiligabend');
INSERT INTO `tx_holidays_holidays` VALUES (10,0,0,0,1,0,0,0,360,'','','1. Weihnachtstag');
INSERT INTO `tx_holidays_holidays` VALUES (11,0,0,0,1,0,0,0,361,'','','2. Weihnachtstag');
INSERT INTO `tx_holidays_holidays` VALUES (12,0,0,0,1,0,0,0,366,'','','Silvester');
INSERT INTO `tx_holidays_holidays` VALUES (13,0,0,0,1,0,0,1,-2,'','','Karfreitag');
INSERT INTO `tx_holidays_holidays` VALUES (14,0,0,0,1,0,0,1,-1,'','','Ostersamstag');
INSERT INTO `tx_holidays_holidays` VALUES (15,0,0,0,1,0,0,1,1,'','','Ostermontag');
INSERT INTO `tx_holidays_holidays` VALUES (16,0,0,0,1,0,0,1,39,'','','Christi Himmelfahrt');
INSERT INTO `tx_holidays_holidays` VALUES (17,0,0,0,1,0,0,1,49,'','','Pfingstsonntag');
INSERT INTO `tx_holidays_holidays` VALUES (18,0,0,0,1,0,0,1,50,'','','Pfingstmontag');
INSERT INTO `tx_holidays_holidays` VALUES (19,0,0,0,1,0,0,1,60,'','','Fronleichnam');
INSERT INTO `tx_holidays_holidays` VALUES (20,0,0,0,1,0,0,1,-48,'','54','Rosenmontag');
INSERT INTO `tx_holidays_holidays` VALUES (21,0,0,0,1,0,0,1,-47,'','54','Faschingsdienstag');
INSERT INTO `tx_holidays_holidays` VALUES (22,0,0,0,1,0,0,1,-46,'','54','Aschermittwoch');

--
-- Table structure for table `tx_holidays_holidaynames`
--
DROP TABLE IF EXISTS `tx_holidays_holidaynames`;
CREATE TABLE `tx_holidays_holidaynames` (
  `uid` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL default '0',
  `tstamp` int(11) NOT NULL default '0',
  `crdate` int(11) NOT NULL default '0',
  `cruser_id` int(11) NOT NULL default '0',
  `holiday_uid` int(11) NOT NULL default '0',
  `language_uid` int(11) NOT NULL default '0',
  `local_name` tinytext,
  PRIMARY KEY  (`uid`),
  KEY `parent` (`pid`)
) ;

--
-- Dumping data for table `tx_holidays_holidaynames`
--
INSERT INTO `tx_holidays_holidaynames` VALUES (1,0,0,0,1,1,43,'Neujahr');
INSERT INTO `tx_holidays_holidaynames` VALUES (2,0,0,0,1,1,30,'New Year''s Day');
INSERT INTO `tx_holidays_holidaynames` VALUES (3,0,0,0,1,2,43,'Ostersonntag');
INSERT INTO `tx_holidays_holidaynames` VALUES (4,0,0,0,1,2,30,'Easter Sunday');
INSERT INTO `tx_holidays_holidaynames` VALUES (5,0,0,0,1,3,43,'Heilige drei Könige');
INSERT INTO `tx_holidays_holidaynames` VALUES (6,0,0,0,1,3,30,'Epiphany');
INSERT INTO `tx_holidays_holidaynames` VALUES (7,0,0,0,1,4,43,'Mariä Himmelfahrt');
INSERT INTO `tx_holidays_holidaynames` VALUES (8,0,0,0,1,4,30,'Assumption Day');
INSERT INTO `tx_holidays_holidaynames` VALUES (9,0,0,0,1,5,43,'Tag der Arbeit');
INSERT INTO `tx_holidays_holidaynames` VALUES (10,0,0,0,1,5,30,'Labour Day');
INSERT INTO `tx_holidays_holidaynames` VALUES (11,0,0,0,1,6,43,'Tag der deutschen Einheit');
INSERT INTO `tx_holidays_holidaynames` VALUES (12,0,0,0,1,6,30,'Day of german unity');
INSERT INTO `tx_holidays_holidaynames` VALUES (13,0,0,0,1,7,43,'Reformationstag');
INSERT INTO `tx_holidays_holidaynames` VALUES (14,0,0,0,1,7,30,'Reformation Day');
INSERT INTO `tx_holidays_holidaynames` VALUES (15,0,0,0,1,8,43,'Allerheiligen');
INSERT INTO `tx_holidays_holidaynames` VALUES (16,0,0,0,1,8,30,'All Saints'' Day');
INSERT INTO `tx_holidays_holidaynames` VALUES (17,0,0,0,1,9,43,'Heiligabend');
INSERT INTO `tx_holidays_holidaynames` VALUES (18,0,0,0,1,9,30,'Christmas Eve');
INSERT INTO `tx_holidays_holidaynames` VALUES (19,0,0,0,1,10,43,'1. Weihnachtstag');
INSERT INTO `tx_holidays_holidaynames` VALUES (20,0,0,0,1,10,30,'Christmas Day');
INSERT INTO `tx_holidays_holidaynames` VALUES (21,0,0,0,1,11,43,'2. Weihnachtstag');
INSERT INTO `tx_holidays_holidaynames` VALUES (22,0,0,0,1,11,30,'Boxing Day');
INSERT INTO `tx_holidays_holidaynames` VALUES (23,0,0,0,1,12,43,'Silvester');
INSERT INTO `tx_holidays_holidaynames` VALUES (24,0,0,0,1,12,30,'New Year''s Eve');
INSERT INTO `tx_holidays_holidaynames` VALUES (25,0,0,0,1,13,43,'Karfreitag');
INSERT INTO `tx_holidays_holidaynames` VALUES (26,0,0,0,1,13,30,'Good Friday');
INSERT INTO `tx_holidays_holidaynames` VALUES (27,0,0,0,1,14,43,'Karsamstag');
INSERT INTO `tx_holidays_holidaynames` VALUES (28,0,0,0,1,14,30,'Holy Saturday');
INSERT INTO `tx_holidays_holidaynames` VALUES (29,0,0,0,1,15,43,'Ostermontag');
INSERT INTO `tx_holidays_holidaynames` VALUES (30,0,0,0,1,15,30,'Easter Monday');
INSERT INTO `tx_holidays_holidaynames` VALUES (31,0,0,0,1,16,43,'Christi Himmelfahrt');
INSERT INTO `tx_holidays_holidaynames` VALUES (32,0,0,0,1,16,30,'Ascension Day');
INSERT INTO `tx_holidays_holidaynames` VALUES (33,0,0,0,1,17,43,'Pfingstsonntag');
INSERT INTO `tx_holidays_holidaynames` VALUES (34,0,0,0,1,17,30,'Pentecost Sunday');
INSERT INTO `tx_holidays_holidaynames` VALUES (35,0,0,0,1,18,43,'Pfingstmontag');
INSERT INTO `tx_holidays_holidaynames` VALUES (36,0,0,0,1,18,30,'Pentecost Monday');
INSERT INTO `tx_holidays_holidaynames` VALUES (37,0,0,0,1,19,43,'Fronleichnam');
INSERT INTO `tx_holidays_holidaynames` VALUES (38,0,0,0,1,19,1,'Corpus Christi');
INSERT INTO `tx_holidays_holidaynames` VALUES (39,0,0,0,1,20,43,'Rosenmontag');
INSERT INTO `tx_holidays_holidaynames` VALUES (40,0,0,0,1,20,30,'Carnival Monday');
INSERT INTO `tx_holidays_holidaynames` VALUES (41,0,0,0,1,21,43,'Faschingsdienstag');
INSERT INTO `tx_holidays_holidaynames` VALUES (42,0,0,0,1,21,30,'Shrove Tuesday');
INSERT INTO `tx_holidays_holidaynames` VALUES (43,0,0,0,1,22,43,'Aschermittwoch');
INSERT INTO `tx_holidays_holidaynames` VALUES (44,0,0,0,1,22,30,'Ash Wednesday');



