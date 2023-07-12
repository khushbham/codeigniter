<h1><?php echo $workshop->workshop_titel ?></h1>
<h2>Producten beheren</h2>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php echo base_url('cms/workshops/producten/'.$workshop->workshop_ID) ?>">
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
				<th>Product</th>
				<th>Beschikbaar</th>
			</thead>

			<tbody>
				<?php foreach($producten as $product): ?>
					<tr>
						<td><input type="checkbox" name="producten[]" value="<?php echo $product->product_ID ?>" <?php foreach($gekoppeld as $item) { if($item['id'] == $product->product_ID) {echo 'checked';}} ?> /><?php echo $product->product_naam ?></td>
						<td><input type="radio" name="product_beschikbaar[<?php echo $product->product_ID ?>]" value="altijd" checked /> Altijd <input type="radio" name="product_beschikbaar[<?php echo $product->product_ID ?>]" value="na" <?php foreach($gekoppeld as $item) { if($item['id'] == $product->product_ID) { if($item['wanneer_beschikbaar'] == 'na'){echo 'checked';}}} ?> /> Na een workshop</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<p class="submit"><input type="submit" value="Selectie opslaan"/><a href="<?php echo base_url('cms/workshops/'.$workshop->workshop_ID) ?>" title="Annuleren">Annuleren</a></p>
	</form>
</div>