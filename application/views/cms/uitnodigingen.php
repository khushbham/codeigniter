<h1>Automation</h1>

<p id="links">
	<a href="<?php echo base_url('cms/uitnodigingen/toevoegen') ?>" title="Automation toevoegen">Automation toevoegen</a>
</p>

<?php if(sizeof($uitnodigingen) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<thead>
		<tr>
			<th>Onderwerp</th>
			<th>Dagen na afloop</th>
			<th class="bekijken"></th>
			<th class="wijzigen"></th>
			<th class="verwijderen"></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($uitnodigingen as $item):
			?>
			<tr>
				<td><a href="<?php echo base_url('cms/uitnodigingen/'.$item->uitnodiging_ID) ?>" title="Uitnodiging bekijken"><?php echo $item->uitnodiging_onderwerp ?></a></td>
				<td><?php echo ucfirst($item->uitnodiging_dagen_na_afloop) ?></td>
				<td class="bekijken"><a href="<?php echo base_url('cms/uitnodigingen/'.$item->uitnodiging_ID) ?>" title="Uitnodiging bekijken">Bekijken</a></td>
				<td class="wijzigen"><a href="<?php echo base_url('cms/uitnodigingen/wijzigen/'.$item->uitnodiging_ID) ?>" title="Uitnodiging wijzigen">Wijzigen</a></td>
				<td class="verwijderen"><a href="<?php echo base_url('cms/uitnodigingen/verwijderen/'.$item->uitnodiging_ID) ?>" title="Uitnodiging verwijderen">Verwijderen</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p>Er staan momenteel geen Automations in de database.</p>
<?php endif; ?>

<h1>Uitnodigingslinks</h1>

<p id="links">
	<a href="<?php echo base_url('cms/uitnodigingen/link_toevoegen') ?>" title="Uitnodigingslink toevoegen">Uitnodigingslink toevoegen</a>
</p>
<?php if(sizeof($links) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<thead>
		<tr>
			<th>Workshop</th>
			<th>Groep</th>
			<th>Link</th>
			<th>Gebruikt</th>
			<th>Limiet</th>
			<th class="wijzigen"></th>
			<th class="verwijderen"></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($links as $link):
			?>
			<tr>
				<td><?php echo ucfirst($link->workshop_titel) ?></td>
				<td><?php if(!empty($link->groep_naam)) { echo ucfirst($link->groep_naam); } else { echo "X"; } ?></td>
				<td><?php echo $link->link_link ?></td>
                <td><?php if(!empty($link->link_gebruikt)) { echo "ja"; } else { echo "nee"; } ?></td>
				<td title="limiet"><?php if(is_numeric($link->uitnodiging_limiet)) echo $link->uitnodiging_limiet; else echo 'X'; ?></td>
				<td class="wijzigen"><a href="<?php echo base_url('cms/uitnodigingen/link_wijzigen/'.$link->link_ID) ?>" title="Uitnodiging wijzigen">Wijzigen</a></td>
				<td class="verwijderen"><a href="<?php echo base_url('cms/uitnodigingen/link_verwijderen/'.$link->link_ID) ?>" title="Uitnodiging verwijderen">Verwijderen</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p>Er staan momenteel geen uitnodigingslinks in de database.</p>
<?php endif; ?>
