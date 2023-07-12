RENAME TABLE stem_opendagen TO stem_kennismakingsworkshops;
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_ID kennismakingsworkshop_ID INT(11);
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_titel kennismakingsworkshop_titel VARCHAR(250);
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_omschrijving kennismakingsworkshop_omschrijving TEXT;
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_capaciteit kennismakingsworkshop_capaciteit INT(11);
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_dagen_korting_na_afloop kennismakingsworkshop_dagen_korting_na_afloop INT(11);
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_publicatiedatum kennismakingsworkshop_publicatiedatum DATETIME;
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_datum kennismakingsworkshop_datum DATETIME;
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_prijs kennismakingsworkshop_prijs INT(11);
ALTER TABLE stem_kennismakingsworkshops CHANGE COLUMN opendag_aanmelden_tekst kennismakingsworkshop_aanmelden_tekst TEXT;
ALTER TABLE stem_aanmeldingen CHANGE COLUMN opendag_ID kennismakingsworkshop_ID INT(11);
ALTER TABLE stem_aanmeldingen CHANGE COLUMN aanmelding_opendag_korting aanmelding_kennismakingsworkshop_korting ENUM('ja', 'nee');
ALTER TABLE stem_aanmeldingen CHANGE aanmelding_type aanmelding_type ENUM('intake','auditie', 'stemtest','workshop', 'opendag', 'kennismakingsworkshop') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
UPDATE stem_aanmeldingen set aanmelding_type = 'kennismakingsworkshop' where aanmelding_type = 'opendag';
ALTER TABLE stem_aanmeldingen CHANGE aanmelding_type aanmelding_type ENUM('intake','auditie', 'stemtest','workshop', 'kennismakingsworkshop') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;