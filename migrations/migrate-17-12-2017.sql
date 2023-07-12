SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `stem_gratis_lessen` (
`les_ID` int(1) NOT NULL,
  `les_youtube_link` varchar(255) NOT NULL,
  `les_publicatie_datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_gratis_lessen`
 ADD PRIMARY KEY (`les_ID`);

ALTER TABLE `stem_gratis_lessen`
MODIFY `les_ID` int(1) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stem_gratis_lessen` ADD `les_tekst` TEXT NOT NULL AFTER `les_youtube_link`;

ALTER TABLE `stem_gratis_lessen` CHANGE `les_publicatie_datum` `les_publicatiedatum` DATE NOT NULL;

ALTER TABLE `stem_gratis_lessen` ADD `les_titel` VARCHAR(255) NOT NULL AFTER `les_ID`;

INSERT INTO `stem_gegevens` (`gegeven_ID`, `gegeven_naam`, `gegeven_waarde`) VALUES ('10', 'Gratis workshop aan', 'ja');

