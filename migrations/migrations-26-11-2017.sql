CREATE TABLE IF NOT EXISTS `stem_locaties` (
`locatie_ID` int(11) NOT NULL,
  `locatie_adres` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_locaties`
 ADD PRIMARY KEY (`locatie_ID`);

ALTER TABLE `stem_locaties`
MODIFY `locatie_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stem_groepen_lessen` ADD `les_locatie_ID` INT NOT NULL DEFAULT '4' AFTER `groep_les_eindtijd`;

INSERT INTO `stem_locaties` (`locatie_ID`, `locatie_adres`) VALUES
(1, 'Middelstegracht 89u, 2312 TT, Leiden'),
(2, 'Middelstegracht 89d, 2312 TT Leiden'),
(3, 'Ondiep-Zuidzijde 6, 3551 BW, Utrecht');

ALTER TABLE `stem_locaties`
 ADD PRIMARY KEY (`locatie_ID`);

ALTER TABLE `stem_locaties`
MODIFY `locatie_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

