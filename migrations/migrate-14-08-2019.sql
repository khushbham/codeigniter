
CREATE TABLE `stem_annuleringen` (
  `annulering_ID` int(11) NOT NULL,
  `workshop_ID` int(11) NOT NULL,
  `annulering_percentage` int(11) NOT NULL,
  `annulering_actief` enum('Ja','Nee') COLLATE latin1_german1_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

ALTER TABLE `stem_annuleringen`
  ADD PRIMARY KEY (`annulering_ID`);

ALTER TABLE `stem_annuleringen`
  MODIFY `annulering_ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `stem_annuleringen` CHANGE `annulering_actief` `annulering_actief` ENUM('Ja','Nee') CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL;

INSERT INTO `stem_paginas` (`pagina_ID`, `pagina_titel`, `pagina_titel_menu`, `pagina_url`, `pagina_inleiding`, `pagina_tekst`, `media_ID`, `meta_gewenst`, `meta_title`, `meta_description`) VALUES ('13', 'Annuleringsverzekering', 'Annuleringsverzekering', 'Annuleringsverzekering', 'Dit is de detail pagina van de annuleringsverzekering.', 'De annuleringsverzekering zorgt ervoor dat jij als cursist 14 dagen van te voren kan afmelden of inschrijven voor een andere groep. Hierdoor kan je kiezen voor een andere startdatum, mocht de originele startdatum niet uitkomen.', NULL, '1', NULL, NULL);

INSERT INTO `stem_paginas` (`pagina_ID`, `pagina_titel`, `pagina_titel_menu`, `pagina_url`, `pagina_inleiding`, `pagina_tekst`, `media_ID`, `meta_gewenst`, `meta_title`, `meta_description`) VALUES ('14', 'Annuleringsverzekering', 'Annuleringsverzekering', 'Annuleringsverzekering', 'Dit is de detail pagina van de annuleringsverzekering.', 'De annuleringsverzekering zorgt ervoor dat jij als cursist 14 dagen van te voren kan afmelden of inschrijven voor een andere groep. Hierdoor kan je kiezen voor een andere startdatum, mocht de originele startdatum niet uitkomen.', NULL, '1', NULL, NULL);

ALTER TABLE `stem_aanmeldingen` ADD `annuleringsverzekering` INT NOT NULL DEFAULT '0' AFTER `aanmelding_geexporteerd`;