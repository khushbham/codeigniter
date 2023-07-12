<h1><?php echo $item->nieuws_titel ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/nieuws/') ?>" title="Alle berichten">Alle berichten</a>
	<a href="<?php echo base_url('cms/nieuws/wijzigen/'.$item->nieuws_ID) ?>" title="Bericht wijzigen" class="wijzigen">Bericht wijzigen</a>
	<a href="<?php echo base_url('cms/nieuws/verwijderen/'.$item->nieuws_ID) ?>" title="Bericht verwijderen" class="verwijderen">Bericht verwijderen</a>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>URL</th>
		<td><?php echo $item->nieuws_url ?></td>
	</tr>
	<tr>
		<th>Datum</th>
		<td><?php echo date('d-m-Y, H:i', strtotime($item->nieuws_datum)) ?> uur</td>
	</tr>
	<tr>
		<th>Inleiding</th>
		<td><?php echo $item->nieuws_inleiding ?></td>
	</tr>
	<tr>
		<th>Bericht</th>
		<td><?php echo $item->nieuws_bericht ?></td>
	</tr>
	<tr>
		<th>Gepubliceerd</th>
		<td><?php echo ucfirst($item->nieuws_gepubliceerd) ?></td>
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
		<th>Meta title</th>
		<td><?php if(!empty($item->meta_title)) echo $item->meta_title; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Meta description</th>
		<td><?php if(!empty($item->meta_description)) echo $item->meta_description; else echo '...'; ?></td>
	</tr>
	<tr>
        <th>Meta Image</th>
        <td>
            <?php if(sizeof($meta_media) > 0): ?>
                <table cellpadding="0" cellspacing="0" class="media">
                    <?php foreach($meta_media as $bestand): ?>
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
</table>