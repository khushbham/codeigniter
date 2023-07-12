
CREATE TABLE `stem_beoordelingscriteria` (
  `beoordelingscriteria_ID` int(11) NOT NULL,
  `beoordelingscriteria_naam` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `stem_beoordelingscriteria`
--
ALTER TABLE `stem_beoordelingscriteria`
  ADD PRIMARY KEY (`beoordelingscriteria_ID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `stem_beoordelingscriteria`
--
ALTER TABLE `stem_beoordelingscriteria`
  MODIFY `beoordelingscriteria_ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE `stem_beoordelingscriteria_resultaat` (
  `beoordelingscriteria_resultaat_ID` int(11) NOT NULL,
  `beoordelingscriteria_ID` int(11) NOT NULL,
  `resultaat_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `beoordelingscriteria_resultaat`
--
ALTER TABLE `stem_beoordelingscriteria_resultaat`
  ADD PRIMARY KEY (`beoordelingscriteria_resultaat_ID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `beoordelingscriteria_resultaat`
--
ALTER TABLE `stem_beoordelingscriteria_resultaat`
  MODIFY `beoordelingscriteria_resultaat_ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `stem_beoordelingscriteria_resultaat` ADD `beoordelingscriteria_resultaat` VARCHAR(255) NOT NULL AFTER `resultaat_ID`;

ALTER TABLE `stem_beoordelingscriteria_resultaat` ADD `beoordelingscriteria_resultaat_opnieuw` VARCHAR(255) NULL DEFAULT NULL AFTER `beoordelingscriteria_resultaat`;

ALTER TABLE `stem_beoordelingscriteria_resultaat` ADD `huiswerk_ID` INT NOT NULL AFTER `beoordelingscriteria_resultaat_opnieuw`;