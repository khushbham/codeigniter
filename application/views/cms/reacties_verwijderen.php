<h1>Reactie verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de reactie <strong><?php echo $item->reactie_titel ?></strong> van deelnemer <strong><?php echo $item->reactie_deelnemer ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('cms/reacties/verwijderen/'.$item->reactie_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/reacties/wijzigen/'.$item->reactie_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>