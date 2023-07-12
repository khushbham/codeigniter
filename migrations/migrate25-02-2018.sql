CREATE TABLE IF NOT EXISTS `stem_upselling` (
`upselling_ID` int(11) NOT NULL,
  `workshop_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `stem_upselling_connecties` (
`upselling_connecties_ID` int(11) NOT NULL,
  `upselling_ID` int(11) NOT NULL,
  `product_ID` int(11) NOT NULL,
  `upselling_prijs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `stem_upselling`
 ADD PRIMARY KEY (`upselling_ID`);

ALTER TABLE `stem_upselling_connecties`
 ADD PRIMARY KEY (`upselling_connecties_ID`);

ALTER TABLE `stem_upselling`
MODIFY `upselling_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stem_upselling_connecties`
MODIFY `upselling_connecties_ID` int(11) NOT NULL AUTO_INCREMENT;