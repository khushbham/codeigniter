CREATE TABLE IF NOT EXISTS `stem_les_beoordelingen` (
`les_beoordeling_ID` int(11) NOT NULL,
  `les_ID` int(11) NOT NULL,
  `les_beoordeling` int(11) NOT NULL,
  `gebruiker_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_les_beoordelingen`
 ADD PRIMARY KEY (`les_beoordeling_ID`);

ALTER TABLE `stem_les_beoordelingen`
MODIFY `les_beoordeling_ID` int(11) NOT NULL AUTO_INCREMENT;

