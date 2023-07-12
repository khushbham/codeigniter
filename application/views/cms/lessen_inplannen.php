<h1>Les <?php echo $actie ?></h1>
<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Workshop</th>
		<td><?php echo $workshop->workshop_titel ?></td>
	</tr>
	<tr>
		<th>Groep</th>
		<td><?php echo $groep->groep_naam ?></td>
	</tr>
	<tr>
		<th>Les</th>
		<td><?php echo $les->les_titel ?></td>
	</tr>
	<tr>
		<th>Type</th>
		<td><?php echo ucfirst($les->les_locatie) ?></td>
	</tr>
	<tr>
		<th>Soort</th>
		<td><?php if(!empty($les->les_type_soort)) { echo ucfirst($les->les_type_soort); } else { echo '...'; } ?></td>
	</tr>
</table>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'inplannen') echo base_url('cms/lessen/inplannen/'.$les->les_ID.'/'.$groep->groep_ID); else echo base_url('cms/lessen/groep/'.$les->les_ID.'/'.$groep->groep_ID.'/'.$groep_les_ID); ?>">
	<?php if(empty($les->les_gekoppeld_aan) || $les->les_gekoppeld_aan == 0 || empty($les->les_beschikbaar) || $les->les_beschikbaar == 0) { ?>
	<p><label for="item_docent">Docent</label>
			<select name="item_docent" id="item_docent">
				<option value="">Selecteer docent</option>
				<option value="">---</option>
				<?php foreach($docenten as $docent) { ?>
				<option value="<?php echo $docent->docent_ID ?>" <?php if($docent->docent_ID == $docent_ID) { echo 'selected'; } ?>><?php echo $docent->docent_naam ?></option>
				<?php echo ($docent->docent_ID == $docent_ID); ?>
				<?php } ?>
			</select></p>
		<p><label for="technicus">Technicus</label><input type="text" name="technicus" id="technicus" value="<?php echo $technicus ?>" /></p>
			<p><label for="item_datum_dag">Datum *</label><input type="text" name="item_datum_dag" id="item_datum_dag" class="datum_smal" value="<?php echo $item_datum_dag ?>" /><input type="text" name="item_datum_maand" id="item_datum_maand" class="datum_smal" value="<?php echo $item_datum_maand ?>" /><input type="text" name="item_datum_jaar" id="item_datum_jaar" class="datum_breed" value="<?php echo $item_datum_jaar ?>" /><span class="feedback"><?php echo $item_datum_feedback ?></span></p>
			<p><label for="item_tijd_uren">Begintijd *</label><input type="text" name="item_tijd_uren" id="item_tijd_uren" class="datum_smal" value="<?php echo $item_tijd_uren ?>" /><input type="text" name="item_tijd_minuten" id="item_tijd_minuten" class="datum_smal" value="<?php echo $item_tijd_minuten ?>" /><span class="feedback"><?php echo $item_tijd_feedback ?></span></p>
			<p><label for="item_eindtijd_uren">Eindtijd *</label><input type="text" name="item_eindtijd_uren" id="item_eindtijd_uren" class="datum_smal" value="<?php echo $item_eindtijd_uren ?>" /><input type="text" name="item_eindtijd_minuten" id="item_eindtijd_minuten" class="datum_smal" value="<?php echo $item_eindtijd_minuten ?>" /><span class="feedback"><?php echo $item_eindtijd_feedback ?></span></p>
		<?php } ?>
		<?php if($les->les_locatie == 'studio'): ?>
            <p><label for="item_eindtijd_uren">Locatie *</label><input type="radio" name="item_locatie_ID" onchange="showAnders(this.value)" value="1" <?php if($item_locatie_ID == '1') echo 'checked'; ?> /> Middelstegracht 89u, 2312 TT, Leiden</p>
            <p><input style="margin-left:200px" type="radio" name="item_locatie_ID" onchange="showAnders(this.value)" value="2" <?php if($item_locatie_ID == '2') echo 'checked'; ?> /> Middelstegracht 89d, 2312 TT, Leiden</p>
			<p><input style="margin-left:200px" type="radio" name="item_locatie_ID" onchange="showAnders(this.value)" value="3" <?php if($item_locatie_ID == '3') echo 'checked'; ?> /> Ondiep-Zuidzijde 6, 3551 BW, Utrecht</p>
			<p><input style="margin-left:200px" type="radio" name="item_locatie_ID" onchange="showAnders(this.value)" value="5" <?php if($item_locatie_ID == '5') echo 'checked'; ?> /> Donauweg 10, 1043 AJ, Amsterdam</p>
            <p><input style="margin-left:200px" type="radio" name="item_locatie_ID" onchange="showAnders(this.value)" value="4" <?php if($item_locatie_ID == '4') echo 'checked'; ?> /> Anders</p>
            <div id="item_adres_anders">
                <p><label for="item_adres">Adres *</label><input type="text" name="item_adres" id="item_adres" value="<?php echo $item_adres ?>" /><span class="feedback"><?php echo $item_adres_feedback ?></span></p>
                <p><label for="item_postcode">Postcode *</label><input type="text" name="item_postcode" id="item_postcode" value="<?php echo $item_postcode ?>" /><span class="feedback"><?php echo $item_postcode_feedback ?></span></p>
                <p><label for="item_plaats">Plaats *</label><input type="text" name="item_plaats" id="item_plaats" value="<?php echo $item_plaats ?>" /><span class="feedback"><?php echo $item_plaats_feedback ?></span></p>
            </div>
		<?php endif; ?>
		<?php if(!empty($les->les_gekoppeld_aan) && $les->les_gekoppeld_aan == 1) { ?>
			<p><label for="item_gekoppeld_aan">Gekoppeld aan les</label>
				<select name="item_gekoppeld_aan" id="item_gekoppeld_aan">
					<option value="">Selecteer een les</option>
					<option value="">---</option>
					<?php foreach($lessen as $item) { ?>
						<option value="<?php echo $item->les_ID ?>" <?php if($item->les_ID == $item_gekoppeld_aan) { echo 'selected'; } ?>><?php echo $item->les_titel ?></option>
					<?php } ?>
				</select></p>
				<p><label for="item_dagen_ervoor_beschikbaar">les dagen ervoor beschikbaar *</label><input type="text" name="item_dagen_ervoor_beschikbaar" id="item_dagen_ervoor_beschikbaar" value="<?php echo $item_dagen_ervoor_beschikbaar ?>" /></p>
		<?php } ?>
		<p class="submit"><input type="submit" value="Les <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/groepen/'.$groep->groep_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/lessen/uitplannen/'.$groep_les_ID) ?>" title="Uitplannen">Uitplannen</a><?php endif; ?></p>
	</form>
</div>