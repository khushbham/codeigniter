<div id="verwijderen">
	<h1>Bericht verwijderen</h1>
	<?php if(is_array($bericht)): ?>
		<p>Weet je zeker dat je de volgende berichten wilt verwijderen?</p>
		<form method="post" action="<?php echo base_url('cms/berichten/verwijder_meerdere/ja') ?>" id="formulier">
			<?php foreach ($bericht as $ber): ?>
			<p>
				<input type="hidden" name="geselecteerde_berichten[]" value="<?php echo $ber->bericht_ID ?>">
				<strong><?php echo $ber->bericht_onderwerp ?></strong> van <strong><?php echo $ber->gebruiker_naam ?></strong>
			</p>
			<?php endforeach; ?>
			<p>
				<input type="submit" value="Ja" class="verwijderen">
				<a href="<?php echo base_url('cms/berichten') ?>" title="Annuleren" class="algemeen">Nee</a>
			</p>
		</form>
	<?php else: ?>
		<p>Weet je zeker dat je het bericht <strong><?php echo $bericht->bericht_onderwerp ?></strong> van <strong><?php echo $bericht->gebruiker_naam ?></strong> wilt verwijderen?</p>
		<p>
			<a href="<?php echo base_url('cms/berichten/verwijderen/'.$item_ID) . '/ja' ?>" title="Bericht verwijderen">Ja</a> /
			<?php if($pagina == 'bericht'): ?>
				<a href="<?php echo base_url('cms/berichten/'.$item_ID) ?>" title="Annuleren">Nee</a>
			<?php else: ?>
				<a href="<?php echo base_url('cms/berichten') ?>" title="Annuleren">Nee</a>
			<?php endif; ?>
		</p>
	<?php endif; ?>
</div>