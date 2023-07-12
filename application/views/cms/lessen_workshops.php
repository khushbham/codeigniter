<h1>Workshops</h1>

<div id="workshops" style="padding:0 0 25px 0">
	<?php if(sizeof($workshops) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel" data-items="workshops">
			<thead>
				<tr>
					<th>Titel</th>
					<th>Afkorting</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($workshops as $item): ?>
					<tr data-item="<?php echo $item->workshop_ID ?>" <?php if(($item->workshop_gepubliceerd == 'ja') && ($item->workshop_uitgelicht == 'ja')) echo 'class="uitgelicht"'; ?> <?php if($item->workshop_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
						<td><a href="<?php echo base_url('cms/lessen/workshop/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
						<td><a href="<?php echo base_url('cms/lessen/workshop/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_afkorting ?></a></td>
						</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen standaard workshops in de database.</p>
	<?php endif; ?>
	</div>

	<?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
<h1>Les types</h1>
<?php if($this->session->userdata('beheerder_rechten') != 'docent'): ?>
	<p id="links">
		<a href="<?php echo base_url('cms/lessen/type_toevoegen') ?>" title="Les type toevoegen">Les type toevoegen</a>
	</p>
<?php endif; ?>

<div class="types">
	<?php if (!empty($les_types)) { ?>
	<table cellpadding="0" cellspacing="0" class="tabel" data-items="lessen">
		<thead>
		<tr>
			<th>Type</th>
			<?php if($this->session->userdata('beheerder_rechten') != 'docent'): ?>
				<th class="wijzigen"></th>
				<th class="verwijderen"></th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
			<?php foreach($les_types as $item): ?>
				<tr data-item="<?php echo $item->les_type_ID ?>">
						<td><?php echo $item->les_type_soort ?></a></td>
					<?php if($this->session->userdata('beheerder_rechten') != 'docent'): ?>
						<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/type_wijzigen/'.$item->les_type_ID) ?>" title="Les type wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/lessen/type_verwijderen/'.$item->les_type_ID) ?>" title="Les type verwijderen">Verwijderen</a></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php } else { ?>
		<p>Er staan geen les types in de database.</p>
	<?php } ?>
</div>

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
<?php endif; ?>