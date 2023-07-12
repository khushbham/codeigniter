<h1>Groepen</h1>

<p id="links">
	<a href="<?php echo base_url('cms/groepen/toevoegen') ?>" title="Groep toevoegen">Groep toevoegen</a>
</p>

<h2>Filters</h2>
<p>
<form method="post" action="<?php echo base_url('cms/groepen/pagina/'.$huidige_pagina.'/'.$huidige_pagina_archief . '/' . $huidige_pagina_actief . '/'. false . '/'. false . '/' . $filter) ?>">
	<label for="filter_locatie">Filter locatie</label>
	<select name="filter_locatie" id="filter_locatie">
		<option value="">Selecteer locatie</option>
		<option value="">---</option>
		<?php foreach($locaties as $locatie) { ?>
			<option value="<?php echo $locatie->locatie_ID ?>" <?php if($locatie->locatie_ID == $filter) echo 'selected'; ?>><?php echo $locatie->locatie_adres ?></option>
		<?php } ?>
		<option value="4" <?php if(4 == $filter) echo 'selected'; ?>>Handmatig ingevoerd / anders</option>
	</select>
</p>
<p><input type="submit" value="Filter groepen"></p>
</form>

<?php  if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support' || $this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker' || $this->session->userdata('beheerder_rechten') == 'docent'): ?>
<ul class="tab">
    <li><a href="javascript:void(0)" id="tab_actief" class="tablinks <?php if($archief == 0 && $groepen == 0 && $aanmelden == 0) echo 'active'; ?>"  onclick="openArchief(event, 'actief')">Actief</a></li>
	<li><a href="javascript:void(0)" id="tab_aanmelden" class="tablinks <?php if($archief == 0 && $groepen == 0 && $aanmelden == 1) echo 'active'; ?>"  onclick="openArchief(event, 'aanmelden')">Aanmelden</a></li>
	<li><a href="javascript:void(0)" id="tab_groepen" class="tablinks <?php if($archief == 0 && $groepen == 1 && $aanmelden == 0) echo 'active'; ?>"  onclick="openArchief(event, 'groepen')">Groepen</a></li>
	<li><a href="javascript:void(0)" id="tab_archief" class="tablinks <?php if($archief == 1 && $groepen == 0 && $aanmelden == 0) echo 'active'; ?>" onclick="openArchief(event, 'archief')">Archief</a></li>
