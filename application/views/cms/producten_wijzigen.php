<h1>Product <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/producten/toevoegen/'); else echo base_url('cms/producten/wijzigen/'.$item_ID); ?>">
		<p><label for="item_naam">Naam *</label><input type="text" name="item_naam" id="item_naam" value="<?php echo $item_naam ?>" /><span class="feedback"><?php echo $item_naam_feedback ?></span></p>
		<p><label for="item_beschrijving">Beschrijving *</label><textarea name="item_beschrijving" id="item_beschrijving" class="opmaak"><?php echo $item_beschrijving ?></textarea><span class="feedback"><?php echo $item_beschrijving_feedback ?></span></p>
		<p><label for="item_prijs">Prijs *</label><input type="text" name="item_prijs" id="item_prijs" value="<?php echo $item_prijs ?>" /><span class="feedback"><?php echo $item_prijs_feedback ?></span></p>
		<p>
			<label for="item_huur">Bruikleen *</label>
			<input type="radio" name="item_huur" id="item_huur_ja" value="1" <?php if($item_huur == 1) echo 'checked'; ?> /> Ja
			<input type="radio" name="item_huur" id="item_huur_nee" value="0" <?php if($item_huur == 0) echo 'checked'; ?> /> Nee
		</p>
		<p><label for="item_borg">Borg *</label><input type="text" name="item_borg" id="item_borg" value="<?php echo $item_borg ?>" /></p>
		<p><label for="item_prijs_naderhand">Prijs na bruikleen *</label><input type="text" name="item_prijs_naderhand" id="item_prijs_naderhand" value="<?php echo $item_prijs_naderhand ?>" /></p>
		<p><label for="item_beschrijving_huur">Bruikleen beschrijving</label><textarea name="item_beschrijving_huur" id="item_beschrijving_huur" class="opmaak"><?php echo $item_beschrijving_huur ?></textarea></p>
		<p>
			<label for="item_beschikbaar">Beschikbaar *</label>
			<input type="radio" name="item_beschikbaar" id="item_beschikbaar_ja" value="ja" <?php if($item_beschikbaar == 'ja') echo 'checked'; ?> /> Ja
			<input type="radio" name="item_beschikbaar" id="item_beschikbaar_nee" value="nee" <?php if($item_beschikbaar == 'nee') echo 'checked'; ?> /> Nee
			<span class="feedback"><?php echo $item_beschikbaar_feedback ?></span>
		</p>
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

		<p class="submit"><input type="submit" value="Product <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/producten/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/producten/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>
