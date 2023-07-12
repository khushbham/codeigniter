ALTER TABLE `stem_groepen` ADD `groep_actief_datum` DATE NOT NULL AFTER `groep_archiveren`;
ALTER TABLE `stem_groepen` ADD `groep_archief_datum` DATE NOT NULL AFTER `groep_actief_datum`;