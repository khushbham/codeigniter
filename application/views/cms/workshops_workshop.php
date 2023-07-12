<h1><?php echo $workshop->workshop_titel ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/workshops/') ?>" title="Alle workshops">Alle workshops</a>
	<a href="<?php echo base_url('cms/workshops/lessen_kopieren/'. $workshop->workshop_ID) ?>" title="Lessen kopieren">Lessen kopiëren</a>
	<a href="<?php echo base_url('cms/workshops/wijzigen/'.$workshop->workshop_ID) ?>" title="Workshop wijzigen" class="wijzigen">Workshop wijzigen</a>
	<a href="<?php echo base_url('cms/workshops/verwijderen/'.$workshop->workshop_ID) ?>" title="Workshop verwijderen" class="verwijderen">Workshop verwijderen</a>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Ondertitel</th>
		<td><?php echo $workshop->workshop_ondertitel ?></td>
	</tr>
	<tr>
		<th>URL</th>
		<td><?php echo $workshop->workshop_url ?></td>
	</tr>
	<tr>
		<th>Afkorting</th>
		<td><?php echo $workshop->workshop_afkorting ?></td>
	</tr>
	<tr>
		<th>Locatie</th>
		<td><?php if(!empty($workshop->workshop_locatie)) echo $workshop->workshop_locatie; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Type</th>
		<td>
			<?php
			switch($workshop->workshop_type)
			{
				case 'groep':
				echo 'Online en fysieke groepslessen';
				break;

				case 'online':
				echo 'Online groepslessen';
				break;

				case 'individueel':
				echo 'Online lessen individueel';
				break;
			}
			?>
		</td>
	</tr>
	<tr>
		<th>Soort</th>
		<td><?php echo $workshop->workshop_soort ?></td>
	</tr>
    <tr>
        <th>Niveau</th>
        <td>
            <?php
            switch($workshop->workshop_niveau)
            {
                case '0':
                    echo 'Geen';
                    break;

                case '1':
                    echo 'Niveau 1: Stemtest';
                    break;

                case '2':
                    echo 'Niveau 2: Kenningsmakingsworkshops & Introductieworkshop localhost';
                    break;

                case '3':
                    echo 'Niveau 3: Basisworkshop (Leiden & Utrecht) en Bootcamp';
                    break;
                case '4':
                    echo 'Niveau 4: Animatieworkshop / Specialty Workshops';
                    break;
                case '5':
                    echo 'Niveau 5: Vervolgworkshops';
                    break;
            }
            ?>
        </td>
    </tr>
	<tr>
		<th>Specialty</th>
		<td><?php echo ucfirst($workshop->workshop_specialty) ?></td>
	</tr>
	<tr>
		<th>Gepubliceerd</th>
		<td><?php echo ucfirst($workshop->workshop_gepubliceerd) ?></td>
	</tr>
	<tr>
		<th>Uitgelicht</th>
		<td><?php echo ucfirst($workshop->workshop_uitgelicht) ?></td>
	</tr>
	<tr>
		<th>Groepsgrootte zichtbaar</th>
		<td><?php if($workshop->workshop_grootte_zichtbaar == 1) echo 'Ja'; else echo 'Nee'; ?></td>
	</tr>
	<tr>
		<th>Inleiding</th>
		<td><?php echo $workshop->workshop_inleiding ?></td>
	</tr>
	<tr>
		<th>Beschrijving</th>
		<td><?php if(!empty($workshop->workshop_beschrijving)) echo $workshop->workshop_beschrijving; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Cursistenmodule tekst</th>
		<td><?php if(!empty($workshop->workshop_cursistenmodule_tekst)) echo $workshop->workshop_cursistenmodule_tekst; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Prijs</th>
		<td><?php echo $workshop->workshop_prijs ?>,00 euro</td>
	</tr>
	<tr>
		<th>Startdatum</th>
		<td><?php if($workshop->workshop_startdatum != '' && $workshop->workshop_startdatum != '0000-00-00 00:00:00') echo date('d-m-y', strtotime($workshop->workshop_startdatum)); else echo '...' ?><?php if(strtotime($workshop->workshop_startdatum) > time()) echo ' (direct beginnen)'; ?></td>
	</tr>
	<tr>
		<th>Freqentie</th>
		<td><?php echo $workshop->workshop_frequentie.' dagen tussen elke individuele les (n.v.t. op groepslessen)' ?></td>
	</tr>
	<tr>
		<th>Duur</th>
		<td><?php if($workshop->workshop_duur == 1) echo '1 les'; else echo $workshop->workshop_duur.' lessen' ?></td>
	</tr>
	<tr>
		<th>Capaciteit</th>
		<td><?php if($workshop->workshop_capaciteit == 1) echo '1 deelnemer'; else echo $workshop->workshop_capaciteit.' deelnemers'; ?></td>
	</tr>
	<tr>
		<th>Toelatingseisen</th>
		<td><?php if(!empty($workshop->workshop_toelatingseisen)) echo $workshop->workshop_toelatingseisen; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Inclusief</th>
		<td><?php if(!empty($workshop->workshop_inclusief)) echo $workshop->workshop_inclusief; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Exclusief</th>
		<td><?php if(!empty($workshop->workshop_exclusief)) echo $workshop->workshop_exclusief; else echo '...'; ?></td>
	</tr>
	<?php if($workshop->workshop_niveau == 5) { ?>
		<tr>
			<th>Uitnodigingscode aan</th>
			<td><?php echo ucfirst($workshop->workshop_stemtest) ?></td>
		</tr>
		<tr>
			<th>Uitnodigingscode</th>
			<td><?php echo $workshop->workshop_stemtest_code ?></td>
		</tr>
	<?php } else { ?>
	<tr>
		<th>Stemtest</th>
		<td><?php echo ucfirst($workshop->workshop_stemtest) ?></td>
	</tr>
	<tr>
		<th>Stemtest code</th>
		<td><?php echo $workshop->workshop_stemtest_code ?></td>
	</tr>
	<tr>
		<th>Stemtest / intake prijs</th>
		<td><?php if(!empty($workshop->workshop_stemtest_prijs)) echo $workshop->workshop_stemtest_prijs.',00 euro'; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Stemtest / intake tekst</th>
		<td><?php if(!empty($workshop->workshop_stemtest_tekst)) echo $workshop->workshop_stemtest_tekst; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Aantal dagen korting op een workshop na afloop van stemtest</th>
		<td><?php echo $workshop->workshop_stemtest_dagen_korting_na_afloop ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th>Aanmelden tekst</th>
		<td><?php if(!empty($workshop->workshop_aanmelden_tekst)) echo $workshop->workshop_aanmelden_tekst; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Producten tekst</th>
		<td><?php if(!empty($workshop->workshop_producten_tekst)) echo $workshop->workshop_producten_tekst; else echo '...'; ?></td>
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
		<th>Uitgelichte afbeelding</th>
		<td>
			<?php if(sizeof($media_uitgelicht) > 0): ?>
				<table cellpadding="0" cellspacing="0" class="media">
					<?php foreach($media_uitgelicht as $bestand): ?>
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
		<td><?php if(!empty($workshop->meta_title)) echo $workshop->meta_title; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Meta description</th>
		<td><?php if(!empty($workshop->meta_description)) echo $workshop->meta_description; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Welkomstmail ingesteld</th>
		<td><?php if(!empty($workshop->workshop_welkomstmail)) echo 'Ja'; else echo 'Nee' ?></td>
	</tr>
	<tr>
		<th>Herinneringsmail ingesteld</th>
		<td><?php if(!empty($workshop->workshop_herinneringsmail)) echo 'Ja'; else echo 'Nee' ?></td>
	</tr>
	<tr>
        <th>Feedbackmail ingesteld</th>
        <td><?php if(!empty($workshop->workshop_feedbackmail)) echo 'Ja'; else echo 'Nee' ?></td>
    </tr>
    <tr>
        <th>in3 aan</th>
        <td><?php if($workshop->workshop_in3 == 1) echo 'Ja'; else echo 'Nee' ?></td>
    </tr>
    <tr>
        <th>Cursistenmodule aan</th>
        <td><?php if($workshop->volledige_cursistenmodule == 1) echo 'Ja'; else echo 'Nee' ?></td>
    </tr>
    <tr>
        <th>Workshop zichtbaar publiek</th>
        <td><?php if($workshop->workshop_zichtbaar_publiek == 1) echo 'Ja'; else echo 'Nee' ?></td>
    </tr>
    <tr>
        <th>Workshop zichtbaar cursist</th>
        <td><?php if($workshop->workshop_zichtbaar_cursist == 1) echo 'Ja'; else echo 'Nee' ?></td>
    </tr>
</table>
<div id="lessen">
	<h2>Lessen</h2>
	<p><a href="<?php echo base_url('cms/lessen/toevoegen/'.$workshop->workshop_ID) ?>" title="Les toevoegen">Les toevoegen</a></p>
	<?php if(sizeof($lessen) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="lessen">
			<tr>
				<th>Titel</th>
				<th>Locatie</th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
				<th class="verwijderen"></th>
			</tr>
			<?php foreach($lessen as $les): ?>
				<?php if($les->groep_ID): ?>
					<tr data-item="<?php echo $les->les_ID ?>">
						<td><a href="<?php echo base_url('cms/lessen/'.$les->les_ID) ?>" title="Les bekijken"><?php echo $les->les_titel ?></a></td>
						<td><a href="<?php echo base_url('cms/lessen/'.$les->les_ID) ?>" title="Les bekijken"><?php echo ucfirst($les->les_locatie) ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/lessen/'.$les->les_ID) ?>" title="Les bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/extra_wijzigen/'. $les->groep_ID . '/' .$les->les_ID) ?>" title="Les wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/lessen/verwijderen/'.$les->les_ID) ?>" title="Les verwijderen">Verwijderen</a></td>
					</tr>
				<?php else: ?>
					<tr data-item="<?php echo $les->les_ID ?>">
						<td><a href="<?php echo base_url('cms/lessen/'.$les->les_ID) ?>" title="Les bekijken"><?php echo $les->les_titel ?></a></td>
						<td><a href="<?php echo base_url('cms/lessen/'.$les->les_ID) ?>" title="Les bekijken"><?php echo ucfirst($les->les_locatie) ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/lessen/'.$les->les_ID) ?>" title="Les bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/wijzigen/'.$les->les_ID) ?>" title="Les wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/lessen/verwijderen/'.$les->les_ID) ?>" title="Les verwijderen">Verwijderen</a></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>
	<?php else: ?>
		<p>Er zijn geen lessen toegevoegd aan deze workshop.</p>
	<?php endif; ?>
