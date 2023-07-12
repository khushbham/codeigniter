<h1>Inzendingen</h1>

<!-- OVERZICHT RESULTATEN -->

<div id="inzendingen">
	<?php if(sizeof($inzendingen)) { ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th>Datum</th>
				<th>Tijd</th>
				<th>Deelnemer</th>
				<th>Opdracht</th>
				<th>Uploads</th>
				<th>Beoordeling</th>
				<th>Feedback</th>
				<th class="wijzigen"></th>
			</tr>
			<?php foreach($inzendingen as $inzending): ?>
			<tr>
				<td><?php echo date('d/m/Y', strtotime($inzending->opdracht_datum)) ?></td>
				<td><?php echo date('H:i', strtotime($inzending->opdracht_datum)) ?></td>
				<td><a href="<?php echo base_url('cms/deelnemers/'.$inzending->gebruiker_ID) ?>" title="Bekijk deelnemer"><?php echo $inzending->gebruiker_naam ?></a></td>
				<td><a href="<?php echo base_url('opdracht/'.$inzending->opdracht_url) ?>" title="Opdracht bekijken"><?php echo $inzending->opdracht_titel ?></a></td>
				<td>
					<?php if(isset($inzending->uploads) && $inzending->uploads != null) : ?>
					<form method='post' action='<?php echo base_url('opdrachten/inzendingen/download/'.$inzending->opdracht_ID.'/'.$inzending->gebruiker_ID) ?>'>
						<input type="submit" name="createZip" value='Download'>
					</form>
					<?php else : ?>
						<em>Geen uploads</em>
					<?php endif; ?>
				</td>
				<td>
					<div class="rating_fixed">
						<label class="<?php if ($inzending->opdracht_beoordeling >= 5) {	echo ' selected'; } ?>">
							<input type="radio" name="rating" value="5" title="5 stars"> 5
						</label>
						<label class="<?php if ($inzending->opdracht_beoordeling >= 4) { echo ' selected'; } ?>">
							<input type="radio" name="rating" value="4" title="4 stars"> 4
						</label>
						<label class="<?php if ($inzending->opdracht_beoordeling >= 3) { echo ' selected'; } ?>">
							<input type="radio" name="rating" value="3" title="3 stars"> 3
						</label>
						<label class="<?php if ($inzending->opdracht_beoordeling >= 2) { echo ' selected'; } ?>">
							<input type="radio" name="rating" value="2" title="2 stars"> 2
						</label>
						<label class="<?php if ($inzending->opdracht_beoordeling >= 1) { echo ' selected'; } ?>">
							<input type="radio" name="rating" value="1" title="1 star"> 1
						</label>
					</div>
				</td>
				<td>
				<?php if (!empty($inzending->opdracht_beoordeling_feedback)) : ?>
					<?php echo $inzending->opdracht_beoordeling_feedback ?>
				<?php elseif (!empty($inzending->opdracht_beoordeling)) : ?>
					<em>Geen feedback</em>
				<?php else : ?>
					<em>Nog geen feedback</em>
				<?php endif; ?>
				</td>
				<td class="wijzigen"><a href="<?php echo base_url('opdrachten/inzendingen/beoordelen/'.$inzending->opdracht_beoordeling_ID) ?>" title="Opdracht beoordelen">Opdrachten beoordelen</a></td>
			</tr>
			<?php endforeach; ?>
		</table>
	<?php } else { ?>
	<p>Er zijn nog geen inzendingen beschikbaar.</p>
	<?php } ?>
</div>