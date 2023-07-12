<h1>Media verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u de <?php echo $item->media_type ?> <strong><?php echo $item->media_titel ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('cms/media/verwijderen/'.$item->media_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/media/wijzigen/'.$item->media_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>