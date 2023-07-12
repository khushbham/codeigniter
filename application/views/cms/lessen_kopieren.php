<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" <?php echo base_url('cms/workshops/lessen_kopieren/'. $item->workshop_ID); ?>">
		<h2>Lessen kopieren van <?php echo $item->workshop_titel ?></h2>

		<p>
		<label for="workshop_ID">Lessen kopiëren naar</label>
			<select name="workshop_ID" id="workshop_ID">
				<?php foreach($workshops as $workshop) { ?>
					<option value="<?php echo $workshop->workshop_ID ?>" ><?php echo $workshop->workshop_ID . "  " . $workshop->workshop_titel ?></option>
				<?php } ?>
			</select>
		</p>

		<p><input type="checkbox" name="geselecteerde_lessen_checkbox" onClick="toggle(this)"/>Alle lessen</p>
		<table cellpadding="0" cellspacing="0" class="tabel" width="">
					<thead>
					<tr>
						<th class="geselecteerd"></th>
						<th class="naam">Naam</th>
					</tr>
					</thead>
					<tbody>
						<?php foreach($lessen as $les): ?>
							<tr>
								<td class="geselecteerd"><input class="geselecteerde_lessen_checkbox" type="checkbox" name="geselecteerde_lessen[]" value="<?php echo $les->les_ID ?>" ></td>
								<td class="naam"><?php echo $les->les_titel ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					</table>
		<p class="submit"><input type="submit" value="Kopiëren" />
	</form>
</div>