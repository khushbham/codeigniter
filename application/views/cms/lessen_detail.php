<h1><?php echo $item->les_titel ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/lessen/') ?>" title="Alle lessen">Alle lessen</a>
	<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
		<a href="<?php echo base_url('cms/lessen/wijzigen/'.$item->les_ID) ?>" title="Les wijzigen" class="wijzigen">Les wijzigen</a>
		<a href="<?php echo base_url('cms/lessen/verwijderen/'.$item->les_ID) ?>" title="Les verwijderen" class="verwijderen">Les verwijderen</a>
	<?php endif; ?>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Beschrijving</th>
		<td><?php echo nl2br($item->les_beschrijving) ?></td>
	</tr>
	<tr>
		<th>Type</th>
		<td><?php if(!empty($item_type[0]->les_type_soort)) echo $item_type[0]->les_type_soort; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Header video url</th>
		<td><?php if(!empty($item->les_video_url)) echo $item->les_video_url; else echo '...' ?></td>
	</tr>
	<tr>
		<th>Header placeholder afbeelding</th>
		<td><?php if(!empty($item->les_image_url)) echo $item->les_image_url; else echo '...' ?></td>
	</tr>
	<tr>
		<th>Typeform url</th>
		<td><?php if(!empty($item->les_typeform_url)) echo $item->les_typeform_url; else echo '...' ?></td>
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
        <th>Media overschrijven voor nieuwe groepen</th>
        <td><?php if(!empty($media_nieuw)) { if($media_nieuw[0]->media_ingang != '0000-00-00') { echo 'Ja'; } else { echo 'Nee'; } } else { echo 'Nee'; } ?> </td>
    </tr>
    <tr>
        <th>Opdrachten</th>
        <td><?php echo $item->les_huiswerk ?></td>
    </tr>
	<tr>
		<th>Aantal uploads</th>
		<td><?php echo $item->les_huiswerk_aantal ?></td>
	</tr>
	<tr>
		<th>Locatie</th>
		<td><?php echo $item->les_locatie ?></td>
	</tr>
	<?php if($this->session->userdata('beheerder_rechten') != 'docent'): ?>
		<tr>
			<th>Workshop</th>
			<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Bekijk de workshop <?php echo $item->workshop_titel ?>"><?php echo $item->workshop_titel ?></a></td>
		</tr>
	<?php else: ?>
		<tr>
			<th>Workshop</th>
			<td><?php echo $item->workshop_titel ?></a></td>
		</tr>
	<?php endif; ?>
	<tr>
		<th>Voorbereidingsmail</th>
		<td><?php if (!empty($item->les_voorbereidingsmail)) { echo 'ja'; } else { echo 'nee'; } ?></td>
	</tr>
	<?php if(!empty($docent)): ?>
		<?php if($this->session->userdata('beheerder_rechten') != 'docent'): ?>
			<tr>
				<th>Opdrachten nakijken</th>
				<td><a href="<?php echo base_url('cms/docenten/'. $docent->docent_ID) ?>" title="Opdracht nakijken"><?php echo $docent->gebruiker_naam ?></a></td>
			</tr>
		<?php else: ?>
			<tr>
				<th>Opdracht nakijken</th>
				<td><?php echo $docent->gebruiker_naam ?></a></td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>
</table>