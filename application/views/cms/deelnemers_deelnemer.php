<?php if(!empty($deelnemer->profiel_foto->media_src)) { ?>
	<a style="text-decoration:none;" id="profiel_foto" href="<?php echo base_url('media/uploads/') . $deelnemer->profiel_foto->media_src; ?>" class="preview"><h1><span class="<?php if($deelnemer->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $deelnemer->gebruiker_naam ?></span></h1><img class="preview" id="profiel_foto"/>
<?php } else { ?>
	<h1 id="deelnemer_naam"><span class="<?php if($deelnemer->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $deelnemer->gebruiker_naam ?></span></h1>
<?php } ?>
<p id="links">
	<a href="<?php echo base_url('cms/deelnemers/') ?>" title="Alle deelnemers">Alle deelnemers</a>
	<a href="<?php echo base_url('cms/deelnemers/wijzigen/'.$deelnemer->gebruiker_ID) ?>" title="Deelnemer wijzigen" class="wijzigen">Deelnemer wijzigen</a>
	<a href="<?php echo base_url('cms/deelnemers/alleLessenBekeken/'.$deelnemer->gebruiker_ID) ?>" title="Deelnemer wijzigen" class="wijzigen">Lessen bekeken</a>
    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
        <a href="<?php echo base_url('cms/deelnemers/verwijderen/'.$deelnemer->gebruiker_ID) ?>" title="Deelnemer verwijderen" class="verwijderen">Deelnemer verwijderen</a>
        <a href="<?php echo base_url('cms/deelnemers/inloggen/'.$deelnemer->gebruiker_ID) ?>" title="Inloggen als <?php echo $deelnemer->gebruiker_voornaam ?>" class="algemeen">Inloggen als <?php echo $deelnemer->gebruiker_voornaam ?></a>
        <a href="<?php echo base_url('cms/deelnemers/exporteren/'.$deelnemer->gebruiker_ID) ?>" title="Groep exporteren">Deelnemer exporteren</a>
        <a onclick="printDeelnemer(<?php echo $deelnemer->gebruiker_ID ?>)" title="Printen">Printen</a>
    <?php endif; ?>
</p>
<h2>Gegevens</h2>
<table cellpadding="0" cellspacing="0" class="gegevens">
    <tr>
        <th>Rechten</th>
        <td><?php echo ucfirst($deelnemer->gebruiker_rechten) ?></td>
    </tr>
	<tr>
		<th>Status</th>
		<td><?php echo ucfirst($deelnemer->gebruiker_status) ?></td>
	</tr>
	<tr>
		<th>Markering</th>
		<td><?php echo ucfirst($deelnemer->gebruiker_markering) ?></td>
	</tr>
	<tr>
		<th>Bedrijfsnaam</th>
		<td><?php if(!empty($deelnemer->gebruiker_bedrijfsnaam)) echo $deelnemer->gebruiker_bedrijfsnaam; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Geslacht</th>
		<td><?php if(!empty($deelnemer->gebruiker_geslacht)) echo ucfirst($deelnemer->gebruiker_geslacht); else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Geboortedatum</th>
		<td><?php if(!empty($deelnemer->gebruiker_geboortedatum) && $deelnemer->gebruiker_geboortedatum != '0000-00-00') echo date('d/m/Y', strtotime($deelnemer->gebruiker_geboortedatum)); else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Adres</th>
		<td><?php if(!empty($deelnemer->gebruiker_adres)) echo $deelnemer->gebruiker_adres; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Postcode</th>
		<td><?php if(!empty($deelnemer->gebruiker_postcode)) echo $deelnemer->gebruiker_postcode; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Plaats</th>
		<td><?php if(!empty($deelnemer->gebruiker_plaats)) echo $deelnemer->gebruiker_plaats; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Telefoonnummer</th>
		<td><?php if(!empty($deelnemer->gebruiker_telefoonnummer)) echo $deelnemer->gebruiker_telefoonnummer; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Mobiel</th>
		<td><?php if(!empty($deelnemer->gebruiker_mobiel)) echo $deelnemer->gebruiker_mobiel; else echo $deelnemer->gebruiker_mobiel; ?></td>
	</tr>
	<tr>
		<th>E-mailadres</th>
		<td><?php echo $deelnemer->gebruiker_emailadres ?></td>
	</tr>
	<tr>
		<th>Notities</th>
		<td><?php if(!empty($deelnemer->gebruiker_notities)) echo $deelnemer->gebruiker_notities; else echo '...'; ?></td>
	</tr>
</table>
<h2>Instellingen</h2>
<table cellpadding="0" cellpadding="0" class="gegevens">
	<tr>
		<th>Instelling anoniem</th>
		<td><?php echo ucfirst($deelnemer->gebruiker_instelling_anoniem) ?></td>
	</tr>
	<tr>
		<th>Instelling e-mail updates</th>
		<td><?php echo ucfirst($deelnemer->gebruiker_instelling_email_updates) ?></td>
	</tr>
