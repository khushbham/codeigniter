ALTER TABLE `stem_producten` ADD `product_beschikbaar` ENUM('ja','nee') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'ja' AFTER `product_prijs`; 
