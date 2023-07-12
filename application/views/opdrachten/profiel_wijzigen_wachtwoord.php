<div id="profiel">
	<h1>Wachtwoord wijzigen</h1>
	<form method="post" action="<?php echo current_url() ?>">
		<p><label for="wachtwoord_oud">Oud wachtwoord *</label><input type="password" name="wachtwoord_oud" value="<?php echo $wachtwoord_oud ?>" id="wachtwoord_oud" /></p>
		<p><label for="wachtwoord_nieuw">Nieuw wachtwoord *</label><input type="password" name="wachtwoord_nieuw" id="wachtwoord_nieuw" value="<?php echo $wachtwoord_nieuw ?>" /></p>
		<p><label for="wachtwoord_herhaal">Herhaal nieuw *</label><input type="password" name="wachtwoord_herhaal" id="wachtwoord_herhaal" value="<?php echo $wachtwoord_herhaal ?>" /></p>
		<p class="verplicht">* verplichte velden</p>
		<p class="feedback"><?php echo $feedback ?></p>
		<p><input class="button-orange" type="submit" name="wijzigen" id="wijzigen" value="Wijzigen" /></p>
	</form>
</div>