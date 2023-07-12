<div id="profiel">
	<h1>Profiel wijzigen</h1>
	<form method="post" action="<?php echo current_url() ?>">
		<p><label for="gebruiker_bedrijfsnaam">Bedrijfsnaam</label><input type="text" name="gebruiker_bedrijfsnaam" id="gebruiker_bedrijfsnaam" value="<?php echo $gebruiker_bedrijfsnaam ?>" /><span class="feedback"><?php echo $gebruiker_bedrijfsnaam_feedback ?></span></p>
		<p><label for="gebruiker_voornaam">Voornaam *</label><input type="text" name="gebruiker_voornaam" id="gebruiker_voornaam" value="<?php echo $gebruiker_voornaam ?>" /><span class="feedback"><?php echo $gebruiker_voornaam_feedback ?></span></p>
		<p><label for="gebruiker_tussenvoegsel">Tussenvoegsel</label><input type="text" name="gebruiker_tussenvoegsel" id="gebruiker_tussenvoegsel" value="<?php echo $gebruiker_tussenvoegsel ?>" /><span class="feedback"><?php echo $gebruiker_tussenvoegsel_feedback ?></span></p>
		<p><label for="gebruiker_achternaam">Achternaam *</label><input type="text" name="gebruiker_achternaam" id="gebruiker_achternaam" value="<?php echo $gebruiker_achternaam ?>" /><span class="feedback"><?php echo $gebruiker_achternaam_feedback ?></span></p>
		<p>
			<label for="gebruiker_geslacht">Geslacht *</label>
			<span class="radio_buttons">
				<input type="radio" name="gebruiker_geslacht" id="gebruiker_geslacht" value="man" <?php if($gebruiker_geslacht == 'man') echo 'checked'; ?> /> Man <input type="radio" name="gebruiker_geslacht" value="vrouw" <?php if($gebruiker_geslacht == 'vrouw') echo 'checked'; ?> /> Vrouw
			</span>
			<span class="feedback"><?php echo $gebruiker_geslacht_feedback ?></span>
		</p>
		<p><label for="gebruiker_geboortedatum_dag">Geboortedatum *</label><input type="text" name="gebruiker_geboortedatum_dag" id="gebruiker_geboortedatum_dag" class="datum_smal" value="<?php echo $gebruiker_geboortedatum_dag ?>" maxlength="2" /><input type="text" name="gebruiker_geboortedatum_maand" id="gebruiker_geboortedatum_maand" class="datum_smal" value="<?php echo $gebruiker_geboortedatum_maand ?>" maxlength="2" /><input type="text" name="gebruiker_geboortedatum_jaar" id="gebruiker_geboortedatum_jaar" class="datum_breed" value="<?php echo $gebruiker_geboortedatum_jaar ?>" maxlength="4" /><span class="feedback"><?php echo $gebruiker_geboortedatum_feedback ?></span></p>
		<p><label for="gebruiker_adres">Adres *</label><input type="text" name="gebruiker_adres" id="gebruiker_adres" value="<?php echo $gebruiker_adres ?>" /><span class="feedback"><?php echo $gebruiker_adres_feedback ?></span></p>
		<p><label for="gebruiker_postcode">Postcode *</label><input type="text" name="gebruiker_postcode" id="gebruiker_postcode" value="<?php echo $gebruiker_postcode ?>" maxlength="7" /><span class="feedback"><?php echo $gebruiker_postcode_feedback ?></span></p>
		<p><label for="gebruiker_plaats">Plaats *</label><input type="text" name="gebruiker_plaats" id="gebruiker_plaats" value="<?php echo $gebruiker_plaats ?>" /><span class="feedback"><?php echo $gebruiker_plaats_feedback ?></span></p>
		<p><label for="gebruiker_telefoonnummer">Telefoonnummer *</label><input type="text" name="gebruiker_telefoonnummer" id="gebruiker_telefoonnummer" value="<?php echo $gebruiker_telefoonnummer ?>" /><span class="feedback"><?php echo $gebruiker_telefoonnummer_feedback ?></span></p>
		<p><label for="gebruiker_mobiel">Mobiel</label><input type="text" name="gebruiker_mobiel" id="gebruiker_mobiel" value="<?php echo $gebruiker_mobiel ?>" /><span class="feedback"><?php echo $gebruiker_mobiel_feedback ?></span></p>
		<p><label for="gebruiker_emailadres">E-mailadres *</label><input type="text" name="gebruiker_emailadres" id="gebruiker_emailadres" value="<?php echo $gebruiker_emailadres ?>" /><span class="feedback"><?php echo $gebruiker_emailadres_feedback ?></span></p>
		<p class="verplicht">* verplichte velden</p>
		<p class="feedback"><?php echo $feedback ?></p>
		<p><input type="submit" name="wijzigen" id="wijzigen" value="Wijzigen" /></p>
	</form>
</div>