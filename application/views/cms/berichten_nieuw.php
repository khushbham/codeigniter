<?php if(!$item_verzonden): ?>
	<div id="berichten" style="padding: 0 0 110px;">
		<form method="post" action="<?php echo current_url() ?>">
			<?php if($item_beantwoorden && !$item_doorsturen) { ?>
				<h1>Bericht beantwoorden</h1>
				<div id="buttons">
					<p>
						<input type="submit" name="item_verzenden" id="item_verzenden" value="Verzenden" class="belangrijk" />
						<a href="<?php echo base_url('cms/berichten/'.$item_ID) ?>" title="Annuleren">Annuleren</a>
					</p>
				</div>
				<p><label>Aan</label><?php echo $bericht->gebruiker_naam ?></p>
			<?php } else if (!$item_doorsturen && !$item_beantwoorden) { ?>
				<h1>Nieuw bericht</h1>
				<div id="buttons">
					<p>
						<input type="submit" name="item_verzenden" id="item_verzenden" value="Verzenden" class="belangrijk" />
						<a href="<?php echo base_url('cms/berichten') ?>" title="Annuleren">Annuleren</a>
					</p>
				</div>
				<?php if($item_ontvanger_type == 'deelnemerslijst'): ?>
					<p><label for="item_ontvanger">Aan</label>Deelnemerslijst (<?php echo sizeof($item_ontvangers) ?>)</p>
					<input type="hidden" name="item_ontvanger_type" value="deelnemerslijst" />
					<input type="hidden" name="item_ontvanger" value="<?php echo $item_ontvanger ?>" />
				<?php else: ?>
					<p>
						<label for="item_ontvanger">Aan</label>
						<select multiple="multiple" name="item_ontvanger[]" id="item_ontvanger" required>
							<option value="">Selecteer een ontvanger</option>

							<!-- Beheerders -->

							<?php if(sizeof($beheerders) > 0): ?>
								<optgroup label="Beheerders">
									<?php foreach($beheerders as $beheerder):?>
											<option value="<?php echo $beheerder->gebruiker_ID ?>"><?php echo $beheerder->gebruiker_naam ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>

							<!-- Docent -->

							<?php if(sizeof($docenten) > 0): ?>
								<optgroup label="Docenten">
								<option value="1" <?php if($item_ontvanger == '1') echo 'selected="selected"'; ?>>Barnier Geerling</option>
									<?php foreach($docenten as $docent):?>
										<?php if($docent->gebruiker_status == 'actief'): ?>
											<option value="<?php echo $docent->gebruiker_ID ?>"><?php echo $docent->gebruiker_naam ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>


							<!-- Groepen -->

							<?php if($this->session->userdata('beheerder_rechten') && sizeof($groepen) > 0): ?>
								<optgroup label="Groepen">
									<?php foreach($groepen as $groep): ?>
										<option value="<?php echo 'groep-'.$groep->groep_ID ?>" <?php if($item_ontvanger == 'groep-'.$groep->groep_ID) echo 'selected="selected"'; ?>><?php echo $groep->groep_naam ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>

							<!-- Cursisten -->

							<?php if(sizeof($cursisten) > 0): ?>
								<optgroup label="Cursisten">
									<?php foreach($cursisten as $cursist): ?>
										<option value="<?php echo $cursist->gebruiker_ID ?>" <?php if($cursist->gebruiker_ID == $item_ontvanger) echo 'selected="selected"'; ?>><?php echo $cursist->gebruiker_voornaam.' '.$cursist->gebruiker_tussenvoegsel.''.$cursist->gebruiker_achternaam ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>

						</select>
					</p>
				<?php endif; ?>
			<?php } else { ?>
				<h1>Doorsturen</h1>
				<div id="buttons">
					<p>
						<input type="submit" name="item_verzenden" id="item_verzenden" value="Verzenden" class="belangrijk" />
						<a href="<?php echo base_url('cms/berichten') ?>" title="Annuleren">Annuleren</a>
					</p>
				</div>
				<?php if($item_ontvanger_type == 'deelnemerslijst'): ?>
					<p><label for="item_ontvanger">Aan</label>Deelnemerslijst (<?php echo sizeof($item_ontvangers) ?>)</p>
					<input type="hidden" name="item_ontvanger_type" value="deelnemerslijst" />
					<input type="hidden" name="item_ontvanger" value="<?php echo $item_ontvanger ?>" />
				<?php else: ?>
					<p>
						<label for="item_ontvanger">Aan</label>
						<select multiple="multiple" name="item_ontvanger[]" id="item_ontvanger">
							<option value="">Selecteer een ontvanger</option>

							<!-- Beheerders -->

							<?php if(sizeof($beheerders) > 0): ?>
								<optgroup label="Beheerders">
									<?php foreach($beheerders as $beheerder):?>
											<option value="<?php echo $beheerder->gebruiker_ID ?>"><?php echo $beheerder->gebruiker_naam ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>

							<!-- Docent -->

							<?php if(sizeof($docenten) > 0): ?>
								<optgroup label="Docenten">
								<option value="1" <?php if($item_ontvanger == '1') echo 'selected="selected"'; ?>>Barnier Geerling</option>
									<?php foreach($docenten as $docent):?>
										<?php if($docent->gebruiker_status == 'actief'): ?>
											<option value="<?php echo $docent->gebruiker_ID ?>"><?php echo $docent->gebruiker_naam ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>


							<!-- Groepen -->

							<?php if($this->session->userdata('beheerder_rechten') && sizeof($groepen) > 0): ?>
								<optgroup label="Groepen">
									<?php foreach($groepen as $groep): ?>
										<option value="<?php echo 'groep-'.$groep->groep_ID ?>" <?php if($item_ontvanger == 'groep-'.$groep->groep_ID) echo 'selected="selected"'; ?>><?php echo $groep->groep_naam ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>

							<!-- Cursisten -->

							<?php if(sizeof($cursisten) > 0): ?>
								<optgroup label="Cursisten">
									<?php foreach($cursisten as $cursist): ?>
										<option value="<?php echo $cursist->gebruiker_ID ?>" <?php if($cursist->gebruiker_ID == $item_ontvanger) echo 'selected="selected"'; ?>><?php echo $cursist->gebruiker_voornaam.' '.$cursist->gebruiker_tussenvoegsel.''.$cursist->gebruiker_achternaam ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>

						</select>
					</p>
				<?php endif; ?>
			<?php } ?>
			<p><label for="item_onderwerp">Onderwerp</label><input type="text" name="item_onderwerp" id="item_onderwerp" value="<?php echo $item_onderwerp ?>" autocomplete="off" required /></p>
            <p>
                <label for="item_templates">Template</label>
                <select name="item_templates" id="item_templates" onchange="update_template()">
                    <option value="---">Selecteer een template</option>
                    <option value="---">---</option>
                    <?php if(sizeof($templates) > 0) { ?>
                        <?php foreach($templates as $template) { ?>
                            <option value="<?php echo $template->template_ID ?>"><?php echo $template->template_titel ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </p>
            <p><label for="bericht_no_reply">No reply</label><input type="checkbox" name="bericht_no_reply" id="bericht_no_reply" <?php if($bericht_no_reply) echo 'checked'; ?> /></p>
			<p><label for="item_tekst" style="width: inherit">Tekst <span style="font-weight: normal; font-style: italic">(Je kunt altijd de tags [voornaam] en [achternaam] gebruiken. Voor groepen zijn ook [startdatum] en [workshop] beschikbaar.)</span></label><textarea name="item_tekst" id="item_tekst" class="opmaak_simpel"><?php echo $item_tekst ?></textarea></p>
            <br>
            <div id="media">
                <div id="media_label">
                    <p><label for="item_media">Media</label></p>
                </div>
                <div id="media_container">
                    <div id="media_lijst">
                        <table cellpadding="0" cellspacing="0">
                            <?php $item_media = ''; ?>
                            <?php if($bericht_media > 0): foreach($bericht_media as $item): ?>
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
                        <p><a href="#koppelen" title="Media koppelen aan bericht" class="koppelen" data-soort="media" data-welkomstmail="true">Media koppelen</a> | <a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen aan mediabibliotheek (opent een nieuw tabblad / venster)" target="_blank">Media toevoegen</a></p>
                        <input type="hidden" name="item_media" id="item_media" value="<?php echo $item_media ?>" data-aantal="0" />
                    </div>
                </div>
            </div>
            <div class="templates">
                <p>
                <div>
					<span>
						<label for="template_naam"><b>Template naam</b></label>
						<input type="text" name="template_naam" id="template_naam" value=""/>
					</span>
                    <input id="template_opslaan" type="button" value="Template opslaan" onclick="template_aanpassen()"/>
                </div> <?php if (!empty($template_feedback)) echo '<div class="Template_feedback" style="float: right;">'. $template_feedback .'</div>'; ?>
                </p>
            </div>
		</form>
	</div>
	<br>
<?php else: ?>
	<div id="item_verzonden">
		<h1>Bericht verzonden</h1>
			<p>Je bericht <strong><?php echo $item_onderwerp ?></strong> is succesvol verzonden aan de geselecteerde gebruikers.</p>
		<p><a href="<?php echo base_url('cms/berichten') ?>" title="Naar alle berichten">Naar alle berichten</a></p>
	</div>
<?php endif; ?>