</table>
<?php if(sizeof($afspraken) > 0): ?>
	<h2>Afspraken</h2>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="datum">Aangemeld</th>
			<th>Type</th>
			<th>Workshop</th>
			<th>Afspraak</th>
			<th>Voldoende</th>
			<th class="wijzigen"></th>
    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
			<th class="verwijderen"></th>
        <?php endif; ?>
		</tr>
		<?php foreach($afspraken as $item): ?>
			<tr>
				<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
				<td><?php echo ucfirst($item->aanmelding_type) ?></td>
				<td><?php echo ucfirst($item->workshop_titel) ?></td>
				<td><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak wijzigen"><?php if($item->aanmelding_afspraak == '0000-00-00 00:00:00') echo 'Afspraak maken!'; else echo date('d-m-Y', strtotime($item->aanmelding_afspraak)); ?></a></td>
				<td><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak wijzigen"><?php echo ucfirst($item->aanmelding_voldoende) ?></a></td>
				<td class="wijzigen"><a href="<?php echo base_url('cms/afspraken/'.$item->aanmelding_ID) ?>" title="Afspraak wijzigen"></a></td>
            <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
				<td class="verwijderen"><a href="<?php echo base_url('cms/deelnemers/afmelden/'.$item->aanmelding_ID) ?>" title="Deelnemer afmelden"></a></td>
            <?php endif; ?>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>
