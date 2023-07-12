<h1>Workshop verwijderen</h1>
<div id="verwijderen">
	<?php if(sizeof($aanmeldingen) > 0): ?>
		<p>De workshop <strong><?php echo $item->workshop_titel ?></strong> kan niet worden verwijderd.<br />
		Eén of meerdere deelnemers hebben zich aangemeld voor de workshop.</p>
	<?php else: ?>
		<p>Weet u zeker dat u de workshop <strong><?php echo $item->workshop_titel ?></strong> wilt verwijderen?<br />De gekoppelde lessen en groepen (indien aanwezig) worden ook verwijderd.<br />Er zijn nog geen aanmeldingen van deelnemers aanwezig.</p>
		<p><a href="<?php echo base_url('cms/workshops/verwijderen/'.$item->workshop_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/workshops/wijzigen/'.$item->workshop_ID) ?>" title="Nee, annuleren">Nee</a></p>
	<?php endif; ?>
</div>