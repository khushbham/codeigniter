<h1>Gegevens wijzigen</h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php echo base_url('cms/gegevens/wijzigen/'); ?>">
		<?php for($i = 0; $i < sizeof($gegevens); $i++): ?>
			<?php if ($gegevens[$i]->gegeven_naam == 'Demo account actief'): ?>
				<p><label for="item_<?php echo $i ?>"><?php echo $gegevens[$i]->gegeven_naam ?> *</label><span class="radios"><input type="radio" name="gegevens[]" id="item_<?php echo $i ?>" value="ja" <?php if($gegevens[$i]->gegeven_waarde  == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="gegevens[]" id="item_<?php echo $i ?>" value="nee" <?php if($gegevens[$i]->gegeven_waarde  == 'nee') echo 'checked'; ?> /> Nee</span></p>
			<?php elseif($gegevens[$i]->gegeven_naam == 'Huiswerk insturen'): ?>
				<p><label for="item_<?php echo $i ?>"><?php echo $gegevens[$i]->gegeven_naam ?> *</label><span class="radios"><input type="radio" name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>" value="ja" <?php if($gegevens[$i]->gegeven_waarde  == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>" value="nee" <?php if($gegevens[$i]->gegeven_waarde  == 'nee') echo 'checked'; ?> /> Nee </span></p>
			<?php elseif($gegevens[$i]->gegeven_naam == 'Huiswerk geblokkeerd bericht'): ?>
				<p><label for="item_<?php echo $i ?>"><?php echo $gegevens[$i]->gegeven_naam ?> *</label><textarea name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>"><?php echo $gegevens[$i]->gegeven_waarde ?></textarea></p>
            <?php elseif ($gegevens[$i]->gegeven_naam == 'Gratis workshop aan'): ?>
                <p><label for="item_<?php echo $i ?>"><?php echo $gegevens[$i]->gegeven_naam ?> *</label><span class="radios"><input type="radio" name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>" value="ja" <?php if($gegevens[$i]->gegeven_waarde  == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>" value="nee" <?php if($gegevens[$i]->gegeven_waarde  == 'nee') echo 'checked'; ?> /> Nee </span></p>
			<?php elseif ($gegevens[$i]->gegeven_naam == 'onderhoud publieke site'): ?>
            	<p><label for="item_<?php echo $i ?>"><?php echo $gegevens[$i]->gegeven_naam ?></label><span class="radios"><input type="radio" name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>" value="ja" <?php if($gegevens[$i]->gegeven_waarde  == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>" value="nee" <?php if($gegevens[$i]->gegeven_waarde  == 'nee') echo 'checked'; ?> /> Nee </span></p>
			<?php elseif ($gegevens[$i]->gegeven_naam == 'onderhoud cursistenmodule'): ?>
                <p><label for="item_<?php echo $i ?>"><?php echo $gegevens[$i]->gegeven_naam ?></label><span class="radios"><input type="radio" name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>" value="ja" <?php if($gegevens[$i]->gegeven_waarde  == 'ja') echo 'checked'; ?> /> Ja <input type="radio" name="gegevens[<?php echo $i ?>]" id="item_<?php echo $i ?>" value="nee" <?php if($gegevens[$i]->gegeven_waarde  == 'nee') echo 'checked'; ?> /> Nee </span></p>
			<?php else: ?>
				<p><label for="item_<?php echo $i ?>"><?php echo $gegevens[$i]->gegeven_naam ?> *</label><input type="text" name="gegevens[]" id="item_<?php echo $i ?>" value="<?php echo $gegevens[$i]->gegeven_waarde ?>" /></p>
			<?php endif; ?>
		<?php endfor; ?>
<br>
		<h3>Betaalmethodes percentage wijzigen</h3>
		<?php for($i = 0; $i < sizeof($betaal_methodes); $i++): ?>
			<p><label for="item_<?php echo $i ?>"><?php echo $betaal_methodes[$i]->naam ?> *</label><input type="text" name="betaalmethodes[]" id="methode_<?php echo $i ?>" value="<?php echo $betaal_methodes[$i]->percentage ?>" /></p>
		<?php endfor; ?>
		<?php if(!empty($feedback)): ?><p class="feedback"><?php echo $feedback ?></p><?php endif; ?>
		<p class="submit"><input type="submit" value="Gegevens wijzigen" /> <a href="<?php echo base_url('cms/gegevens/') ?>" title="Annuleren">Annuleren</a></p>
	</form>
</div>