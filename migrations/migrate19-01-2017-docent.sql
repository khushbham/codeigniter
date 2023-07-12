ALTER TABLE `stem_lessen` ADD `docent_ID` INT(11) DEFAULT NULL AFTER `groep_ID`;
ALTER TABLE `stem_docenten` ADD `gebruiker_ID` INT(11) DEFAULT NULL AFTER `media_ID`;
ALTER TABLE `stem_resultaten` ADD `docent_ID` INT(11) DEFAULT NULL AFTER `les_ID`;
ALTER TABLE stem_gebruikers CHANGE COLUMN gebruiker_rechten gebruiker_rechten ENUM('admin', 'support', 'deelnemer', 'docent');

-- De docenten tabel zal geleegd moeten worden en de docenten moeten 1 voor 1 opnieuw moeten worden toegevoegd.
-- (dit is zodat er voor de docenten geldige gebruiker accounts worden toegevoegd)
--Ze staan hieronder ingevoegd om het makkelijker te maken.

-- docent_naam, docent_inleiding, docent_tekst, media_id
-- Beatrijs Sluiter
-- Na haar opleiding aan de Akademie voor Kleinkunst heeft ze jaren in de theaters in Nederland en Belgie gespeeld met Kindercabaret Potvoordrie. Daarna nog 12 jaar bij Theatergroep Mevrouw Smit.
--<p><span lang="NL">Vanaf 1980 werkte Beatrijs Sluijter bij diverse omroepen&nbsp; (AVRO 5 jaar, TROS 1 jaar en KRO 12,5 jaar) als programmamaakster, presentatrice en redactielid. In 1980 begon ook haar 'stemmenwerk'.<br /></span></p><p><span lang="NL">Beatrijs heeft honderden commercials, e-learning programma&rsquo;s, navigatie systemen, voice-response systemen en documentaires ingesproken is en sinds 1995 de vaste stem van het CBR.</span></p><p><span lang="NL">Ook de animatiewereld ontdekte haar. In welke films en series zat ze niet. Daar is haar liefde begonnen om haar kennis door te geven aan andere acteurs. Niets leukers dan de stem te regisseren en te zien hoe de stem bij het karakter past en tot leven komt.</span></p><p><span lang="NL">Casten ,regisseren, inspreken en lesgeven. Ze doet het tot op de dag van vandaag met heel veel plezier en passie.</span></p><p><em>Beatrijs is gastdocent voor de basisworkshop localhost en de workshop animatie &amp; nasynchronisatie.<br /></em></p>
-- 74

-- docent_naam, docent_inleiding, docent_tekst, media_id
-- Ben Maasdam
-- Sommigen noemen hem de nestor van het stemmenvak. Waarmee ze eigenlijk willen zeggen dat hij een oudje is die toch nog aardig meekomt...
--<p>Maar eigenlijk klopt dat wel. Ben Maasdam zit al meer dan dertig jaar in het vak en geeft al vijftien jaar trainingen en individuele coaching aan mensen die hopen om met hun stem een leuke boterham te verdienen.<br />Dat doet hij naast het inspreken van alles waarbij een lekkere stem de presentatie n&eacute;t dat beetje extra geeft. Voor radio en tv-commercials, maar ook voor documentaires, bedrijfsfilms, audio tours, computerspellen, tekenfilms, e-learningprogramma's en nog veel meer. <br />Mede hierdoor weet Ben wel zo'n beetje waar Abraham de mosterd haalt. En: een oude schoolmeester verloochent zijn afkomst nooit!<br /><br />Met veel aanstekelijk plezier en deskundigheid vertelt hij welke tips en trucs je nodig hebt om verder te komen in dit mooie vak. <br /><br /><em>Ben Maasdam verzorgt een videoles voor de basisworkshop&nbsp;localhost.</em><br /><br /><br /></p>
-- 80

-- docent_naam, docent_inleiding, docent_tekst, media_id
-- Alex Boon
-- Gedreven stemdocent, stem- en spraaktrainer. Werkt met professionals voor wie spreken een significant onderdeel vormt van de beroepsuitoefening
--<p><span lang="NL">Tijdens zijn studie logopedie stond &ldquo;de stemantropoloog&rdquo; in Alex Boon op. Zijn fascinatie voor de verbindingen tussen taal, stem, spraak, persoonlijkheid en cultuur brachten hem via zang en toneel in 1995 tot de start van zijn onderneming Rond Je Stem. <br />Sindsdien werkt hij aan de spraak en &lsquo;performance&rsquo; van professionals die hun inhoud op een functionele manier over het voetlicht willen brengen. De zo ontwikkelde werkwijze van het telkens opnieuw leren luisteren en spreken past hij toe in cursussen aan collega&rsquo;s, workshops voor belangstellenden of coaching en training van sprekers. Van acteur tot vergadertijger, politicus tot docent: fysiek zelfonderzoek in de stemwerkplaats leert hen de specifieke persoonlijke dynamiek adequaat in te zetten in de communicatie. Hij stelt hen in staat om autonoom te kunnen werken aan stem en spraak. Docent op verschillende opleidingen en instituten en trainer/coach bij een aantal bureau&rsquo;s. Vakidioot met ingebouwde spraakwaterval - verder heb je weinig last van hem. <br /></span></p><p><em><span lang="NL">Alex verzorgt een videoles voor de basisworkshop localhost en is gastdocent voor de vervolgworkshop localhost.</span></em></p><p>&nbsp;</p>
-- 96

