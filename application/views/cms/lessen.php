<h1><?php echo $workshop->workshop_titel ?> Lessen</h1>

<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
<p id="links">
	<a href="<?php echo base_url('cms/lessen/toevoegen') ?>" title="Les toevoegen">Les toevoegen</a>
</p>
<?php endif; ?>

<div id="nieuws">
	<?php if(sizeof($lessen) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="lessen">
			<thead>
				<tr>
					<th>Titel</th>
					<th>Locatie</th>
					<th>Workshop</th>
					<th>Beoordeling</th>
					<th class="bekijken"></th>
					<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
						<th class="wijzigen"></th>
						<th class="verwijderen"></th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($lessen as $item): ?>
					<tr data-item="<?php echo $item->les_ID ?>">
					<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
							<td><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
							<td><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo ucfirst($item->les_locatie) ?></a></td>
							<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
						<?php else: ?>
							<td><?php echo $item->les_titel ?></a></td>
							<td><?php echo ucfirst($item->les_locatie) ?></a></td>
							<td><?php echo $item->workshop_titel ?></a></td>
						<?php endif; ?>
                        <td>
                            <a href="<?php echo base_url('cms/lessen/les_beoordelingen/'.$item->les_ID) ?>" title="Beoordelingen bekijken">
                                <span>
                                <div class="rating_fixed" onclick="window.location.href='<?php echo base_url('cms/lessen/les_beoordelingen/'.$item->les_ID) ?>'">
                                <?php for($i = 0; $i < 5; $i++) { ?>
                                    <?php if(!empty($item->les_beoordeling)) { ?>
                                        <?php if ($item->les_beoordeling > 0) {?>
                                            <label class="selected">
                                                <input type="radio" name="rating">
                                            </label>
                                        <?php } else { ?>
                                            <label class="unselected">
                                                <input type="radio" name="rating">
                                            </label>
                                        <?php } $item->les_beoordeling--; ?>
                                    <?php } else { ?>
                                        <label class="unselected">
                                            <input type="radio" name="rating">
                                        </label>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                                </span>
                            </a>
                           </td>
						<td class="bekijken"><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken">Bekijken</a></td>
					<?php if($this->session->userdata('beheerder_rechten') != 'docent' && $this->session->userdata('beheerder_rechten') != 'opleidingsmedewerker'): ?>
						<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/wijzigen/'.$item->les_ID) ?>" title="Les wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/lessen/verwijderen/'.$item->les_ID) ?>" title="Les verwijderen">Verwijderen</a></td>
					<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php if($aantal_paginas > 1): ?>
			<div id="paginanummering">
				<p>
					<?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
						<?php if($i == $huidige_pagina): ?>
							<a href="<?php echo base_url('cms/lessen/pagina/'.$workshop->workshop_ID.'/'.$i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
						<?php else: ?>
							<a href="<?php echo base_url('cms/lessen/pagina/'.$workshop->workshop_ID.'/'.$i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
						<?php endif; ?>
						<?php if($i < $aantal_paginas) echo ' |'; ?>
					<?php endfor; ?>
				</p>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<p>Er staan geen lessen in de database.</p>
	<?php endif; ?>
</div>