</div>
<?php if($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online'): ?>
	<div id="groepen">
		<h2>Groepen</h2>
		<p><a href="<?php echo base_url('cms/groepen/toevoegen/'.$workshop->workshop_ID) ?>" title="Groep toevoegen">Groep toevoegen</a></p>
		<?php if(sizeof($groepen) > 0): ?>
			<table cellpadding="0" cellspacing="0" class="tabel">
				<tr>
					<th>Naam</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
				<?php foreach($groepen as $groep): ?>
					<tr <?php if($groep->groep_aanmelden == 'nee') echo 'class="concept"'; ?>>
						<td><a href="<?php echo base_url('cms/groepen/'.$groep->groep_ID) ?>" title="Groep bekijken"><?php echo $groep->groep_naam ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/groepen/'.$groep->groep_ID) ?>" title="Groep bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/groepen/wijzigen/'.$groep->groep_ID) ?>" title="Groep wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/groepen/verwijderen/'.$groep->groep_ID) ?>" title="Groep verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			<p>Er zijn geen groepen gekoppeld aan deze workshop.</p>
		<?php endif; ?>
	</div>
<?php else: ?>
	<div id="deelnemers">
	<h2><?php echo sizeof($deelnemers) ?> deelnemers</h2>
	<div id="deelnemer_toevoegen">
		<form method="post" action="<?php echo base_url('cms/workshops/deelnemer_toevoegen/'.$workshop->workshop_ID) ?>">
			<p><label for="item_deelnemer">Deelnemer</label>
				<select name="item_deelnemer" id="item_deelnemer">
					<option value="">Deelnemer selecteren</option>
					<?php foreach($alle_deelnemers as $deelnemer): ?>
						<option value="<?php echo $deelnemer->gebruiker_ID ?>"><?php echo $deelnemer->gebruiker_naam ?></option>
					<?php endforeach; ?>
				</select>
				<input type="submit" value="Deelnemer toevoegen"/></p>
		</form>
	</div>
	<?php if(sizeof($deelnemers) > 0): ?>
			<table cellpadding="0" cellspacing="0" class="tabel">
				<thead>
					<tr>
						<th>Naam</th>
						<th class="bekijken"></th>
						<th class="wijzigen"></th>
						<th class="verwijderen"></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($deelnemers as $item):
					?>
						<tr>
							<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
							<td class="bekijken"><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken">Bekijken</a></td>
							<td class="wijzigen"><a href="<?php echo base_url('cms/deelnemers/wijzigen/'.$item->gebruiker_ID) ?>" title="Deelnemer wijzigen">Wijzigen</a></td>
							<td class="verwijderen"><a href="<?php echo base_url('cms/deelnemers/afmelden/'.$item->aanmelding_ID) ?>" title="Deelnemer afmelden">Afmelden</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p>Er zijn nog geen aanmeldingen.</p>
	<?php endif; ?>
