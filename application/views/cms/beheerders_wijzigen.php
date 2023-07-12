<h1>Beheerder <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/beheerders/toevoegen/'); else echo base_url('cms/beheerders/wijzigen/'.$item_ID); ?>">
		<p>
			<label for="item_rechten">Rechten</label>
			<select onchange="showDocentfields(this.value);" name="item_rechten" id="item_rechten">
				<option value="admin" <?php if($item_rechten == 'admin') echo 'selected'; ?>>Admin</option>
				<option value="support" <?php if($item_rechten == 'support') echo 'selected'; ?>>Support</option>
                <option value="opleidingsmedewerker" <?php if($item_rechten == 'opleidingsmedewerker') echo 'selected'; ?>>Opleidingsmedewerker</option>
                <option value="contentmanager" <?php if($item_rechten == 'contentmanager') echo 'selected'; ?>>Contentmanager</option>
			</select>
			<span class="feedback"><?php echo $item_rechten_feedback ?></span>
		</p>
		<p><label for="item_bedrijfsnaam">Bedrijfsnaam</label><input type="text" name="item_bedrijfsnaam" id="item_bedrijfsnaam" value="<?php echo $item_bedrijfsnaam ?>" /><span class="feedback"><?php echo $item_bedrijfsnaam_feedback ?></span></p>
		<p><label for="item_voornaam">Voornaam *</label><input type="text" name="item_voornaam" id="item_voornaam" value="<?php echo $item_voornaam ?>" /><span class="feedback"><?php echo $item_voornaam_feedback ?></span></p>
		<p><label for="item_tussenvoegsel">Tussenvoegsel</label><input type="text" name="item_tussenvoegsel" id="item_tussenvoegsel" value="<?php echo $item_tussenvoegsel ?>" /><span class="feedback"><?php echo $item_tussenvoegsel_feedback ?></span></p>
		<p><label for="item_achternaam">Achternaam *</label><input type="text" name="item_achternaam" id="item_achternaam" value="<?php echo $item_achternaam ?>" /><span class="feedback"><?php echo $item_achternaam_feedback ?></span></p>
		<p><label for="item_geslacht">Geslacht</label><input type="radio" name="item_geslacht" value="man" <?php if($item_geslacht == 'man') echo 'checked'; ?> /> Man <input type="radio" name="item_geslacht" value="vrouw" <?php if($item_geslacht == 'vrouw') echo 'checked'; ?> /> Vrouw <span class="feedback"><?php echo $item_geslacht_feedback ?></span></p>
		<p><label for="item_geboortedatum">Geboortedatum</label><input type="text" name="item_geboortedatum_dag" id="item_geboortedatum" value="<?php echo $item_geboortedatum_dag ?>" maxlength="2" class="datum_smal" /><input type="text" name="item_geboortedatum_maand" value="<?php echo $item_geboortedatum_maand ?>" maxlength="2" class="datum_smal" /><input type="text" name="item_geboortedatum_jaar" value="<?php echo $item_geboortedatum_jaar ?>" maxlength="4" class="datum_breed" /><span class="feedback"><?php echo $item_geboortedatum_feedback ?></span></p>
		<p><label for="item_adres">Adres</label><input type="text" name="item_adres" id="item_adres" value="<?php echo $item_adres ?>" /><span class="feedback"><?php echo $item_adres_feedback ?></span></p>
		<p><label for="item_postcode">Postcode</label><input type="text" name="item_postcode_cijfers" id="item_postcode" value="<?php echo $item_postcode_cijfers ?>" maxlength="4" class="postcode_breed" /><input type="text" name="item_postcode_letters" value="<?php echo $item_postcode_letters ?>" maxlength="2" class="postcode_smal" /><span class="feedback"><?php echo $item_postcode_feedback ?></span></p>
		<p><label for="item_plaats">Plaats</label><input type="text" name="item_plaats" id="item_plaats" value="<?php echo $item_plaats ?>" /><span class="feedback"><?php echo $item_plaats_feedback ?></span></p>
		<p><label for="item_telefoonnummer">Telefoonnummer</label><input type="text" name="item_telefoonnummer" id="item_telefoonnummer" value="<?php echo $item_telefoonnummer ?>" /><span class="feedback"><?php echo $item_telefoonnummer_feedback ?></span></p>
		<p><label for="item_mobiel">Mobiel</label><input type="text" name="item_mobiel" id="item_mobiel" value="<?php echo $item_mobiel ?>" /><span class="feedback"><?php echo $item_mobiel_feedback ?></span></p>
		<p><label for="item_emailadres">E-mailadres *</label><input type="text" name="item_emailadres" id="item_emailadres" value="<?php echo $item_emailadres ?>" /><span class="feedback"><?php echo $item_emailadres_feedback ?></span></p>
		<p><label for="item_notities">Notities (intern)</label><textarea name="item_notities" id="item_notities"><?php echo $item_notities ?></textarea><span class="feedback"><?php echo $item_notities_feedback ?></span></p>
		<p><label for="item_instelling_anoniem">Instelling anoniem</label><input type="radio" name="item_instelling_anoniem" value="ja" <?php if($item_instelling_anoniem == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="item_instelling_anoniem" value="nee" <?php if($item_instelling_anoniem == 'nee') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_instelling_anoniem_feedback ?></span></p>
		<p><label for="item_instelling_email_updates">Instelling e-mail updates</label><input type="radio" name="item_instelling_email_updates" value="ja" <?php if($item_instelling_email_updates == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="item_instelling_email_updates" value="nee" <?php if($item_instelling_email_updates == 'nee') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_instelling_email_updates_feedback ?></span></p>
        <div id="opleidingsmedewerker">
            <p><label for="item_naam">Titel *</label><input type="text" name="item_naam" id="item_naam" value="<?php echo $item_naam ?>" /><span class="feedback"><?php echo $item_naam_feedback ?></span></p>
            <p><label for="item_inleiding">Inleiding *</label><textarea name="item_inleiding" id="item_inleiding"><?php echo $item_inleiding ?></textarea><span class="feedback"><?php echo $item_inleiding_feedback ?></span></p>
            <p><label for="item_tekst">Bericht *</label><textarea name="item_tekst" id="item_tekst" class="opmaak"><?php echo $item_tekst ?></textarea><span class="feedback"><?php echo $item_tekst_feedback ?></span></p>

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
        </div>
        <p class="submit"><input type="submit" value="Beheerder <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/beheerders/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/beheerders/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>