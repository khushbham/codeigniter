
ALTER TABLE `stem_gebruikers` ADD `gebruiker_profiel_foto` INT NOT NULL DEFAULT '0' AFTER `gebruiker_instelling_email_updates`;

CREATE TABLE IF NOT EXISTS `stem_profiel_media` (
`media_profiel_ID` int(11) NOT NULL,
  `media_type` enum('pdf','afbeelding','mp3','video','playlist') NOT NULL,
  `media_src` varchar(250) NOT NULL,
  `media_titel` varchar(250) DEFAULT NULL,
  `media_datum` datetime NOT NULL,
  `gebruiker_ID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `stem_profiel_media`
 ADD PRIMARY KEY (`media_profiel_ID`);

