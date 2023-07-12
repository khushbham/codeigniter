<!--------------->
<!-- Goedendag -->
<!--------------->

<h1><?php echo $goedendag ?> <?php echo $this->session->userdata('beheerder_voornaam') ?></h1>
<!------------>
<!-- Zoeken -->
<!------------>
<?php if($this->session->userdata('beheerder_rechten') != 'contentmanager') { ?>
<?php if ($this->session->userdata('beheerder_rechten') != 'docent'): ?>
	<div id="zoeken">
		<form method="post" action="<?php echo base_url('cms/zoeken') ?>">
			<div id="zoekveld">
				<p><input type="text" name="zoeken_term" id="zoeken_term" placeholder="Zoeken" autofocus="on" autocomplete="off" /></p>
			</div>
			<div id="zoekresultaten">
				<table cellpadding="0" cellspacing="0" border="0">
				</table>
			</div>
		</form>
	</div>
<?php else: ?>
	<input type="hidden" id="zoeken_docent" value="" />
	<div id="zoeken">
		<form method="post" action="<?php echo base_url('cms/zoeken') ?>">
			<div id="zoekveld">
				<p><input type="text" name="zoeken_term" id="zoeken_term" placeholder="Zoeken" autofocus="on" autocomplete="off" /></p>
			</div>
			<div id="zoekresultaten">
				<table cellpadding="0" cellspacing="0" border="0">
				</table>
			</div>
		</form>
	</div>
<?php endif; ?>

<?php if($this->session->userdata('beheerder_rechten') == "docent" || $this->session->userdata('beheerder_rechten') == "opleidingsmedewerker") { ?>
<div id="links">
	<a href="<?php echo base_url('cms/dashboard/inloggen') ?>">Cursistenmodule bekijken</a>
</div>
<?php } ?>

<?php if(isset($lessen) && sizeof($lessen) > 0): ?>
	<div class="overzicht">
		<h2>Geplande lessen</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="datum">Datum</th>
				<th class="tijd">Start</th>
				<th class="tijd">Eind</th>
				<th>Groep</th>
				<th>Les</th>
				<th>Workshop</th>
				<th>Adres</th>
			</tr>
			<?php foreach($lessen as $item): ?>
				<tr>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->groep_les_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->groep_les_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->groep_les_eindtijd)) ?></td>
					<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="groep bekijken"><?php echo $item->groep_naam ?></a></td>
					<td><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
					<?php if($this->session->userdata('beheerder_rechten') == 'beheerder') { ?>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
					<?php } else { ?>
					<td><?php echo $item->workshop_titel ?></td>
					<?php } ?>
					<?php if($item->les_locatie == 'online'): ?>
						<td>Online</td>
					<?php else: ?>
						<td><?php if($item->les_locatie_ID == 4) { echo $item->groep_les_adres .", ". $item->groep_les_postcode. " ".$item->groep_les_plaats; } else { echo $item->locatie_adres; } ?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php elseif (!isset($ingestuurd_huiswerk) && sizeof($ingestuurd_huiswerk) == 0 && $this->session->userdata('beheerder_rechten') == 'docent'): ?>
	<p><em>Er hoeven geen opdrachten beoordeeld te worden.</em></p>
<?php endif; ?>


<!------------------------->
<!-- Ingestuurd huiswerk -->
<!------------------------->

