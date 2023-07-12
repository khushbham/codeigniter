<h1>Les verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de les <strong><?php echo $item->les_titel ?></strong> van de workshop <strong><?php echo $item->workshop_titel ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('cms/lessen/verwijderen/'.$item->les_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/lessen/wijzigen/'.$item->les_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>