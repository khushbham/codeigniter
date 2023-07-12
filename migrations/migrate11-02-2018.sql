ALTER TABLE `stem_workshops` ADD `workshop_zichtbaar` TINYINT(1) NOT NULL DEFAULT '1' AFTER `workshop_capayable`;
ALTER TABLE `stem_workshops` ADD `volledige_cursistenmodule` TINYINT(1) NOT NULL DEFAULT '1' AFTER `workshop_zichtbaar`;
ALTER TABLE `stem_gebruikers` CHANGE `gebruiker_rechten` `gebruiker_rechten` ENUM('admin','support','deelnemer','docent','demo','opleidingsmedewerker','kandidaat') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'deelnemer';

CREATE TABLE IF NOT EXISTS `stem_kandidaat_producten` (
`kandidaat_product_ID` int(11) NOT NULL,
  `product_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `stem_kandidaat_workshops` (
`kandidaat_workshop_ID` int(11) NOT NULL,
  `workshop_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_kandidaat_producten`
 ADD PRIMARY KEY (`kandidaat_product_ID`);

ALTER TABLE `stem_kandidaat_workshops`
 ADD PRIMARY KEY (`kandidaat_workshop_ID`);

ALTER TABLE `stem_kandidaat_producten`
MODIFY `kandidaat_product_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stem_kandidaat_workshops`
MODIFY `kandidaat_workshop_ID` int(11) NOT NULL AUTO_INCREMENT;

