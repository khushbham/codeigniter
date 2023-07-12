ALTER TABLE `stem_groepen` ADD `groep_min_gebruikers` INT NULL AFTER `groep_downloadlinkmail`;
ALTER TABLE `stem_groepen` ADD `groep_drempelwaarde_versturen` INT NULL AFTER `groep_min_gebruikers`;