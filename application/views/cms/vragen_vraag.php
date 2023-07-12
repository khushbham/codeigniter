<h1><?php echo $item->vraag_titel ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/vragen/') ?>" title="Alle vragen">Alle vragen</a>
	<a href="<?php echo base_url('cms/vragen/wijzigen/'.$item->vraag_ID) ?>" title="Vraag wijzigen" class="wijzigen">Vraag wijzigen</a>
	<a href="<?php echo base_url('cms/vragen/verwijderen/'.$item->vraag_ID) ?>" title="Vraag verwijderen" class="verwijderen">Vraag verwijderen</a>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Antwoord</th>
		<td><?php echo $item->vraag_antwoord ?></td>
	</tr>
	<tr>
		<th>Media</th>
		<td>
			<?php if(sizeof($media) > 0): ?>
				<table cellpadding="0" cellspacing="0" class="media">
					<?php foreach($media as $bestand): ?>
						<tr>
                            <?php
							$item_media_link = '#';
                            if($bestand->media_type == 'pdf') { $media_src = base_url('images/pdf.png'); $item_media_link = base_url('/media/pdf/'.$bestand->media_src); }
                            elseif($bestand->media_type == 'afbeelding') { $media_src = base_url('media/afbeeldingen/thumbnail/'.$bestand->media_src); $item_media_link = base_url('/media/afbeeldingen/origineel/'.$bestand->media_src); }
                            elseif($bestand->media_type == 'video') { $media_src = '//view.vzaar.com/'.$bestand->media_src.'/thumb'; $item_media_link = '//view.vzaar.com/'.$bestand->media_src; }
                            elseif($bestand->media_type == 'mp3') { $media_src = base_url('images/mp3.png'); $item_media_link = base_url('/media/audio/'.$bestand->media_src); }
                            ?>
                            <td class="media_image"><a href="<?php echo $item_media_link ?>" target="<?php if($item_media_link != '#') echo '_blank'; ?>"><img src="<?php echo $media_src ?>" title="<?php echo $bestand->media_titel ?>" /></a></td>
							<td class="media_titel"><?php echo $bestand->media_titel ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th>Type</th>
		<td><?php echo ucfirst($item->vraag_type) ?></td>
	</tr>
	<tr>
		<th>Gepubliceerd</th>
		<td><?php echo ucfirst($item->vraag_gepubliceerd) ?></td>
	</tr>
</table>