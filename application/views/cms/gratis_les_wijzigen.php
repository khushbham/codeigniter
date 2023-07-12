<h1>Les <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/lessen/gratis_les_toevoegen/'); else echo base_url('cms/lessen/gratis_les_wijzigen/'.$item_ID); ?>">
		<p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
		<p><label for="item_youtube_link">Youtube link *</label><input type="text" name="item_youtube_link" id="item_youtube_link" value="<?php echo $item_youtube_link ?>" /><span class="feedback"><?php echo $item_youtube_link_feedback ?></span></p>
		<p><label for="item_youtube_link">Type les</label>
		<p><label for="item_beschrijving">Tekst *</label><textarea name="item_tekst" id="item_tekst" class="opmaak"><?php echo $item_tekst ?></textarea><span class="feedback"><?php echo $item_tekst_feedback ?></span></p>
        <p><label for="item_datum_dag">Datum *</label><input type="text" name="item_datum_dag" id="item_datum_dag" class="datum_smal" value="<?php echo $item_datum_dag ?>" /><input type="text" name="item_datum_maand" id="item_datum_maand" class="datum_smal" value="<?php echo $item_datum_maand ?>" /><input type="text" name="item_datum_jaar" id="item_datum_jaar" class="datum_breed" value="<?php echo $item_datum_jaar ?>" /><span class="feedback"><?php echo $item_datum_feedback ?></span></p>
		<p class="submit"><input type="submit" value="Les <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/lessen/gratis_les_detail/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/lessen/gratis_les_verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>