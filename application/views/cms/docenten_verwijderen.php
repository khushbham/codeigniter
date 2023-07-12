<h1>Docent verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de docent <strong><?php echo $item->docent_naam ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('cms/docenten/verwijderen/'.$item->docent_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/docenten/wijzigen/'.$item->docent_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>