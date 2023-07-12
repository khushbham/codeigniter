<h1>Aanmelding</h1>



<!------------>
<!-- ACTIES -->
<!------------>

<p id="links">
	<a href="<?php echo base_url('cms/aanmeldingen') ?>" title="Alle aanmeldingen">Alle aanmeldingen</a>
	<a href="<?php echo base_url('cms/aanmeldingen/wijzigen/'.$item->aanmelding_ID) ?>" title="Aanmelding wijzigen" class="wijzigen">Aanmelding wijzigen</a>
    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
	<a href="<?php echo base_url('cms/aanmeldingen/verwijderen/'.$item->aanmelding_ID) ?>" title="Aanmelding verwijderen" class="verwijderen">Aanmelding verwijderen</a>
    <?php endif; ?>
</p>



<!-------------->
<!-- GEGEVENS -->
<!-------------->

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Nummer</th>
		<td><?php echo $item->aanmelding_ID ?></td>
	</tr>
	<tr>
		<th>Aangemeld</th>
		<td><?php echo date('d-m-Y', strtotime($item->aanmelding_datum)) ?> om <?php echo date('H:i:s', strtotime($item->aanmelding_datum)) ?> uur</td>
	</tr>
	<tr>
		<th>Type</th>
		<td><?php echo ucfirst($item->aanmelding_type) ?></td>
	</tr>
	<tr>
		<th>Workshop</th>
		<td><a href="<?php echo base_url('cms/workshops/'.$item->aanmelding_workshop_ID) ?>" title="Bekijk workshop"><?php echo $item->workshop_titel ?></a></td>
	</tr>
	<?php if(!empty($item->groep_ID)): ?>
		<tr>
			<th>Groep</th>
			<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Bekijk groep"><?php echo $item->groep_naam ?></a></td>
		</tr>
	<?php endif; ?>
	<tr>
		<th>Deelnemer</th>
		<td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Bekijk deelnemer"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
	</tr>
	<tr>
		<th>iDEAL referentie</th>
		<td><?php if(!empty($item->aanmelding_ideal_ID)) echo $item->aanmelding_ideal_ID; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>iDEAL status</th>
		<?php if($item->aanmelding_ideal_tijdstip != '0000-00-00 00:00:00'): ?>
			<td><?php echo $item->aanmelding_ideal_status.' ('.$statusupdates[$item->aanmelding_ideal_status].')'; ?></td>
		<?php else: ?>
			<td>...</td>
		<?php endif; ?>
	</tr>
	<tr>
		<th>iDEAL update</th>
		<td><?php if($item->aanmelding_ideal_tijdstip != '0000-00-00 00:00:00') echo date('d-m-Y', strtotime($item->aanmelding_ideal_tijdstip)).' om '.date('H:i:s', strtotime($item->aanmelding_ideal_tijdstip)).' uur'; else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Betaald</th>
		<?php if($item->aanmelding_betaald_datum == '0000-00-00 00:00:00'): ?>
			<td>Nog niet betaald</td>
		<?php else: ?>
			<td>&euro; <?php echo $item->aanmelding_betaald_bedrag ?>,00 op <?php echo date('d-m-Y', strtotime($item->aanmelding_betaald_datum)) ?> om <?php echo date('H:i:s', strtotime($item->aanmelding_betaald_datum)) ?> uur <?php if(!empty($item->aanmelding_betaald_kortingscode)): ?>met couponcode '<?php echo $item->aanmelding_betaald_kortingscode ?>'<?php endif; ?></td>
		<?php endif; ?>
	</tr>
	<?php if(in_array($item->aanmelding_type, array('intake', 'stemtest'))): ?>
		<tr>
			<th>Afspraak</th>
			<?php if($item->aanmelding_afspraak != '0000-00-00 00:00:00'): ?>
				<td><?php echo date('d-m-Y', strtotime($item->aanmelding_afspraak)) ?> om <?php echo date('H:i:s', strtotime($item->aanmelding_afspraak)) ?> uur <?php if($item->aanmelding_afspraak_eindtijd != '00:00:00'): ?>tot <?php echo $item->aanmelding_afspraak_eindtijd ?> uur<?php endif; ?></td>
			<?php else: ?>
				<td>Nog geen afspraak</td>
			<?php endif; ?>
		</tr>
		<tr>
			<th>Afspraak resultaat</th>
			<td><?php if($item->aanmelding_voldoende == 'onbekend') echo 'Onbekend'; elseif($item->aanmelding_voldoende == 'ja') echo 'Voldoende'; else echo 'Onvoldoende'; ?> <?php if($item->aanmelding_voldoende == 'ja') echo '('.$item->aanmelding_code.')'; ?></td>
		</tr>
	<?php endif; ?>
	<tr>
		<th>Afgerond</th>
		<td><?php if($item->aanmelding_afgerond): ?>Ja op <?php echo date('d-m-Y', strtotime($item->aanmelding_afgerond_datum)) ?> om <?php echo date('H:i:s', strtotime($item->aanmelding_afgerond_datum)) ?> uur<?php else: ?>Nee<?php endif; ?></td>
	</tr>
