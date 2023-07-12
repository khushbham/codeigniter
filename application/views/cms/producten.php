<h1>Producten</h1>

<p id="links">
	<a href="<?php echo base_url('cms/producten/toevoegen') ?>" title="Product toevoegen">Product toevoegen</a>
</p>

<div id="producten">
	<?php if(sizeof($producten) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="producten">
			<thead>
				<tr>
					<th class="product">ID</th>
					<th class="product">Naam</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($producten as $item): ?>
					<tr data-item="<?php echo $item->product_ID ?>">
						<td class="product"><a href="<?php echo base_url('cms/producten/'.$item->product_ID) ?>" title="Product bekijken"><?php echo $item->product_ID ?></a></td>
						<td class="product"><a href="<?php echo base_url('cms/producten/'.$item->product_ID) ?>" title="Product bekijken"><?php echo $item->product_naam ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/producten/'.$item->product_ID) ?>" title="Product bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/producten/wijzigen/'.$item->product_ID) ?>" title="Product wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/producten/verwijderen/'.$item->product_ID) ?>" title="Product verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen producten in de database.</p>
	<?php endif; ?>
</div>