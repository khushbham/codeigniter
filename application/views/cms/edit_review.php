<head>
	<script src="https://www.localhost/assets/js/recorderjs/dist/recorder.js"></script>
   <style type="text/css">
		div#inhoud div#audio {
		display: flex;
		justify-content: space-between;
		align-items: center;
		}
		.audio_titel{
			display: flex;
			justify-content: center;
		}
		span.delete_huiswerk {
		margin-top: 15px;
		margin-left: 10px;
		}
		.crossIcon {
		margin: 0 10px 0 0 ;
		font-size: 23px;
		color: red;
		z-index: 999999;
		}
    </style>
</head>
<input type="hidden" name="upload" id="ID" value="<?php echo $resultaat->resultaat_ID ?>" />

<h1>Opdrachten beoordelen</h1>
<form method="post" enctype="multipart/form-data" action="<?php echo base_url('cms/edit-review/' . $resultaat->resultaat_ID); ?>">
	<table cellpadding="0" cellspacing="0" class="gegevens">
		<tr>
			<th>Ingestuurd</th>
			<td><?php echo date('d-m-Y', strtotime($resultaat->resultaat_ingestuurd_datum)) ?> om <?php echo date('H:i:s', strtotime($resultaat->resultaat_ingestuurd_datum)) ?> uur</td>
		</tr>
		<?php if ($this->session->userdata('beheerder_rechten') != 'docent') : ?>
			<tr>
				<th>Deelnemer</th>
				<td><a href="<?php echo base_url('cms/deelnemers/' . $resultaat->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php echo $resultaat->gebruiker_naam ?></a></td>
			</tr>
			<tr>
				<th>Les</th>
				<td><a href="<?php echo base_url('cms/lessen/' . $resultaat->les_ID) ?>" title="Les bekijken"><?php echo $resultaat->les_titel ?></a></td>
			</tr>
			<tr>
				<th>Workshop</th>
				<td><a href="<?php echo base_url('cms/workshops/' . $resultaat->workshop_ID) ?>" title="Workshop bekijken"><?php echo $resultaat->workshop_titel ?></a></td>
			</tr>
		<?php else : ?>
			<tr>
				<th>Deelnemer</th>
				<td><?php echo $resultaat->gebruiker_naam ?></a></td>
			</tr>
			<tr>
				<th>Les</th>
				<td><?php echo $resultaat->les_titel ?></a></td>
			</tr>
			<tr>
				<th>Workshop</th>
				<td><?php echo $resultaat->workshop_titel ?></a></td>
			</tr>
		<?php endif; ?>
		<tr>
			<th>Opdrachten</th>
			<td>

				<?php foreach ($huiswerk as $item) : ?>
						<div id="audio">
							<div class="audio" style="float: left;">
								<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
								<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="auto"></audio>
							</div>
							<span class="delete_huiswerk"><a href="<?php echo base_url('cms/huiswerk/verwijder_item/' . $item->huiswerk_ID . '/' . $resultaat->resultaat_ID) ?>">X</a></span>
						</div>
				<?php endforeach; ?>
			</td>
		</tr>
		<?php if ($resultaat->resultaat_voldoende != 'onbekend') : ?>
			<?php if (!empty($huiswerk)) { ?>
				<?php foreach ($huiswerk as $item) { ?>
					<tr>
						<th><strong><?php echo 'Opdracht: ' . $item->huiswerk_titel ?></strong></th>
						<td></td>
					</tr>
					<?php foreach ($item->resultaten as $criteria_resultaat) { ?>
						<tr>

							<th><?php echo $criteria_resultaat->beoordelingscriteria_naam ?></th>
							<td><input type="radio" name="item_criteria_voldoende[<?php echo $criteria_resultaat->beoordelingscriteria_ID ?>][<?php echo $criteria_resultaat->huiswerk_ID ?>]" id="item_criteria_voldoende" value="voldoende" <?php if ($criteria_resultaat->beoordelingscriteria_resultaat == 'voldoende') echo 'checked'; ?> /> Voldoende

								<input type="radio" name="item_criteria_voldoende[<?php echo $criteria_resultaat->beoordelingscriteria_ID ?>][<?php echo $criteria_resultaat->huiswerk_ID ?>]" id="item_criteria_onvoldoende" value="onvoldoende" <?php if ($criteria_resultaat->beoordelingscriteria_resultaat == 'onvoldoende') echo 'checked'; ?> /> Onvoldoende
							</td>
						</tr>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<tr>
				<th>Voldoende *</th>

				<td><input type="radio" name="item_voldoende" id="item_voldoende_ja" value="ja" <?php if ($item_voldoende == 'ja' || ($item_voldoende != 'ja' && $item_voldoende != 'nee')) echo 'checked'; ?> /> Ja
					<input type="radio" name="item_voldoende" id="item_voldoende_nee" value="nee" <?php if ($item_voldoende == 'nee') echo 'checked'; ?> /> Nee
					<span class="feedback"><?php echo $item_voldoende_feedback ?></span>
				</td>
			</tr>
			<tr>
				<th>Feedback</th>
				<td>
					<textarea name="item_feedback_tekst" id="item_feedback_tekst" class="opmaak_simpel"><?php if (!empty($resultaat->resultaat_feedback_tekst)) {
																											echo $resultaat->resultaat_feedback_tekst;
																										} else {
																											echo '';
																										} ?></textarea>
				</td>
			</tr>
			<?php if ($resultaat->resultaat_opnieuw_voldoende != 'onbekend') : ?>
				<tr>
					<th>Opnieuw feedback</th>
					<td>
						<textarea name="resultaat_opnieuw_feedback_tekst" id="item_feedback_tekst" class="opmaak_simpel"><?php if (!empty($resultaat->resultaat_opnieuw_feedback_tekst)) {
																																echo $resultaat->resultaat_opnieuw_feedback_tekst;
																															} else {
																																echo '';
																															} ?></textarea>
					</td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
		<tr>
			<th>Notities</th>
			<td><textarea name="item_notities" id="item_feedback_tekst" class="opmaak_simpel"><?php if (!empty($resultaat->resultaat_notities)) echo $resultaat->resultaat_notities;
																								else echo ''; ?></textarea></td>
		</tr>

		<input type="hidden" name="item_opnieuw" value="0" />
		<input type="hidden" name="opnieuw_insturen" value="ja" />

		<?php if (isset($docent)) : ?>
			<tr>
				<th>Docent</th>
				<td><?php echo $docent->gebruiker_naam; ?></a></td>
			</tr>
		<?php endif; ?>
	</table>
	<p class="submit"><input type="submit" value="Bijwerken" name="update_review" /></p>
</form>
