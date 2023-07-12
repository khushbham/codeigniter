ALTER TABLE `stem_gebruikers` CHANGE `gebruiker_rechten` `gebruiker_rechten` ENUM('admin','support','deelnemer','docent','demo','opleidingsmedewerker','kandidaat', 'test') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'deelnemer';
INSERT INTO `stemacter`.`stem_gebruikers` (`gebruiker_ID`, `gebruiker_rechten`, `gebruiker_status`, `gebruiker_bedrijfsnaam`, `gebruiker_naam`, `gebruiker_voornaam`, `gebruiker_tussenvoegsel`, `gebruiker_achternaam`, `gebruiker_geslacht`, `gebruiker_geboortedatum`, `gebruiker_adres`, `gebruiker_postcode`, `gebruiker_plaats`, `gebruiker_telefoonnummer`, `gebruiker_mobiel`, `gebruiker_emailadres`, `gebruiker_wachtwoord`, `gebruiker_aangemeld`, `gebruiker_online`, `gebruiker_notities`, `gebruiker_notitie_verbergen`, `gebruiker_instelling_anoniem`, `gebruiker_instelling_email_updates`) VALUES (NULL, 'test', 'actief', 'localhost', 'TEST account', 'TEST', NULL, 'account', 'man', '2018-04-15', 'Middelstegracht 89u', '2312 TT', 'leiden', '06 44 14 16 61', '06 44 14 16 61', 'info@localhost', '07c827a8a93b0b44e6d64eb655740569aec265e8', '2018-04-15 00:00:00', '2018-04-15 00:00:00', 'DUMMY ACCOUNTA', '0', 'nee', 'ja');