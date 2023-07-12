<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" <?php echo base_url('cms/lessen/beoordelingen/'); ?>">
		<p>
		<label for="lessen1">lessen 1</label>
			<select name="lessen1" id="lessen1">
			<?php foreach($lessen1 as $les) { ?>
				<option value="<?php echo $les->les_ID ?>" ><?php echo $les->les_ID . "  " . $les->les_titel ?></option>
			<?php } ?>
			</select>
		</p>
		<p>
			<label for="lessen2">lessen 2</label>
			<select name="lessen2" id="lessen2">
			<?php foreach($lessen2 as $les) { ?>
				<option value="<?php echo $les->les_ID ?>" ><?php echo $les->les_ID . "  " . $les->les_titel ?></option>
			<?php } ?>
			</select>
		</p>
		<p class="submit"><input type="submit" value="transfer" />
	</form>
	<p><?php echo $q ?></p>
</div>