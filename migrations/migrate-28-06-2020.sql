ALTER TABLE `stem_producten` ADD `product_huur` INT(1) NOT NULL DEFAULT '0' AFTER `product_prijs`;
ALTER TABLE `stem_producten` ADD `product_borg` INT(255) NOT NULL DEFAULT '0' AFTER `product_prijs`;
ALTER TABLE `stem_producten` ADD `product_prijs_naderhand` INT(255) NOT NULL AFTER `product_huur`;
ALTER TABLE `stem_bestellingen_producten` ADD `bestellingen_huur` INT(255) NOT NULL DEFAULT '0' AFTER `verzonden`;