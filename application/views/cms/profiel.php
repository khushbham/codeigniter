<h1>Profiel</h1>
<div id="profiel">
	<div id="profiel_gegevens">
		<p><?php echo $gebruiker_voornaam ?> <?php echo $gebruiker_tussenvoegsel ?> <?php echo $gebruiker_achternaam ?><?php if(!empty($gebruiker_bedrijfsnaam)) echo ', '.$gebruiker_bedrijfsnaam ?><br />
		<?php echo $gebruiker_adres ?><br />
		<?php echo $gebruiker_postcode ?> <?php echo $gebruiker_plaats ?></p>
		<p>T <?php if(empty($gebruiker_telefoonnummer)) { echo "-"; } else { echo $gebruiker_telefoonnummer; } ?><br />
			M <?php if(empty($gebruiker_mobiel)) { echo "-"; } else { echo $gebruiker_mobiel; } ?></p>
		<p>E-mailadres <?php echo $gebruiker_emailadres ?></p>
	</div>
	<div id="profiel_buttons">
		<p><a href="<?php echo base_url('cms/profiel/wijzigen') ?>" title="Profiel wijzigen" class="button">Profiel wijzigen</a></p>
		<p><a href="<?php echo base_url('cms/profiel/wijzigen/wachtwoord') ?>" title="Wachtwoord wijzigen" class="button">Wachtwoord wijzigen</a></p>
		<p><a href="<?php echo base_url('cms/profiel/wijzigen/instellingen') ?>" title="Instellingen wijzigen" class="button">Instellingen wijzigen</a></p>
	</div>
</div>