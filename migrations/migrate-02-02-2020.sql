CREATE TABLE `stem_lessen_bekeken` (
  `les_bekeken_ID` int(11) NOT NULL,
  `les_ID` int(11) NOT NULL,
  `workshop_ID` int(11) NOT NULL,
  `gebruiker_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `stem_lessen_bekeken`
--
ALTER TABLE `stem_lessen_bekeken`
  ADD PRIMARY KEY (`les_bekeken_ID`);
