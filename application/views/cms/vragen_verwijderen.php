<h1>Vragen verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de vraag <strong><?php echo $item->vraag_titel ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('cms/vragen/verwijderen/'.$item->vraag_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/vragen/wijzigen/'.$item->vraag_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>