<?php if(isset($ingestuurd_huiswerk) && sizeof($ingestuurd_huiswerk) > 0 && $this->session->userdata('beheerder_rechten') == 'admin'): ?>
	<div class="overzicht">
		<h2>Ingestuurde opdrachten</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="datum">Datum</th>
				<th class="tijd">Tijd</th>
				<th>Deelnemer</th>
				<th>Les</th>
				<th>Workshop</th>
				<th class="wijzigen"></th>
			</tr>
			<?php foreach($ingestuurd_huiswerk as $item): ?>
				<tr>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Bekijk deelnemer"><span class="<?php if(isset($item->gebruiker_markering) && $item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Bekijk workshop"><?php echo $item->workshop_titel ?></a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Huiswerk beoordelen">Opdrachten beoordelen</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>


<?php if(isset($ingestuurd_huiswerk) && sizeof($ingestuurd_huiswerk) > 0 && $this->session->userdata('beheerder_rechten') == 'docent'): ?>
	<div class="overzicht">
		<h2>Ingestuurde opdrachten</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="datum">Datum</th>
				<th class="tijd">Tijd</th>
				<th>Deelnemer</th>
				<th>Les</th>
				<th>Workshop</th>
				<th class="wijzigen"></th>
			</tr>
			<?php foreach($ingestuurd_huiswerk as $item): ?>
				<tr>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
					<td><?php echo $item->gebruiker_naam ?></td>
					<td><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
					<td><?php echo $item->workshop_titel ?></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Opdrachten beoordelen">Opdrachten beoordelen</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php elseif (!isset($ingestuurd_huiswerk) && sizeof($ingestuurd_huiswerk) == 0 && $this->session->userdata('beheerder_rechten') == 'docent'): ?>
	<p><em>Er hoeven geen opdrachten beoordeeld te worden.</em></p>
<?php endif; ?>
<!-------------------->
<!-- Afspraak maken -->
<!-------------------->

<?php if(isset($afspraak_maken) && sizeof($afspraak_maken) > 0): ?>
	<div class="overzicht">
		<h2>Afspraak maken</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="nummer">#</th>
				<th class="datum">Datum</th>
				<th class="tijd">Tijd</th>
				<th>Type</th>
				<th>Titel</th>
				<th>Deelnemer</th>
				<th>Afspraak</th>
				<th class="betaald">Betaald</th>
			</tr>
			<?php foreach($afspraak_maken as $item): ?>
				<tr>
					<td class="nummer"><?php echo $item->aanmelding_ID ?></td>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->aanmelding_datum)) ?></td>
					<td><?php echo ucfirst($item->aanmelding_type) ?></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Bekijk workshop"><?php echo $item->workshop_titel ?></a></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if(isset($item->gebruiker_markering) && $item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00'): ?><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Betaling wijzigen">Betaling wijzigen</a><?php else: ?><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak maken!">Afspraak maken!</a><?php endif; ?></td>
					<td class="betaald"><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>



<!------------------------>
<!-- Geplande afspraken -->
<!------------------------>

<?php if(isset($geplande_afspraken) && sizeof($geplande_afspraken) > 0): ?>
	<div class="overzicht">
		<h2>Geplande afspraken</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="datum">Datum</th>
				<th class="tijden">Tijd</th>
				<th>Type</th>
				<th>Titel</th>
				<th>Deelnemer</th>
				<th>Voldoende</th>
			</tr>
			<?php foreach($geplande_afspraken as $item): ?>
				<tr>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_afspraak)) ?></td>
					<td class="tijden"><?php echo date('H:i', strtotime($item->aanmelding_afspraak)) ?><?php if(!empty($item->aanmelding_afspraak_eindtijd)) echo ' - '.substr($item->aanmelding_afspraak_eindtijd, 0, 5); ?></td>
					<td><?php echo ucfirst($item->aanmelding_type) ?></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Bekijk workshop"><?php echo $item->workshop_titel ?></a></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if(isset($item->gebruiker_markering) && $item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak wijzigen"><?php echo ucfirst($item->aanmelding_voldoende) ?></a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>



<!------------------>
<!-- Aanmeldingen -->
<!------------------>

