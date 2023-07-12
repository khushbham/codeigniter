<h1>Automation <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/uitnodigingen/toevoegen/'); else echo base_url('cms/uitnodigingen/wijzigen/'.$item_ID); ?>">
		<p>
			<label for="item_workshop">Workshop</label>
			<select name="item_workshop" id="js_item_workshop">
				<option value="">Selecteer een workshop</option>

				<!-- Workshops (standaard) -->

				<?php if(sizeof($workshops_standaard) > 0): ?>
					<optgroup label="Standaard">
						<?php foreach($workshops_standaard as $workshop): ?>
							<option value="<?php echo $workshop->workshop_ID ?>" <?php if($workshop->workshop_ID == $item_workshop) echo 'selected="selected"'; ?>><?php echo $workshop->workshop_titel ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endif; ?>

				<!--- Workshops (specialties) -->

				<?php if(sizeof($workshops_specialties) > 0): ?>
					<optgroup label="Specialties">
						<?php foreach($workshops_specialties as $workshop): ?>
							<option value="<?php echo $workshop->workshop_ID ?>" <?php if($workshop->workshop_ID == $item_workshop) echo 'selected="selected"'; ?>><?php echo $workshop->workshop_titel ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endif; ?>
			</select>
			<span class="feedback"><?php echo $item_workshop_feedback ?></span>
		</p>

		<p><label for="item_onderwerp">Onderwerp</label><input type="text" name="item_onderwerp" id="item_onderwerp" value="<?php echo $item_onderwerp ?>" autocomplete="off" /><span class="feedback"><?php echo $item_onderwerp_feedback ?></span></p>
		<p><label for="item_tekst">Tekst</label><textarea name="item_tekst" id="item_tekst" class="opmaak"><?php echo $item_tekst ?></textarea><span class="feedback"><?php echo $item_tekst_feedback ?></span></p>
		<p id="js-nales">
			<label for="item_nales">Les</label>
			<select name="item_nales" id="item_nales" data-les="<?php echo $item_nales ?>">
				<option value="">Selecteer een les</option>
				<!-- Lessen -->
				<!-- Worden geladen door AJAX -->
			</select>
			<span class="feedback"><?php echo $item_nales_feedback ?></span>
		</p>
		<p id="nales-dagen">
			<label for="item_nales_dagen">Aantal dagen na afloop</label>
			<input type="text" name="item_nales_dagen" id="item_nales_dagen" class="datum_smal" value="<?php echo $item_nales_dagen ?>"/>
			<span class="feedback"><?php echo $item_nales_dagen_feedback ?></span>
		</p>
		<p class="submit"><input type="submit" name="item_opslaan" id="item_opslaan" value="Opslaan" class="belangrijk" /> <a href="<?php echo base_url('cms/uitnodigingen/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/uitnodigingen/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>