-- docent_naam, docent_inleiding, docent_tekst, media_id
-- Willem van den Top
-- Voordat hij in 1997 voor de eerste keer achter een microfoon ging zitten om een tekst in te spreken, had hij al talloze stemopnames gedaan als geluidstechnicus.
-- <p>Hij kent het vak daardoor van verschillende kanten, en had al veel beroepsstemmen van dichtbij aan het werk gezien voordat hij er zelf eentje werd.<br />Sinds 2005 is Willem van den Top fulltime voice-over en dagelijks te horen op radio, tv en internet. Maar ook in klaslokalen, apps en speelgoed.<br />Zijn werkveld bestrijkt commercials, bedrijfsfilms, documentaires, eLearning, tv-programma&rsquo;s, hoorspelen en IVR. Eigen baas, dus niet alleen bezig met inspreken maar ook met (geluids)techniek, klantcontact en de papierwinkels. Plus dat hij zich graag inzet voor het op weg helpen van nieuw talent. Omdat de stemmenwereld nu anders in elkaar zit dan toen hij begon, en hij het zonde zou vinden al zijn kennis en ervaring voor zichzelf te houden. Dol op praten over stemmenwerk en niet bang om geheimen prijs te geven.<br /><br /><em>Willem van den Top verzorgt een videoles voor de basisworkshop&nbsp;localhost.</em></p>
-- 84

-- docent_naam, docent_inleiding, docent_tekst, media_id
-- Kyra Macco
-- Na haar afstuderen aan de toneelschool werkte Kyra jarenlang in het theater en voor televisie, en maakte daar kennis met het voice over vak. Zij is inmiddels een gerenommeerde en veel gevraagde 'stem' in zeer uiteenlopende producties.
-- <p>'All Round' is een goede omschrijving voor haar range, met een tone of voice van warm tot informatief, zakelijk tot nonchalant, vriendelijk tot stoer en multi-inzetbaar.<br /><br />Maar ook het les geven bleek in haar bloed te zitten.<br />Sinds jaar en dag geeft zij les aan acteurs en voice over studenten, geeft voice coachings aan presentatoren, commentatoren en voor wie dat verder wil of nodig heeft. En dat doet zij vol motivatie: haar passie voor taal, stem, inlevingsvermogen en communicatie maken haar tot een zeer gewaardeerde docente.<br /><br /></p>
-- 148

-- docent_naam, docent_inleiding, docent_tekst, media_id
-- Marja Ypenburg
-- Marja Ypenburg is al sinds 1977 werkzaam als logopedist. Ze behandelt  mensen van jong tot oud, die  problemen hebben op het gebied van stem, spraak, taal en gehoor. Ook heeft ze groepslessen gegeven aan docenten-in-opleiding.
-- <p><span lang="NL">Van jongs af aan is Marja actief met zingen en toneel, waaronder jaren bij AVRO's jeugd- en schoolradio. Het is dan ook logisch dat Marja kiest voor een stemberoep: logopedist. </span></p>
-- 95

-- docent_naam, docent_inleiding, docent_tekst, media_id
-- Jacqueline Blom
-- Al ruim 20 jaar een ervaren en veelzijdig voice professional, met een warme en vertrouwde stem. En een nog warmer karakter.
-- <p>Jacqueline Blom begon met stemmenwerk toen het vak nog grotendeels in de kinderschoenen stond. Ze kent daardoor alle aspecten van het inspreekvak. Haar tone of voice is zakelijk, warm en geruststellend, maar ook net zo makkelijk vol humor en plezier. En dat straalt ze uit.&nbsp;</p><p><span lang="NL">Naast haar stemmenwerk heeft Jacqueline ervaring in presentatie- trainings- en acteerwerk en deelt ze haar kennis van het vak als voice-coach voor aankomend talent.<br /></span></p><p><em>Jacqueline verzorgt een videoles voor de basisworkshop localhost.</em></p><p>&nbsp;</p><p><span lang="NL">&nbsp;</span></p><p>&nbsp;</p>
-- 77

-- docent_naam, docent_inleiding, docent_tekst, media_id
-- Renate Dorrestein
-- Renate Dorrestein is niet alleen de auteur van tientallen romans, zij spreekt de audio-versie van haar werk ook al jarenlang zelf in.
-- <p><span lang="NL">Na zoveel uren in de studio weet zij als geen ander waar je als voorlezer op moet letten. Door haar ervaring als schrijfster kan ze bovendien op heldere manier zaken zoals &lsquo;het geheim van de alinea&rsquo; uitleggen. Ze gelooft dat luisterboeken niet alleen ideaal zijn voor lange autoritten en tijdens het strijken, maar ook het ultieme cadeau voor je slechtziende oma, je dyslectische neefje en je laaggeletterde buurman. Vanwege haar inspanningen op dit gebied werd ze in 2008 uitgeroepen tot Ambassadeur van het Luisterboek. Tevens is zij gekozen als de auteur &eacute;n voorlezer van het Luistergeschenk 2014, Liever horen we onszelf, dat in de jaarlijkse Week van het Luisterboek aan boekenkopers wordt aangeboden. Haar stemhelden zijn Jan Meng, Job Cohen en Cees van Ede.</span></p><p><em>Renate Dorrestein is gastdocent voor de workshop luisterboeken en hoorspelen.</em></p>
-- 156

