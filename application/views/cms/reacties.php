<h1>Reacties</h1>

<p id="links">
	<a href="<?php echo base_url('cms/reacties/toevoegen') ?>" title="Reactie toevoegen">Reactie toevoegen</a>
</p>

<div id="nieuws">
	<?php if(sizeof($reacties) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
				<tr>
					<th class="datum">Datum</th>
					<th class="tijd">Tijd</th>
					<th>Titel</th>
					<th>Deelnemer</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($reacties as $item): ?>
					<tr <?php if($item->reactie_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
						<td class="datum"><a href="<?php echo base_url('cms/reacties/'.$item->reactie_ID) ?>" title="Reactie bekijken"><?php echo date('d/m/Y', strtotime($item->reactie_datum)) ?></a></td>
						<td class="tijd"><a href="<?php echo base_url('cms/reacties/'.$item->reactie_ID) ?>" title="Reactie bekijken"><?php echo date('H:i', strtotime($item->reactie_datum)) ?></a></td>
						<td><a href="<?php echo base_url('cms/reacties/'.$item->reactie_ID) ?>" title="Reactie bekijken"><?php if($item->reactie_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->reactie_titel ?></a></td>
						<td><a href="<?php echo base_url('cms/reacties/'.$item->reactie_ID) ?>" title="Reactie bekijken"><?php if($item->reactie_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->reactie_deelnemer ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/reacties/'.$item->reactie_ID) ?>" title="Reactie bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/reacties/wijzigen/'.$item->reactie_ID) ?>" title="Reactie wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/reacties/verwijderen/'.$item->reactie_ID) ?>" title="Reactie verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php if($aantal_paginas > 1): ?>
			<div id="paginanummering">
				<p>
					<?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
						<?php if($i == $huidige_pagina): ?>
							<a href="<?php echo base_url('cms/reacties/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
						<?php else: ?>
							<a href="<?php echo base_url('cms/reacties/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
						<?php endif; ?>
						<?php if($i < $aantal_paginas) echo ' |'; ?>
					<?php endfor; ?>
				</p>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<p>Er staan geen reacties in de database.</p>
	<?php endif; ?>
</div>