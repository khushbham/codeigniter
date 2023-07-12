CREATE TABLE IF NOT EXISTS `stem_paginas` (
`pagina_ID` int(11) NOT NULL,
  `pagina_titel` varchar(250) NOT NULL,
  `pagina_titel_menu` varchar(250) NOT NULL,
  `pagina_url` varchar(250) NOT NULL,
  `pagina_inleiding` text NOT NULL,
  `pagina_tekst` text NOT NULL,
  `media_ID` int(11) DEFAULT NULL,
  `meta_gewenst` smallint(6) NOT NULL DEFAULT '1',
  `meta_title` varchar(60) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO `stem_paginas` (`pagina_ID`, `pagina_titel`, `pagina_titel_menu`, `pagina_url`, `pagina_inleiding`, `pagina_tekst`, `media_ID`, `meta_gewenst`, `meta_title`, `meta_description`) VALUES
(10, 'Leren localhost: het gaat niet alleen om de stem', 'Visie', 'visie', '', '<p><strong>Het brein achter localhost is regisseur, producent en stemacteur Barnier Geerling. Hij heeft in de 25 jaar dat hij zelf acteert een verarassende visie op het ontwikkeld. Hij draagt die visie inmiddels met een team van 12 docenten over op zijn cursisten.</strong></p>\r\n<p><strong>Zijn visie:</strong> localhost gaat niet alleen om de stem. Barnier: "wij geloven dat je alleen succesvol kunt zijn, als je de volgende 3 elementen als pijlers onder je mooie stem&nbsp; weet te zetten.</p>\r\n<p><strong>1: tekstbegrip</strong>&nbsp;<br />Barnier: "we leren onze cursisten per woord te zien: waarom staat dit hier? Hoe speel je met een nadruk? je moet ongeloofelijk goed doorhebben wat er staat. Pas dan kun je met je stem echt iets verkopen. Het helpt als je vanuit vooropleiding of je werk ervaring hebt met taal, maar dat is niet verplicht."</p>\r\n<p><strong> 2: lichaamstaal</strong>&nbsp;<br />Wist je dat je lichaamstaal kunt horen? als je met iemand belt, hoor je of iemand glimlacht of chagrijnig kijkt. Bij localhost krijg je als cursist dan ook veel feedback op je lichaamstaal en mimiek. Barnier: "We leren cursisten praten met de ogen en met de handen. Dat hoor je terug in de producties die ze inspreken."</p>\r\n<p><strong>3</strong><strong>: stemtechniek</strong>&nbsp;<br />localhost is niet simpelweg het aanleren van een trucje. Barnier: "Van teveel techniek ga je in je hoofd zitten. Daar klinkt een tekst ''gemaakt'' van. Bij ons leer je zeker stemtechniek, maar je gaat in de lessen vooral van denken naar voelen. ''Wat voel je bij een tekst?''. Veel cursisten vinden die aanpak eerst verschrikkelijk. "Moet ik nu gaan v&oacute;&eacute;len?!" Maar als ze het durven toelaten, en horen dat het waanzinnig veel effect heeft, dan zijn ze om. Die doorbraak is voor ons als docenten geweldig om mee te maken."</p>\r\n<p>[BUTTON-BEGIN]https://localhost/workshops [BUTTON-EIND][BUTTON-TEKST-BEGIN]&nbsp; Onze workshops [BUTTON-TEKST-EIND]</p>', 0, 1, '', '');

ALTER TABLE `stem_paginas`
 ADD PRIMARY KEY (`pagina_ID`);

ALTER TABLE `stem_paginas`
MODIFY `pagina_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;