<?php endif; ?>
<div id="producten">
	<h2>Producten</h2>
	<p><a href="<?php echo base_url('cms/workshops/producten/'.$workshop->workshop_ID) ?>" title="Producten beheren">Producten beheren</a></p>
	<?php if(sizeof($producten) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th>Naam</th>
				<th>Wanneer beschikbaar</th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
				<th class="verwijderen"></th>
			</tr>
			<?php foreach($producten as $product): ?>
				<tr>
					<td><a href="<?php echo base_url('cms/producten/'.$product->product_ID) ?>" title="Product bekijken"><?php echo $product->product_naam ?></a></td>
					<td><?php if($product->wanneer_beschikbaar == 'altijd') { echo $product->wanneer_beschikbaar; } else { if($product->wanneer_beschikbaar == 'na'){ echo 'Na de workshop'; } else { echo '...'; }} ?></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/producten/'.$product->product_ID) ?>" title="Product bekijken">Bekijken</a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/producten/wijzigen/'.$product->product_ID) ?>" title="Product wijzigen">Wijzigen</a></td>
					<td class="verwijderen"><a href="<?php echo base_url('cms/producten/verwijderen/'.$product->product_ID) ?>" title="Product verwijderen">Verwijderen</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else: ?>
		<p>Er zijn geen producten gekoppeld aan deze workshop.</p>
	<?php endif; ?>
</div>