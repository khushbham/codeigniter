<h1>Afspraak wijzigen</h1>
<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Deelnemer</th>
		<td><?php echo $afspraak->gebruiker_naam ?></td>
	</tr>
	<tr>
		<th>Telefoonnummer</th>
		<td><?php if(empty($afspraak->gebruiker_telefoonnummer)) echo '-'; else echo $afspraak->gebruiker_telefoonnummer ?></td>
	</tr>
	<tr>
		<th>Mobiel</th>
		<td><?php if(empty($afspraak->gebruiker_mobiel)) echo '-'; else echo $afspraak->gebruiker_mobiel ?></td>
	</tr>
	<tr>
		<th>E-mailadres</th>
		<td><?php echo $afspraak->gebruiker_emailadres ?></td>
	</tr>
</table>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php echo base_url('cms/afspraken/'.$item_ID); ?>">
		<h2><strong>Afspraak</strong></h2>
		<p><label for="item_datum_dag">Datum *</label><input type="text" name="item_datum_dag" id="item_datum_dag" class="datum_smal" value="<?php echo $item_datum_dag ?>" /><input type="text" name="item_datum_maand" id="item_datum_maand" class="datum_smal" value="<?php echo $item_datum_maand ?>" /><input type="text" name="item_datum_jaar" id="item_datum_jaar" class="datum_breed" value="<?php echo $item_datum_jaar ?>" /><span class="feedback"><?php echo $item_datum_feedback ?></span></p>
		<p><label for="item_tijd_uren">Begintijd *</label><input type="text" name="item_tijd_uren" id="item_tijd_uren" class="datum_smal" value="<?php echo $item_tijd_uren ?>" /><input type="text" name="item_tijd_minuten" id="item_tijd_minuten" class="datum_smal" value="<?php echo $item_tijd_minuten ?>" /><span class="feedback"><?php echo $item_tijd_feedback ?></span></p>
		<p><label for="item_eindtijd_uren">Eindtijd *</label><input type="text" name="item_eindtijd_uren" id="item_eindtijd_uren" class="datum_smal" value="<?php echo $item_eindtijd_uren ?>" /><input type="text" name="item_eindtijd_minuten" id="item_eindtijd_minuten" class="datum_smal" value="<?php echo $item_eindtijd_minuten ?>" /><span class="feedback"><?php echo $item_eindtijd_feedback ?></span></p>
		<h2><strong>Uitslag</strong></h2>
		<p>
			<label for="item_voldoende">Voldoende</label>
			<input type="radio" name="item_voldoende" id="item_voldoende_onbekend" value="onbekend" <?php if($item_voldoende == 'onbekend') echo 'checked'; ?> /> Onbekend
			<input type="radio" name="item_voldoende" id="item_voldoende_ja" value="ja" <?php if($item_voldoende == 'ja') echo 'checked'; ?> /> Ja
			<input type="radio" name="item_voldoende" id="item_voldoende_nee" value="nee" <?php if($item_voldoende == 'nee') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_voldoende_feedback ?></span>
		</p>
		<p><label for="item_code">Code *</label><input type="text" name="item_code" id="item_code" value="<?php echo $item_code ?>" /><span class="feedback"><?php echo $item_code_feedback ?></span></p>
		<p class="submit"><input type="submit" value="Afspraak wijzigen" /> <a href="<?php echo base_url('cms/deelnemers/'.$afspraak->gebruiker_ID) ?>" title="Annuleren">Annuleren</a></p>
	</form>
</div>