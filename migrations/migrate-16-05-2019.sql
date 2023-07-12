ALTER TABLE `stem_blogs` ADD `blog_publicatiedatum` DATE NOT NULL AFTER `blog_gepubliceerd`;
ALTER TABLE `stem_blogs` ADD `blog_uitgelicht` ENUM('ja','nee') NOT NULL AFTER `blog_gepubliceerd`;
update stem_blogs set blog_uitgelicht = "nee";