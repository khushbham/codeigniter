<h1>Docenten</h1>

<p id="links">
	<a href="<?php echo base_url('cms/docenten/toevoegen') ?>" title="Docent toevoegen">Docent toevoegen</a>
</p>

<div id="nieuws">
	<?php if(sizeof($docenten) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="docenten">
			<thead>
				<tr>
					<th>Naam</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($docenten as $item): ?>
					<tr data-item="<?php echo $item->docent_ID ?>" class="<?php if($item->gebruiker_status == 'inactief') echo 'concept'; ?>">
						<td><a href="<?php echo base_url('cms/docenten/'.$item->docent_ID) ?>" title="Docent bekijken"><?php echo $item->docent_naam ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/docenten/'.$item->docent_ID) ?>" title="Docent bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/docenten/wijzigen/'.$item->docent_ID) ?>" title="Docent wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/docenten/verwijderen/'.$item->docent_ID) ?>" title="Docent verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php if($aantal_paginas > 1): ?>
			<div id="paginanummering">
				<p>
					<?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
						<?php if($i == $huidige_pagina): ?>
							<a href="<?php echo base_url('cms/docenten/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
						<?php else: ?>
							<a href="<?php echo base_url('cms/docenten/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
						<?php endif; ?>
						<?php if($i < $aantal_paginas) echo ' |'; ?>
					<?php endfor; ?>
				</p>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<p>Er staan geen docenten in de database.</p>
	<?php endif; ?>
</div>