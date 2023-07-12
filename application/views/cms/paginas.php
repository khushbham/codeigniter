<h1>Pagina's</h1>
<div id="nieuws">
	<?php if(sizeof($paginas) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
				<tr>
					<th>Pagina</th>
					<th>Titel</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($paginas as $item): ?>
					<tr>
						<td><a href="<?php echo base_url('cms/paginas/'.$item->pagina_ID) ?>" title="Pagina bekijken"><?php echo $item->pagina_titel_menu ?></a></td>
						<td><a href="<?php echo base_url('cms/paginas/'.$item->pagina_ID) ?>" title="Pagina bekijken"><?php echo $item->pagina_titel ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/paginas/'.$item->pagina_ID) ?>" title="Pagina bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/paginas/wijzigen/'.$item->pagina_ID) ?>" title="Pagina wijzigen">Wijzigen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php if($aantal_paginas > 1): ?>
			<div id="paginanummering">
				<p>
					<?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
						<?php if($i == $huidige_pagina): ?>
							<a href="<?php echo base_url('cms/paginas/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
						<?php else: ?>
							<a href="<?php echo base_url('cms/paginas/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
						<?php endif; ?>
						<?php if($i < $aantal_paginas) echo ' |'; ?>
					<?php endfor; ?>
				</p>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<p>Er staan geen pagina's in de database.</p>
	<?php endif; ?>
</div>