CREATE TABLE IF NOT EXISTS `stem_aanwezigheid` (
`aanwezigheid_ID` int(11) NOT NULL,
  `gebruiker_ID` int(11) NOT NULL,
  `les_ID` int(11) NOT NULL,
  `aanwezigheid_aanwezig` enum('ja','nee') NOT NULL DEFAULT 'ja'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_aanwezigheid`
 ADD PRIMARY KEY (`aanwezigheid_ID`);

ALTER TABLE `stem_aanwezigheid`
MODIFY `aanwezigheid_ID` int(11) NOT NULL AUTO_INCREMENT;
