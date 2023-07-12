<h1>Nieuws <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/nieuws/toevoegen/'); else echo base_url('cms/nieuws/wijzigen/'.$item_ID); ?>">
		<p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
		<p><label for="item_url">URL *</label><input type="text" name="item_url" id="item_url" value="<?php echo $item_url ?>" /><span class="feedback"><?php echo $item_url_feedback ?></span></p>
		<p><label for="item_datum_dag">Datum *</label><input type="text" name="item_datum_dag" id="item_datum_dag" class="datum_smal" value="<?php echo $item_datum_dag ?>" /><input type="text" name="item_datum_maand" id="item_datum_maand" class="datum_smal" value="<?php echo $item_datum_maand ?>" /><input type="text" name="item_datum_jaar" id="item_datum_jaar" class="datum_breed" value="<?php echo $item_datum_jaar ?>" /><span class="feedback"><?php echo $item_datum_feedback ?></span></p>
		<p><label for="item_tijd_uren">Tijd *</label><input type="text" name="item_tijd_uren" id="item_tijd_uren" class="datum_smal" value="<?php echo $item_tijd_uren ?>" /><input type="text" name="item_tijd_minuten" id="item_tijd_minuten" class="datum_smal" value="<?php echo $item_tijd_minuten ?>" /><input type="text" name="item_tijd_seconden" id="item_tijd_seconden" class="datum_smal" value="<?php echo $item_tijd_seconden ?>" /><span class="feedback"><?php echo $item_tijd_feedback ?></span></p>
		<p><label for="item_inleiding">Inleiding *</label><textarea name="item_inleiding" id="item_inleiding"><?php echo $item_inleiding ?></textarea><span class="feedback"><?php echo $item_inleiding_feedback ?></span></p>
		<p><label for="item_bericht">Bericht *</label><textarea name="item_bericht" id="item_bericht" class="opmaak"><?php echo $item_bericht ?></textarea><span class="feedback"><?php echo $item_bericht_feedback ?></span></p>

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

		<p><label for="item_meta_title">Meta title</label><input type="text" name="item_meta_title" id="item_meta_title" value="<?php echo $item_meta_title ?>" maxlength="60" /><span class="feedback"><?php echo $item_meta_title_feedback ?></span></p>
		<p><label for="item_meta_description">Meta description</label><input type="text" name="item_meta_description" id="item_meta_description" value="<?php echo $item_meta_description ?>" maxlength="160" /><span class="feedback"><?php echo $item_meta_description_feedback ?></span></p>

		<p>
            <label> Aanbevolen Maat </label>
            1200 x 630 pixels, afbeeldingen worden automatisch aangepast aan dit formaat, <a href="http://www.picmonkey.com/" title="Gebruik PicMonkey voor het croppen van afbeeldingen naar het juiste formaat van 620 x 380 pixels" target="_blank">afbeelding croppen?</a>
        </p>
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

		<p><label for="item_gepubliceerd">Publiceren *</label><input type="radio" name="item_gepubliceerd" value="ja" <?php if($item_gepubliceerd == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="item_gepubliceerd" value="nee" <?php if($item_gepubliceerd == 'nee') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_gepubliceerd_feedback ?></span></p>
		<p class="submit"><input type="submit" value="Nieuws <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/nieuws/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/nieuws/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>