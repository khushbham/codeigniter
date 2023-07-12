<h1>Lessen bekeken</h1>

<div id="aanmeldingen">
	<?php if(sizeof($lessen) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
		<thead>
			<thead>
			<tr>
					<th>Titel</th>
					<th>Workshop</th>
					</tr>
			</thead>
			<tbody>
				<?php foreach($lessen as $item): ?>
					<tr>
							<td><?php echo $item->les_titel ?></a></td>
							<td><?php echo $item->workshop_titel ?></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>De cursist heeft nog geen lessen bekeken.</p>
	<?php endif; ?>
</div>