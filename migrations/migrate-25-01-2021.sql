-- Create 'stem_opdrachten'
CREATE TABLE `stem_opdrachten` (
	`opdracht_ID` int(11) auto_increment NOT NULL,
	`opdracht_titel` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`opdracht_beschrijving` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`opdracht_audio_titel` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`opdracht_uploads` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL NULL,
	`opdracht_uploads_aantal` int(11) DEFAULT 0 NOT NULL,
	`opdracht_media_ID` int(11) NOT NULL,
	`opdracht_url` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	CONSTRAINT `PRIMARY` PRIMARY KEY (opdracht_ID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

CREATE TABLE `stem_uploads` (
	`upload_ID` int(11) auto_increment NOT NULL,
	`upload_titel` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`upload_src` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`upload_datum` datetime NOT NULL,
	`gebruiker_ID` int(11) NOT NULL,
	`opdracht_ID` int(11) NOT NULL,
	CONSTRAINT `PRIMARY` PRIMARY KEY (upload_ID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;


CREATE TABLE `stem_opdrachten_beoordelingen` (
	`opdracht_beoordeling_ID` int(11) auto_increment NOT NULL,
	`opdracht_datum` datetime NOT NULL,
	`gebruiker_ID` int(11) NOT NULL,
	`opdracht_ID` int(11) NOT NULL,
	`opdracht_beoordeling` int(11) DEFAULT NULL NULL,
	`opdracht_beoordeling_feedback` text CHARACTER SET latin1 COLLATE latin1_german1_ci DEFAULT NULL NULL,
	CONSTRAINT `PRIMARY` PRIMARY KEY (opdracht_beoordeling_ID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

ALTER TABLE `stem_uploads` ADD CONSTRAINT `stem_uploads_FK` FOREIGN KEY (opdracht_ID) REFERENCES `stem_opdrachten(opdracht_ID)`;
ALTER TABLE `stem_uploads` ADD CONSTRAINT `stem_uploads_FK_1` FOREIGN KEY (gebruiker_ID) REFERENCES `stem_gebruikers(gebruiker_ID)`;
