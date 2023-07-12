-- localhost KENNISMAKINGSWORKSHOP ACCOUNT
-- LET OP! : moet een folder worden toegevoegd. media/kennismakingsworkshops

CREATE TABLE IF NOT EXISTS `stem_kennismakingsworkshop_media` (
`kennismakings_media_ID` int(11) NOT NULL AUTO_INCREMENT,
  `media_titel` varchar(250) NOT NULL,
  `media_src` varchar(250) NOT NULL,
  `media_datum` date NOT NULL,
  `kennismakingsworkshop_ID` int(11) NOT NULL,
  `gebruiker_ID` int(11) NOT NULL,
  PRIMARY KEY (`kennismakings_media_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

