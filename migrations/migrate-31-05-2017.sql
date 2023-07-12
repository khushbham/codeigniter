CREATE TABLE IF NOT EXISTS `stem_templates` (
`template_ID` int(11) NOT NULL,
  `template_titel` varchar(50) NOT NULL,
  `template_tekst` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `stem_templates`
 ADD PRIMARY KEY (`template_ID`);

ALTER TABLE `stem_templates`
MODIFY `template_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stem_berichten` ADD `bericht_verwijderd_ontvanger` INT(1) NOT NULL AFTER `bericht_beantwoord`;
ALTER TABLE `stem_berichten` ADD `bericht_verwijderd_afzender` INT(1) NOT NULL AFTER `bericht_verwijderd_ontvanger`;