<?php if(isset($aanmeldingen) && sizeof($aanmeldingen) > 0): ?>
	<div class="overzicht">
		<h2>Aanmeldingen</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="nummer">#</th>
				<th class="datum">Datum</th>
				<th class="tijd">Tijd</th>
				<th>Type</th>
				<th>Titel</th>
				<th>Deelnemer</th>
				<th class="betaald">Betaald</th>
			</tr>
			<?php foreach($aanmeldingen as $item): ?>
				<tr>
					<td class="nummer"><?php echo $item->aanmelding_ID ?></td>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->aanmelding_datum)) ?></td>
					<?php if($item->aanmelding_type == 'kennismakingsworkshop')  { ?>
						<td style="padding-right: 20px"><?php echo ucfirst('workshop') ?></td>
					<?php } else { ?>
						<td><?php echo ucfirst($item->aanmelding_type) ?></td>
					<?php } ?>
					<?php if($item->aanmelding_type == 'kennismakingsworkshop'): ?>
						<td><a href="<?php echo base_url('cms/kennismakingsworkshops/'.$item->kennismakingsworkshop_ID) ?>" title="Kennismakingsworkshop bekijken"><?php echo $item->kennismakingsworkshop_titel ?></a></td>
					<?php else: ?>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
					<?php endif; ?>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if(isset($item->gebruiker_markering) && $item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td class="betaald"><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00'){ if($item->aanmelding_herinnering_datum == '0000-00-00 00:00:00') echo '<a href="'. base_url('cms/aanmeldingen/niet_betaald/'.$item->aanmelding_ID.'/'.true).'" onclick="return confirm(\'Weet je zeker dat je een betalingsherinnering wilt sturen?\')" title="Stuur Betalingsherinnering"><span class="nee verzenden"></span></a>'; else echo '<span class="nee herinnering"></span>'; } else echo '<span class="ja"></span>'; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>



<!------------------>
<!-- Bestellingen -->
<!------------------>

<?php if(isset($bestellingen_verzenden) && sizeof($bestellingen_verzenden) > 0): ?>
	<div class="overzicht">
		<h2>Bestellingen</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="nummer">#</th>
				<th class="datum">Datum</th>
				<th class="tijd">Producten</th>
				<th>Deelnemer</th>
				<th class="betaald">Betaald</th>
				<th class="verzonden">Verzonden</th>
			</tr>
			<?php foreach($bestellingen_verzenden as $item): ?>
				<tr>
					<td class="nummer"><?php echo $item->aanmelding_ID ?></td>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
					<td class="tijd"><?php echo $item->product_naam; ?></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if(isset($item->gebruiker_markering) && $item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td class="betaald"><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></td>
					<td class="verzonden"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php if($item->bestelling_verzonden_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>



<!--------------------->
<!-- Recent ingelogd -->
<!--------------------->

<?php if(isset($recent_ingelogd) && sizeof($recent_ingelogd) > 0): ?>
	<div class="overzicht">
		<h2>Recent ingelogd</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
			<tr>
				<th>Ingelogd</th>
				<th>Deelnemer</th>
				<th>E-mailadres</th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
                <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
				    <th class="verwijderen"></th>
                <?php endif; ?>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach($recent_ingelogd as $item):

				// Gebruiker online initialiseren

				$datum_dag 			= date('d-m-Y', strtotime($item->gebruiker_online));
				$datum_uren 		= date('H', strtotime($item->gebruiker_online));
				$datum_minuten 		= date('i', strtotime($item->gebruiker_online));
				$datum_seconden 	= date('s', strtotime($item->gebruiker_online));

				if($datum_dag == date('d-m-Y'))
				{
					$gebruiker_online = date('H:i', strtotime($item->gebruiker_online)).' uur';
				}
				else
				{
					$gebruiker_online = date('d-m-Y', strtotime($item->gebruiker_online));
				}

				?>
				<tr>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php echo $gebruiker_online ?></a></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php echo $item->gebruiker_emailadres ?></a></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken">Bekijken</a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/deelnemers/wijzigen/'.$item->gebruiker_ID) ?>" title="Deelnemer wijzigen">Wijzigen</a></td>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
					    <td class="verwijderen"><a href="<?php echo base_url('cms/deelnemers/verwijderen/'.$item->gebruiker_ID) ?>" title="Deelnemer verwijderen">Verwijderen</a></td>
                    <?php endif; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
<?php } else { ?>
	<?php if(sizeof($blogs) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th class="datum">Datum</th>
                <th class="tijd">Tijd</th>
                <th>Titel</th>
                <th>Geschreven door</th>
                <th class="bekijken"></th>
                <th class="wijzigen"></th>
                <th class="verwijderen"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($blogs as $item): ?>
                <tr <?php if($item->blog_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
                    <td class="datum"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php echo date('d/m/Y', strtotime($item->blog_datum)) ?></a></td>
                    <td class="tijd"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php echo date('H:i', strtotime($item->blog_datum)) ?></a></td>
                    <td><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php if($item->blog_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->blog_titel ?></a></td>
                    <td><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php if($item->blog_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->blog_deelnemer ?></a></td>
                    <td class="bekijken"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken">Bekijken</a></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/blog/wijzigen/'.$item->blog_ID) ?>" title="Artikel wijzigen">Wijzigen</a></td>
                    <td class="verwijderen"><a href="<?php echo base_url('cms/blog/verwijderen/'.$item->blog_ID) ?>" title="Artikel verwijderen">Verwijderen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
<?php endif; ?>
<?php } ?>
