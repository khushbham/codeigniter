<h1>Les uitplannen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de les <strong><?php echo $item->les_titel ?></strong> van de workshop <strong><?php echo $item->workshop_titel ?></strong> wilt uitplannen voor de groep <strong><?php echo $item->groep_naam ?></strong>?<br />
	De workshop les wordt hiermee niet verwijderd. Alleen de groepsles wordt uitgepland.</p>
	<p><a href="<?php echo base_url('cms/lessen/uitplannen/'.$item->groep_les_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/lessen/groep/'.$item->les_ID.'/'.$item->groep_ID.'/'.$item->groep_les_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>