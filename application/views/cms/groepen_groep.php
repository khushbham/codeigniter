<h1 id="groep_naam">Groep <?php echo $item->groep_naam ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/groepen/') ?>" title="Alle groepen">Alle groepen</a>
	<?php if ($item->groep_archiveren == 0) : ?>
		<a href="<?php echo base_url('cms/groepen/wijzigen/' . $item->groep_ID) ?>" title="Groep wijzigen" class="wijzigen">Wijzigen</a>
		<a href="<?php echo base_url('cms/groepen/archiveren/'.$item->groep_ID) ?>" title="Groep archiveren" class="archiveren">Archiveren</a>
	<?php else : ?>
		<a href="<?php echo base_url('cms/groepen/terugzetten/'.$item->groep_ID) ?>" title="Groep terugzetten" class="archiveren">Terugzetten</a>
	<?php endif; ?>
	<?php if ($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support' || $this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker') : ?>
		<?php if ($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support') : ?>
			<a href="<?php echo base_url('cms/groepen/verwijderen/' . $item->groep_ID) ?>" title="Groep verwijderen" class="verwijderen">Verwijderen</a>
			<a href="<?php echo base_url('cms/groepen/exporteren/' . $item->groep_ID) ?>" title="Groep exporteren">Exporteren</a>
		<?php endif; ?>
		<a href="<?php echo base_url('cms/lessen/extra_toevoegen/' . $item->groep_ID) ?>" title="Extra les inplannen">Extra les inplannen</a>
		<a href="<?php echo base_url('cms/lessen/live_toevoegen/' . $item->groep_ID) ?>" title="Live les inplannen">Live les inplannen</a>
		<?php if ($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support') : ?>
			<a onclick="printContent(<?php echo $item->groep_ID ?>)" title="Groep printen">Groep printen</a>
		<?php endif; ?>
		<a href="<?= base_url('cms/deelnemers/afdrukken/' . $item->groep_ID) ?>" target="_blank" style="margin-top:10px" title="Live les inplannen">Certificaten printen</a>
	<?php endif; ?>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Titel</th>
		<td><?php if ($item->groep_titel) echo $item->groep_titel;
			else echo "..." ?></td>
	</tr>
	<tr>
		<th>Workshop</th>
		<td><a href="<?php echo base_url('cms/workshops/' . $item->workshop_ID) ?>" title="Bekijk workshop"><?php echo $item->workshop_titel ?></a></td>
	</tr>
	<tr>
		<th>Status</th>
		<td><?php if ($item->groep_archiveren == 0) echo 'Actief';
			else echo 'Gearchiveerd'; ?></td>
	</tr>
	<tr>
		<th>Capaciteit</th>
		<td><?php echo $item->workshop_capaciteit ?> deelnemers</td>
	</tr>
	<tr>
		<th>Aanmelden</th>
		<td><?php echo ucfirst($item->groep_aanmelden) ?></td>
	</tr>
	<tr>
		<th>Startdatum</th>
		<td><?php if ($item->groep_startdatum != '0000-00-00') echo date('d-m-Y', strtotime($item->groep_startdatum));
			else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Actief datum</th>
		<td><?php if ($item->groep_actief_datum != '0000-00-00') echo date('d-m-Y', strtotime($item->groep_actief_datum));
			else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Archief datum</th>
		<td><?php if ($item->groep_archief_datum != '0000-00-00') echo date('d-m-Y', strtotime($item->groep_archief_datum));
			else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Geautomatiseerde mails</th>
		<td><?php if ($item->groep_geautomatiseerde_mails == 1) echo 'Aan';
			else echo 'Uit' ?></td>
	</tr>
	<tr>
		<th>Feedback mail</th>
		<td><?php if ($item->groep_feedback_mail == 1) echo 'Aan';
			else echo 'Uit' ?></td>
	</tr>
	<tr>
		<th>Notities</th>
		<td><?php if (!empty($item->groep_notities)) echo nl2br($item->groep_notities);
			else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Downloadlink mail</th>
		<td><?php if (!empty($item->groep_downloadlinkmail)) echo 'Ja';
			else echo 'Nee' ?></td>
	</tr>
	<tr>
		<th>Drempelwaarde aantal gebruikers</th>
		<td><?php if (!empty($item->groep_min_gebruikers)) echo $item->groep_min_gebruikers;
			else echo '...' ?></td>
	</tr>
	<tr>
		<th>Drempelwaarde mail versturen</th>
		<td><?php if (!empty($item->groep_drempelwaarde_versturen)) echo $item->groep_drempelwaarde_versturen . ' dagen van te voren';
			else echo '...' ?></td>
	</tr>
</table>

<div id="lessen">
	<h2>Lessen</h2>
	<?php if (sizeof($lessen) > 0) : ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th class="datum">Datum</th>
				<th class="tijden">Tijd</th>
				<th>Titel</th>
				<th>Docent</th>
				<th>Technicus</th>
				<th>Locatie</th>
				<th class="wijzigen"></th>
				<?php if ($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support' || $this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker') : ?>
					<th class="verwijderen"></th>
				<?php endif; ?>
			</tr>
			<?php
			foreach ($lessen as $les) :
				$groep_les = $this->lessen_model->getGroepLesByLesID($les->les_ID, $item->groep_ID);
				if ($groep_les == null) :
			?>
					<tr class="concept">
						<td class="datum">...</td>
						<td class="tijden">...</td>
						<td><a href="<?php echo base_url('cms/lessen/inplannen/' . $les->les_ID . '/' . $item->groep_ID) ?>" title="Les bekijken"><?php echo $les->les_titel ?></a></td>
						<td>...</td>
						<td>...</td>
						<td><a href="<?php echo base_url('cms/lessen/inplannen/' . $les->les_ID . '/' . $item->groep_ID) ?>" title="Les bekijken"><?php echo ucfirst($les->les_locatie) ?></a></td>
						<?php if ($les->les_type_ID == 21) { ?>
							<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/live_toevoegen/' . $item->groep_ID . '/' . $les->les_ID) ?>" title="Live les inplannen">Live wijzigen</a></td>
						<?php } else { ?>
							<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/inplannen/' . $les->les_ID . '/' . $item->groep_ID) ?>" title="Les inplannen">Inplannen</a></td>
						<?php } ?>
						<?php if ($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support' || $this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker') : ?>
							<td class="verwijderen"></td>
						<?php endif; ?>
					</tr>
				<?php else : ?>

					<?php
					for ($i = 1; $i <= sizeof($lessen); $i++) :
						if ($i == $les_active) $lessen[$i - 1]->les_class = 'active';
						elseif ($i < $les_active) $lessen[$i - 1]->les_class = 'geweest';
						else $lessen[$i - 1]->les_class = 'binnenkort';
					endfor;
					?>
					<tr class=<?php echo $les->les_class ?>>
						<td class="datum"><a href="<?php echo base_url('cms/lessen/groep/' . $les->les_ID . '/' . $item->groep_ID . '/' . $groep_les->groep_les_ID) ?>" title="Les wijzigen"><?php if ($groep_les->groep_les_datum != "0000-00-00 00:00:00") {
																																												echo date('d-m-Y', strtotime($groep_les->groep_les_datum));
																																											} else {
																																												echo "X";
																																											} ?></a></td>
						<td class="tijden"><a href="<?php echo base_url('cms/lessen/groep/' . $les->les_ID . '/' . $item->groep_ID . '/' . $groep_les->groep_les_ID) ?>" title="Les wijzigen"><?php if ($groep_les->groep_les_datum != "0000-00-00 00:00:00") {
																																													echo date('H:i', strtotime($groep_les->groep_les_datum));
																																												} else {
																																													echo "X";
																																												} ?><?php if ($groep_les->groep_les_datum != "0000-00-00 00:00:00") {
																																																																																	if (!empty($groep_les->groep_les_eindtijd)) echo ' - ' . substr($groep_les->groep_les_eindtijd, 0, 5);
																																																																																} else {
																																																																																	echo "";
																																																																																} ?></a></td>
						<td><a href="<?php echo base_url('cms/lessen/groep/' . $les->les_ID . '/' . $item->groep_ID . '/' . $groep_les->groep_les_ID) ?>" title="Les wijzigen"><?php echo $les->les_titel ?></a></td>
						<?php if (!empty($groep_les->docent_ID)) {
							$docent = $this->docenten_model->getDocentByID($groep_les->docent_ID);
						?>
							<td><?php echo $docent->docent_naam ?></td>
						<?php } else { ?>
							<td>...</td>
						<?php } ?>
						<?php if (!empty($groep_les->technicus)) {
						?>
							<td><?php echo $groep_les->technicus ?></td>
						<?php } else { ?>
							<td>...</td>
						<?php } ?>
						<?php if ($les->les_locatie == 'online') : ?>
							<td><a href="<?php echo base_url('cms/lessen/groep/' . $les->les_ID . '/' . $item->groep_ID . '/' . $groep_les->groep_les_ID) ?>" title="Les wijzigen">Online</td>
						<?php else : ?>
							<td><a href="<?php echo base_url('cms/lessen/groep/' . $les->les_ID . '/' . $item->groep_ID . '/' . $groep_les->groep_les_ID) ?>" title="Les wijzigen"><?php if ($groep_les->les_locatie_ID == 4) {
																																										echo $groep_les->groep_les_adres . ", " . $groep_les->groep_les_postcode . " " . $groep_les->groep_les_plaats;
																																									} else {
																																										echo $groep_les->locatie_adres;
																																									} ?></a></td>
						<?php endif; ?>
						<?php if ($les->les_locatie != 'online') : ?>
							<td class="bekijken"><a href="<?php echo base_url('cms/aanwezigheid/detail/' . $groep_les->groep_les_ID) ?>" title="Bekijk aanwezigheid">Aanwezigheid</a></td>
						<?php else : ?>
						<?php endif; ?>
						<?php if ($les->les_type_ID == 21) { ?>
							<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/live_wijzigen/' . $item->groep_ID . '/' . $les->les_ID) ?>" title="Live les wijzigen">Live wijzigen</a></td>
						<?php } else if (!empty($les->groep_ID)) { ?>
							<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/extra_wijzigen/' . $les->groep_ID . '/' . $les->les_ID) ?>" title="Les wijzigen">Wijzigen</a></td>
						<?php } else { ?>
							<td class="wijzigen"><a href="<?php echo base_url('cms/lessen/groep/' . $les->les_ID . '/' . $item->groep_ID . '/' . $groep_les->groep_les_ID) ?>" title="Les wijzigen">Wijzigen</a></td>
						<?php } ?>
						<?php if ($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support' || $this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker') : ?>
							<?php if ($les->les_type_ID == 21) { ?>
								<td class="verwijderen"><a href="<?php echo base_url('cms/lessen/live_verwijderen/' . $groep_les->les_ID . '/' . $item->groep_ID) ?>" title="Les verwijderen">Live les verwijderen</a></td>
							<?php } else { ?>
								<td class="verwijderen"><a href="<?php echo base_url('cms/lessen/uitplannen/' . $groep_les->groep_les_ID) ?>" title="Les uitplannen">Uitplannen</a></td>
							<?php } ?>
						<?php endif; ?>
					</tr>
			<?php
				endif;
			endforeach;
			?>
		</table>
	<?php else : ?>
		<p>Er zijn geen lessen toegevoegd aan deze workshop.</p>
	<?php endif; ?>
</div>


<form method="post" action="<?php echo base_url('cms/groepen/groep_migreren/' . $item->groep_ID) ?>">
	<p><label for="item_groep">Groep migreren</label>
		<select name="item_groep" id="item_groep">
			<option value="">Groep selecteren</option>
			<?php foreach ($groepen as $groep) : ?>
				<option value="<?php echo $groep->groep_ID ?>"><?php echo $groep->groep_naam ?></option>
			<?php endforeach; ?>
		</select>
		<input type="submit" name="groep_migreren" id="groep_migreren" value="Groep migreren" /></p>
</form>




<div id="deelnemers">
	<h2>Deelnemers</h2>
	<div id="deelnemer_toevoegen">
		<form method="post" action="<?php echo base_url('cms/groepen/deelnemer_toevoegen/' . $item->groep_ID) ?>">
			<p><label for="item_deelnemer">Deelnemer</label>
				<select name="item_deelnemer" id="item_deelnemer">
					<option value="">Deelnemer selecteren</option>
					<?php foreach ($alle_deelnemers as $deelnemer) : ?>
						<option value="<?php echo $deelnemer->gebruiker_ID ?>"><?php echo $deelnemer->gebruiker_naam ?></option>
					<?php endforeach; ?>
				</select>
				<input type="submit" name="deelnemer_toevoegen" id="deelnemer_toevoegen" value="Deelnemer toevoegen" /></p>
		</form>
	</div>
	<?php if (sizeof($deelnemers) > 0) : ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th>Foto</th>
				<th>Aangemeld</th>
				<th>Betaald</th>
				<th>Bedrag</th>
				<th>Deelnemer</th>
				<th>Thuisstudio</th>
				<th>Beoordeling</th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
				<?php if ($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support') : ?>
					<th class="verwijderen"></th>
				<?php endif; ?>
			</tr>
			<?php foreach ($deelnemers as $deelnemer) : ?>
				<tr>
					<td>
						<?php if (!empty($deelnemer->profiel_foto->media_src)) { ?>
							<a id="profiel_foto" href="<?php echo base_url('media/uploads/') . $deelnemer->profiel_foto->media_src; ?>" class="preview">Foto<img class="preview" id="profiel_foto" />
							<?php } ?>
					</td>
					<td><?php echo date('d-m-Y', strtotime($deelnemer->aanmelding_datum)) ?></td>
					<td><?php if ($deelnemer->aanmelding_betaald_datum != '' && $deelnemer->aanmelding_betaald_datum != '0000-00-00 00:00:00') echo date('d-m-Y', strtotime($deelnemer->aanmelding_betaald_datum));
						else 'Nog niet betaald'; ?></td>
					<td><?php echo $deelnemer->aanmelding_betaald_bedrag ?>,00</td>
					<td><a href="<?php echo base_url('cms/deelnemers/' . $deelnemer->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($deelnemer->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $deelnemer->gebruiker_naam ?></span></a></td>
					<td id="paid_<?php echo $deelnemer->gebruiker_ID ?>" >Bruikleen</td>
					<td>
						<a href="<?php echo base_url('cms/groepen/gebruiker_beoordelingen/' . $deelnemer->gebruiker_ID . '/' . $item->workshop_ID) ?>" title="Beoordelingen bekijken">
							<span>
								<div class="rating_fixed" onclick="window.location.href='<?php echo base_url('cms/groepen/gebruiker_beoordelingen/' . $deelnemer->gebruiker_ID . '/' . $item->workshop_ID) ?>'">
									<?php for ($i = 0; $i < 5; $i++) { ?>
										<?php if (!empty($deelnemer->gebruiker_beoordeling)) { ?>
											<?php if ($deelnemer->gebruiker_beoordeling > 0) { ?>
												<label class="selected">
													<input type="radio" name="rating">
												</label>
											<?php } else { ?>
												<label class="unselected">
													<input type="radio" name="rating">
												</label>
											<?php }
											$deelnemer->gebruiker_beoordeling--; ?>
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
					<td class="bekijken"><a href="<?php echo base_url('cms/deelnemers/' . $deelnemer->gebruiker_ID) ?>" title="Deelnemer bekijken"></a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/deelnemers/wijzigen/' . $deelnemer->gebruiker_ID) ?>" title="Deelnemer wijzigen"></a></td>
					<?php if ($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support') : ?>
						<td class="verwijderen"><a href="<?php echo base_url('cms/deelnemers/afmelden/' . $deelnemer->aanmelding_ID) ?>" title="Deelnemer afmelden"></a></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else : ?>
		<p>Er zijn nog geen aanmeldingen.</p>
	<?php endif; ?>
</div>
<script>
	<?php
	if(sizeof($paid) > 0){
	foreach ($paid as $key => $value) {
	?>
		document.getElementById('paid_<?php echo $value->gebruiker_ID; ?>').innerText = "Gekocht";
	<?php		
	}
	}
	?>

</script>
