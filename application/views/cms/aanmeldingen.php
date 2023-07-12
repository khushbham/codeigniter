<h1>Aanmeldingen workshops</h1>

<h2>Aanmeldingen</h2>
<p id="links">
    <a href="<?php echo base_url('cms/aanmeldingen/aanmeldingenExporteren') ?>" title="Nieuwe aanmeldingen exporteren">Nieuwe aanmeldingen exporteren</a>
    <a href="<?php echo base_url('cms/aanmeldingen/exportGeschiedenis') ?>">Export geschiedenis</a>
</p>
<ul class="tab">
    <li><a href="javascript:void(0)" id="tab_actief" class="tablinks <?php if($archief == false) echo 'active'; ?>" onclick="openArchief(event, 'actief')">Actief</a></li>
    <li><a href="javascript:void(0)" id="tab_archief" class="tablinks <?php if($archief == true) echo 'active'; ?>" onclick="openArchief(event, 'archief')">Archief</a></li>
</ul>
<input type="hidden" name="archief_open" id="archief_open" value="<?php echo $archief ?>">

<div id="aanmeldingen">
    <div id="actief" class="tabcontent">
	<?php if(sizeof($aanmeldingen) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
				<tr>
					<th class="nummer">#</th>
					<th class="datum">Datum</th>
					<th class="tijd">Tijd</th>
					<th>Type</th>
                    <th>Titel</th>
                    <th>Annul verzekering</th>
					<th>Deelnemer</th>
					<th class="betaald">Betaald</th>
					<th class="bekijken"></th>
                    <th class="wijzigen"></th>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
					    <th></th>
                    <?php endif; ?>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
					    <th class="verwijderen"></th>
                    <?php endif; ?>
				</tr>
			</thead>
			<tbody>
			<?php foreach($aanmeldingen as $item): ?>
				<tr>
					<td class="nummer"><?php echo $item->aanmelding_ID ?></td>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($item->aanmelding_datum)) ?></td>
					<?php if($item->aanmelding_type == 'kennismakingsworkshop')  { ?>
						<td style="padding-right: 5px"><?php echo ucfirst('workshop') ?></td>
					<?php } else { ?>
						<td style="padding-right: 5px"><?php echo ucfirst($item->aanmelding_type) ?></td>
					<?php } ?>
					<?php if(isset($item->workshop_ID)) : ?>
						<td style="padding: 5px"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
					<?php endif; ?>
					<?php if(isset($item->kennismakingsworkshop_ID)) : ?>
						<td style="padding: 5px"><a href="<?php echo base_url('cms/kennismakingsworkshop/'.$item->kennismakingsworkshop_ID) ?>" title="Kennismakingsworkshop bekijken"><?php echo $item->kennismakingsworkshop_titel ?></a></td>
                    <?php endif; ?>
                    <td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php echo ($item->annuleringsverzekering == 1) ? "Ja" : "Nee";  ?></a></td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td class="betaald"><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00'){ if($item->aanmelding_herinnering_datum == '0000-00-00 00:00:00') echo '<a href="'. base_url('cms/aanmeldingen/niet_betaald/'.$item->aanmelding_ID).'" onclick="return confirm(\'Weet je zeker dat je een betalingsherinnering wilt sturen?\')" title="Stuur Betalingsherinnering"><span class="nee verzenden"></span></a>'; else echo '<span class="nee herinnering"></span>'; } else echo '<span class="ja"></span>'; ?></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/aanmeldingen/'.$item->aanmelding_ID) ?>" title="Aanmelding bekijken">Bekijken</a></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/aanmeldingen/wijzigen/'.$item->aanmelding_ID) ?>" title="Aanmelding wijzigen">Wijzigen</a></td>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
                    <td class="wachtwoord">
                        <a href="<?php echo base_url('cms/aanmeldingen/wachtwoord/'.$item->aanmelding_ID) ?>" title="Nieuw wachtwoord versturen">W</a>
                    </td>
                    <?php endif; ?>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
					    <td class="verwijderen"><a href="<?php echo base_url('cms/deelnemers/afmelden/'.$item->aanmelding_ID) ?>" title="Deelnemer afmelden">Afmelden</a></td>
                    <?php endif; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen actieve aanmeldingen in de database.</p>
	<?php endif; ?>
    </div>

    <div id="archief" class="tabcontent">
    <?php if(sizeof($aanmeldingen_archief) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th class="nummer">#</th>
                <th class="datum">Datum</th>
                <th class="tijd">Tijd</th>
                <th>Type</th>
                <th>Titel</th>
                <th>Deelnemer</th>
                <th class="betaald">Betaald</th>
                <th class="bekijken"></th>
                <th class="wijzigen"></th>
                <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
                    <th class="verwijderen"></th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach($aanmeldingen_archief as $item): ?>
                <tr>
                    <td class="nummer"><?php echo $item->aanmelding_ID ?></td>
                    <td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
                    <td class="tijd"><?php echo date('H:i', strtotime($item->aanmelding_datum)) ?></td>
                    <?php if($item->aanmelding_type == 'kennismakingsworkshop')  { ?>
                        <td style="padding-right: 20px"><?php echo ucfirst('workshop') ?></td>
                    <?php } else { ?>
                        <td><?php echo ucfirst($item->aanmelding_type) ?></td>
                    <?php } ?>
                    <?php if(isset($item->workshop_ID)) : ?>
                        <td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
                    <?php endif; ?>
                    <?php if(isset($item->kennismakingsworkshop_ID)) : ?>
                        <td><a href="<?php echo base_url('cms/kennismakingsworkshop/'.$item->kennismakingsworkshop_ID) ?>" title="Kennismakingsworkshop bekijken"><?php echo $item->kennismakingsworkshop_titel ?></a></td>
                    <?php endif; ?>
                    <td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
                    <td class="betaald"><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00'){ if($item->aanmelding_herinnering_datum == '0000-00-00 00:00:00') echo '<a href="'. base_url('cms/aanmeldingen/niet_betaald/'.$item->aanmelding_ID).'" onclick="return confirm(\'Weet je zeker dat je een betalingsherinnering wilt sturen?\')" title="Stuur Betalingsherinnering"><span class="nee verzenden"></span></a>'; else echo '<span class="nee herinnering"></span>'; } else echo '<span class="ja"></span>'; ?></td>
                    <td class="bekijken"><a href="<?php echo base_url('cms/aanmeldingen/'.$item->aanmelding_ID) ?>" title="Aanmelding bekijken">Bekijken</a></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/aanmeldingen/wijzigen/'.$item->aanmelding_ID) ?>" title="Aanmelding wijzigen">Wijzigen</a></td>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
                        <td class="verwijderen"><a href="<?php echo base_url('cms/deelnemers/afmelden/'.$item->aanmelding_ID) ?>" title="Deelnemer afmelden">Afmelden</a></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er staan geen aanmeldingen in het archief.</p>
    <?php endif; ?>
    </div><br/>

	<h2>Afgerond</h2>
	<?php if(sizeof($aanmeldingen_afgerond) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<thead>
				<tr>
					<th class="nummer">#</th>
					<th class="datum">Datum</th>
					<th class="tijd">Tijd</th>
					<th>Type</th>
					<th>Workshop</th>
					<th>Deelnemer</th>
					<th class="betaald">Betaald</th>
					<th class="bekijken"></th>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
					    <th class="wijzigen"></th>
					    <th class="verwijderen"></th>
                    <?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($aanmeldingen_afgerond as $item): ?>
					<tr>
						<td class="nummer"><?php echo $item->aanmelding_ID ?></td>
						<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
						<td class="tijd"><?php echo date('H:i', strtotime($item->aanmelding_datum)) ?></td>
						<td><?php echo ucfirst($item->aanmelding_type) ?></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
						<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
						<td class="betaald"><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/aanmeldingen/'.$item->aanmelding_ID) ?>" title="Aanmelding bekijken">Bekijken</a></td>
                        <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
						    <td class="wijzigen"><a href="<?php echo base_url('cms/aanmeldingen/wijzigen/'.$item->aanmelding_ID) ?>" title="Aanmelding wijzigen">Wijzigen</a></td>
						    <td class="verwijderen"><a href="<?php echo base_url('cms/aanmeldingen/verwijderen/'.$item->aanmelding_ID) ?>" title="Aanmelding verwijderen">Verwijderen</a></td>
                        <?php endif; ?>
                    </tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen afgeronde aanmeldingen in de database.</p>
	<?php endif; ?>

    <h2>Verlopen</h2>
    <?php if(sizeof($aanmeldingen_verlopen) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th class="nummer">#</th>
                <th class="datum">Datum</th>
                <th class="tijd">Tijd</th>
                <th>Type</th>
                <th>Workshop</th>
                <th>Deelnemer</th>
                <th class="betaald">Betaald</th>
                <th class="bekijken"></th>
                <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
                    <th class="wijzigen"></th>
                    <th class="verwijderen"></th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach($aanmeldingen_verlopen as $item): ?>
                <tr>
                    <td class="nummer"><?php echo $item->aanmelding_ID ?></td>
                    <td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
                    <td class="tijd"><?php echo date('H:i', strtotime($item->aanmelding_datum)) ?></td>
                    <td><?php echo ucfirst($item->aanmelding_type) ?></td>
                    <td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
                    <td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
                    <td class="betaald"><?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></td>
                    <td class="bekijken"><a href="<?php echo base_url('cms/aanmeldingen/'.$item->aanmelding_ID) ?>" title="Aanmelding bekijken">Bekijken</a></td>
                    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
                        <td class="wijzigen"><a href="<?php echo base_url('cms/aanmeldingen/wijzigen/'.$item->aanmelding_ID) ?>" title="Aanmelding wijzigen">Wijzigen</a></td>
                        <td class="verwijderen"><a href="<?php echo base_url('cms/aanmeldingen/verwijderen/'.$item->aanmelding_ID) ?>" title="Aanmelding verwijderen">Verwijderen</a></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er staan geen verlopen aanmeldingen in de database.</p>
    <?php endif; ?>
</div>