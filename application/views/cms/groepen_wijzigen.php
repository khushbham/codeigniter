<h1>Groep <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" <?php if($this->session->userdata('gebruiker_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'support') { ?> onsubmit="return confirm('Weet je zeker dat je dit wilt doen?')"; <?php } ?> action="<?php if($actie == 'toevoegen') echo base_url('cms/groepen/toevoegen/'); else echo base_url('cms/groepen/wijzigen/'.$item_ID); ?>">
		<p><label for="item_naam">Naam *</label><input type="text" name="item_naam" id="item_naam" value="<?php echo $item_naam ?>" /><span class="feedback"><?php echo $item_naam_feedback ?></span></p>
		<p><label for="item_titel">Titel *</label><input type="text" name="item_titel" id="item_titel" value="<?php echo $item_titel ?>" /><span class="feedback"><?php echo $item_titel_feedback ?></span></p>
		<p><label for="item_workshop">Workshop *</label>
			<select name="item_workshop" id="item_workshop">
				<option value="">Workshop selecteren</option>
				<option value="">---</option>
				<?php foreach($workshops as $workshop): ?>
					<option value="<?php echo $workshop->workshop_ID ?>" <?php if($workshop->workshop_ID == $item_workshop) echo 'selected'; ?>><?php echo $workshop->workshop_titel ?></option>
				<?php endforeach; ?>
			</select>
			<span class="feedback"><?php echo $item_workshop_feedback ?></span>
		</p>
		<p><label for="item_aanmelden">Aanmelden *</label><input type="radio" name="item_aanmelden" value="ja" <?php if($item_aanmelden == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="item_aanmelden" value="nee" <?php if($item_aanmelden == 'nee') echo 'checked'; ?> /> Nee</p>
		<p><label for="item_geautomatiseerde_mails">Geautomatiseerde mails/berichten *</label><input type="radio" name="item_geautomatiseerde_mails" value="1" <?php if($item_geautomatiseerde_mails == '1') echo 'checked'; ?> /> Aan <input type="radio" name="item_geautomatiseerde_mails" value="0" <?php if($item_geautomatiseerde_mails == '0') echo 'checked'; ?> /> Nee</p>
		<p><label for="item_feedback_mail">Feedbackmail *</label><input type="radio" name="item_feedback_mail" value="1" <?php if($item_feedback_mail == '1') echo 'checked'; ?> /> Aan <input type="radio" name="item_feedback_mail" value="0" <?php if($item_feedback_mail == '0') echo 'checked'; ?> /> Nee</p>
		<p><label for="item_datum_dag">Startdatum *</label><input type="text" name="item_datum_dag" id="item_datum_dag" class="datum_smal" value="<?php echo $item_datum_dag ?>" /><input type="text" name="item_datum_maand" id="item_datum_maand" class="datum_smal" value="<?php echo $item_datum_maand ?>" /><input type="text" name="item_datum_jaar" id="item_datum_jaar" class="datum_breed" value="<?php echo $item_datum_jaar ?>" /><span class="feedback"><?php echo $item_datum_feedback ?></span></p>
		<p><label for="item_actief_datum_dag">Actief datum </label><input type="text" name="item_actief_datum_dag" id="item_actief_datum_dag" class="datum_smal" value="<?php echo $item_actief_datum_dag ?>" /><input type="text" name="item_actief_datum_maand" id="item_actief_datum_maand" class="datum_smal" value="<?php echo $item_actief_datum_maand ?>" /><input type="text" name="item_actief_datum_jaar" id="item_actief_datum_jaar" class="datum_breed" value="<?php echo $item_actief_datum_jaar ?>" /><span class="feedback"><?php echo $item_actief_datum_feedback ?></span></p>
		<p><label for="item_archief_datum_dag">Archief datum </label><input type="text" name="item_archief_datum_dag" id="item_archief_datum_dag" class="datum_smal" value="<?php echo $item_archief_datum_dag ?>" /><input type="text" name="item_archief_datum_maand" id="item_archief_datum_maand" class="datum_smal" value="<?php echo $item_archief_datum_maand ?>" /><input type="text" name="item_archief_datum_jaar" id="item_archief_datum_jaar" class="datum_breed" value="<?php echo $item_archief_datum_jaar ?>" /><span class="feedback"><?php echo $item_archief_datum_feedback ?></span></p>
		<p><label for="item_min_gebruikers">Drempelwaarde aantal gebruikers</label><input type="text" name="item_min_gebruikers" id="item_min_gebruikers" value="<?php echo $item_min_gebruikers ?>" /></p>
		<p><label for="item_drempelwaarde_versturen">Drempelwaarde mail versturen van te voren</label><input type="text" name="item_drempelwaarde_versturen" id="item_drempelwaarde_versturen" value="<?php echo $item_drempelwaarde_versturen ?>" /></p>
		<p><label for="item_notities">Notities</label><textarea name="item_notities" id="item_notities"><?php echo $item_notities ?></textarea><span class="feedback"><?php echo $item_notities_feedback ?></span></p>
		<?php if($actie == 'wijzigen') { ?>
			<p><label for="item_downloadlinkmail">Downloadlink mail</label><?php if(empty($item_downloadlinkmail)) { ?> Nee | <a href="<?php echo base_url('cms/groepen/downloadlink_mail/'.$item_ID) ?>" title="Downloadlink mail toevoegen">Downloadlink mail toevoegen</a><?php } else { ?> Ja | <a href="<?php echo base_url('cms/groepen/downloadlink_mail/'.$item_ID) ?>" title="Voorbereidingsmail wijzigen.">Downloadlink mail wijzigen</a><?php } ?></p>
		<?php } ?>
		<p class="submit"><input type="submit" value="Groep <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/groepen/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?>
        <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
            <a href="<?php echo base_url('cms/groepen/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
        <?php endif; ?>
	</form>
</div>