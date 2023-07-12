<h1>Beheerder verwijderen</h1>
<?php if(in_array($item->gebruiker_ID, array(1, 454))): ?>
	<p>Deze beheerder kan niet worden verwijderd.</p>
<?php else: ?>
	<div id="verwijderen">
		<p>Weet u zeker dat u de beheerder <strong><?php echo $item->gebruiker_naam ?></strong> wilt verwijderen?</p>
		<p><a href="<?php echo base_url('cms/beheerders/verwijderen/'.$item->gebruiker_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/beheerders/wijzigen/'.$item->gebruiker_ID) ?>" title="Nee, annuleren">Nee</a></p>
	</div>
<?php endif; ?>
