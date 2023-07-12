<h1><?php echo $item->product_naam ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/producten/') ?>" title="Alle producten">Alle producten</a>
	<a href="<?php echo base_url('cms/producten/wijzigen/'.$item->product_ID) ?>" title="Product wijzigen" class="wijzigen">Product wijzigen</a>
	<a href="<?php echo base_url('cms/producten/verwijderen/'.$item->product_ID) ?>" title="Product verwijderen" class="verwijderen">Product verwijderen</a>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Beschrijving</th>
		<td><?php echo $item->product_beschrijving ?></td>
	</tr>
	<tr>
		<th>Prijs</th>
		<td><?php echo $item->product_prijs ?>,00 euro</td>
	</tr>
	<tr>
		<th>Bruikleen</th>
		<td><?php if($item->product_huur == 1) echo 'Ja'; else echo 'Nee'; ?></td>
	</tr>
	<tr>
		<th>Borg</th>
		<td><?php echo $item->product_borg ?>,00 euro</td>
	</tr>
	<tr>
		<th>Prijs na bruikleen</th>
		<td><?php echo $item->product_prijs_naderhand ?></td>
	</tr>
	<tr>
		<th>Bruikleen beschrijving</th>
		<td><?php if(!empty($item->product_beschrijving_huur)) echo $item->product_beschrijving_huur; else echo '...'; ?></td>
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
</table>
