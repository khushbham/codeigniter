<h1>Live les <?php echo $actie ?></h1>
<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Workshop</th>
		<td><?php echo $workshop->workshop_titel ?></td>
	</tr>
	<tr>
		<th>Groep</th>
		<td><?php echo $groep->groep_naam ?></td>
	</tr>
</table>

<div id="toevoegen_wijzigen" class="formulier">
<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/lessen/live_toevoegen/'.$item_ID. '/'. $les_ID); else echo base_url('cms/lessen/live_wijzigen/'.$item_ID. '/'. $les_ID); ?>">
    <p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
    <p><label for="item_beschrijving">Beschrijving *</label><textarea name="item_beschrijving" id="item_beschrijving" class="opmaak"><?php echo $item_beschrijving ?></textarea><span class="feedback"><?php echo $item_beschrijving_feedback ?></span></p>
	<p><label for="item_docent">Type video</label>
			<select name="item_video_type" id="item_video_type">
			<option value="vimeo_standaard" <?php if($item_video_type == "vimeo_standaard") { echo 'selected'; } ?>>Vimeo standaard</option>
			<option value="vimeo" <?php if($item_video_type == "vimeo") { echo 'selected'; } ?>>Vimeo recurring</option>
			<option value="zoom" <?php if($item_video_type == "zoom") { echo 'selected'; } ?>>Zoom</option>
			</select></p>
    <p><label for="item_video_url">Video url of meeting ID *</label><input type="text" name="item_video_url" id="item_video_url" value="<?php echo $item_video_url ?>" /><span class="feedback"><?php echo $item_video_url_feedback ?></span></p>
	<p><label for="item_docent">Docent</label>
			<select name="item_docent" id="item_docent">
				<option value="">Selecteer docent</option>
				<option value="">---</option>
				<?php foreach($docenten as $docent) { ?>
				<option value="<?php echo $docent->docent_ID ?>" <?php if($docent->docent_ID == $docent_ID) { echo 'selected'; } ?>><?php echo $docent->docent_naam ?></option>
				<?php echo ($docent->docent_ID == $docent_ID); ?>
				<?php } ?>
			</select></p>
		<p><label for="technicus">Technicus</label><input type="text" name="technicus" id="technicus" value="<?php echo $technicus ?>" /></p>
        <p><label for="item_datum_dag">Datum *</label><input type="text" name="item_datum_dag" id="item_datum_dag" class="datum_smal" value="<?php echo $item_datum_dag ?>" /><input type="text" name="item_datum_maand" id="item_datum_maand" class="datum_smal" value="<?php echo $item_datum_maand ?>" /><input type="text" name="item_datum_jaar" id="item_datum_jaar" class="datum_breed" value="<?php echo $item_datum_jaar ?>" /><span class="feedback"><?php echo $item_datum_feedback ?></span></p>
        <p><label for="item_tijd_uren">Begintijd *</label><input type="text" name="item_tijd_uren" id="item_tijd_uren" class="datum_smal" value="<?php echo $item_tijd_uren ?>" /><input type="text" name="item_tijd_minuten" id="item_tijd_minuten" class="datum_smal" value="<?php echo $item_tijd_minuten ?>" /><span class="feedback"><?php echo $item_tijd_feedback ?></span></p>
        <p><label for="item_eindtijd_uren">Eindtijd *</label><input type="text" name="item_eindtijd_uren" id="item_eindtijd_uren" class="datum_smal" value="<?php echo $item_eindtijd_uren ?>" /><input type="text" name="item_eindtijd_minuten" id="item_eindtijd_minuten" class="datum_smal" value="<?php echo $item_eindtijd_minuten ?>" /><span class="feedback"><?php echo $item_eindtijd_feedback ?></span></p>
		<p class="submit"><input type="submit" value="Les <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/lessen/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/lessen/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>