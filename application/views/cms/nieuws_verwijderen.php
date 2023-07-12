<h1>Nieuws verwijderen</h1>
<div id="verwijderen">
	<p>Weet u zeker dat u het bericht <strong><?php echo $item->nieuws_titel ?></strong> wilt verwijderen?</p>
	<p><a href="<?php echo base_url('cms/nieuws/verwijderen/'.$item->nieuws_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/nieuws/wijzigen/'.$item->nieuws_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>