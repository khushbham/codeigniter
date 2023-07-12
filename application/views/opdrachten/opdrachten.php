<h1>Opdrachten</h1>

<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
<p id="links">
	<a href="<?php echo base_url('opdrachten/opdrachten/toevoegen') ?>" title="Opdracht toevoegen">Opdracht toevoegen</a>
</p>
<?php endif; ?>

<div id="opdrachten">
	<?php if(sizeof($opdrachten) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel" data-items="Opdrachten">
			<thead>
				<tr>
					<th>Titel</th>
					<th>URL</th>
					<th># uploads</th>
					<th># Deelnames</th>
					<th class="bekijken"></th>
					<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
						<th class="wijzigen"></th>
						<th class="verwijderen"></th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($opdrachten as $item): ?>
					<tr data-item="<?php echo $item->opdracht_ID ?>">
						<td><a href="<?php echo base_url('opdracht/'.$item->opdracht_url) ?>" title="Opdracht bekijken"><?php echo $item->opdracht_titel ?></a></td>
						<td><?php echo $item->opdracht_url ?></td>
						<td><?php echo $item->opdracht_uploads_aantal ?></td>
                        <td>TBD</td>
						<td class="bekijken"><a href="<?php echo base_url('opdracht/'.$item->opdracht_url) ?>" title="Opdracht bekijken">Bekijken</a></td>
					<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
						<td class="wijzigen"><a href="<?php echo base_url('opdrachten/opdrachten/wijzigen/'.$item->opdracht_ID) ?>" title="Opdracht wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('opdrachten/opdrachten/verwijderen/'.$item->opdracht_ID) ?>" title="Opdracht verwijderen">Verwijderen</a></td>
					<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen opdrachten in de database.</p>
	<?php endif; ?>
</div>