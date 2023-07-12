<h1>Deelnemers</h1>

<div id="links">
	<a href="<?php echo base_url('cms/deelnemers/toevoegen') ?>" title="Deelnemer toevoegen">Deelnemer toevoegen</a>
	<?php if(sizeof($deelnemers) > 0): ?>
		<div id="bericht_sturen">
			<?php
			$deelnemerslijst = '';
			foreach($deelnemers as $deelnemer) $deelnemerslijst .= $deelnemer->gebruiker_ID.',';
			$deelnemerslijst = trim($deelnemerslijst, ",");
			?>
			<form method="post" action="<?php echo base_url('cms/berichten/nieuw') ?>">
				<input type="hidden" name="deelnemerslijst" id="deelnemerslijst" value="<?php echo $deelnemerslijst ?>" />
				<input type="submit" value="Bericht sturen" />
			</form>
		</div>
	<?php endif; ?>
</div>

<div id="filters">
	<h2>Filters</h2>
	<form method="post" action="<?php echo base_url('cms/deelnemers') ?>">
		<div id="filter_archief_div">
			<p>
				<label for="filter_archief">Zoeken in</label>
				<input type="radio" name="filter_archief" id="filter_archief2" onchange="update_groep(this)" value="0" <?php if($filter_archief == '0') echo 'checked'?>/> Actieve groepen <input type="radio" name="filter_archief" id="filter_archief1" value="1" onchange="update_groep(this)" <?php if($filter_archief == '1') echo 'checked'; ?> /> Gearchiveerde groepen
			</p>
		</div>
		<p>
			<label for="filter_status">Status</label>
			<select name="filter_status" id="filter_status">
				<option value="">Selecteer status</option>
				<option value="">---</option>
				<option value="concept" <?php if($filter_status == 'concept') echo 'selected'; ?>>Concept</option>
				<option value="actief" <?php if($filter_status == 'actief') echo 'selected'; ?>>Actief</option>
				<option value="inactief" <?php if($filter_status == 'inactief') echo 'selected'; ?>>Inactief</option>
			</select>
		</p>
		<p>
			<label for="filter_ingelogd">Ingelogd</label>
			<select name="filter_ingelogd" id="filter_ingelogd">
				<option value="">Selecteer ingelogd</option>
				<option value="">---</option>
				<option value="ja" <?php if($filter_ingelogd == 'ja') echo 'selected'; ?>>Ja</option>
				<option value="nee" <?php if($filter_ingelogd == 'nee') echo 'selected'; ?>>Nee</option>
			</select>
		</p>
		<p>
			<label for="filter_geslacht">Geslacht</label>
			<select name="filter_geslacht" id="filter_geslacht">
				<option value="">Selecteer geslacht</option>
				<option value="">---</option>
				<option value="man" <?php if($filter_geslacht == 'man') echo 'selected'; ?>>Man</option>
				<option value="vrouw" <?php if($filter_geslacht == 'vrouw') echo 'selected'; ?>>Vrouw</option>
			</select>
		</p>
		<p>
			<label for="filter_workshop">Workshop</label>
			<select name="filter_workshop" id="filter_workshop" onchange="update_groep(this)">
				<option value="">Selecteer workshop</option>
				<option value="">---</option>
				<?php foreach($workshops as $item): ?>
					<option value="<?php echo $item->workshop_ID ?>" <?php if($filter_workshop == $item->workshop_ID) echo 'selected'; ?>><?php echo $item->workshop_titel ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<div id="filter_groep_div">
			<p>
			<label for="filter_groep">Groep</label>
			<select name="filter_groep" id="filter_groep">
				<option value="">Selecteer groep</option>
				<option value="">---</option>
				<?php foreach($groepen as $item): ?>
					<option value="<?php echo $item->groep_ID ?>" <?php if($filter_groep == $item->groep_ID) echo 'selected'; ?>><?php echo $item->groep_naam ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		</div>
		<div id="filter_beoordeling_div">
			<p>
			<label for="filter_beoordeling">Beoordeling</label>
			<select name="filter_beoordeling" id="filter_beoordeling">
				<option value="">Selecteer beoordeling</option>
				<option value="">---</option>
					<option value="1" <?php if($filter_beoordeling == 1) echo 'selected'; ?>>1</option>
					<option value="2" <?php if($filter_beoordeling == 2) echo 'selected'; ?>>2</option>
					<option value="3" <?php if($filter_beoordeling == 3) echo 'selected'; ?>>3</option>
					<option value="4" <?php if($filter_beoordeling == 4) echo 'selected'; ?>>4</option>
					<option value="5" <?php if($filter_beoordeling == 5) echo 'selected'; ?>>5</option>
			</select>
		</p>
		</div>
		<input type="hidden" name="laatste_groep" id="laatste_groep" value="<?php echo $filter_groep ?>" />
		<!--
		<p>
			<label for="filter_huiswerk">Huiswerk ingestuurd?</label>
			<select name="filter_huiswerk" id="filter_huiswerk">
				<option value="">Maakt niet uit</option>
				<option value="">---</option>
				<option value="ja" <?php if($filter_huiswerk == 'ja') echo 'selected'; ?>>Ja</option>
				<option value="nee" <?php if($filter_huiswerk == 'nee') echo 'selected'; ?>>Nee</option>
				<option value="gedeeltelijk" <?php if($filter_huiswerk == 'gedeeltelijk') echo 'selected'; ?>>Gedeeltelijk</option>
			</select>
		</p>
		<p>
			<label for="filter_updates">E-mail updates</label>
			<select name="filter_updates" id="filter_updates">
				<option value="">Selecteer e-mail updates instelling</option>
				<option value="">---</option>
				<option value="ja" selected>Ja</option>
				<option value="nee">Nee</option>
				<option value="beide">Beide</option>
			</select>
		</p>
		-->
		<p><input type="submit" value="Filter deelnemers"></p>
	</form>
