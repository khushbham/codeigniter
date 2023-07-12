<!-- TITEL EN UITLEG -->

<div id="uitleg">
	<h1>Resultaten</h1>
</div>

<!-- OVERZICHT PRODUCTEN -->

<div id="resultaten">
	<?php if (sizeof($resultaten)) { ?>
		<?php foreach ($resultaten as $resultaat) : ?>
			<h2><?php echo $resultaat->les_titel ?></h2>
			<?php if (!empty($resultaat->resultaat_feedback_tekst)) : ?>
				<p><?php echo nl2br($resultaat->resultaat_feedback_tekst) ?></p>
			<?php endif; ?>
			<article style="display:flex; margin-bottom: 2em;">
				<div id='box' style="margin-right: 1em">
					<h3>Eerste inzending</h3>
					<div id="audio">
						<?php if (!empty($resultaat->huiswerk)) { ?>
							<?php foreach ($resultaat->huiswerk as $item) : ?>
								<div class="audio">
									<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
									<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="none"></audio>
								</div>
								<table cellpadding="10" cellspacing="0" class="gegevens">
									<?php foreach ($item->resultaten as $criteria_resultaat) { ?>
										<tr>
											<th><?php echo $criteria_resultaat->beoordelingscriteria_naam ?></th>
											<td><?php echo $criteria_resultaat->beoordelingscriteria_resultaat ?></td>
										</tr>
									<?php } ?>
								</table>
							<?php endforeach; ?>
					</div>
				<?php } ?>
				<?php if (!empty($resultaat->resultaat_feedback_src)) { ?>
					<div class="audio <?php if ($resultaat->resultaat_voldoende == 'ja') {
											echo "voldoende";
										} else {
											echo "onvoldoende";
										} ?>">
						<div class="audio_titel">Feedback docent</div>
						<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_feedback_src) ?>" preload="auto"></audio>
					</div>
				<?php } ?>
				<br>
				<?php if ($resultaat->resultaat_voldoende == 'ja') { ?>
					<h3>Resultaat: Voldoende</h3>
				<?php } elseif ($resultaat->resultaat_voldoende == 'nee') { ?>
					<h3>Resultaat: Onvoldoende</h3>
				<?php } else { ?>
					<h3>Resultaat: Nog niet beoordeeld</h3>
				<?php } ?>
				</div>

				<?php if (!empty($resultaat->huiswerk_opnieuw)) { ?>
					<div id='box' style="padding-left: 1em; border-left: 2px dotted orange;">
						<h3>Tweede inzending</h3>
						<div id="audio">
							<?php foreach ($resultaat->huiswerk_opnieuw as $item) : ?>
								<div class="audio">
									<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
									<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="none"></audio>
								</div>
								<table cellpadding="10" cellspacing="0" class="gegevens">
									<?php foreach ($item->resultaten as $criteria_resultaat) { ?>
										<tr>
											<th><?php echo $criteria_resultaat->beoordelingscriteria_naam ?></th>
											<td><?php echo $criteria_resultaat->beoordelingscriteria_resultaat ?></td>
										</tr>
									<?php } ?>
								</table>
							<?php endforeach; ?>
						</div>

						<?php if (!empty($resultaat->resultaat_opnieuw_feedback_src)) { ?>
							<div class="audio <?php if ($resultaat->resultaat_opnieuw_voldoende == 'ja') {
													echo "voldoende";
												} else {
													echo "onvoldoende";
												} ?>">
								<div class="audio_titel">Feedback docent</div>
								<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_opnieuw_feedback_src) ?>" preload="auto"></audio>
							</div>
						<?php } ?>
						<br>
						<?php if ($resultaat->resultaat_opnieuw_voldoende == 'ja') { ?>
							<h3>Resultaat: Voldoende</h3>
						<?php } elseif ($resultaat->resultaat_opnieuw_voldoende == 'nee') { ?>
							<h3>Resultaat: Onvoldoende</h3>
						<?php } else { ?>
							<h3>Resultaat: Nog niet beoordeeld</h3>
						<?php } ?>
					</div>
				<?php } ?>
			</article>
		<?php endforeach; ?>
	<?php } else { ?>
		<p>Er zijn nog geen resultaten beschikbaar</p>
	<?php } ?>
</div>