</ul>
<input type="hidden" name="aanmelden_open" id="aanmelden_open" value="<?php echo $aanmelden ?>">
<input type="hidden" name="archief_open" id="archief_open" value="<?php echo $archief ?>">
<input type="hidden" name="groepen_open" id="groepen_open" value="<?php echo $groepen ?>">
<div id="nieuws">
<div id="aanmelden" class="tabcontent">
		<?php if(sizeof($groepen_aanmelden) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel" width="">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Titel</th>
					<th>Workshop</th>
					<th class="archiveren"></th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($groepen_aanmelden as $item): ?>
					<?php if($item->plekken_over > 0): ?>
						<tr <?php if($item->groep_aanmelden == 'nee') echo 'class="concept"'; ?>>
							<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->groep_naam ?></a></td>
							<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php if($item->groep_titel) echo $item->groep_titel; else echo "..." ?></a></td>
							<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->workshop_titel ?></a></td>
							<td class="archiveren"><a href="<?php echo base_url('cms/groepen/archiveren/'.$item->groep_ID . '/'. $huidige_pagina . '/' . false) ?>" title="Groep archiveren">archiveren</a></td>
							<td class="bekijken"><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken">Bekijken</a></td>
							<td class="wijzigen"><a href="<?php echo base_url('cms/groepen/wijzigen/'.$item->groep_ID) ?>" title="Groep wijzigen">Wijzigen</a></td>
							<td class="verwijderen"><a href="<?php echo base_url('cms/groepen/verwijderen/'.$item->groep_ID) ?>" title="Groep verwijderen">Verwijderen</a></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p>Er staan geen open voor aanmelden groepen in de database.</p>
		<?php endif; ?>
	</div>

<div id="actief" class="tabcontent">
		<?php if(sizeof($groepen_actief) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel" width="">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Titel</th>
					<th>Workshop</th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($groepen_actief as $item): ?>
					<tr <?php if($item->groep_aanmelden == 'nee') echo 'class="concept"'; ?>>
						<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->groep_naam ?></a></td>
						<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php if($item->groep_titel) echo $item->groep_titel; else echo "..." ?></a></td>
						<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->workshop_titel ?></a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/groepen/wijzigen/'.$item->groep_ID) ?>" title="Groep wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/groepen/verwijderen/'.$item->groep_ID) ?>" title="Groep verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php if($aantal_paginas_actief > 1): ?>
			<div id="paginanummering">
				<p>
					<?php for($i = 1; $i <= $aantal_paginas_actief; $i++): ?>
							<?php if($i == $huidige_pagina_actief): ?>
								<a href="<?php echo base_url('cms/groepen/pagina/'.$huidige_pagina.'/'.$huidige_pagina_archief . '/'. $i . '/'. false . '/'. false . '/' . $filter) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
							<?php else: ?>
								<a href="<?php echo base_url('cms/groepen/pagina/'.$huidige_pagina.'/'.$huidige_pagina_archief . '/' . $i . '/'. false . '/'. false . '/' . $filter) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
							<?php endif; ?>
						<?php if($i < $aantal_paginas) echo ' |'; ?>
					<?php endfor; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php else: ?>
			<p>Er staan geen actieve groepen in de database.</p>
		<?php endif; ?>
	</div>

	<div id="groepen" class="tabcontent">
		<?php if(sizeof($alle_groepen) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel" width="">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Titel</th>
					<th>Workshop</th>
					<th class="archiveren"></th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($alle_groepen as $item): ?>
					<tr <?php if($item->groep_aanmelden == 'nee') echo 'class="concept"'; ?>>
						<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->groep_naam ?></a></td>
						<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php if($item->groep_titel) echo $item->groep_titel; else echo "..." ?></a></td>
						<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->workshop_titel ?></a></td>
						<td class="archiveren"><a href="<?php echo base_url('cms/groepen/archiveren/'.$item->groep_ID . '/'. $huidige_pagina . '/' . false) ?>" title="Groep archiveren">archiveren</a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/groepen/wijzigen/'.$item->groep_ID) ?>" title="Groep wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/groepen/verwijderen/'.$item->groep_ID) ?>" title="Groep verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php if($aantal_paginas > 1): ?>
			<div id="paginanummering">
				<p>
					<?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
							<?php if($i == $huidige_pagina): ?>
								<a href="<?php echo base_url('cms/groepen/pagina/'.$i.'/'.$huidige_pagina_archief . '/' . $huidige_pagina_actief . '/'. 0 . '/'. 1 . '/' . $filter) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
							<?php else: ?>
								<a href="<?php echo base_url('cms/groepen/pagina/'.$i.'/'.$huidige_pagina_archief . '/' . $huidige_pagina_actief . '/'. 0 . '/'. 1 . '/' . $filter) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
							<?php endif; ?>
						<?php if($i < $aantal_paginas) echo ' |'; ?>
					<?php endfor; ?>
				</p>
			</div>
		<?php endif; ?>
		<?php else: ?>
			<p>Er staan geen groepen in de database.</p>
		<?php endif; ?>
	</div>

    <div id="archief" class="tabcontent">
        <?php if(sizeof($groepen_archief) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Titel</th>
                <th>Workshop</th>
                <th class="archiveren"></th>
                <th class="bekijken"></th>
                <th class="wijzigen"></th>
                <th class="verwijderen"></th>
            </tr>
            </thead>
            <tbody>
        <?php foreach($groepen_archief as $item): ?>
            <tr <?php if($item->groep_aanmelden == 'nee') echo 'class="concept"'; ?>>
                <td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->groep_naam ?></a></td>
                <td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php if($item->groep_titel) echo $item->groep_titel; else echo "..."; ?></a></td>
                <td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->workshop_titel ?></a></td>
                <td class="dearchiveren"><a href="<?php echo base_url('cms/groepen/terugzetten/'.$item->groep_ID . '/'. $huidige_pagina  . '/' . true) ?>" title="Groep terugzetten">Terugzetten</a></td>
                <td class="bekijken"><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken">Bekijken</a></td>
                <td class="wijzigen"><a href="<?php echo base_url('cms/groepen/wijzigen/'.$item->groep_ID) ?>" title="Groep wijzigen">Wijzigen</a></td>
                <td class="verwijderen"><a href="<?php echo base_url('cms/groepen/verwijderen/'.$item->groep_ID) ?>" title="Groep verwijderen">Verwijderen</a></td>
            </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
        <?php if($aantal_paginas_archief > 1): ?>
            <div id="paginanummering">
                <p>
                    <?php for($i = 1; $i <= $aantal_paginas_archief; $i++): ?>
                            <?php if($i == $huidige_pagina_archief): ?>
								<a href="<?php echo base_url('cms/groepen/pagina/'.$huidige_pagina.'/'. $i . '/' . $huidige_pagina_actief . '/'. 1 . '/'. 0 . '/' . $filter) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
                            <?php else: ?>
                                <a href="<?php echo base_url('cms/groepen/pagina/'.$huidige_pagina.'/'. $i . '/' . $huidige_pagina_actief .'/'. 1 . '/'. 0 . '/' . $filter) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
                            <?php endif ?>
                        <?php if($i < $aantal_paginas_archief) echo ' |'; ?>
                    <?php endfor; ?>
                </p>
            </div>
        <?php endif; ?>
        <?php else: ?>
            <p>Er staan geen groepen in de database.</p>
        <?php endif; ?>
    </div>
	</div>
	<?php else: ?>
    <div id="groepen">
        <?php if(sizeof($alle_groepen) > 0): ?>
            <table cellpadding="0" cellspacing="0" class="tabel" width="">
                <thead>
                <tr>
                    <th>Naam</th>
                    <th>Workshop</th>
                    <th class="bekijken"></th>
                    <th class="wijzigen"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($alle_groepen as $item): ?>
                    <tr <?php if($item->groep_aanmelden == 'nee') echo 'class="concept"'; ?>>
                        <td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->groep_naam ?></a></td>
                        <td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->workshop_titel ?></a></td>
                        <td class="bekijken"><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken">Bekijken</a></td>
                        <td class="wijzigen"><a href="<?php echo base_url('cms/groepen/wijzigen/'.$item->groep_ID) ?>" title="Groep wijzigen">Wijzigen</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if($aantal_paginas > 1): ?>
                <div id="paginanummering">
                    <p>
                        <?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
                            <?php if($i == $huidige_pagina): ?>
                                <a href="<?php echo base_url('cms/groepen/pagina/'.$i.'/'.$huidige_pagina_archief . '/' . $huidige_pagina_actief . '/'. 0 . '/'. 1 . '/' . $filter) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
                            <?php else: ?>
                                <a href="<?php echo base_url('cms/groepen/pagina/'.$i.'/'.$huidige_pagina_archief . '/' . $huidige_pagina_actief . '/'. 0 . '/'. 1 . '/' . $filter) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
                            <?php endif; ?>
                            <?php if($i < $aantal_paginas) echo ' |'; ?>
                        <?php endfor; ?>
                    </p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>Er staan geen groepen in de database.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>
<br><br>