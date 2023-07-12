<h1>Media <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" enctype="multipart/form-data" action="<?php if($actie == 'toevoegen') echo base_url('cms/media/toevoegen/'); else echo base_url('cms/media/wijzigen/'.$item_ID); ?>">
			<p id="keuzes">
				<label for="item_type">Type *</label>
				<input type="radio" name="item_type" value="pdf" <?php if($item_type == 'pdf') echo 'checked'; ?> /> PDF
				<input type="radio" name="item_type" value="afbeelding" <?php if($item_type == 'afbeelding') echo 'checked'; ?> /> Afbeelding
				<input type="radio" name="item_type" value="video" <?php if($item_type == 'video') echo 'checked'; ?> /> Video
				<input type="radio" name="item_type" value="playlist" <?php if($item_type == 'playlist') echo 'checked'; ?> /> Playlist
				<input type="radio" name="item_type" value="mp3" <?php if($item_type == 'mp3') echo 'checked'; ?> /> MP3
				<span class="feedback"><?php echo $item_type_feedback ?></span>
			</p>
		<p><label for="item_titel">Titel</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
			<div class="keuze pdf <?php if($item_type == 'pdf') echo 'active'; ?>">
				<p><label for="item_src_pdf">Selecteer PDF *</label><input type="file" name="item_src_pdf" id="item_src_pdf" value="<?php echo $item_src ?>" /><span class="feedback"><?php echo $item_src_feedback ?></span></p>
			</div>
			<div class="keuze afbeelding <?php if($item_type == 'afbeelding') echo 'active'; ?>">
				<p><label>Maten</label>620 x 380 / 163 pixels, kleinere afbeeldingen zijn in verhouding, <a href="http://www.picmonkey.com/" title="Gebruik PicMonkey voor het croppen van afbeeldingen naar het juiste formaat van 620 x 380 pixels" target="_blank">afbeelding croppen?</a></p>
				<p><label for="item_src_afbeelding">Selecteer afbeelding *</label><input type="file" name="item_src_afbeelding" id="item_src_afbeelding" value="<?php echo $item_src ?>" /><span class="feedback"><?php echo $item_src_feedback ?></span></p>
			</div>
			<div class="keuze mp3 <?php if($item_type == 'mp3') echo 'active'; ?>">
				<p><label for="item_src_mp3">Selecteer MP3 *</label><input type="file" name="item_src_mp3" id="item_src_mp3" value="<?php echo $item_src ?>" /><span class="feedback"><?php echo $item_src_feedback ?></span></p>
			</div>
		<div class="keuze video <?php if($item_type == 'video') echo 'active'; ?>">
			<p><label for="item_src_video">Vzaar video ID *</label><input type="text" name="item_src_video" id="item_src_video" value="<?php echo $item_src ?>" placeholder="Bijvoorbeeld: 1158071" /><span class="feedback"><?php echo $item_src_feedback ?></span></p>
		</div>
		<div class="keuze playlist <?php if($item_type == 'playlist') echo 'active'; ?>">
			<p><label for="item_src_playlist">Vzaar playlist ID *</label><input type="text" name="item_src_playlist" id="item_src_playlist" value="<?php echo $item_src ?>" placeholder="Bijvoorbeeld: 2587" /><span class="feedback"><?php echo $item_src_feedback ?></span></p>
		</div>
		<p class="submit"><input type="submit" value="Media <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/media/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/media/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>