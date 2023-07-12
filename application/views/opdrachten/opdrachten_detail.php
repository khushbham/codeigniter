<h1><?php echo $item->opdracht_titel ?></h1>

<p id="links">
	<a href="<?php echo base_url('opdrachten/opdrachten/') ?>" title="Alle opdrachten">Alle opdrachten</a>
	<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
		<a href="<?php echo base_url('opdrachten/opdrachten/wijzigen/'.$item->opdracht_ID) ?>" title="Opdracht wijzigen" class="wijzigen">Opdracht wijzigen</a>
		<a href="<?php echo base_url('opdrachten/opdrachten/verwijderen/'.$item->opdracht_ID) ?>" title="Opdracht verwijderen" class="verwijderen">Opdracht verwijderen</a>
	<?php endif; ?>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>URL</th>
		<td><?php echo nl2br($item->opdracht_url) ?></td>
	</tr>
	<tr>
		<th>Beschrijving</th>
		<td><?php echo nl2br($item->opdracht_beschrijving) ?></td>
	</tr>
	<tr>
		<th>Media</th>
		<td>
			<?php if(sizeof($media) > 0): ?>
				<table cellpadding="0" cellspacing="0" class="media js-sorteren" data-items="media">
					<?php foreach($media as $bestand): ?>
						<tr data-item="<?php echo $bestand->media_connectie_ID ?>">
							<?php
							$item_media_link = '#';
							if($bestand->media_type == 'pdf') { $media_src = base_url('images/icoon-pdf.jpg'); $item_media_link = base_url('/media/pdf/'.$bestand->media_src); }
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
        <th>Opdrachten</th>
        <td><?php echo $item->opdracht_uploads ?></td>
    </tr>
	<tr>
		<th>Aantal uploads</th>
		<td><?php echo $item->opdracht_uploads_aantal ?></td>
	</tr>
</table>