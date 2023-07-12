<?php

function veranderTijdstip($tijdstip)
{
	$tijdstip_array = explode(' ', $tijdstip);
	
	$datum 		= $tijdstip_array[0];
	$tijd 		= $tijdstip_array[1];
	
	$datum_array 	= explode('-', $datum);
	$tijd_array 	= explode(':', $tijd);
	
	$dag 		= $datum_array[2];
	$maand 		= intval($datum_array[1]);
	$jaar 		= $datum_array[0];
	
	$seconden 	= $tijd_array[2];
	$minuten 	= $tijd_array[1];
	$uren 		= $tijd_array[0];
	
	echo $dag.' '.veranderMaand($maand).' '.$jaar;
}

function toonDatum($tijdstip)
{
	$tijdstip_array 	= explode(' ', $tijdstip);
	$datum 				= $tijdstip_array[0];
	$datum_array 		= explode('-', $datum);
	$dag 				= $datum_array[2];
	$maand 				= intval($datum_array[1]);
	$jaar 				= $datum_array[0];
	
	if($maand < 10) $maand = '0'.$maand;
	
	echo $dag.' '.$maand.' '.substr($jaar, 2, 2);
}

function toonTijd($tijdstip)
{
	$tijdstip_array 	= explode(' ', $tijdstip);
	$tijd 				= $tijdstip_array[1];
	$tijd_array 		= explode(':', $tijd);
	$seconden 			= $tijd_array[2];
	$minuten 			= $tijd_array[1];
	$uren 				= $tijd_array[0];
	
	echo $uren.':'.$minuten;
}

function veranderMaand($maand)
{
	$maanden = array('januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');
	return $maanden[$maand-1];
}

?>