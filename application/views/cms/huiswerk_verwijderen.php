<h1>Opdracht verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de opdracht van <strong><?php echo $item->gebruiker_naam ?></strong> voor de les <strong><?php echo $item->les_titel ?></strong> van de workshop <strong><?php echo $item->workshop_titel ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('cms/huiswerk/verwijderen/'.$item->resultaat_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>