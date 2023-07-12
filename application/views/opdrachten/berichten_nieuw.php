<?php if(!$item_verzonden): ?>
	<div id="berichten">
		<form method="post" action="<?php echo current_url() ?>">
			<?php if($item_beantwoorden): ?>
				<h1>Bericht beantwoorden</h1>
				<div id="buttons">
					<p>
						<input type="submit" name="item_verzenden" id="item_verzenden" value="Verzenden" class="belangrijk" />
						<a href="<?php echo base_url('opdrachten/berichten/'.$item_ID) ?>" title="Annuleren">Annuleren</a>
					</p>
				</div>
				<p><label>Aan</label><?php echo $bericht->gebruiker_naam ?></p>
			<?php else: ?>
				<h1>Nieuw bericht</h1>
				<div id="buttons">
					<p>
						<input type="submit" name="item_verzenden" id="item_verzenden" value="Verzenden" class="belangrijk" />
						<a href="<?php echo base_url('opdrachten/berichten') ?>" title="Annuleren">Annuleren</a>
					</p>
				</div>
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

						<optgroup label="Docenten">
							<?php foreach($docenten as $docent):?>
								<?php if($docent->gebruiker_status == 'actief' && $docent->docent_berichten_aan == 'ja'): ?>
								<option value="<?php echo $docent->gebruiker_ID ?>"><?php echo $docent->gebruiker_naam ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</optgroup>


						<!-- Groepen -->

						<?php if($this->session->userdata('beheerder_rechten') && sizeof($groepen) > 0): ?>
							<optgroup label="Groepen">
								<?php foreach($groepen as $groep): ?>
									<option value="<?php echo 'groep-'.$groep->groep_ID ?>" <?php if($item_ontvanger == 'groep-'.$groep->groep_ID) echo 'selected="selected"'; ?>><?php echo $groep->groep_naam ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endif; ?>


						<!-- Deelnemers -->

						<?php if(sizeof($medecursisten) > 0): ?>
							<optgroup label="Medecursisten">
								<?php foreach($medecursisten as $deelnemer): ?>
									<option value="<?php echo $deelnemer->gebruiker_ID ?>" <?php if($deelnemer->gebruiker_ID == $item_ontvanger) echo 'selected="selected"'; ?>><?php echo $deelnemer->gebruiker_voornaam.' '.$deelnemer->gebruiker_tussenvoegsel.''.$deelnemer->gebruiker_achternaam ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endif; ?>

						</select>
					</p>
			<?php endif; ?>
			<p><label for="item_onderwerp">Onderwerp</label><input type="text" name="item_onderwerp" id="item_onderwerp" value="<?php echo $item_onderwerp ?>" autocomplete="off" required/></p>
			<p><label for="item_tekst">Tekst</label><textarea name="item_tekst" id="item_tekst" class="opmaak_simpel"><?php echo $item_tekst ?></textarea></p>
            <?php if($bericht_media > 0): ?>
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
                        <input type="hidden" name="item_media" id="item_media" value="<?php echo $item_media ?>" data-aantal="0" />
                    </div>
                </div>
            </div>
        <?php endif; ?>
		</form>
	</div>
<?php else: ?>
	<div id="item_verzonden">
		<h1>Bericht verzonden</h1>
		<?php if(isset($item_ontvanger_type) && $item_ontvanger_type == 'groep'): ?>
			<p>Je bericht <strong><?php echo $item_onderwerp ?></strong> is succesvol verzonden naar de groep <strong><?php echo $item_groep->groep_naam ?></strong>.</p>
		<?php else: ?>
			<p>Je bericht <strong><?php echo $item_onderwerp ?></strong> is succesvol verzonden naar de geselecteerde gebruikers</strong>.</p>
		<?php endif; ?>
		<p><a href="<?php echo base_url('opdrachten/berichten') ?>" title="Naar alle berichten">Naar alle berichten</a></p>
	</div>
<?php endif; ?>