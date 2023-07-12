<h1>Deelnemer verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de deelnemer <strong><?php echo $item->gebruiker_naam ?></strong> wilt verwijderen?<br />
	Ook al de deelnemer zijn / haar aanmeldingen, bestellingen, lessen, opdrachten, beoordelingen en berichten worden verwijderd.</p>
	<p><a href="<?php echo base_url('cms/deelnemers/verwijderen/'.$item->gebruiker_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/deelnemers/wijzigen/'.$item->gebruiker_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>