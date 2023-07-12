<h1>Gratis les verwijderen</h1>
<div id="verwijderen">
		<p>Weet u zeker dat u de les <strong><?php echo $item[0]->les_titel ?></strong> wilt verwijderen?</p>
		<p><a href="<?php echo base_url('cms/lessen/gratis_les_verwijderen/'.$item[0]->les_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/lessen/gratis_les_wijzigen/'.$item[0]->les_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>