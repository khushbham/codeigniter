<h1>Aanmelding <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" <?php if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'support') { ?> onsubmit="return confirm('Weet je zeker dat je dit wilt doen?')"; <?php } ?> action="<?php if($actie == 'toevoegen') echo base_url('cms/aanmeldingen/toevoegen/'); else echo base_url('cms/aanmeldingen/wijzigen/'.$item_ID); ?>">
		<p>
		<label for="workshop_ID">Workshop</label>
			<select name="workshop_ID" id="workshop_ID">
				<?php foreach($workshops as $workshop) { ?>
					<option value="<?php echo $workshop->workshop_ID ?>"  <?php if($workshop_ID == $workshop->workshop_ID) echo 'selected'; ?>><?php echo $workshop->workshop_ID . "  " . $workshop->workshop_titel ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
		<label for="groep_ID">Groep</label>
			<select name="groep_ID" id="groep_ID">
				<option value="" <?php if($groep_ID == '') echo 'selected'; ?> >Geen groep</option>
				<?php foreach($groepen as $groep) { ?>
					<option value="<?php echo $groep->groep_ID ?>" <?php if($groep_ID == $groep->groep_ID) echo 'selected'; ?>><?php echo $groep->groep_ID . "  " . $groep->groep_naam ?></option>
				<?php } ?>
			</select>
		</p>

		<p><label for="item_betaald">Betaald *</label><input type="radio" name="item_betaald" value="ja" <?php if($item_betaald == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="item_betaald" value="nee" <?php if($item_betaald == 'nee') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_betaald_feedback ?></span></p>
		<p><label for="item_afgerond">Afgerond *</label><input type="radio" name="item_afgerond" value="ja" <?php if($item_afgerond == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="item_afgerond" value="nee" <?php if($item_afgerond == 'nee') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_afgerond_feedback ?></span></p>
        <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support' || $this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker'): ?>
		    <p class="submit"><input type="submit" value="Aanmelding <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/aanmeldingen/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/aanmeldingen/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
        <?php endif; ?>
	</form>
</div>