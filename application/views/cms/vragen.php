<h1>Vragen</h1>

<p id="links">
	<a href="<?php echo base_url('cms/vragen/toevoegen') ?>" title="Vraag toevoegen">Vraag toevoegen</a>
</p>

<h2>Website</h2>
<div id="vragen_website">
	<?php if(sizeof($vragen_website) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="vragen">
			<thead>
				<tr>
					<th>Vraag</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($vragen_website as $item): ?>
					<tr data-item="<?php echo $item->vraag_ID ?>" <?php if($item->vraag_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
						<td><a href="<?php echo base_url('cms/vragen/'.$item->vraag_ID) ?>" title="Vraag bekijken"><?php if($item->vraag_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->vraag_titel ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/vragen/'.$item->vraag_ID) ?>" title="Vraag bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/vragen/wijzigen/'.$item->vraag_ID) ?>" title="Vraag wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/vragen/verwijderen/'.$item->vraag_ID) ?>" title="Vraag verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen vragen voor de website in de database.</p>
	<?php endif; ?>
</div>
<h2>Cursistenmodule</h2>
<div id="vragen_cursistenmodule">
	<?php if(sizeof($vragen_cursistenmodule) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="vragen">
			<thead>
				<tr>
					<th>Vraag</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($vragen_cursistenmodule as $item): ?>
					<tr data-item="<?php echo $item->vraag_ID ?>" <?php if($item->vraag_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
						<td><a href="<?php echo base_url('cms/vragen/'.$item->vraag_ID) ?>" title="Vraag bekijken"><?php if($item->vraag_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->vraag_titel ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/vragen/'.$item->vraag_ID) ?>" title="Vraag bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/vragen/wijzigen/'.$item->vraag_ID) ?>" title="Vraag wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/vragen/verwijderen/'.$item->vraag_ID) ?>" title="Vraag verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen vragen voor de cursistenmodule in de database.</p>
	<?php endif; ?>
</div>