<h1>Beheerders</h1>

<p id="links">
	<a href="<?php echo base_url('cms/beheerders/toevoegen') ?>" title="Beheerder toevoegen">Beheerder toevoegen</a>
</p>

<?php if(sizeof($beheerders) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Rol</th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
				<th class="verwijderen"></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($beheerders as $item):
				if ($item->gebruiker_rechten != 'docent'):
			?>
				<tr>
					<td><a href="<?php echo base_url('cms/beheerders/'.$item->gebruiker_ID) ?>" title="Beheerder bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><?php echo ucfirst($item->gebruiker_rechten) ?></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/beheerders/'.$item->gebruiker_ID) ?>" title="Beheerder bekijken">Bekijken</a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/beheerders/wijzigen/'.$item->gebruiker_ID) ?>" title="Beheerder wijzigen">Wijzigen</a></td>
					<td class="verwijderen"><a href="<?php echo base_url('cms/beheerders/verwijderen/'.$item->gebruiker_ID) ?>" title="Beheerder verwijderen">Verwijderen</a></td>
				</tr>
					<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p>Er staan momenteel geen beheerders in de database.</p>
<?php endif; ?>