<h1>Opdrachten</h1>
<?php if($this->session->userdata('beheerder_rechten') != 'docent'): ?>
<div id="huiswerk">
	<?php if(sizeof($resultaten) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="datum">Datum</th>
				<th class="tijd">Tijd</th>
				<th>Deelnemer</th>
				<th>Les</th>
				<th>Workshop</th>
				<th class="wijzigen"></th>
			</tr>
			<?php foreach($resultaten as $item): ?>
				<tr>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Opdracht beoordelen">Opdracht beoordelen</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else: ?>
		<p><em>Er hoeven geen opdrachten beoordeeld te worden.</em></p>
	<?php endif; ?>
	<?php if(sizeof($beoordelingen) > 0): ?>
		<h2>Recente beoordelingen</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="datum">Datum</th>
				<th class="tijd">Tijd</th>
				<th class="resultaat"></th>
				<th>Deelnemer</th>
				<th>Les</th>
				<th>Workshop</th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
			</tr>
			<?php foreach($beoordelingen as $item): ?>
				<tr>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->resultaat_beoordeeld_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->resultaat_beoordeeld_datum)) ?></td>
					<td class="<?php if($item->resultaat_voldoende == 'ja') echo 'voldoende'; else echo 'onvoldoende'; ?>"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Beoordeling bekijken"><span></span></a></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Beoordeling bekijken"></a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/edit-review/'.$item->resultaat_ID) ?>" title="Opdracht beoordelen">Opdracht beoordelen</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>
<?php else: ?>
	<div id="huiswerk">
		<?php if(sizeof($resultaten) > 0): ?>
			<table cellpadding="0" cellspacing="0" class="tabel">
				<tr>
					<th class="datum">Datum</th>
					<th class="tijd">Tijd</th>
					<th>Deelnemer</th>
					<th>Les</th>
					<th>Workshop</th>
					<th class="wijzigen"></th>
				</tr>
				<?php foreach($resultaten as $item): ?>
					<tr>
						<td class="datum"><?php echo date('d/m/Y', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
						<td class="tijd"><?php echo date('H:i', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
						<td><?php echo $item->gebruiker_naam ?></td>
						<td><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
						<td><?php echo $item->workshop_titel ?></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Opdracht beoordelen">Opdracht beoordelen</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			<p><em>Er hoeven geen opdrachten beoordeeld te worden.</em></p>
		<?php endif; ?>
		<?php if(sizeof($beoordelingen) > 0): ?>
			<h2>Recente beoordelingen</h2>
			<table cellpadding="0" cellspacing="0" class="tabel">
				<tr>
					<th class="datum">Datum</th>
					<th class="tijd">Tijd</th>
					<th class="resultaat"></th>
					<th>Deelnemer</th>
					<th>Les</th>
					<th>Workshop</th>
					<th class="bekijken"></th>
				</tr>
				<?php foreach($beoordelingen as $item): ?>
					<tr>
						<td class="datum"><?php echo date('d/m/Y', strtotime($item->resultaat_beoordeeld_datum)) ?></td>
						<td class="tijd"><?php echo date('H:i', strtotime($item->resultaat_beoordeeld_datum)) ?></td>
						<td class="<?php if($item->resultaat_voldoende == 'ja') echo 'voldoende'; else echo 'onvoldoende'; ?>"><span></span></td>
						<td><?php echo $item->gebruiker_naam ?></td>
						<td><?php echo $item->les_titel ?></td>
						<td><?php echo $item->workshop_titel ?></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Beoordeling bekijken"></a></td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
<?php endif; ?>
