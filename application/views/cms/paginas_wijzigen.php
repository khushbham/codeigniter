<h1>Pagina <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/paginas/toevoegen/'); else echo base_url('cms/paginas/wijzigen/'.$item_ID); ?>">
		<p><label for="item_titel_menu">Titel menu *</label><input type="text" name="item_titel_menu" id="item_titel_menu" value="<?php echo $item_titel_menu ?>" /><span class="feedback"><?php echo $item_titel_menu_feedback ?></span></p>
		<p><label for="item_url">URL *</label><input type="text" name="item_url" id="item_url" value="<?php echo $item_url ?>" /><span class="feedback"><?php echo $item_url_feedback ?></span></p>
		<p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
		<p><label for="item_inleiding">Inleiding *</label><input type="text" name="item_inleiding" id="item_inleiding" value="<?php echo $item_inleiding ?>" /><span class="feedback"><?php echo $item_inleiding_feedback ?></span></p>
        <p>
            <label for="item_toevoegen">Voeg een nieuw item toe</label>
            <select name="item_toevoegen" id="item_toevoegen">
                <option value="0">Selecteer een nieuw item</option>
                <option value="1">Tab</option>
                <option value="2">Omlijning</option>
                <option value="3">Blauwe achtergrond</option>
                <option value="4">Vinkje</option>
                <option value="5">Link</option>
                <option value="6">Call to action button</option>
            </select>
        </p>
        <p class="submit"><input type="button" class="add_item" onclick="AddItem()" value="Item toevoegen" /></p>
		<p><label for="item_tekst">Tekst *</label><textarea name="item_tekst" id="item_tekst" class="opmaak"><?php echo $item_tekst ?></textarea><span class="feedback"><?php echo $item_tekst_feedback ?></span></p>

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

		<?php if($item_ID == 15): ?>
			<p><label for="item_video_url">Video url *</label><input type="text" name="item_video_url" id="item_video_url" value="<?php echo $item_video_url ?>" /><span class="feedback"><?php echo $item_video_url_feedback ?></span></p>
			<p><label for="item_chat_url">Chat url *</label><input type="text" name="item_chat_url" id="item_chat_url" value="<?php echo $item_chat_url ?>" /></p>
		<?php endif; ?>

		<?php if($item_meta_gewenst): ?>
			<p><label for="item_meta_title">Meta title</label><input type="text" name="item_meta_title" id="item_meta_title" value="<?php echo $item_meta_title ?>" maxlength="60" /><span class="feedback"><?php echo $item_meta_title_feedback ?></span></p>
			<p><label for="item_meta_description">Meta description</label><input type="text" name="item_meta_description" id="item_meta_description" value="<?php echo $item_meta_description ?>" maxlength="160" /><span class="feedback"><?php echo $item_meta_description_feedback ?></span></p>

			<p>
	            <label> Aanbevolen Maat </label>
	            1200 x 630 pixels, afbeeldingen worden automatisch aangepast aan dit formaat, <a href="http://www.picmonkey.com/" title="Gebruik PicMonkey voor het croppen van afbeeldingen naar het juiste formaat van 620 x 380 pixels" target="_blank">afbeelding croppen?</a>
	        </p>

	        <!-- META IMAGE UPLOAD -->
	        <div id="media_uitgelicht">
	            <div id="media_label">
	                <p><label for="item_media_uitgelicht">Meta Image</label></p>
	            </div>
	            <div id="media_container">
	                <div id="media_lijst">
	                    <table cellpadding="0" cellspacing="0">
	                        <?php $item_media_uitgelicht = ''; ?>
	                        <?php if($meta_media > 0): foreach($meta_media as $item): ?>
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
	                        <?php $item_media_uitgelicht .= $item->media_ID.','; endforeach; endif; ?>
	                    </table>
	                </div>
	                <div id="media_acties">
	                     <p><a href="#koppelen" title="Media koppelen aan les" data-specific_sort="afbeelding" class="koppelen" data-soort="media_uitgelicht">Media koppelen</a> | <a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen aan mediabibliotheek (opent een nieuw tabblad / venster)" target="_blank">Media toevoegen</a></p>
	                     <input type="hidden" name="item_media_uitgelicht" id="item_media_uitgelicht" value="<?php echo $item_media_uitgelicht ?>" data-aantal="1" />
	                </div>
	            </div>
	        </div>
		<?php endif; ?>

		<p class="submit"><input type="submit" value="Pagina <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/paginas/'.$item_ID) ?>" title="Annuleren">Annuleren</a></p>
	</form>
</div>