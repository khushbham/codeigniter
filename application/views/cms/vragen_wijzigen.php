<h1>Vraag <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/vragen/toevoegen/'); else echo base_url('cms/vragen/wijzigen/'.$item_ID); ?>">
		<p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
		<p><label for="item_antwoord">Antwoord *</label><textarea name="item_antwoord" id="item_antwoord" class="opmaak"><?php echo $item_antwoord ?></textarea><span class="feedback"><?php echo $item_antwoord_feedback ?></span></p>

		<div id="media">
			<div id="media_label">
				<p><label for="item_media">Media</label></p>
			</div>
			<div id="media_container">
				<div id="media_lijst">
					<table cellpadding="0" cellspacing="0">
						<?php $item_media = ''; ?>
						<?php if($media > 0): foreach($media as $item): ?>
							<tr>
                                <?php
								$item_media_link = '#';
                                if($item->media_type == 'pdf') { $media_src = base_url('images/pdf.png'); $item_media_link = base_url('/media/pdf/'.$item->media_src); }
                                elseif($item->media_type == 'afbeelding') { $media_src = base_url('media/afbeeldingen/thumbnail/'.$item->media_src); $item_media_link = base_url('/media/afbeeldingen/origineel/'.$item->media_src); }
                                elseif($item->media_type == 'video') { $media_src = '//view.vzaar.com/'.$item->media_src.'/thumb'; $item_media_link = '//view.vzaar.com/'.$item->media_src; }
                                elseif($item->media_type == 'mp3') { $media_src = base_url('images/mp3.png'); $item_media_link = base_url('/media/audio/'.$item->media_src); }
                                ?>
                                <td class="media_image"><a href="<?php echo $item_media_link ?>" target="<?php if($item_media_link != '#') echo '_blank'; ?>"><img src="<?php echo $media_src ?>" title="<?php echo $item->media_titel ?>" /></a></td>
								<td class="media_titel"><?php echo $item->media_titel ?></td>
							</tr>
						<?php $item_media .= $item->media_ID.','; endforeach; endif; ?>
					</table>
				</div>
				<div id="media_acties">
					<p><a href="#koppelen" title="Media koppelen aan les" class="koppelen">Media koppelen</a> | <a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen aan mediabibliotheek (opent een nieuw tabblad / venster)" target="_blank">Media toevoegen</a></p>
					<input type="hidden" name="item_media" id="item_media" value="<?php echo $item_media ?>" data-aantal="1" />
				</div>
			</div>
		</div>

		<p><label for="item_type">Type *</label><input type="radio" name="item_type" value="website" <?php if($item_type == 'website') echo 'checked'; ?> /> Website <input type="radio" name="item_type" value="cursistenmodule" <?php if($item_type == 'cursistenmodule') echo 'checked'; ?> /> Cursistenmodule <span class="feedback"><?php echo $item_type_feedback ?></span></p>
		<p><label for="item_gepubliceerd">Publiceren *</label><input type="radio" name="item_gepubliceerd" value="ja" <?php if($item_gepubliceerd == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="item_gepubliceerd" value="nee" <?php if($item_gepubliceerd == 'nee') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_gepubliceerd_feedback ?></span></p>
		<p class="submit"><input type="submit" value="Vraag <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/vragen/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/vragen/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>