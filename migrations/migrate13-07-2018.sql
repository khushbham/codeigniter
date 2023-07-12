-- CREATE TABLE IF NOT EXISTS `stem_updated_lessen` (
-- `updated_les_ID` int(11) NOT NULL,
--   `les_ID` int(11) NOT NULL,
--   `media_connectie_ID` int(11) NOT NULL,
--   `updated_datum` date NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- ALTER TABLE `stem_updated_lessen`
--  ADD PRIMARY KEY (`updated_les_ID`);
--
-- ALTER TABLE `stem_updated_lessen`
-- MODIFY `updated_les_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stem_media_connecties` ADD `media_ingang` DATE NOT NULL AFTER `content_ID`;
