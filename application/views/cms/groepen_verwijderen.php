<h1>Groep verwijderen</h1>
<div id="verwijderen">
	<?php if(sizeof($aanmeldingen) > 0): ?>
		<p>De groep <strong><?php echo $item->groep_naam ?></strong> van de workshop <strong><?php echo $item->workshop_titel ?></strong> kan niet worden verwijderd.<br />
		Eén of meer deelnemers hebben zich aangemeld voor deze groep.</p>
	<?php else: ?>
		<p>Weet u zeker dat u de groep <strong><?php echo $item->groep_naam ?></strong> van de workshop <strong><?php echo $item->workshop_titel ?></strong> wilt verwijderen?</p>
		<p><a href="<?php echo base_url('cms/groepen/verwijderen/'.$item->groep_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/groepen/wijzigen/'.$item->groep_ID) ?>" title="Nee, annuleren">Nee</a></p>
	<?php endif; ?>
</div>