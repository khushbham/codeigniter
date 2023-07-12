<h1>Deelnemer afmelden</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de deelnemer <strong><?php echo $aanmelding->gebruiker_naam ?></strong> wilt afmelden voor de <?php echo $aanmelding->aanmelding_type ?> <strong><?php echo $aanmelding->workshop_titel ?></strong>?<br />
	Ook al de deelnemer zijn / haar lessen, opdrachten, beoordelingen, afspraken worden verwijderd.</p>
	<?php if($bestelling != null): ?><p>Ook is er een bestelling gekoppeld aan deze aanmelding. Ook deze wordt verwijderd.</p><?php endif; ?>
	<p><a href="<?php echo base_url('cms/deelnemers/afmelden/'.$aanmelding->aanmelding_ID.'/ja') ?>" title="Ja, afmelden">Ja</a> / <a href="<?php echo base_url('cms/deelnemers/'.$aanmelding->gebruiker_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>