</div>

<h2><?php echo sizeof($deelnemers) ?> / <?php echo $aantal_deelnemers ?> deelnemers</h2>
<?php if(sizeof($deelnemers) > 0): ?>
<?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
<div id="deelnemers_actie">
	<form id="deelnemers_actie" method="post" action="<?php echo base_url('cms/deelnemers/deelnemersExporteren/') ?>">
		<input type="hidden" name="deelnemerslijst" id="deelnemerslijst" value="<?php echo $deelnemerslijst ?>" />
		<p><input type="submit" value="Deelnemers exporteren"></p>
	</form>
	<form method="post" action="<?php echo base_url('cms/deelnemers/deelnemersStatusActief/') ?>">
		<input type="hidden" name="deelnemerslijst" id="deelnemerslijst" value="<?php echo $deelnemerslijst ?>" />
		<p><input type="submit" value="Deelnemers status actief"></p>
	</form>
	<form method="post" action="<?php echo base_url('cms/deelnemers/deelnemersStatusInactief/') ?>">
		<input type="hidden" name="deelnemerslijst" id="deelnemerslijst" value="<?php echo $deelnemerslijst ?>" />
		<p><input type="submit" value="Deelnemers status inactief"></p>
	</form>
	<input type="button" value="Print lijst" id="printbutton" onclick="printContent('deelnemerslijst')">
</div>
<?php endif; ?>
<?php endif; ?>
<div id="deelnemers">
	<?php if(sizeof($deelnemers) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
				<tr>
					<th></th>
					<th>Deelnemer</th>
					<th>E-mailadres</th>
                    <th>Rechten</th>
					<th>Status</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
					    <th class="verwijderen"></th>
                    <?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($deelnemers as $item):
				?>
					<tr class="<?php echo $item->gebruiker_status ?>">
						<td>
							<?php if(!empty($item->profiel_foto->media_src)) { ?>
								<a id="profiel_foto" href="<?php echo base_url('media/uploads/') . $item->profiel_foto->media_src; ?>" class="preview">Foto<img class="preview" id="profiel_foto"/>
							<?php } ?>
						</td>
						<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
						<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php echo $item->gebruiker_emailadres ?></a></td>
						<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php echo $item->gebruiker_rechten ?></a></td>
						<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php echo ucfirst($item->gebruiker_status) ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/deelnemers/wijzigen/'.$item->gebruiker_ID) ?>" title="Deelnemer wijzigen">Wijzigen</a></td>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
						<td class="verwijderen"><a href="<?php echo base_url('cms/deelnemers/verwijderen/'.$item->gebruiker_ID) ?>" title="Deelnemer verwijderen">Verwijderen</a></td>
                    <?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Geen deelnemers gevonden die voldoen aan de geselecteerde filters.</p>
	<?php endif; ?>
</div>
