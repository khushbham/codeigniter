<head>
	<script src="https://www.localhost/assets/js/recorderjs/dist/recorder.js"></script>
</head>

<h1>Opdrachten beoordelen</h1>
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
					<?php if ($resultaat->resultaat_voldoende == 'onbekend') : ?>
						<div class="audio" style="float: left;">
							<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
							<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="auto"></audio>
						</div>
						<span class="delete_huiswerk"><a href="<?php echo base_url('cms/huiswerk/verwijder_item/' . $item->huiswerk_ID . '/' . $resultaat->resultaat_ID) ?>">X</a></span>
					<?php elseif ($resultaat->resultaat_voldoende != 'onbekend') : ?>
						<div class="audio">
							<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
							<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="auto"></audio>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</td>
	</tr>
	<?php if ($resultaat->resultaat_voldoende != 'onbekend') : ?>
		<tr>
			<th>Resultaat</th>
			<td>
			<?php if (!empty($resultaat->resultaat_feedback_src)) : ?>
				<div id="audio">
					<div class="audio <?php if ($resultaat->resultaat_voldoende == 'ja') echo 'voldoende';
										else echo 'onvoldoende'; ?>">
						<div class="audio_titel"><?php if ($resultaat->resultaat_voldoende == 'ja') echo 'Voldoende';
													else echo 'Onvoldoende'; ?></div>
						<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_feedback_src) ?>" preload="auto"></audio>
					</div>
				</div>
			<?php else: ?>
				<?php if ($resultaat->resultaat_voldoende == 'ja') echo 'Voldoende';
										else echo 'Onvoldoende'; ?>
			<?php endif; ?>
			</td>
		</tr>
		<?php if (!empty($huiswerk)) { ?>
			<?php foreach ($huiswerk as $item) { ?>
				<tr>
					<th><strong><?php echo 'Opdracht: ' . $item->huiswerk_titel ?></strong></th>
					<td></td>
				</tr>
				<?php foreach ($item->resultaten as $criteria_resultaat) { ?>
					<tr>
						<th><?php echo $criteria_resultaat->beoordelingscriteria_naam ?></th>
						<td><?php echo $criteria_resultaat->beoordelingscriteria_resultaat ?></td>
					</tr>
				<?php } ?>
			<?php } ?>
		<?php } ?>
		<tr>
			<th>Feedback</th>
			<td><?php if (!empty($resultaat->resultaat_feedback_tekst)) echo $resultaat->resultaat_feedback_tekst;
				else echo '...'; ?></td>
		</tr>
		<?php if (!empty($resultaat->resultaat_opnieuw_feedback_tekst)){ ?>
			<tr>
				<th>Opnieuw feedback</th>
				<td><?php if (!empty($resultaat->resultaat_opnieuw_feedback_tekst)) echo $resultaat->resultaat_opnieuw_feedback_tekst;
					else echo '...'; ?></td>
			</tr>
		<?php } ?>
		<?php if ($resultaat->resultaat_voldoende == 'nee') : ?>
			<tr>
				<th>Opnieuw</th>
				<td><?php echo $resultaat->resultaat_opnieuw ?></td>
			</tr>
		<?php endif; ?>
		<tr>
			<th>Beoordeeld</th>
			<td><?php echo date('d-m-Y', strtotime($resultaat->resultaat_beoordeeld_datum)) ?> om <?php echo date('H:i:s', strtotime($resultaat->resultaat_beoordeeld_datum)) ?> uur</td>
		</tr>
		<?php if ($resultaat->resultaat_opnieuw_ingestuurd_datum != '' && $resultaat->resultaat_opnieuw_ingestuurd_datum != '0000-00-00 00:00:00') : ?>
			<tr>
				<th>Opnieuw ingestuurd</th>
				<td>
				<?php foreach ($opnieuw as $item) : ?>
					<div id="audio">
						<div class="audio">
							<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
							<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="auto"></audio>
						</div>
					</div>
				<?php endforeach; ?>

				</td>
			</tr>
			<?php if ($resultaat->resultaat_opnieuw_voldoende != 'onbekend') : ?>
				<tr>
					<th>Opnieuw resultaat</th>
					<td>
						<div id="audio">
							<div class="audio <?php if ($resultaat->resultaat_opnieuw_voldoende == 'ja') echo 'voldoende';
												else echo 'onvoldoende'; ?>">
								<div class="audio_titel"><?php if ($resultaat->resultaat_opnieuw_voldoende == 'ja') echo 'Voldoende';
															else echo 'Onvoldoende'; ?></div>
								<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_opnieuw_feedback_src) ?>" preload="auto"></audio>
							</div>
						</div>
					</td>
				</tr>
				<?php if (!empty($opnieuw)) { ?>
					<?php foreach ($opnieuw as $item) { ?>
						<tr>
							<th><strong><?php echo 'Opdracht opnieuw: ' . $item->huiswerk_titel ?></strong></th>
							<td></td>
						</tr>
						<?php foreach ($item->resultaten as $criteria_resultaat) { ?>
							<tr>
								<th><?php echo $criteria_resultaat->beoordelingscriteria_naam ?></th>
								<td><?php echo $criteria_resultaat->beoordelingscriteria_resultaat ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				<tr>
					<th>Opnieuw feedback</th>
					<td><?php if (!empty($resultaat->resultaat_opnieuw_feedback_tekst)) echo $resultaat->resultaat_opnieuw_feedback_tekst;
						else echo '...'; ?></td>
				</tr>
				<tr>
					<th>Opnieuw beoordeeld</th>
					<td><?php echo date('d-m-Y', strtotime($resultaat->resultaat_opnieuw_beoordeeld_datum)) ?> om <?php echo date('H:i:s', strtotime($resultaat->resultaat_opnieuw_beoordeeld_datum)) ?> uur</td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<tr>
		<th>Notities</th>
		<td><?php if (!empty($resultaat->resultaat_notities)) echo $resultaat->resultaat_notities;
			else echo '...'; ?></a></td>
	</tr>
	<?php if (isset($docent)) : ?>
		<tr>
			<th>Docent</th>
			<td><?php echo $docent->gebruiker_naam; ?></a></td>
		</tr>
	<?php endif; ?>
</table>
<?php if ($resultaat->resultaat_beoordelen == 'ja') : ?>
	<div class="formulier">
		<form method="post" enctype="multipart/form-data" action="<?php echo base_url('cms/huiswerk/beoordelen/' . $resultaat->resultaat_ID); ?>">
			<h2><strong>Uitslag</strong></h2>
			<?php if ($resultaat->resultaat_voldoende == 'nee') { ?>
				<?php if (!empty($beoordelingscriteria)) { ?>
					<?php if (!empty($opnieuw)) { ?>
						<?php foreach ($opnieuw as $item) { ?>
							<h3><strong><?php echo 'Opdracht: ' . $item->huiswerk_titel ?></strong></h3>
							<?php if ($resultaat->workshop_niveau == 5) { ?>
								<h4>Inhoud</h4>
							<?php } ?>
							<?php foreach ($beoordelingscriteria as $criteria) { ?>
								<p>
									<label for="item_criteria"><?php echo $criteria->beoordelingscriteria_naam ?></label>
									<input type="radio" name="item_criteria_voldoende[<?php echo $criteria->beoordelingscriteria_ID ?>][<?php echo $item->huiswerk_ID ?>]" id="item_criteria_voldoende" value="voldoende" <?php if ($item_voldoende == 'voldoende' || ($item_voldoende != 'onvoldoende' && $item_voldoende != 'onvoldoende')) echo 'checked'; ?> /> Voldoende
									<input type="radio" name="item_criteria_voldoende[<?php echo $criteria->beoordelingscriteria_ID ?>][<?php echo $item->huiswerk_ID ?>]" id="item_criteria_onvoldoende" value="onvoldoende" <?php if ($item_voldoende == 'onvoldoende') echo 'checked'; ?> /> Onvoldoende
								</p>
							<?php } ?>
							<?php if ($resultaat->workshop_niveau == 5) { ?>
								<h4>Techniek</h4>
								<?php foreach ($beoordelingscriteriaVWS as $criteriaVWS) { ?>
									<p>
										<label for="item_criteria"><?php echo $criteriaVWS->beoordelingscriteria_naam ?></label>
										<input type="radio" name="item_criteria_voldoende[<?php echo $criteriaVWS->beoordelingscriteria_ID ?>][<?php echo $item->huiswerk_ID ?>]" id="item_criteria_voldoende" value="voldoende" <?php if ($item_voldoende == 'voldoende' || ($item_voldoende != 'onvoldoende' && $item_voldoende != 'onvoldoende')) echo 'checked'; ?> /> Voldoende
										<input type="radio" name="item_criteria_voldoende[<?php echo $criteriaVWS->beoordelingscriteria_ID ?>][<?php echo $item->huiswerk_ID ?>]" id="item_criteria_onvoldoende" value="onvoldoende" <?php if ($item_voldoende == 'onvoldoende') echo 'checked'; ?> /> Onvoldoende
									</p>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } else { ?>
				<?php if (!empty($beoordelingscriteria)) { ?>
					<?php if (!empty($huiswerk)) { ?>
						<?php foreach ($huiswerk as $item) { ?>
							<h3><strong><?php echo 'Opdracht: ' . $item->huiswerk_titel ?></strong></h3>
							<?php if ($resultaat->workshop_niveau == 5) { ?>
								<h4>Inhoud</h4>
							<?php } ?>
							<?php foreach ($beoordelingscriteria as $criteria) { ?>
								<p>
									<label for="item_criteria"><?php echo $criteria->beoordelingscriteria_naam ?></label>
									<input type="radio" name="item_criteria_voldoende[<?php echo $criteria->beoordelingscriteria_ID ?>][<?php echo $item->huiswerk_ID ?>]" id="item_criteria_voldoende" value="voldoende" <?php if ($item_voldoende == 'voldoende' || ($item_voldoende != 'onvoldoende' && $item_voldoende != 'onvoldoende')) echo 'checked'; ?> /> Voldoende
									<input type="radio" name="item_criteria_voldoende[<?php echo $criteria->beoordelingscriteria_ID ?>][<?php echo $item->huiswerk_ID ?>]" id="item_criteria_onvoldoende" value="onvoldoende" <?php if ($item_voldoende == 'onvoldoende') echo 'checked'; ?> /> Onvoldoende
								</p>
							<?php } ?>
							<?php if ($resultaat->workshop_niveau == 5) { ?>
								<h4>Techniek</h4>
								<?php foreach ($beoordelingscriteriaVWS as $criteriaVWS) { ?>
									<p>
										<label for="item_criteria"><?php echo $criteriaVWS->beoordelingscriteria_naam ?></label>
										<input type="radio" name="item_criteria_voldoende[<?php echo $criteriaVWS->beoordelingscriteria_ID ?>][<?php echo $item->huiswerk_ID ?>]" id="item_criteria_voldoende" value="voldoende" <?php if ($item_voldoende == 'voldoende' || ($item_voldoende != 'onvoldoende' && $item_voldoende != 'onvoldoende')) echo 'checked'; ?> /> Voldoende
										<input type="radio" name="item_criteria_voldoende[<?php echo $criteriaVWS->beoordelingscriteria_ID ?>][<?php echo $item->huiswerk_ID ?>]" id="item_criteria_onvoldoende" value="onvoldoende" <?php if ($item_voldoende == 'onvoldoende') echo 'checked'; ?> /> Onvoldoende
									</p>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			<p>
				<label for="item_voldoende">Voldoende *</label>
				<input type="radio" name="item_voldoende" id="item_voldoende_ja" value="ja" <?php if ($item_voldoende == 'ja' || ($item_voldoende != 'ja' && $item_voldoende != 'nee')) echo 'checked'; ?> /> Ja
				<input type="radio" name="item_voldoende" id="item_voldoende_nee" value="nee" <?php if ($item_voldoende == 'nee') echo 'checked'; ?> /> Nee
				<span class="feedback"><?php echo $item_voldoende_feedback ?></span>
			</p>
			<p><label for="item_feedback_tekst">Feedback</label><textarea name="item_feedback_tekst" id="item_feedback_tekst" class="opmaak_simpel"><?php echo $item_feedback_tekst ?></textarea><span class="feedback"><?php echo $item_feedback_tekst_feedback ?></span></p>
			<p><label for="item_notities">Notities</label><textarea name="item_notities" id="item_notities_beoordelen" class="opmaak_simpel"><?php echo $item_notities ?></textarea></p>
			<?php if ($resultaat->resultaat_opnieuw_ingestuurd_datum == '' || $resultaat->resultaat_opnieuw_ingestuurd_datum == '0000-00-00 00:00:00') : ?>
				<p><label for="item_opnieuw">Opdrachten opnieuw *</label><input type="text" name="item_opnieuw" id="item_opnieuw" value="<?php echo $item_opnieuw ?>" /><span class="feedback"><?php echo $item_opnieuw_feedback ?></span></p>
				<input type="hidden" name="opnieuw_insturen" value="nee" />
			<?php else : ?>
				<input type="hidden" name="item_opnieuw" value="0" />
				<input type="hidden" name="opnieuw_insturen" value="ja" />
			<?php endif; ?>

			<p><label for="Audio_option">Audio type</label><input type="radio" name="audio_option" id="audio_mp3" value="audio_src" onClick="audio_type()" checked /> Mp3 uploaden <input type="radio" name="audio_option" id="audio_opnemen" value="audio_opnemen" onClick="audio_type()" />Audio direct opnemen</span></p>
			<div id="audio_record">
				<?php
				function getBrowser()
				{
					$u_agent = $_SERVER['HTTP_USER_AGENT'];
					$bname = 'Unknown';
					$platform = 'Unknown';
					$version = "";
					$ub = "";

					//First get the platform?
					if (preg_match('/linux/i', $u_agent)) {
						$platform = 'linux';
					} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
						$platform = 'mac';
					} elseif (preg_match('/windows|win32/i', $u_agent)) {
						$platform = 'windows';
					}

					// Next get the name of the useragent yes seperately and for good reason
					if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
						$bname = 'Internet Explorer';
						$ub = "MSIE";
					} elseif (preg_match('/Firefox/i', $u_agent)) {
						$bname = 'Mozilla Firefox';
						$ub = "Firefox";
					} elseif (preg_match('/Chrome/i', $u_agent)) {
						$bname = 'Google Chrome';
						$ub = "Chrome";
					} elseif (preg_match('/Safari/i', $u_agent)) {
						$bname = 'Apple Safari';
						$ub = "Safari";
					} elseif (preg_match('/Opera/i', $u_agent)) {
						$bname = 'Opera';
						$ub = "Opera";
					} elseif (preg_match('/Netscape/i', $u_agent)) {
						$bname = 'Netscape';
						$ub = "Netscape";
					}

					if (!empty($ub)) {
						$known = array('Version', $ub, 'other');
						$pattern = '#(?<browser>' . join('|', $known) .
							')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
						if (!preg_match_all($pattern, $u_agent, $matches)) {
							// we have no matching number just continue
						}


						// see how many we have
						$i = count($matches['browser']);
						if ($i != 1) {
							if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
								$version = $matches['version'][0];
							} else {
								$version = $matches['version'][1];
							}
						} else {
							$version = $matches['version'][0];
						}
					}

					if ($version == null || $version == "") {
						$version = "?";
					}

					return array(
						'userAgent' => $u_agent,
						'name'      => $bname,
						'short'		=> $ub,
						'version'   => $version,
						'platform'  => $platform,
						'pattern'    => $pattern
					);
				}
				$ua = getBrowser();

				if (!empty($ua['short'])) {
					if ($ua['short'] != 'Safari') { ?>
						<label>Feedback opnemen</label>
						<div id="recordingslist">
							<p>Klik op "opnemen" om direct te beginnen met opnemen. </p>
							<p>Klik op "Stop" om de opname op te slaan en te beluisteren.</p>
						</div>
						<div id="opname_buttons">
							<button type="button" id="button_opnemen" onclick="startRecording(this);">Opnemen</button>
							<button type="button" id="button_stop" onclick="stopRecording(this);" disabled>Stop</button>
							<input type="hidden" name="upload" id="ID" value="<?php echo $resultaat->resultaat_ID ?>" />
							<span class="feedback" style="color:red"><?php echo $item_feedback_src_feedback ?></span>
						</div>
					<?php } else { ?>
						<p style="color: red">Geluid opnemen wordt niet ondersteund door uw browser.</p>
					<?php } ?>
			</div>
		<?php } else { ?>
			<p style="color: red">Geluid opnemen wordt niet ondersteund door uw browser.</p>
		<?php } ?>
		<div id="audio_src">
			<p><label for="item_feedback_src">Feedback (mp3) *</label><input type="file" name="item_feedback_src" id="item_feedback_src" />
		</div>
		<div style="clear: both;">
			<p class="submit"><input type="submit" value="Beoordelen" /> <a href="<?php echo base_url('cms/huiswerk/verwijderen/' . $resultaat->resultaat_ID) ?>" title="Verwijderen">Verwijderen</a></p>
		</div>

		</form>
	</div>
<?php endif; ?>