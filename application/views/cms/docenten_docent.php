<h1><?php echo $item->docent_naam ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/docenten/') ?>" title="Alle docenten">Alle docenten</a>
	<a href="<?php echo base_url('cms/docenten/wijzigen/'.$item->docent_ID) ?>" title="Docent wijzigen" class="wijzigen">Docent wijzigen</a>
	<a href="<?php echo base_url('cms/docenten/verwijderen/'.$item->docent_ID) ?>" title="Docent verwijderen" class="verwijderen">Docent verwijderen</a>
</p>

<h2>Gegevens</h2>
<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Status</th>
		<td><?php if(!empty($docent->gebruiker_status)) echo ucfirst($docent->gebruiker_status); else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Bedrijfsnaam</th>
		<td><?php if(!empty($docent->gebruiker_bedrijfsnaam)) echo $docent->gebruiker_bedrijfsnaam; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Geslacht</th>
		<td><?php if(!empty($docent->gebruiker_geslacht)) echo ucfirst($docent->gebruiker_geslacht); else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Geboortedatum</th>
		<td><?php if(!empty($docent->gebruiker_geboortedatum) && $docent->gebruiker_geboortedatum != '0000-00-00') echo date('d/m/Y', strtotime($docent->gebruiker_geboortedatum)); else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Adres</th>
		<td><?php if(!empty($docent->gebruiker_adres)) echo $docent->gebruiker_adres; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Postcode</th>
		<td><?php if(!empty($docent->gebruiker_postcode)) echo $docent->gebruiker_postcode; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Plaats</th>
		<td><?php if(!empty($docent->gebruiker_plaats)) echo $docent->gebruiker_plaats; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Telefoonnummer</th>
		<td><?php if(!empty($docent->gebruiker_telefoonnummer)) echo $docent->gebruiker_telefoonnummer; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Mobiel</th>
		<td><?php if(!empty($docent->gebruiker_mobiel)) echo $docent->gebruiker_mobiel; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>E-mailadres</th>
		<td><?php if(!empty($docent->gebruiker_emailadres)) echo $docent->gebruiker_emailadres; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Berichten aan</th>
		<td><?php if(!empty($docent->docent_berichten_aan)) echo ucfirst($docent->docent_berichten_aan); else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Notities</th>
		<td><?php if(!empty($docent->gebruiker_notities)) echo $docent->gebruiker_notities; else echo '...'; ?></td>
	</tr>
</table>

<h2>Docent informatie</h2>
<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Inleiding</th>
		<td><?php echo nl2br($item->docent_inleiding) ?></td>
	</tr>
	<tr>
		<th>Tekst</th>
		<td><?php echo $item->docent_tekst ?></td>
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
                            <td class="media_image"><a href="<?php echo $item_media_link ?>" target="<?php if($item_media_link != '#') echo '_blank'; ?>"><img src="<?php echo $media_src ?>" title="<?php echo $item->media_titel ?>" /></a></td>
							<td class="media_titel"><?php echo $bestand->media_titel ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</td>
	</tr>
</table>