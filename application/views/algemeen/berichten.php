<div id="berichten">
	<h1>Berichten</h1>
	<div id="buttons">
		<p><a href="<?php echo base_url($platform.'/berichten/nieuw') ?>" title="Nieuw bericht" class="belangrijk">Nieuw</a></p>
	</div>
	<?php if(sizeof($berichten) > 0): ?>
		<table cellpadding="0" cellspacing="0" border="0" class="berichten">
			<tr>
				<th class="beantwoord"></th>
				<th class="nieuw"></th>
				<th class="afzender">Van</th>
				<th class="onderwerp">Onderwerp</th>
				<th class="datum">Datum</th>
				<th class="tijd">Tijd</th>
				<th class="verwijderen"></th>
			</tr>
			<?php foreach($berichten as $bericht): ?>
				<tr>
					<td class="beantwoord <?php echo $bericht->bericht_beantwoord ?>"></th>
					<td class="nieuw"><a href="<?php echo base_url($platform.'/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><div class="enveloppe <?php echo $bericht->bericht_nieuw ?>"><?php echo $bericht->bericht_nieuw ?></div></a></td>
					<td class="afzender"><a href="<?php echo base_url($platform.'/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php if($bericht->bericht_afzender_type == 'docent') echo '<strong>'; ?><?php echo $bericht->gebruiker_naam ?><?php if($bericht->bericht_afzender_type == 'docent') echo '</strong>'; ?></a></td>
					<td class="onderwerp"><a href="<?php echo base_url($platform.'/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo $bericht->bericht_onderwerp ?></a></td>
					<td class="datum"><a href="<?php echo base_url($platform.'/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo toonDatum($bericht->bericht_datum) ?></a></td>
					<td class="tijd"><a href="<?php echo base_url($platform.'/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo toonTijd($bericht->bericht_datum) ?></a></td>
					<td class="verwijderen"><a href="<?php echo base_url($platform.'/berichten/verwijderen/'.$bericht->bericht_ID) ?>" title="Bericht verwijderen">Verwijderen</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else: ?>
		<p>Je hebt geen berichten.</p>
	<?php endif; ?>
</div>