</table>



<!---------------->
<!-- BESTELLING -->
<!---------------->

<h2>Bestelling</h2>
<?php if($bestelling != null): ?>
	<table cellpadding="0" cellspacing="0" class="gegevens">
		<tr>
			<th><?php if(sizeof($producten) == 1) echo 'Product'; else echo 'Product'; ?></th>
			<td>
				<?php
				$bestelling_producten = '';
				foreach($producten as $product) $bestelling_producten .= $product->product_naam.', ';
				echo substr($bestelling_producten, 0, -2);
				?>
			</td>
		</tr>
		<tr>
			<th>Afleveradres</th>
			<td><?php echo $bestelling->bestelling_adres ?>, <?php echo $bestelling->bestelling_postcode ?> <?php echo $bestelling->bestelling_plaats ?></td>
		</tr>
		<tr>
			<th>Verzonden</th>
			<?php if($bestelling->bestelling_verzonden_datum == '0000-00-00 00:00:00'): ?>
				<td>Nog niet verzonden</td>
			<?php else: ?>
				<td><?php echo date('d-m-Y', strtotime($bestelling->bestelling_verzonden_datum)) ?> om <?php echo date('H:i:s', strtotime($bestelling->bestelling_verzonden_datum)) ?> uur</td>
			<?php endif; ?>
		</tr>
	</table>
<?php else: ?>
	<p><em>Geen bestelling aanwezig.</em></p>
<?php endif; ?>



<!------------>
<!-- LESSEN -->
<!------------>

<h2>Lessen <?php echo $item->workshop_titel ?></h2>
<table cellpadding="0" cellspacing="0" class="tabel">
	<tr>
		<th>Datum</th>
		<th>Tijd</th>
		<th>Les</th>
		<th>Huiswerk</th>
		<th>Voldoende</th>
	</tr>
	<?php foreach($lessen as $les): ?>
		<tr>
			<td><?php if(in_array($item->workshop_type, array('groep', 'online'))) echo date('d-m-Y', strtotime($les->groep_les_datum)); else echo date('d-m-Y', strtotime($les->individu_les_datum)); ?></td>
			<td><?php if(in_array($item->workshop_type, array('groep', 'online'))) echo date('H:i', strtotime($les->groep_les_datum)); else echo date('H:i', strtotime($les->individu_les_datum)); ?> uur</td>
			<td><?php echo $les->les_titel ?></td>
			<td><?php if($les->les_huiswerk_aantal > 0) echo 'Ja'; else echo 'Nee'; ?></td>
			<td>
				<?php
				if($les->les_huiswerk_aantal == 0)
				{
					echo '-';
				}
				else
				{
					if(isset($les->resultaat_voldoende))
					{
						if($les->resultaat_voldoende == 'ja' || $les->resultaat_opnieuw_voldoende == 'ja')
						{
							echo 'Ja';
						}
						else
						{
							echo 'Nee';
						}
					}
					else
					{
						echo 'Onbekend';
					}
				}
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>