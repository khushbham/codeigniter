<h1>Opdracht <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('opdrachten/opdrachten/toevoegen/'); else echo base_url('opdrachten/opdrachten/wijzigen/'.$item_ID); ?>">
		<p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
		<p><label for="item_url">URL *</label><input type="text" name="item_url" id="item_url" value="<?php echo $item_url ?>" /><span class="feedback"><?php echo $item_url_feedback ?></span></p>
		<p><label for="item_beschrijving">Beschrijving *</label><textarea name="item_beschrijving" id="item_beschrijving" class="opmaak"><?php echo $item_beschrijving ?></textarea><span class="feedback"><?php echo $item_beschrijving_feedback ?></span></p>
		<p><label for="item_audio_titel">Audio titel *</label><input type="text" name="item_audio_titel" id="item_audio_titel" value="<?php echo $item_audio_titel ?>" placeholder="Audio voorbeelden"><?php echo $item_audio_titel ?></textarea><span class="feedback"><?php echo $item_audio_titel_feedback ?></span></p>
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
		<p><label for="item_upload">Uploads</label><textarea name="item_upload" id="item_upload" class="opmaak"><?php echo $item_upload ?></textarea><span class="feedback"><?php echo $item_upload_feedback ?></span></p>
		<p><label for="item_upload_aantal">Aantal uploads</label><input type="text" name="item_upload_aantal" id="item_upload_aantal" value="<?php echo $item_upload_aantal ?>" /><span class="feedback"><?php echo $item_upload_aantal_feedback ?></span></p>

		<p class="submit"><input type="submit" value="Opdracht <?php echo $actie ?>" /> <a href="<?php echo base_url('opdrachten/opdrachten/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('opdrachten/opdrachten/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>