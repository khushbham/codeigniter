
--
-- Tabelstructuur voor tabel `stem_blogs`
--

CREATE TABLE IF NOT EXISTS `stem_blogs` (
`blog_ID` int(11) NOT NULL,
  `blog_gepubliceerd` enum('ja','nee') NOT NULL DEFAULT 'ja',
  `blog_url` varchar(250) NOT NULL,
  `blog_titel` varchar(250) NOT NULL,
  `blog_deelnemer` varchar(250) NOT NULL,
  `blog_bericht` text NOT NULL,
  `blog_datum` datetime NOT NULL,
  `media_ID` int(11) NOT NULL,
  `media_tonen` enum('ja','nee') NOT NULL DEFAULT 'ja',
  `media_link` varchar(250) DEFAULT NULL,
  `meta_title` varchar(60) DEFAULT NULL,
  `meta_description` varchar(160) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `stem_blogs`

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `stem_blogs`
--
ALTER TABLE `stem_blogs`
 ADD PRIMARY KEY (`blog_ID`);

--
-- AUTO_INCREMENT voor een tabel `stem_blogs`
--
ALTER TABLE `stem_blogs`
MODIFY `blog_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;
