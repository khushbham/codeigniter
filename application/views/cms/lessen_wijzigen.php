<h1>Les <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/lessen/toevoegen/'); else echo base_url('cms/lessen/wijzigen/'.$item_ID); ?>">
		<p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
		<p><label for="item_type">Type les</label>
		<select name="item_type" id="item_type">
			<option value="">Selecteer een type</option>
			<?php if(!empty($item_types)) {
				foreach($item_types as $item) { ?>
					<option value="<?php echo $item->les_type_ID ?>" <?php if($item->les_type_ID == $item_les_type) echo 'selected';?>><?php echo $item->les_type_soort ?></option>
				<?php } ?>
			<?php } ?>
		</select></p>
		<p><label for="item_beschrijving">Beschrijving *</label><textarea name="item_beschrijving" id="item_beschrijving" class="opmaak"><?php echo $item_beschrijving ?></textarea><span class="feedback"><?php echo $item_beschrijving_feedback ?></span></p>
		<p><label for="item_docent">Type video</label>
			<select name="item_video_type" id="item_video_type">
				<option value="vimeo_standaard" <?php if($item_video_type == "vimeo_standaard") { echo 'selected'; } ?>>Vimeo standaard</option>
				<option value="vimeo" <?php if($item_video_type == "vimeo") { echo 'selected'; } ?>>Vimeo recurring</option>
				<option value="zoom" <?php if($item_video_type == "zoom") { echo 'selected'; } ?>>Zoom</option>
			</select></p>
		<p><label for="item_video_url">Video url</label><input type="text" name="item_video_url" id="item_video_url" value="<?php echo $item_video_url ?>" /><span class="feedback"><?php echo $item_video_url_feedback ?></span></p>
		<p><label for="item_typeform_url">Typeform url</label><input type="text" name="item_typeform_url" id="item_typeform_url" value="<?php echo $item_typeform_url ?>" /><span class="feedback"><?php echo $item_typeform_url ?></span></p>
		<?php if($actie == 'wijzigen') { ?>
		<p><label for="item_placeholder">Placeholder afbeelding</label><?php if(empty($item_placeholder)) { ?> Nee | <a href="<?php echo base_url('cms/lessen/placeholder_toevoegen/'.$item_ID) ?>" title="Media overschrijven">Media toevoegen</a><?php } else { ?> Ja | <a href="<?php echo base_url('cms/lessen/placeholder_toevoegen/'.$item_ID) ?>" title="overschreven media wijzigen.">Media wijzigen</a><?php } ?></p>
		<?php } ?>
		<div id="media">
			<div id="media_label">
				<p><label for="item_media">Media</label></p>
			</div>
			<div id="media_container">
				<div id="media_lijst">
					<table cellpadding="0" cellspacing="0" class="js-sorteren-alleen-media-bijwerken">
						<tbody>
							<?php $item_media = ''; ?>
							<?php if($media > 0): foreach($media as $item): ?>
                                <?php if($item->media_ingang == '0000-00-00') { ?>
                                    <tr data-media-id="<?php echo $item->media_ID ?>" <?php if(!empty($media_oud)) if($item->media_ingang == "0000-00-00") echo " style='background-color: rgb(192, 192, 194);'" ?>>
                                        <?php
                                        $item_media_link = '#';
                                        if($item->media_type == 'pdf') { $media_src = base_url('images/pdf.png'); $item_media_link = base_url('/media/pdf/'.$item->media_src); }
                                        elseif($item->media_type == 'afbeelding') { $media_src = base_url('media/afbeeldingen/thumbnail/'.$item->media_src); $item_media_link = base_url('/media/afbeeldingen/origineel/'.$item->media_src); }
                                        elseif($item->media_type == 'video') { $media_src = '//view.vzaar.com/'.$item->media_src.'/thumb'; $item_media_link = '//view.vzaar.com/'.$item->media_src; }
                                        elseif($item->media_type == 'mp3') { $media_src = base_url('images/mp3.png'); $item_media_link = base_url('/media/audio/'.$item->media_src); }
                                        ?>
                                        <td class="media_image"><a href="<?php echo $item_media_link ?>" target="<?php if($item_media_link != '#') echo '_blank'; ?>"><img src="<?php echo $media_src ?>" title="<?php echo $item->media_titel ?>" /></a></td>
                                        <td class="media_titel"><?php echo $item->media_titel ?></td>
                                        <td class="media_ontkoppelen" onclick="ontkoppelen(this); "><span style="color:red; cursor:pointer;">X</span></td>
                                    </tr>
                                <?php } ?>
							<?php $item_media .= $item->media_ID.','; endforeach; endif; ?>
						</tbody>
					</table>
				</div>
				<div id="media_acties">
					<p><a href="#koppelen" title="Media koppelen aan les" class="koppelen">Media koppelen</a> | <a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen aan mediabibliotheek (opent een nieuw tabblad / venster)" target="_blank">Media toevoegen</a> | <a href="#ontkoppelen" title="Alle media ontkoppelen van de les" class="ontkoppelen">Alle media ontkoppelen</a></p>
					<input type="hidden" name="item_media" id="item_media" value="<?php echo $item_media ?>" data-aantal="0" />
				</div>
			</div>
		</div>
        <p><label for="item_media_overschreven">Media updaten voor nieuwe groepen</label><?php if(empty($item_media_overschrijven)) { ?> Nee | <a href="<?php echo base_url('cms/lessen/mediaOverschrijven/'.$item_ID) ?>" title="Media overschrijven">Media toevoegen</a><?php } else { ?> Ja | <a href="<?php echo base_url('cms/lessen/mediaOverschrijven/'.$item_ID) ?>" title="overschreven media wijzigen.">Media wijzigen</a><?php } ?></p>
		<p><label for="item_huiswerk">Opdrachten</label><textarea name="item_huiswerk" id="item_huiswerk" class="opmaak"><?php echo $item_huiswerk ?></textarea><span class="feedback"><?php echo $item_huiswerk_feedback ?></span></p>
		<p><label for="item_huiswerk_aantal">Aantal uploads</label><input type="text" name="item_huiswerk_aantal" id="item_huiswerk_aantal" value="<?php echo $item_huiswerk_aantal ?>" /><span class="feedback"><?php echo $item_huiswerk_aantal_feedback ?></span></p>
		<p><label for="item_locatie">Locatie *</label><input type="radio" name="item_locatie" value="online" <?php if($item_locatie == 'online') echo 'checked'; ?> /> Online <input type="radio" name="item_locatie" value="studio" <?php if($item_locatie == 'studio') echo 'checked'; ?> /> Studio <span class="feedback"><?php echo $item_locatie_feedback ?></span></p>
		<p><label for="item_workshop">Workshop *</label>
			<select name="item_workshop" id="item_workshop">
				<option value="">Workshop selecteren</option>
				<option value="">---</option>
				<?php foreach($workshops as $workshop): ?>
					<option value="<?php echo $workshop->workshop_ID ?>" <?php if($workshop->workshop_ID == $item_workshop) echo 'selected'; ?>><?php echo $workshop->workshop_titel ?></option>
				<?php endforeach; ?>
			</select>
			<span class="feedback"><?php echo $item_workshop_feedback ?></span>
		</p>

		<p><label for="item_nagekeken_door">Hw nagekeken door *</label>
			<select name="item_nagekeken_door" id="item_workshop">
				<option value="">Docent selecteren</option>
				<option value="">---</option>
				<?php foreach($docenten as $docent): ?>
					<?php if($docent->gebruiker_status == 'actief'): ?>
					<option value="<?php echo $docent->gebruiker_ID ?>" <?php if($docent->gebruiker_ID == $item_docent) echo 'selected'; ?>><?php echo $docent->docent_naam ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			<span class="feedback"><?php echo $item_workshop_feedback ?></span>
			<?php if($actie == 'wijzigen' && $item_first_les == false) { ?>
				<p><label for="item_voorbereidingsmail">Voorbereidingsmail</label><?php if(empty($item_voorbereidingsmail)) { ?> Nee | <a href="<?php echo base_url('cms/lessen/voorbereidingsmail/'.$item_ID) ?>" title="Voorbereidingsmail toevoegen">Voorbereidingsmail toevoegen</a><?php } else { ?> Ja | <a href="<?php echo base_url('cms/lessen/voorbereidingsmail/'.$item_ID) ?>" title="Voorbereidingsmail wijzigen.">voorbereidingsmail wijzigen</a><?php } ?></p>
			<?php } elseif($item_first_les == true) { ?>
				<p><label for="item_welkomstmail">Welkomstmail</label><a href="<?php echo base_url('cms/workshops/wijzigen/'.$item_workshop) ?>" title="welkomstmail instellen">welkomstmail instellen</a></p>
			<?php } ?>
		</p>
		<p class="submit"><input type="submit" value="Les <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/lessen/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/lessen/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>