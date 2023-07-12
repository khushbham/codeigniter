<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ical extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	
	///////////////
	// AFSPRAKEN //
	///////////////
	
	public function afspraken($beveiliging = null)
	{
		if($beveiliging != 'SwW9ihEgRCuz8tP') redirect('');
		
		// Afspraken ophalen
		
		$this->load->model('aanmeldingen_model');
		$afspraken = $this->aanmeldingen_model->getKalenderAfspraken();
		
		
		// Kalender openen
		
		$ical = "";
		
		$ical .= "BEGIN:VCALENDAR\r\n";
		$ical .= "METHOD:PUBLISH\r\n";
		$ical .= "VERSION:2.0\r\n";
		$ical .= "PRODID:-//localhost//NONSGML Afspraken//EN\r\n";
		
		$ical .= "X-WR-CALNAME:Afspraken\r\n";
		$ical .= "X-WR-TIMEZONE:Europe/Amsterdam\r\n";
		
		$ical .= "BEGIN:VTIMEZONE\r\n";
		$ical .= "TZID:Europe/Amsterdam\r\n";
		$ical .= "BEGIN:DAYLIGHT\r\n";
		$ical .= "TZOFFSETFROM:+0100\r\n";
		$ical .= "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\r\n";
		$ical .= "DTSTART:19810329T020000\r\n";
		$ical .= "TZNAME:GMT+02:00\r\n";
		$ical .= "TZOFFSETTO:+0200\r\n";
		$ical .= "END:DAYLIGHT\r\n";
		$ical .= "BEGIN:STANDARD\r\n";
		$ical .= "TZOFFSETFROM:+0200\r\n";
		$ical .= "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\r\n";
		$ical .= "DTSTART:19961027T030000\r\n";
		$ical .= "TZNAME:GMT+01:00\r\n";
		$ical .= "TZOFFSETTO:+0100\r\n";
		$ical .= "END:STANDARD\r\n";
		$ical .= "END:VTIMEZONE\r\n";
		
		// Afspraken toevoegen aan kalender
		
		if(sizeof($afspraken) > 0)
		{
			foreach($afspraken as $afspraak)
			{
				$starttijd				= strtotime($afspraak->aanmelding_afspraak);
				$starttijd_jaar 		= date('Y', $starttijd);
				$starttijd_maand 		= date('m', $starttijd);
				$starttijd_dag 			= date('d', $starttijd);
				$starttijd_uren 		= date('H', $starttijd);
				$starttijd_minuten 		= date('i', $starttijd);
				$starttijd_seconden 	= date('s', $starttijd);
				
				if($afspraak->aanmelding_type == 'intake')
				{
					// Intake 20 minuten
					$minuten = 20;
				}
				else
				{
					// Stemtest 30 minuten
					$minuten = 30;
				}
				
				if(!empty($afspraak->aanmelding_afspraak_eindtijd)) $eindtijd = strtotime($starttijd_jaar.'-'.$starttijd_maand.'-'.$starttijd_dag.' '.$afspraak->aanmelding_afspraak_eindtijd);
				else $eindtijd = $starttijd + (60 * $minuten);
				
				$eindtijd_jaar 			= date('Y', $eindtijd);
				$eindtijd_maand 		= date('m', $eindtijd);
				$eindtijd_dag 			= date('d', $eindtijd);
				$eindtijd_uren 			= date('H', $eindtijd);
				$eindtijd_minuten 		= date('i', $eindtijd);
				$eindtijd_seconden 		= date('s', $eindtijd);
				
				if(!empty($afspraak->gebruiker_mobiel)) $gebruiker_telefoonnummer = ' ('.$afspraak->gebruiker_mobiel.')';
				elseif(!empty($afspraak->gebruiker_telefoonnummer)) $gebruiker_telefoonnummer = ' ('.$afspraak->gebruiker_telefoonnummer.')';
				else $gebruiker_telefoonnummer = '';
				
				$UID		= $afspraak->aanmelding_ID."@localhost";
				$DTSTART 	= $starttijd_jaar.$starttijd_maand.$starttijd_dag.'T'.$starttijd_uren.$starttijd_minuten.$starttijd_seconden;
				$DTEND		= $eindtijd_jaar.$eindtijd_maand.$eindtijd_dag.'T'.$eindtijd_uren.$eindtijd_minuten.$eindtijd_seconden;
				$SUMMARY	= ucfirst($afspraak->aanmelding_type).' '.$afspraak->workshop_afkorting;
				$LOCATION	= $afspraak->gebruiker_naam.$gebruiker_telefoonnummer;
				
				$ical .= "BEGIN:VEVENT\r\n";
				$ical .= "UID:".$UID."\r\n";
				$ical .= "DTSTART;TZID=Europe/Amsterdam:".$DTSTART."\r\n";
				$ical .= "DTEND;TZID=Europe/Amsterdam:".$DTEND."\r\n";
				$ical .= "SUMMARY:".$SUMMARY."\r\n";
				$ical .= "LOCATION:".$LOCATION."\r\n";
				$ical .= "END:VEVENT\r\n";
			}
		}
		
		
		// Kalender sluiten
		
		$ical .= "END:VCALENDAR";
		
		header('Content-type: text/calendar; charset=utf-8');
		header('Content-Disposition: inline; filename=localhost-afspraken.ics');
		
		echo $ical;
		
		exit;
	}
	
	
	//////////////////
	// GROEPSLESSEN //
	//////////////////
	
	public function lessen($beveiliging = null)
	{
		if($beveiliging != 'AGPW5JXVvVGovhK') redirect('');
		
		// Groepslessen ophalen
		
		$this->load->model('lessen_model');
		$this->load->model('docenten_model');
		$lessen = $this->lessen_model->getKalenderLessen();
		
		
		// Kalender openen
		
		$ical = "";
		
		$ical .= "BEGIN:VCALENDAR\r\n";
		$ical .= "METHOD:PUBLISH\r\n";
		$ical .= "VERSION:2.0\r\n";
		$ical .= "PRODID:-//localhost//NONSGML Groepslessen//EN\r\n";
		
		$ical .= "X-WR-CALNAME:Groepslessen\r\n";
		$ical .= "X-WR-TIMEZONE:Europe/Amsterdam\r\n";
		
		$ical .= "BEGIN:VTIMEZONE\r\n";
		$ical .= "TZID:Europe/Amsterdam\r\n";
		$ical .= "BEGIN:DAYLIGHT\r\n";
		$ical .= "TZOFFSETFROM:+0100\r\n";
		$ical .= "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\r\n";
		$ical .= "DTSTART:19810329T020000\r\n";
		$ical .= "TZNAME:GMT+02:00\r\n";
		$ical .= "TZOFFSETTO:+0200\r\n";
		$ical .= "END:DAYLIGHT\r\n";
		$ical .= "BEGIN:STANDARD\r\n";
		$ical .= "TZOFFSETFROM:+0200\r\n";
		$ical .= "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\r\n";
		$ical .= "DTSTART:19961027T030000\r\n";
		$ical .= "TZNAME:GMT+01:00\r\n";
		$ical .= "TZOFFSETTO:+0100\r\n";
		$ical .= "END:STANDARD\r\n";
		$ical .= "END:VTIMEZONE\r\n";

		// Lessen toevoegen aan kalender
		
		if(sizeof($lessen) > 0)
		{
			foreach($lessen as $les)
			{
				$docent 				= "";
				$technicus 				= $les->technicus;
				$starttijd				= strtotime($les->groep_les_datum);
				$starttijd_jaar 		= date('Y', $starttijd);
				$starttijd_maand 		= date('m', $starttijd);
				$starttijd_dag 			= date('d', $starttijd);
				$starttijd_uren 		= date('H', $starttijd);
				$starttijd_minuten 		= date('i', $starttijd);
				$starttijd_seconden 	= date('s', $starttijd);
				
				if($les->les_locatie == 'studio')
				{
					// Studio groepslessen 4 uur
					$uren = 4;
					$locatie = 'Studio';
				}
				else
				{
					// Online groepslessen 1 uur
					$uren = 1;
					$locatie = 'Online';
				}
				
				if(!empty($les->groep_les_eindtijd)) $eindtijd = strtotime($starttijd_jaar.'-'.$starttijd_maand.'-'.$starttijd_dag.' '.$les->groep_les_eindtijd);
				else $eindtijd = $starttijd + (60 * 60 * $uren);
				
				$eindtijd_jaar 			= date('Y', $eindtijd);
				$eindtijd_maand 		= date('m', $eindtijd);
				$eindtijd_dag 			= date('d', $eindtijd);
				$eindtijd_uren 			= date('H', $eindtijd);
				$eindtijd_minuten 		= date('i', $eindtijd);
				$eindtijd_seconden 		= date('s', $eindtijd);

				if(!empty($les->docent_ID)) {
					$docent = $this->docenten_model->getDocentByID($les->docent_ID);
				}
				
				$UID		= $les->groep_les_ID."@localhost";
				$DTSTART 	= $starttijd_jaar.$starttijd_maand.$starttijd_dag.'T'.$starttijd_uren.$starttijd_minuten.$starttijd_seconden;
				$DTEND		= $eindtijd_jaar.$eindtijd_maand.$eindtijd_dag.'T'.$eindtijd_uren.$eindtijd_minuten.$eindtijd_seconden;
				$SUMMARY	= $les->groep_naam.' '.$locatie.' les';
				$LOCATION 	= $les->les_titel;

				if(!empty($docent)) {
					$LOCATION .= " Docent: ". $docent->docent_naam;
				}

				if(!empty($technicus)) {
					$LOCATION .= " Technicus: ". $technicus;
				}
				
				$ical .= "BEGIN:VEVENT\r\n";
				$ical .= "UID:".$UID."\r\n";
				$ical .= "DTSTART;TZID=Europe/Amsterdam:".$DTSTART."\r\n";
				$ical .= "DTEND;TZID=Europe/Amsterdam:".$DTEND."\r\n";
				$ical .= "SUMMARY:".$SUMMARY."\r\n";
				$ical .= "LOCATION:".$LOCATION."\r\n";
				$ical .= "END:VEVENT\r\n";
			}
		}
		
		
		// Kalender sluiten
		
		$ical .= "END:VCALENDAR";
		
		header('Content-type: text/calendar; charset=utf-8');
		header('Content-Disposition: inline; filename=localhost-lessen.ics');
		
		echo $ical;
		
		exit;
	}
}