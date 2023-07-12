<h1>Workshop <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" id="workshopForm" action="<?php if($actie == 'toevoegen') echo base_url('cms/workshops/toevoegen/'); else echo base_url('cms/workshops/wijzigen/'.$item_ID); ?>">
		<?php if ($actie == 'toevoegen') { ?>
			<p>
				<label for="item_kopie">Kopieer een workshop</label>
				<select name="item_kopie" id="item_kopie" onchange="getWorkshopInfo(value)">
					<option value="0">Selecteer workshop</option>
					<?php foreach($workshops as $workshop) { ?>
						<option value="<?php echo $workshop->workshop_ID?>" <?php if(!empty($item_kopie)) echo 'selected'; ?>><?php echo $workshop->workshop_titel ?></option>
					<?php } ?>
				</select>
			</p>
		<?php } ?>
		<p>
			<label for="item_type">Type *</label>
			<select name="item_type" id="item_type">
				<option value="">Selecteer workshop type</option>
				<option value="">---</option>
				<option value="groep" <?php if($item_type == 'groep') echo 'selected'; ?>>Groep</option>
				<option value="online" <?php if($item_type == 'online') echo 'selected'; ?>>Online</option>
				<option value="individueel" <?php if($item_type == 'individueel') echo 'selected'; ?>>Individueel</option>
			</select>
			<span class="feedback"><?php echo $item_type_feedback ?></span>
		</p>
        <p>
            <label for="item_niveau">Niveau *</label>
            <select name="item_niveau" id="item_niveau">
                <option value="">Selecteer workshop Niveau</option>
                <option value="0">---</option>
                <option value="1" <?php if($item_niveau == '1') echo 'selected'; ?>>Niveau 1: Stemtest</option>
                <option value="2" <?php if($item_niveau == '2') echo 'selected'; ?>>Niveau 2: Kenningsmakingsworkshops & Introductieworkshop localhost</option>
                <option value="3" <?php if($item_niveau == '3') echo 'selected'; ?>>Niveau 3: Basisworkshop (Leiden & Utrecht) en Bootcamp</option>
                <option value="4" <?php if($item_niveau == '4') echo 'selected'; ?>>Niveau 4: Animatieworkshop / Specialty Workshops</option>
                <option value="5" <?php if($item_niveau == '5') echo 'selected'; ?>>Niveau 5: Vervolgworkshops</option>
            </select>
            <span class="feedback"><?php echo $item_niveau_feedback ?></span>
        </p>
		<p>
            <label for="item_soort">Soort *</label>
            <select name="item_soort" id="item_niveau">
                <option value="normaal" <?php if($item_soort == 'normaal') echo 'selected'; ?>>Normaal</option>
                <option value="uitgebreid" <?php if($item_soort == 'uitgebreid') echo 'selected'; ?>>Uitgebreid</option>
            </select>
        </p>
		<p>
			<label for="item_specialty">Specialty *</label>
			<input type="radio" name="item_specialty" value="ja" <?php if($item_specialty == 'ja') echo 'checked'; ?> /> Ja
			<input type="radio" name="item_specialty" value="nee" <?php if($item_specialty == 'nee') echo 'checked'; ?> /> Nee
			<span class="feedback"><?php echo $item_specialty_feedback ?></span>
		</p>
		<p>
			<label for="item_gepubliceerd">Publiceren *</label>
			<input type="radio" name="item_gepubliceerd" value="ja" <?php if($item_gepubliceerd == 'ja') echo 'checked'; ?> /> Ja
			<input type="radio" name="item_gepubliceerd" value="nee" <?php if($item_gepubliceerd == 'nee') echo 'checked'; ?> /> Nee
			<span class="feedback"><?php echo $item_gepubliceerd_feedback ?></span>
		</p>
        <p>
            <label for="item_uitgelicht">Uitgelicht *</label>
            <input type="radio" name="item_uitgelicht" value="ja" <?php if($item_uitgelicht == 'ja') echo 'checked'; ?> /> Ja
            <input type="radio" name="item_uitgelicht" value="nee" <?php if($item_uitgelicht == 'nee') echo 'checked'; ?> /> Nee
            <span class="feedback"><?php echo $item_uitgelicht_feedback ?></span>
        </p>
		<p>
			<label for="item_grootte_zichtbaar">Groepsgrootte zichtbaar *</label>
			<input type="radio" name="item_grootte_zichtbaar" value="1" <?php if($item_grootte_zichtbaar == '1') echo 'checked'; ?> /> Ja
			<input type="radio" name="item_grootte_zichtbaar" value="0" <?php if($item_grootte_zichtbaar == '0') echo 'checked'; ?> /> Nee
		</p>
		<p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
		<p><label for="item_ondertitel">Ondertitel *</label><input type="text" name="item_ondertitel" id="item_ondertitel" value="<?php echo $item_ondertitel ?>" /><span class="feedback"><?php echo $item_ondertitel_feedback ?></span></p>
		<p><label for="item_url">URL *</label><input type="text" name="item_url" id="item_url" value="<?php echo $item_url ?>" /><span class="feedback"><?php echo $item_url_feedback ?></span></p>
		<p><label for="item_afkorting">Afkorting *</label><input type="text" name="item_afkorting" id="item_afkorting" value="<?php echo $item_afkorting ?>" /><span class="feedback"><?php echo $item_afkorting_feedback ?></span></p>
		<p><label for="item_locatie">Locatie</label><input type="text" name="item_locatie" id="item_locatie" value="<?php echo $item_locatie ?>" /><span class="feedback"><?php echo $item_locatie_feedback ?></span></p>
		<p><label for="item_inleiding">Inleiding *</label><textarea name="item_inleiding" id="item_inleiding"><?php echo $item_inleiding ?></textarea><span class="feedback"><?php echo $item_inleiding_feedback ?></span></p>
        <p>
            <label for="item_toevoegen">Voeg een nieuw item toe</label>
            <select name="item_toevoegen" id="item_toevoegen">
                <option value="0">Selecteer een nieuw item</option>
                <option value="1">Tab</option>
                <option value="2">Omlijning</option>
                <option value="3">Blauwe achtergrond</option>
                <option value="4">Vinkje</option>
                <option value="5">Link</option>
                <option value="6">Call to action button</option>
            </select>
        </p>
        <p class="submit"><input type="button" class="add_item" onclick="AddItem()" value="Item toevoegen" /></p>
		<p><label for="item_beschrijving">Beschrijving</label><textarea name="item_beschrijving" id="item_beschrijving" class="opmaak"><?php echo $item_beschrijving ?></textarea><span class="feedback"><?php echo $item_beschrijving_feedback ?></span></p>
		<p><label for="item_cursistenmodule_tekst">Cursistenmodule tekst</label><textarea name="item_cursistenmodule_tekst" id="item_cursistenmodule_tekst" class="opmaak"><?php echo $item_cursistenmodule_tekst ?></textarea></p>
		<p><label for="item_prijs">Prijs *</label><input type="text" name="item_prijs" id="item_prijs" value="<?php echo $item_prijs ?>" /><span class="feedback"><?php echo $item_prijs_feedback ?></span></p>
		<p><label for="item_startdatum">Startdatum</label><input type="text" name="item_startdatum_dag" id="item_startdatum_dag" class="datum_smal" value="<?php echo $item_startdatum_dag ?>" /><input type="text" name="item_startdatum_maand" id="item_startdatum_maand" class="datum_smal" value="<?php echo $item_startdatum_maand ?>" /><input type="text" name="item_startdatum_jaar" id="item_startdatum_jaar" class="datum_breed" value="<?php echo $item_startdatum_jaar ?>" /><span class="feedback"><?php echo $item_startdatum_feedback ?></span></p>
		<p><label for="item_frequentie">Frequentie</label><input type="text" name="item_frequentie" id="item_frequentie" value="<?php echo $item_frequentie ?>" /><span class="feedback"><?php echo $item_frequentie_feedback ?></span></p>
		<p><label for="item_duur">Duur *</label><textarea name="item_duur" id="item_duur"><?php echo $item_duur ?></textarea><span class="feedback"><?php echo $item_duur_feedback ?></span></p>
		<p><label for="item_capaciteit">Capaciteit *</label><input type="text" name="item_capaciteit" id="item_capaciteit" value="<?php echo $item_capaciteit ?>" /><span class="feedback"><?php echo $item_capaciteit_feedback ?></span></p>
		<p><label for="item_toelatingseisen">Toelatingseisen</label><textarea name="item_toelatingseisen" id="item_toelatingseisen"><?php echo $item_toelatingseisen ?></textarea><span class="feedback"><?php echo $item_toelatingseisen_feedback ?></span></p>
		<p><label for="item_inclusief">Inclusief</label><textarea name="item_inclusief" id="item_inclusief"><?php echo $item_inclusief ?></textarea><span class="feedback"><?php echo $item_inclusief_feedback ?></span></p>
		<p><label for="item_exclusief">Exclusief</label><textarea name="item_exclusief" id="item_exclusief"><?php echo $item_exclusief ?></textarea><span class="feedback"><?php echo $item_exclusief_feedback ?></span></p>
		<p>
			<label for="item_stemtest_ja">Stemtest / Uitnodigingscode</label>
			<input type="radio" name="item_stemtest" id="item_stemtest_ja" value="ja" <?php if($item_stemtest == 'ja') echo 'checked'; ?> /> Ja
			<input type="radio" name="item_stemtest" id="item_stemtest_nee" value="nee" <?php if($item_stemtest == 'nee') echo 'checked'; ?> /> Nee
			<span class="feedback"><?php echo $item_stemtest_feedback ?></span>
		</p>
		<p><label for="item_stemtest_code">Stemtest / Uitnodigings code</label><input type="text" name="item_stemtest_code" id="item_stemtest_code" value="<?php echo $item_stemtest_code ?>" /><span class="feedback"><?php echo $item_stemtest_code_feedback ?></span></p>
		<p><label for="item_stemtest_prijs">Stemtest / intake prijs</label><input type="text" name="item_stemtest_prijs" id="item_stemtest_prijs" value="<?php echo $item_stemtest_prijs ?>" /><span class="feedback"><?php echo $item_stemtest_prijs_feedback ?></span></p>
		<p><label for="item_stemtest_tekst">Stemtest / intake tekst</label><textarea name="item_stemtest_tekst" id="item_stemtest_tekst" class="opmaak"><?php echo $item_stemtest_tekst ?></textarea><span class="feedback"><?php echo $item_stemtest_tekst_feedback ?></span></p>
		<p><label for="item_stemtest_dagen_korting_na_afloop">Aantal dagen korting op een workshop na afloop van stemtest</label><input type="text" name="item_stemtest_dagen_korting_na_afloop" id="item_stemtest_dagen_korting_na_afloop" value="<?php echo $item_stemtest_dagen_korting_na_afloop ?>" /><span class="feedback"><?php echo $item_stemtest_dagen_korting_na_afloop_feedback ?></span></p>
		<p><label for="item_aanmelden_tekst">Aanmelden tekst</label><textarea name="item_aanmelden_tekst" id="item_aanmelden_tekst" class="opmaak"><?php echo $item_aanmelden_tekst ?></textarea><span class="feedback"><?php echo $item_aanmelden_tekst_feedback ?></span></p>
		<p><label for="item_producten_tekst">Producten tekst</label><textarea name="item_producten_tekst" id="item_producten_tekst" class="opmaak"><?php echo $item_producten_tekst ?></textarea><span class="feedback"><?php echo $item_producten_tekst_feedback ?></span></p>
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
					<p><a href="#koppelen" title="Media koppelen aan les" class="koppelen" data-soort="media">Media koppelen</a> | <a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen aan mediabibliotheek (opent een nieuw tabblad / venster)" target="_blank">Media toevoegen</a></p>
					<input type="hidden" name="item_media" id="item_media" value="<?php echo $item_media ?>" data-aantal="1" />
				</div>
			</div>
		</div>

		<div id="media_uitgelicht">
			<div id="media_label">
				<p><label for="item_media_uitgelicht">Uitgelichte afbeelding</label></p>
			</div>
			<div id="media_container">
				<div id="media_lijst">
					<table cellpadding="0" cellspacing="0">
						<?php $item_media_uitgelicht = ''; ?>
						<?php if($media_uitgelicht > 0): foreach($media_uitgelicht as $item): ?>
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
					 <p><a href="#koppelen" title="Media koppelen aan les" class="koppelen" data-soort="media_uitgelicht">Media koppelen</a> | <a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen aan mediabibliotheek (opent een nieuw tabblad / venster)" target="_blank">Media toevoegen</a></p>
					 <input type="hidden" name="item_media_uitgelicht" id="item_media_uitgelicht" value="<?php echo $item_media_uitgelicht ?>" data-aantal="1" />
				</div>
			</div>
		</div>

		<p><label for="item_meta_title">Meta title</label><input type="text" name="item_meta_title" id="item_meta_title" value="<?php echo $item_meta_title ?>" maxlength="60" /><span class="feedback"><?php echo $item_meta_title_feedback ?></span></p>
		<p><label for="item_meta_description">Meta description</label><input type="text" name="item_meta_description" id="item_meta_description" value="<?php echo $item_meta_description ?>" maxlength="160" /><span class="feedback"><?php echo $item_meta_description_feedback ?></span></p>
		<?php if($actie == 'wijzigen') { ?>
			<p><label for="item_welkomstmail">Welkomstmail ingesteld</label><?php if(empty($item_welkomstmail)) { ?> Nee | <a href="<?php echo base_url('cms/workshops/welkomstmail/'.$item_ID) ?>" title="Welkomstmail toevoegen">Welkomstmail toevoegen</a><?php } else { ?> Ja | <a href="<?php echo base_url('cms/workshops/welkomstmail/'.$item_ID) ?>" title="welkomstmail wijzigen.">Welkomstmail wijzigen</a><?php } ?></p>
			<p><label for="item_herinneringsmail">Herinneringsmail ingesteld</label><?php if(empty($item_herinneringsmail)) { ?> Nee | <a href="<?php echo base_url('cms/workshops/herinneringsmail/'.$item_ID) ?>" title="Herinneringsmail toevoegen">Herinneringsmail toevoegen</a><?php } else { ?> Ja | <a href="<?php echo base_url('cms/workshops/herinneringsmail/'.$item_ID) ?>" title="herinneringsmail wijzigen.">Herinneringsmail wijzigen</a><?php } ?></p>
            <p><label for="item_feedbackmail">Feedbackmail ingesteld</label><?php if(empty($item_feedbackmail)) { ?> Nee | <a href="<?php echo base_url('cms/workshops/feedbackmail/'.$item_ID) ?>" title="Feedbackmail toevoegen">Feedbackmail toevoegen</a><?php } else { ?> Ja | <a href="<?php echo base_url('cms/workshops/feedbackmail/'.$item_ID) ?>" title="feedbackmail wijzigen.">Feedbackmail wijzigen</a><?php } ?></p>
		<?php } ?>
        <p>
            <label for="item_in3">in3 aan *</label>
            <input type="radio" name="item_in3" value="1" <?php if($item_in3 == 1) echo 'checked'; ?> /> Ja
            <input type="radio" name="item_in3" value="0" <?php if($item_in3 == 0) echo 'checked'; ?> /> Nee
            <span class="feedback"><?php echo $item_in3_feedback ?></span>
        </p>
        <p>
            <label for="item_cursistenmodule">Volledige cursistenmodule *</label>
            <input type="radio" name="item_cursistenmodule" value="1" <?php if($item_cursistenmodule == 1) echo 'checked'; ?> /> Ja
            <input type="radio" name="item_cursistenmodule" value="0" <?php if($item_cursistenmodule == 0) echo 'checked'; ?> /> Nee
            <span class="feedback"><?php echo $item_cursistenmodule_feedback ?></span>
        </p>
        <p>
            <label for="item_zichtbaar_publiek">Workshop zichtbaar publiek*</label>
            <input type="radio" name="item_zichtbaar_publiek" value="1" <?php if($item_zichtbaar_publiek == 1) echo 'checked'; ?> /> Ja
            <input type="radio" name="item_zichtbaar_publiek" value="0" <?php if($item_zichtbaar_publiek == 0) echo 'checked'; ?> /> Nee
            <span class="feedback"><?php echo $item_zichtbaar_publiek_feedback ?></span>
        </p>
        <p>
            <label for="item_zichtbaar_cursist">Workshop zichtbaar cursist*</label>
            <input type="radio" name="item_zichtbaar_cursist" value="1" <?php if($item_zichtbaar_cursist == 1) echo 'checked'; ?> /> Ja
            <input type="radio" name="item_zichtbaar_cursist" value="0" <?php if($item_zichtbaar_cursist == 0) echo 'checked'; ?> /> Nee
            <span class="feedback"><?php echo $item_zichtbaar_cursist_feedback ?></span>
        </p>


        <p class="submit"><input type="submit" value="Workshop <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/workshops/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/workshops/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>
