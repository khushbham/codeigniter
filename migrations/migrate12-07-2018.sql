CREATE TABLE IF NOT EXISTS `stem_exports` (
`export_ID` int(11) NOT NULL,
  `export_naam` varchar(255) NOT NULL,
  `begin_ID` int(11) NOT NULL,
  `eind_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `stem_exports`
 ADD PRIMARY KEY (`export_ID`);

ALTER TABLE `stem_exports`
MODIFY `export_ID` int(11) NOT NULL AUTO_INCREMENT;

