ALTER TABLE `stem_lessen` ADD `les_video_url` VARCHAR(255) NULL AFTER `les_voorbereidingsmail`;
ALTER TABLE `stem_les_types` ADD `les_beschikbaar` INT NOT NULL DEFAULT '0' AFTER `les_type_soort`;
ALTER TABLE `stem_les_types` ADD `les_weergeven` INT NOT NULL DEFAULT '0' AFTER `les_beschikbaar`;
ALTER TABLE `stem_les_types` ADD `les_gekoppeld_aan` INT NOT NULL DEFAULT '0' AFTER `les_weergeven`;
ALTER TABLE `stem_groepen_lessen` ADD `les_gekoppeld_aan_ID` INT NULL AFTER `les_voorbereidingsmail_verstuurd`;
ALTER TABLE `stem_groepen_lessen` ADD `les_dagen_ervoor_beschikbaar` INT NOT NULL DEFAULT '0' AFTER `les_gekoppeld_aan_ID`;