<h2>Workshops</h2>
<?php if(sizeof($aanmeldingen) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="datum">Aangemeld</th>
			<th>Betaald</th>
			<th>Type</th>
			<th>Workshop</th>
			<th>Annul verzekering</th>
			<th>Groep</th>
			<th>Beoordeling</th>
			<th>Afgerond</th>
        <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
			<th class="bekijken"></th>
			<th class="verwijderen"></th>
        <?php endif; ?>
		</tr>
		<?php foreach($aanmeldingen as $item): ?>
			<tr>
				<td class="datum"><?php echo date('d/m/Y', strtotime($item->aanmelding_datum)) ?></td>
				<td><?php if($item->aanmelding_betaald_datum != '' && $item->aanmelding_betaald_datum != '0000-00-00 00:00:00') echo date('d-m-Y', strtotime($item->aanmelding_betaald_datum)); else echo 'Nog niet betaald'; ?></td>
				<td><?php echo ucfirst($item->aanmelding_type) ?></td>
				<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Bekijk workshop"><?php echo $item->workshop_titel ?></a></td>
				<td><?php echo ($item->annuleringsverzekering == 1) ? "Ja" : "Nee"; ?></td>
				<td><?php if(in_array($item->workshop_type, array('groep', 'online'))) echo '<a href="'.base_url('cms/groepen/'.$item->groep_ID).'" title="Bekijk groep">'.$item->groep_naam.'</a>'; else echo 'Individueel'; ?></td>
                <td>
                    <a href="<?php echo base_url('cms/groepen/gebruiker_beoordelingen/'.$deelnemer->gebruiker_ID. '/'. $item->workshop_ID) ?>" title="Beoordelingen bekijken">
                        <span>
                            <div class="rating_fixed" onclick="window.location.href='<?php echo base_url('cms/groepen/gebruiker_beoordelingen/'.$deelnemer->gebruiker_ID. '/'. $item->workshop_ID) ?>'">
                                <?php for($i = 0; $i < 5; $i++) { ?>
                                    <?php if(!empty($item->gebruiker_beoordeling)) { ?>
                                        <?php if ($item->gebruiker_beoordeling > 0) {?>
                                            <label class="selected">
                                                <input type="radio" name="rating">
                                            </label>
                                        <?php } else { ?>
                                            <label class="unselected">
                                                <input type="radio" name="rating">
                                            </label>
                                        <?php } $item->gebruiker_beoordeling--; ?>
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
				<td><?php if($item->aanmelding_afgerond): ?>Ja<?php else: ?>Nee<?php endif; ?></td>
            <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
				<td class="bekijken"><a href="<?php echo base_url('cms/deelnemers/bekeken/'.$item->workshop_ID. "/" . $deelnemer->gebruiker_ID) ?>" title="bekeken lessen bekijken">Bekijken</a></td>
				<td class="verwijderen"><a href="<?php echo base_url('cms/deelnemers/afmelden/'.$item->aanmelding_ID) ?>" title="Deelnemer afmelden"></a></td>
            <?php endif; ?>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p><em><?php echo $deelnemer->gebruiker_voornaam ?> volgt nog geen workshops.</em></p>
<?php endif; ?>
<p><a href="<?php echo base_url('cms/deelnemers/aanmelden/'.$deelnemer->gebruiker_ID) ?>" title="Aanmelden voor workshop">Aanmelden voor workshop</a></p>
<?php if(sizeof($bestellingen) > 0): ?>
	<div id="bestellingen">
		<h2>Bestellingen</h2>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th>#</th>
				<th class="datum">Datum</th>
				<th>Tijd</th>
				<th>Deelnemer</th>
				<th>Betaald</th>
				<th>Verzonden</th>
				<th class="wijzigen"></th>
			</tr>
			<?php foreach($bestellingen as $item): ?>
				<tr>
					<td><?php echo $item->bestelling_ID ?></td>
					<?php
					if ($item->aanmelding_ID != null)
					{
						$datum = $item->aanmelding_datum;
						$betaald_datum = $item->aanmelding_betaald_datum;
					}
					else
					{
						$datum = $item->bestelling_datum;
						$betaald_datum = $item->bestelling_betaald_datum;
					}
					?>
					<td><?php echo date('d/m/Y', strtotime($datum)) ?></td>
					<td><?php echo date('H:i', strtotime($datum)) ?> uur</td>
					<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td><?php if($betaald_datum == '0000-00-00 00:00:00') echo '<span class="fout">Nee</span>'; else echo '<span class="goed">Ja</span>'; ?></td>
					<td><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php if($item->bestelling_verzonden_datum == '0000-00-00 00:00:00') echo '<span class="fout">Nee</span>'; else echo '<span class="goed">Ja</span>'; ?></a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling wijzigen"></a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>
<?php if(sizeof($resultaten) > 0): ?>
<div id="huiswerk">
	<h2>Opdrachten</h2>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th>Ingestuurd</th>
			<th>Les</th>
			<th>Workshop</th>
			<th></th>
			<th>Beoordeling</th>
			<th class="bekijken"></th>
			<th class="wijzigen"></th>
		</tr>
		<?php foreach($resultaten as $item): ?>
			<?php if($item->resultaat_opnieuw_ingestuurd_datum == '' || $item->resultaat_opnieuw_ingestuurd_datum == '0000-00-00 00:00:00'): ?>
				<tr>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->resultaat_ingestuurd_datum)) ?></td>
					<td><?php echo $item->les_titel ?></td>
					<td><?php echo $item->workshop_titel ?></td>
					<td>1e keer</td>
					<td><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Opdrachten bekijken"><?php if($item->resultaat_voldoende == 'ja') echo 'Voldoende'; else echo 'Onvoldoende'; ?></a></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Beoordeling bekijken"></a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/edit-review/'.$item->resultaat_ID) ?>" title="Opdracht wijzigen">Opdracht wijzigen</a></td>
				</tr>
			<?php else: ?>
				<tr>
					<td class="datum"><?php echo date('d/m/Y', strtotime($item->resultaat_opnieuw_ingestuurd_datum)) ?></td>
					<td><?php echo $item->les_titel ?></td>
					<td><?php echo $item->workshop_titel ?></td>
					<td>2e keer</td>
					<td><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Opdrachten bekijken"><?php if($item->resultaat_opnieuw_voldoende == 'ja') echo 'Voldoende'; else echo 'Onvoldoende'; ?></a></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/huiswerk/beoordelen/'.$item->resultaat_ID) ?>" title="Beoordeling bekijken"></a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/edit-review/'.$item->resultaat_ID) ?>" title="Opdracht wijzigen">Opdracht wijzigen</a></td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
	</table>
	</div>
<?php endif; ?>
<?php if(sizeof($beoordelingen) > 0): ?>
<div id="beoordelingen">
<h2>Beoordeling van de lessen</h2>
<table cellpadding="0" cellspacing="0" class="tabel">
	<tr>
		<th>Workshop</th>
		<th>Les</th>
		<th>Beoordeling</th>
		<th>Opmerking</th>
	</tr>
		<?php foreach($beoordelingen as $beoordeling): ?>
    <tr>
        <td><a href="<?php echo base_url('cms/workshops/'.$beoordeling->workshop_ID) ?>" title="Bekijk workshop"><?php echo $beoordeling->workshop_titel ?></a></td>
        <td><a href="<?php echo base_url('cms/lessen/'.$beoordeling->les_ID) ?>" title="Les bekijken"><?php echo $beoordeling->les_titel ?></a></td>
        <td>
            <span>
				<div class="rating_fixed" onclick="window.location.href='<?php echo base_url('cms/lessen/les_beoordelingen/'.$beoordeling->les_ID) ?>'">
					<?php for($i = 0; $i < 5; $i++) { ?>
						<?php if(!empty($beoordeling->les_beoordeling)) { ?>
							<?php if ($beoordeling->les_beoordeling > 0) {?>
								<label class="selected">
									<input type="radio" name="rating">
								</label>
							<?php } else { ?>
								<label class="unselected">
									<input type="radio" name="rating">
								</label>
							<?php } $beoordeling->les_beoordeling--; ?>
						<?php } else { ?>
							<label class="unselected">
								<input type="radio" name="rating">
							</label>
						<?php } ?>
					<?php } ?>
				</div>
            </span>
        </td>
        <td><?php echo $beoordeling->les_opmerking ?></td>
    </tr>
	<?php endforeach; ?>
</table>
</div>
<?php endif; ?>