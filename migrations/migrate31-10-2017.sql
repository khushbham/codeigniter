CREATE TABLE IF NOT EXISTS `stem_gebruiker_beoordelingen` (
`gebruiker_beoordeling_ID` int(11) NOT NULL,
  `les_ID` int(11) NOT NULL,
  `gebruiker_beoordeling` int(11) NOT NULL,
  `gebruiker_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_gebruiker_beoordelingen`
 ADD PRIMARY KEY (`gebruiker_beoordeling_ID`);

ALTER TABLE `stem_gebruiker_beoordelingen`
MODIFY `gebruiker_beoordeling_ID` int(11) NOT NULL AUTO_INCREMENT;

