<div id="profiel_instellingen">
	<h1>Instellingen wijzigen</h1>
	<form method="post" action="<?php echo current_url() ?>">
		<div id="instellingen">
			<p><label for="instelling_anoniem_ja">Anoniem (alleen berichten ontvangen van docenten)</label><span class="radios"><input type="radio" name="instelling_anoniem" id="instelling_anoniem_ja" value="ja" <?php if($instelling_anoniem == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="instelling_anoniem" id="instelling_anoniem_nee" value="nee" <?php if($instelling_anoniem == 'nee') echo 'checked'; ?> /> Nee</span></p>
			<p><label for="instelling_email_updates_ja">E-mail updates (stuur mij een e-mail wanneer ik een bericht ontvang)</label><span class="radios"><input type="radio" name="instelling_email_updates" id="instelling_email_updates_ja" value="ja" <?php if($instelling_email_updates == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="instelling_email_updates" id="instelling_email_updates_nee" value="nee" <?php if($instelling_email_updates == 'nee') echo 'checked'; ?> /> Nee</span></p>
		</div>
		<p class="feedback"><?php echo $feedback ?></p>
		<p><input type="submit" name="wijzigen" id="wijzigen" value="Wijzigen" /></p>
	</form>
</div>