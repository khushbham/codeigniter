<div id="berichten">
	<h1>Bericht verwijderen</h1>
	<p>Weet je zeker dat je het bericht <strong><?php echo $bericht->bericht_onderwerp ?></strong> van <strong><?php echo $bericht->gebruiker_naam ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('cursistenmodule/berichten/verwijderen/'.$item_ID.'/ja') ?>/ja" title="Bericht verwijderen">Ja</a> /
	<?php if($pagina == 'bericht'): ?>
		<a href="<?php echo base_url('cursistenmodule/berichten/'.$item_ID) ?>" title="Annuleren">Nee</a>
	<?php else: ?>
		<a href="<?php echo base_url('cursistenmodule/berichten') ?>" title="Annuleren">Nee</a>
	<?php endif; ?>
	</p>
</div>