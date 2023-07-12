<?php if(!$item_verzonden): ?>
	<div id="berichten">
		<form method="post" action="<?php echo current_url() ?>">
			<?php if($item_beantwoorden): ?>
				<h1>Bericht beantwoorden</h1>
				<div id="buttons">
					<p>
						<input type="submit" name="item_verzenden" id="item_verzenden" value="Verzenden" class="belangrijk" />
						<a href="<?php echo base_url($platform.'/berichten/'.$item_ID) ?>" title="Annuleren">Annuleren</a>
					</p>
				</div>
				<p><label>Aan</label><?php echo $bericht->gebruiker_naam ?></p>
			<?php else: ?>
				<h1>Nieuw bericht</h1>
				<div id="buttons">
					<p>
						<input type="submit" name="item_verzenden" id="item_verzenden" value="Verzenden" class="belangrijk" />
						<a href="<?php echo base_url($platform.'/berichten') ?>" title="Annuleren">Annuleren</a>
					</p>
				</div>
				<p>
					<label for="item_ontvanger">Aan</label>
					<select name="item_ontvanger" id="item_ontvanger" required>
						<option value="">Selecteer een ontvanger</option>


						<!-- Docent -->

						<optgroup label="Docent">
							<option value="1" <?php if($item_ontvanger == '1') echo 'selected="selected"'; ?>>Barnier Geerling</option>
						</optgroup>


						<!-- Groepen -->

						<?php if($this->session->userdata('beheerder_rechten') && sizeof($groepen) > 0): ?>
							<optgroup label="Groepen">
								<?php foreach($groepen as $groep): ?>
									<option value="<?php echo 'groep-'.$groep->groep_ID ?>" <?php if($item_ontvanger == 'groep-'.$groep->groep_ID) echo 'selected="selected"'; ?>><?php echo $groep->groep_naam ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endif; ?>


						<!-- Deelnemers -->

						<?php if(sizeof($medecursisten) > 0): ?>
							<optgroup label="Medecursisten">
								<?php foreach($medecursisten as $deelnemer): ?>
									<option value="<?php echo $deelnemer->gebruiker_ID ?>" <?php if($deelnemer->gebruiker_ID == $item_ontvanger) echo 'selected="selected"'; ?>><?php echo $deelnemer->gebruiker_voornaam.' '.$deelnemer->gebruiker_tussenvoegsel.''.$deelnemer->gebruiker_achternaam ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endif; ?>

					</select>
				</p>
			<?php endif; ?>
			<p><label for="item_onderwerp">Onderwerp</label><input type="text" name="item_onderwerp" id="item_onderwerp" value="<?php echo $item_onderwerp ?>" autocomplete="off" required/></p>
			<p><label for="item_tekst">Tekst</label><textarea name="item_tekst" id="item_tekst" class="opmaak_simpel"><?php echo $item_tekst ?></textarea></p>
		</form>
	</div>
<?php else: ?>
	<div id="item_verzonden">
		<h1>Bericht verzonden</h1>
		<?php if(isset($item_ontvanger_type) && $item_ontvanger_type == 'groep'): ?>
			<p>Je bericht <strong><?php echo $item_onderwerp ?></strong> is succesvol verzonden naar de groep <strong><?php echo $item_groep->groep_naam ?></strong>.</p>
		<?php else: ?>
			<p>Je bericht <strong><?php echo $item_onderwerp ?></strong> is succesvol verzonden naar <strong><?php echo $ontvanger->gebruiker_naam ?></strong>.</p>
		<?php endif; ?>
		<p><a href="<?php echo base_url($platform.'/berichten') ?>" title="Naar alle berichten">Naar alle berichten</a></p>
	</div>
<?php endif; ?>