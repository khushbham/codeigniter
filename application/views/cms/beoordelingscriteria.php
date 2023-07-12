<h1>Beoordelingscriteria</h1>

<p id="links">
	<a href="<?php echo base_url('cms/beoordelingscriteria/toevoegen') ?>" title="Beoordelingscriteria toevoegen">Beoordelingscriteria toevoegen</a>
</p>

<div id="nieuws">
	<?php if(sizeof($beoordelingscriteria) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="docenten">
			<thead>
				<tr>
					<th>Naam</th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($beoordelingscriteria as $item): ?>
					<tr data-item="<?php echo $item->beoordelingscriteria_ID ?>">
						<td><?php echo $item->beoordelingscriteria_naam ?></a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/beoordelingscriteria/wijzigen/'.$item->beoordelingscriteria_ID) ?>" title="Beoordelingscriteria wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/beoordelingscriteria/verwijderen/'.$item->beoordelingscriteria_ID) ?>" title="Beoordelingscriteria verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen beoordelingscriteria in de database.</p>
	<?php endif; ?>
</div>