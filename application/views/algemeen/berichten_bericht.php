<div id="berichten">
	<h1>Bericht</h1>
	<div id="buttons">
		<p>
			<a href="<?php echo base_url($platform.'/berichten/beantwoorden/'.$bericht->bericht_ID) ?>" title="Bericht beantwoorden" class="belangrijk">Beantwoorden</a>
			<a href="<?php echo base_url($platform.'/berichten') ?>" title="Annuleren">Annuleren</a>
			<a href="<?php echo base_url($platform.'/berichten/verwijderen/'.$bericht->bericht_ID.'/bericht') ?>" title="Bericht verwijderen">Verwijderen</a>
		</p>
	</div>
	<div id="bericht">
		<div><div class="label">Van</div><div class="tekst"><?php echo $bericht->gebruiker_voornaam.' '.$bericht->gebruiker_tussenvoegsel.' '.$bericht->gebruiker_achternaam ?></div></div>
		<div><div class="label">Datum</div><div class="tekst"><?php echo toonDatum($bericht->bericht_datum) ?>, <?php echo toonTijd($bericht->bericht_datum) ?> uur</div></div>
		<div><div class="label">Onderwerp</div><div class="tekst"><?php echo $bericht->bericht_onderwerp ?></div></div>
		<div><div class="label">Tekst</div><div class="tekst"><?php echo $bericht->bericht_tekst ?></div></div>
	</div>
</div>