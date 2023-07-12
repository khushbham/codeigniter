ALTER TABLE `stem_workshops` ADD `workshop_locatie` VARCHAR(55) NOT NULL DEFAULT 'Leiden' AFTER `workshop_beschrijving`;

CREATE TABLE IF NOT EXISTS `stem_les_types` (
`les_type_ID` int(11) NOT NULL,
  `les_type_soort` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_les_types`
 ADD PRIMARY KEY (`les_type_ID`);

ALTER TABLE `stem_les_types`
MODIFY `les_type_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stem_lessen` ADD `les_type_ID` INT NOT NULL AFTER `les_beschrijving`;