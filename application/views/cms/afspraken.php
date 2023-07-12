<h1>Afspraken intakes en stemtests</h1>
<?php if(sizeof($aanmeldingen_afspraken) > 0): ?>
	<h2>Afspraak maken</h2>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="nummer">#</th>
			<th class="datum">Datum</th>
			<th class="tijd">Tijd</th>
			<th>Type</th>
			<th>Workshop</th>
			<th>Deelnemer</th>
			<th>Betaald</th>
			<th>Afspraak</th>
		</tr>
		<?php foreach($aanmeldingen_afspraken as $item): ?>
			<tr>
				<td class="nummer"><?php echo $item->aanmelding_ID ?></td>
				<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
				<td class="tijd"><?php echo date('H:i', strtotime($item->aanmelding_datum)) ?></td>
				<td><?php echo ucfirst($item->aanmelding_type) ?></td>
				<td><?php echo $item->workshop_titel ?></td>
				<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
				<td><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00') echo '<span class="fout">Nee</span>'; else echo '<span class="goed">Ja</span>'; ?></td>
				<td><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00'): ?><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Betaling wijzigen">Betaling wijzigen</a><?php else: ?><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak maken!">Afspraak maken!</a><?php endif; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>
<h2>Binnenkort</h2>
<?php if(sizeof($afspraken) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="nummer">#</th>
			<th class="datum">Datum</th>
			<th class="tijden">Tijd</th>
			<th>Type</th>
			<th>Workshop</th>
			<th>Deelnemer</th>
			<th>Voldoende</th>
			<th class="wijzigen"></th>
		</tr>
		<?php foreach($afspraken as $item): ?>
			<tr>
				<td class="nummer"><?php echo $item->aanmelding_ID ?></td>
				<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_afspraak)) ?></td>
				<td class="tijden"><?php echo date('H:i', strtotime($item->aanmelding_afspraak)) ?><?php if(!empty($item->aanmelding_afspraak_eindtijd)) echo ' - '.substr($item->aanmelding_afspraak_eindtijd, 0, 5); ?></td>
				<td><?php echo ucfirst($item->aanmelding_type) ?></td>
				<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
				<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
				<td><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak wijzigen"><?php echo ucfirst($item->aanmelding_voldoende) ?></a></td>
				<td class="wijzigen"><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak wijzigen">Wijzigen</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p><em>Er staan geen afspraken op het programma.</em></p>
<?php endif; ?>
<?php if(sizeof($afspraken_geweest) > 0): ?>
	<h2>Geweest</h2>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="nummer">#</th>
			<th class="datum">Datum</th>
			<th class="tijden">Tijd</th>
			<th>Type</th>
			<th>Workshop</th>
			<th>Deelnemer</th>
			<th>Voldoende</th>
			<th class="wijzigen"></th>
		</tr>
		<?php foreach($afspraken_geweest as $item): ?>
			<tr>
				<td class="nummer"><?php echo $item->aanmelding_ID ?></td>
				<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_afspraak)) ?></td>
				<td class="tijden"><?php echo date('H:i', strtotime($item->aanmelding_afspraak)) ?><?php if(!empty($item->aanmelding_afspraak_eindtijd)) echo ' - '.substr($item->aanmelding_afspraak_eindtijd, 0, 5); ?></td>
				<td><?php echo ucfirst($item->aanmelding_type) ?></td>
				<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
				<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
				<td><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak wijzigen"><?php echo ucfirst($item->aanmelding_voldoende) ?></a></td>
				<td class="wijzigen"><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak wijzigen">Wijzigen</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>