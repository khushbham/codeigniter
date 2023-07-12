<h1>Nieuws</h1>

<p id="links">
	<a href="<?php echo base_url('cms/nieuws/toevoegen') ?>" title="Nieuws toevoegen">Nieuws toevoegen</a>
</p>

<div id="nieuws">
	<?php if(sizeof($nieuws) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
				<tr>
					<th class="datum">Datum</th>
					<th class="tijd">Tijd</th>
					<th>Titel</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($nieuws as $item): ?>
					<tr <?php if($item->nieuws_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
						<td class="datum"><a href="<?php echo base_url('cms/nieuws/'.$item->nieuws_ID) ?>" title="Bericht bekijken"><?php echo date('d/m/Y', strtotime($item->nieuws_datum)) ?></a></td>
						<td class="tijd"><a href="<?php echo base_url('cms/nieuws/'.$item->nieuws_ID) ?>" title="Bericht bekijken"><?php echo date('H:i', strtotime($item->nieuws_datum)) ?></a></td>
						<td><a href="<?php echo base_url('cms/nieuws/'.$item->nieuws_ID) ?>" title="Bericht bekijken"><?php if($item->nieuws_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->nieuws_titel ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/nieuws/'.$item->nieuws_ID) ?>" title="Nieuws bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/nieuws/wijzigen/'.$item->nieuws_ID) ?>" title="Nieuws wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/nieuws/verwijderen/'.$item->nieuws_ID) ?>" title="Nieuws verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php if($aantal_paginas > 1): ?>
			<div id="paginanummering">
				<p>
					<?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
						<?php if($i == $huidige_pagina): ?>
							<a href="<?php echo base_url('cms/nieuws/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
						<?php else: ?>
							<a href="<?php echo base_url('cms/nieuws/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
						<?php endif; ?>
						<?php if($i < $aantal_paginas) echo ' |'; ?>
					<?php endfor; ?>
				</p>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<p>Er staat geen nieuws in de database.</p>
	<?php endif; ?>
</div>