CREATE TABLE IF NOT EXISTS `stem_kortingscodes` (
`kortingscode_ID` int(11) NOT NULL,
  `kortingscode` text NOT NULL,
  `kortingscode_percentage` int(11) DEFAULT NULL,
  `kortingscode_vast_bedrag` int(11) DEFAULT NULL,
  `kortingscode_startdatum` date NOT NULL,
  `kortingscode_einddatum` date DEFAULT NULL,
  `kortingscode_limiet` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO `stem_kortingscodes` (`kortingscode_ID`, `kortingscode`, `kortingscode_percentage`, `kortingscode_vast_bedrag`, `kortingscode_startdatum`, `kortingscode_einddatum`, `kortingscode_limiet`) VALUES
(1, 'adinda2017', NULL, 75, '2016-12-08', '0000-00-00', NULL),
(2, 'hidus8j', NULL, 15, '2016-12-08', '0000-00-00', NULL),
(3, 'max2016', NULL, 100, '2016-12-08', '0000-00-00', NULL),
(4, 'liesbeth2016', NULL, 345, '2016-12-08', '0000-00-00', NULL),
(5, 'simon2016', NULL, 55, '2016-12-08', '0000-00-00', NULL),
(6, 'kortingscode', NULL, 1, '2016-12-08', '0000-00-00', NULL),
(7, '782hij3', NULL, 75, '2016-12-08', '0000-00-00', NULL);

ALTER TABLE `stem_kortingscodes`
 ADD PRIMARY KEY (`kortingscode_ID`);

ALTER TABLE `stem_kortingscodes`
MODIFY `kortingscode_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;

CREATE TABLE IF NOT EXISTS `stem_korting_connecties` (
`korting_connectie_ID` int(11) NOT NULL,
  `kortingscode_ID` int(11) DEFAULT NULL,
  `workshop_ID` int(11) DEFAULT NULL,
  `product_ID` int(11) DEFAULT NULL,
  `kennismakingsworkshop_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO `stem_korting_connecties` (`korting_connectie_ID`, `kortingscode_ID`, `workshop_ID`, `product_ID`, `kennismakingsworkshop_ID`) VALUES
(1, 1, 3, 0, 0),
(2, 2, 6, 0, 0),
(3, 3, 9, 0, 0),
(4, 4, 11, 0, 0),
(5, 5, 29, 0, 0),
(6, 6, 31, 0, 0),
(7, 7, 1, 0, 0);

ALTER TABLE `stem_korting_connecties`
 ADD PRIMARY KEY (`korting_connectie_ID`);

ALTER TABLE `stem_korting_connecties`
MODIFY `korting_connectie_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;

ALTER TABLE `stem_workshops` DROP COLUMN `workshop_korting_code`;
ALTER TABLE `stem_workshops` DROP COLUMN `workshop_korting_prijs`;
