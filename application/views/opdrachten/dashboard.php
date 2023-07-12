<!--------------->
<!-- Goedendag -->
<!--------------->

<h1 style="margin-bottom: 1em;"><?php echo $goedendag ?> <?php echo (!empty($this->session->userdata('gebruiker_voornaam'))) ? $this->session->userdata('gebruiker_voornaam') : $this->session->userdata('beheerder_voornaam') ?></h1>

<!---------------------------->
<!-- Ingestuurde opdrachten -->
<!---------------------------->

<?php if($this->session->userdata('beheerder_rechten') == 'admin') : ?>
<?php if(isset($ingestuurde_opdrachten) && sizeof($ingestuurde_opdrachten) > 0): ?>
	<div class="overzicht" style="margin-bottom: 3em;">
		<h2>Ingestuurde opdrachten</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th>Datum</th>
				<th>Tijd</th>
				<th>Deelnemer</th>
				<th>Opdracht</th>
				<th class="wijzigen"></th>
			</tr>
			<?php foreach($ingestuurde_opdrachten as $item): ?>
				<tr>
					<td><?php echo date('d/m/Y', strtotime($item->opdracht_datum)) ?></td>
					<td><?php echo date('H:i', strtotime($item->opdracht_datum)) ?></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Bekijk deelnemer"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><a href="<?php echo base_url('opdracht/'.$item->opdracht_url) ?>" title="Opdracht bekijken"><?php echo $item->opdracht_titel ?></a></td>
					<td class="wijzigen"><a href="<?php echo base_url('opdrachten/inzendingen/beoordelen/'.$item->opdracht_beoordeling_ID) ?>" title="Opdracht beoordelen">Opdrachten beoordelen</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php elseif (!isset($ingestuurde_opdrachten) && sizeof($ingestuurde_opdrachten) == 0) : ?>
	<p><em>Er hoeven geen opdrachten beoordeeld te worden.</em></p>
<?php endif; ?>




<!--------------------->
<!-- Recent ingelogd -->
<!--------------------->

<?php if(isset($recent_ingelogd) && sizeof($recent_ingelogd) > 0): ?>
	<div class="overzicht" style="margin-bottom: 3em;">
		<h2>Recent ingelogd</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
			<tr>
				<th>Ingelogd</th>
				<th>Deelnemer</th>
				<th>E-mailadres</th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
                <?php if($this->session->userdata('beheerder_rechten') == 'admin'): ?>
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

<?php endif; ?>


<?php if($this->session->userdata('gebruiker_rechten') == ('kandidaat' || 'deelnemer')) : ?>
	<!------------------------------------------->
	<!-- Ingestuurde opdrachten van gebruiker -->
	<!------------------------------------------->

	<?php if(isset($voltooide_opdrachten) && sizeof($voltooide_opdrachten) > 0): ?>
		<div class="overzicht" style="margin-bottom: 3em;">
			<h2>Voltooide opdrachten</h2>
			<table cellpadding="0" cellspacing="0" class="tabel">
				<tr>
					<th>Datum</th>
					<th>Tijd</th>
					<th>Opdracht</th>
					<th>Beoordeling</th>
					<th>Feedback</th>
					<th class="bekijken"></th>
				</tr>
				<?php foreach($voltooide_opdrachten as $item) : ?>
					<tr>
						<td><?php echo date('d-m-Y', strtotime($item->opdracht_datum)) ?></td>
						<td><?php echo date('H:i', strtotime($item->opdracht_datum)) ?></td>
						<td><?php echo $item->opdracht_titel ?></td>
						<td>
							<div class="rating_fixed">
								<label class="<?php if ($item->opdracht_beoordeling >= 5) {	echo ' selected'; } ?>">
									<input type="radio" name="rating" value="5" title="5 stars"> 5
								</label>
								<label class="<?php if ($item->opdracht_beoordeling >= 4) { echo ' selected'; } ?>">
									<input type="radio" name="rating" value="4" title="4 stars"> 4
								</label>
								<label class="<?php if ($item->opdracht_beoordeling >= 3) { echo ' selected'; } ?>">
									<input type="radio" name="rating" value="3" title="3 stars"> 3
								</label>
								<label class="<?php if ($item->opdracht_beoordeling >= 2) { echo ' selected'; } ?>">
									<input type="radio" name="rating" value="2" title="2 stars"> 2
								</label>
								<label class="<?php if ($item->opdracht_beoordeling >= 1) { echo ' selected'; } ?>">
									<input type="radio" name="rating" value="1" title="1 star"> 1
								</label>
							</div>
							<?php if (empty($item->opdracht_beoordeling)) : ?>
								<em>Nog geen beoordeling</em>
							<?php endif; ?>
						</td>
						<td>
						<?php if (!empty($item->opdracht_beoordeling_feedback)) : ?>
							<?php echo $item->opdracht_beoordeling_feedback ?>
						<?php elseif (!empty($item->opdracht_beoordeling)) : ?>
							<em>Geen feedback</em>
						<?php else : ?>
							<em>Nog geen feedback</em>
						<?php endif; ?>
						</td>
						<td class="bekijken"><a href="<?php echo base_url('opdracht/'.$item->opdracht_url) ?>" title="Opdracht bekijken">Opdracht bekijken</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	<?php elseif (!isset($voltooide_opdrachten) || empty($voltooide_opdrachten)): ?>
		<p><em>Je hebt nog geen opdrachten gemaakt. Als je een opdracht krijgt toegewezen en je hebt deze voltooid, verschijnt de opdracht hier.</em></p>
	<?php endif; ?>
<?php endif; ?>