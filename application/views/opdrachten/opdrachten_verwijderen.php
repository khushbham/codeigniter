<h1>Opdracht verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de opdracht <strong><?php echo $item->opdracht_titel ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('opdrachten/opdrachten/verwijderen/'.$item->opdracht_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('opdrachten/opdrachten/wijzigen/'.$item->opdracht_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>