CREATE TABLE IF NOT EXISTS `stem_uitnodigingen_links` (
`link_ID` int(11) NOT NULL,
  `workshop_ID` int(11) NOT NULL,
  `groep_ID` int(11) DEFAULT NULL,
  `link_code` varchar(5) NOT NULL,
  `link_gebruikt` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_uitnodigingen_links`
 ADD PRIMARY KEY (`link_ID`), ADD UNIQUE KEY `link_code` (`link_code`);

ALTER TABLE `stem_uitnodigingen_links`
MODIFY `link_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stem_uitnodigingen_links` ADD `link_link` VARCHAR(255) NOT NULL AFTER `link_code`;

